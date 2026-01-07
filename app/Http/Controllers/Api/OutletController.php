<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutletRequest;
use App\Http\Resources\OutletResource;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $query = Outlet::query();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('city')) {
            $query->byCity($request->city);
        }

        $query->orderBy('OutletName', 'asc');

        $outlets = $query->paginate($request->get('per_page', 15));

        return OutletResource::collection($outlets);
    }

    public function store(OutletRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['CreatedBy'] = Auth::user()->name ?? 'System';
            $data['IsDeleted'] = 0;

            $outlet = Outlet::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Outlet created successfully.',
                'data' => new OutletResource($outlet)
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create outlet.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Outlet $outlet)
    {
        return response()->json([
            'success' => true,
            'data' => new OutletResource($outlet)
        ]);
    }

    public function update(OutletRequest $request, Outlet $outlet): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['UpdatedBy'] = Auth::user()->name ?? 'System';

            // Keep existing password if not provided
            if (empty($data['DatabasePassword'])) {
                unset($data['DatabasePassword']);
            }

            $outlet->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Outlet updated successfully.',
                'data' => new OutletResource($outlet)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update outlet.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Outlet $outlet): JsonResponse
    {
        try {
            DB::beginTransaction();

            $outlet->update([
                'IsDeleted' => 1,
                'UpdatedBy' => Auth::user()->name ?? 'System',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Outlet deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete outlet.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request, string $query)
    {
        $outlets = Outlet::search($query)
            ->orderBy('OutletName', 'asc')
            ->paginate($request->get('per_page', 15));

        return OutletResource::collection($outlets);
    }

    public function getCities(): JsonResponse
    {
        $cities = Outlet::whereNotNull('City')
            ->where('City', '!=', '')
            ->distinct()
            ->orderBy('City')
            ->pluck('City');

        return response()->json(['cities' => $cities]);
    }

    public function getActiveOutlets(): JsonResponse
    {
        $outlets = Outlet::active()
            ->select('OutletID', 'OutletCode', 'OutletName', 'IPAddress', 'City', 'Status', 'DatabaseName')
            ->orderBy('OutletName')
            ->get();

        return response()->json(['outlets' => $outlets]);
    }

    public function testConnection(Request $request): JsonResponse
    {
        $request->validate([
            'IPAddress' => 'required|string',
            'DatabaseName' => 'required|string',
            'DatabaseUser' => 'required|string',
        ]);

        try {
            $connectionName = 'outlet_test_' . time();

            Config::set("database.connections.{$connectionName}", [
                'driver' => 'sqlsrv',
                'host' => $request->IPAddress,
                'port' => '1433',
                'database' => $request->DatabaseName,
                'username' => $request->DatabaseUser,
                'password' => $request->DatabasePassword ?? '',
                'charset' => 'utf8',
                'prefix' => '',
                'encrypt' => 'no',
                'trust_server_certificate' => true,
            ]);

            DB::connection($connectionName)->getPdo();
            DB::purge($connectionName);

            return response()->json([
                'success' => true,
                'message' => 'Connection successful!'
            ]);

        } catch (\Exception $e) {
            if (isset($connectionName)) {
                DB::purge($connectionName);
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
