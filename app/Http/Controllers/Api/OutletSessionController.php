<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Exception;

class OutletSessionController extends Controller
{
    public function switch(Request $request): JsonResponse
    {
        $request->validate([
            'OutletID' => 'required|exists:outlets,OutletID',
        ]);

        try {
            $outlet = Outlet::find($request->OutletID);

            if (!$outlet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outlet not found.'
                ], 404);
            }

            if (!$outlet->IPAddress) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outlet IP address is not configured.'
                ]);
            }

            if (!$outlet->DatabaseName || !$outlet->DatabaseUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database credentials not configured for this outlet.'
                ]);
            }

            // Test connection first
            $connectionResult = $this->testOutletConnection($outlet);

            if (!$connectionResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Connection failed: ' . $connectionResult['message']
                ]);
            }

            // Remove old session
            Session::forget('current_outlet');

            // Get decrypted password
            $password = $outlet->DatabasePassword;

            // Set new session
            Session::put('current_outlet', [
                'OutletID' => $outlet->OutletID,
                'OutletCode' => $outlet->OutletCode,
                'OutletName' => $outlet->OutletName,
                'IPAddress' => $outlet->IPAddress,
                'DatabaseName' => $outlet->DatabaseName,
                'DatabasePort' => $outlet->DatabasePort ?? '1433',
                'DatabaseUser' => $outlet->DatabaseUser,
                'DatabasePassword' => $password, // Store as is (encrypted or plain)
                'City' => $outlet->City,
                'Area' => $outlet->Area,
                'Status' => $outlet->Status,
            ]);

            // ✅ IMPORTANT: Explicitly save session
            Session::save();

            // Verify session was saved
            $verify = Session::get('current_outlet');

            if (!$verify) {
                Log::error('Session save failed', [
                    'outlet_id' => $outlet->OutletID,
                    'session_driver' => config('session.driver'),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save session. Please check session configuration.'
                ], 500);
            }

            Log::info('Outlet switched successfully', [
                'outlet_id' => $outlet->OutletID,
                'outlet_name' => $outlet->OutletName,
                'ip' => $outlet->IPAddress,
                'session_id' => Session::getId(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Connected to ' . $outlet->OutletName,
                'outlet' => [
                    'OutletID' => $outlet->OutletID,
                    'OutletCode' => $outlet->OutletCode,
                    'OutletName' => $outlet->OutletName,
                    'IPAddress' => $outlet->IPAddress,
                    'DatabaseName' => $outlet->DatabaseName,
                    'City' => $outlet->City,
                    'Status' => $outlet->Status,
                ],
                'session_id' => Session::getId(), // For debugging
            ]);

        } catch (Exception $e) {
            Log::error('Outlet switch failed', [
                'outlet_id' => $request->OutletID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to switch outlet: ' . $e->getMessage()
            ], 500);
        }
    }

    public function test(): JsonResponse
    {
        $current = Session::get('current_outlet');

        if (!$current) {
            return response()->json([
                'success' => false,
                'message' => 'No outlet selected. Please select an outlet first.',
                'session_id' => Session::getId(),
            ]);
        }

        $outlet = Outlet::find($current['OutletID']);

        if (!$outlet) {
            Session::forget('current_outlet');
            Session::save();

            return response()->json([
                'success' => false,
                'message' => 'Outlet not found. Session cleared.'
            ]);
        }

        $result = $this->testOutletConnection($outlet);

        return response()->json($result);
    }

    public function current(): JsonResponse
    {
        // Debug session
        Log::info('Getting current outlet', [
            'session_id' => Session::getId(),
            'session_data' => Session::all(),
            'has_current_outlet' => Session::has('current_outlet'),
        ]);

        $current = Session::get('current_outlet');

        if (!$current) {
            return response()->json([
                'success' => false,
                'message' => 'No outlet selected.',
                'outlet' => null,
                'session_id' => Session::getId(),
                'debug' => [
                    'session_driver' => config('session.driver'),
                    'session_lifetime' => config('session.lifetime'),
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'outlet' => [
                'OutletID' => $current['OutletID'],
                'OutletCode' => $current['OutletCode'],
                'OutletName' => $current['OutletName'],
                'IPAddress' => $current['IPAddress'],
                'DatabaseName' => $current['DatabaseName'],
                'City' => $current['City'] ?? null,
                'Status' => $current['Status'] ?? null,
            ],
            'session_id' => Session::getId(),
        ]);
    }

    public function clear(): JsonResponse
    {
        Session::forget('current_outlet');
        Session::save();

        return response()->json([
            'success' => true,
            'message' => 'Session cleared.'
        ]);
    }

    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'sql' => 'required|string',
        ]);

        $current = Session::get('current_outlet');

        if (!$current) {
            return response()->json([
                'success' => false,
                'message' => 'No outlet selected. Please select an outlet first.',
                'session_id' => Session::getId(),
            ], 400);
        }

        try {
            $connectionName = 'outlet_exec_' . $current['OutletID'] . '_' . time();

            // Get password (decrypt if needed)
            $password = $current['DatabasePassword'];
            if ($password && str_starts_with($password, 'eyJ')) {
                try {
                    $password = Crypt::decryptString($password);
                } catch (Exception $e) {
                    // Password might not be encrypted
                    Log::warning('Password decryption failed, using as is');
                }
            }

            Config::set("database.connections.{$connectionName}", [
                'driver' => 'sqlsrv',
                'host' => $current['IPAddress'],
                'port' => $current['DatabasePort'] ?? '1433',
                'database' => $current['DatabaseName'],
                'username' => $current['DatabaseUser'],
                'password' => $password,
                'charset' => 'utf8',
                'prefix' => '',
                'encrypt' => 'no',
                'trust_server_certificate' => true,
            ]);

            $sql = trim($request->sql);
            $isSelect = preg_match('/^\s*SELECT/i', $sql);

            if ($isSelect) {
                $results = DB::connection($connectionName)->select($sql);
                DB::purge($connectionName);

                return response()->json([
                    'success' => true,
                    'message' => 'Query executed. Rows: ' . count($results),
                    'data' => $results,
                    'count' => count($results)
                ]);
            } else {
                DB::connection($connectionName)->beginTransaction();
                DB::connection($connectionName)->unprepared($sql);
                $affected = DB::connection($connectionName)->select('SELECT @@ROWCOUNT as affected')[0]->affected ?? 0;
                DB::connection($connectionName)->commit();
                DB::purge($connectionName);

                return response()->json([
                    'success' => true,
                    'message' => 'Executed. Affected rows: ' . $affected,
                    'affected_rows' => $affected
                ]);
            }

        } catch (Exception $e) {
            if (isset($connectionName)) {
                DB::purge($connectionName);
            }

            Log::error('SQL execution failed', [
                'outlet_id' => $current['OutletID'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function testOutletConnection(Outlet $outlet): array
    {
        try {
            $connectionName = 'outlet_test_' . $outlet->OutletID . '_' . time();

            $password = $outlet->DatabasePassword;

            // Try to decrypt if it looks encrypted
            if ($password && str_starts_with($password, 'eyJ')) {
                try {
                    $password = Crypt::decryptString($password);
                } catch (Exception $e) {
                    Log::warning('Password decryption failed', [
                        'outlet_id' => $outlet->OutletID,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Config::set("database.connections.{$connectionName}", [
                'driver' => 'sqlsrv',
                'host' => $outlet->IPAddress,
                'port' => $outlet->DatabasePort ?? '1433',
                'database' => $outlet->DatabaseName,
                'username' => $outlet->DatabaseUser,
                'password' => $password,
                'charset' => 'utf8',
                'prefix' => '',
                'encrypt' => 'no',
                'trust_server_certificate' => true,
            ]);

            DB::connection($connectionName)->getPdo();
            DB::purge($connectionName);

            return [
                'success' => true,
                'message' => 'Connected to ' . $outlet->IPAddress
            ];

        } catch (Exception $e) {
            if (isset($connectionName)) {
                DB::purge($connectionName);
            }

            Log::error('Connection test failed', [
                'outlet_id' => $outlet->OutletID,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
