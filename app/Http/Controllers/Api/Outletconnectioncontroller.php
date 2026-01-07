<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Services\OutletDatabaseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class OutletConnectionController extends Controller
{
    protected OutletDatabaseService $databaseService;

    public function __construct(OutletDatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * Test database connection
     */
    public function testConnection(Request $request): JsonResponse
    {
        $request->validate([
            'OutletID' => 'required|exists:outlets,OutletID',
            'DatabaseName' => 'required|string',
            'DatabaseUser' => 'required|string',
        ]);

        $outlet = Outlet::find($request->OutletID);

        if (!$outlet->IPAddress) {
            return response()->json([
                'success' => false,
                'message' => 'Outlet IP address is not configured.',
            ]);
        }

        $credentials = [
            'DatabaseName' => $request->DatabaseName,
            'DatabasePort' => $request->DatabasePort ?? '1433',
            'DatabaseUser' => $request->DatabaseUser,
            'DatabasePassword' => $request->DatabasePassword,
        ];

        $result = $this->databaseService->testConnection($outlet, $credentials);

        return response()->json($result);
    }

    /**
     * Set outlet connection in session
     */
    public function setSession(Request $request): JsonResponse
    {
        $request->validate([
            'OutletID' => 'required|exists:outlets,OutletID',
            'DatabaseName' => 'required|string',
            'DatabaseUser' => 'required|string',
        ]);

        $outlet = Outlet::find($request->OutletID);

        // Store in session (encrypted)
        $connectionData = [
            'OutletID' => $outlet->OutletID,
            'OutletCode' => $outlet->OutletCode,
            'OutletName' => $outlet->OutletName,
            'IPAddress' => $outlet->IPAddress,
            'DatabaseName' => $request->DatabaseName,
            'DatabasePort' => $request->DatabasePort ?? '1433',
            'DatabaseUser' => $request->DatabaseUser,
            'DatabasePassword' => Crypt::encryptString($request->DatabasePassword ?? ''),
        ];

        Session::put('outlet_connection', $connectionData);

        return response()->json([
            'success' => true,
            'message' => 'Connection saved to session.',
            'outlet' => [
                'OutletID' => $outlet->OutletID,
                'OutletName' => $outlet->OutletName,
                'IPAddress' => $outlet->IPAddress,
            ]
        ]);
    }

    /**
     * Get current session connection
     */
    public function getSession(): JsonResponse
    {
        $connection = Session::get('outlet_connection');

        if (!$connection) {
            return response()->json([
                'success' => false,
                'message' => 'No outlet connection in session.',
                'connection' => null
            ]);
        }

        // Don't expose password
        return response()->json([
            'success' => true,
            'connection' => [
                'OutletID' => $connection['OutletID'],
                'OutletCode' => $connection['OutletCode'],
                'OutletName' => $connection['OutletName'],
                'IPAddress' => $connection['IPAddress'],
                'DatabaseName' => $connection['DatabaseName'],
                'DatabasePort' => $connection['DatabasePort'],
                'DatabaseUser' => $connection['DatabaseUser'],
                'HasPassword' => !empty($connection['DatabasePassword']),
            ]
        ]);
    }

    /**
     * Clear session connection
     */
    public function clearSession(): JsonResponse
    {
        Session::forget('outlet_connection');

        return response()->json([
            'success' => true,
            'message' => 'Connection cleared from session.'
        ]);
    }

    /**
     * Execute query on session outlet
     */
    public function executeOnSession(Request $request): JsonResponse
    {
        $request->validate([
            'SqlScript' => 'required|string',
        ]);

        $connection = Session::get('outlet_connection');

        if (!$connection) {
            return response()->json([
                'success' => false,
                'message' => 'No outlet connection in session. Please select an outlet first.',
            ], 400);
        }

        $outlet = Outlet::find($connection['OutletID']);

        if (!$outlet) {
            return response()->json([
                'success' => false,
                'message' => 'Outlet not found.',
            ], 404);
        }

        // Decrypt password
        $credentials = [
            'DatabaseName' => $connection['DatabaseName'],
            'DatabasePort' => $connection['DatabasePort'],
            'DatabaseUser' => $connection['DatabaseUser'],
            'DatabasePassword' => Crypt::decryptString($connection['DatabasePassword']),
        ];

        $result = $this->databaseService->executeQuery($outlet, $request->SqlScript, $credentials);

        return response()->json($result);
    }
}
