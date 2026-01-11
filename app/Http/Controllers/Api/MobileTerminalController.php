<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OutletConnectionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MobileTerminalController extends Controller
{
    protected $connectionManager;

    public function __construct(OutletConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    public function index(Request $request)
    {
        try {
            $outletId = $request->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected. Please select an outlet from navbar.'
                ], 422);
            }

            // Connect to outlet database
            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            // Build query
            $query = $connection->table('SC_MobileTerminal');

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('device_name', 'like', "%{$search}%")
                        ->orWhere('device_id', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%")
                        ->orWhere('android_id', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('manufacturer', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Filter by synced
            if ($request->has('synced') && $request->synced !== '') {
                $query->where('synced', $request->synced);
            }

            // Filter by device type
            if ($request->has('device_type') && $request->device_type) {
                $query->where('device_type', $request->device_type);
            }

            // Filter by printer type
            if ($request->has('printerType') && $request->printerType) {
                $query->where('printerType', $request->printerType);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $terminals = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $terminals->items(),
                'meta' => [
                    'current_page' => $terminals->currentPage(),
                    'last_page' => $terminals->lastPage(),
                    'per_page' => $terminals->perPage(),
                    'total' => $terminals->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch mobile terminals', [
                'error' => $e->getMessage(),
                'outlet_id' => $outletId ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new mobile terminal
     */
    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:100',
            'device_name' => 'required|string|max:200',
            'model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'android_id' => 'nullable|string|max:100',
            'android_version' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
            'device_type' => 'nullable|string|max:50',
            'terminal_id' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
            'synced' => 'nullable|in:0,1',
            'printerType' => 'nullable|string|max:50',
        ]);

        try {
            $outletId = $request->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected'
                ], 422);
            }

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            // Check duplicate device_id
            $exists = $connection->table('SC_MobileTerminal')
                ->where('device_id', $request->device_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device ID already exists in this outlet'
                ], 422);
            }

            // Insert data
            $data = [
                'device_id' => $request->device_id,
                'device_name' => $request->device_name,
                'model' => $request->model,
                'manufacturer' => $request->manufacturer,
                'serial_number' => $request->serial_number,
                'android_id' => $request->android_id,
                'android_version' => $request->android_version,
                'brand' => $request->brand,
                'device_type' => $request->device_type,
                'terminal_id' => $request->terminal_id,
                'status' => $request->status ?? 1,
                'synced' => $request->synced ?? 0,
                'printerType' => $request->printerType,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $connection->table('SC_MobileTerminal')->insert($data);

            return response()->json([
                'success' => true,
                'message' => 'Mobile terminal created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create mobile terminal', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single mobile terminal
     */
    public function show($id)
    {
        try {
            $outletId = request()->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected'
                ], 422);
            }

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            $terminal = $connection->table('SC_MobileTerminal')
                ->where('id', $id)
                ->first();

            if (!$terminal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terminal not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $terminal
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update mobile terminal
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'device_id' => 'required|string|max:100',
            'device_name' => 'required|string|max:200',
            'model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'android_id' => 'nullable|string|max:100',
            'android_version' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
            'device_type' => 'nullable|string|max:50',
            'terminal_id' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
            'synced' => 'nullable|in:0,1',
            'printerType' => 'nullable|string|max:50',
        ]);

        try {
            $outletId = $request->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected'
                ], 422);
            }

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            // Check if terminal exists
            $exists = $connection->table('SC_MobileTerminal')
                ->where('id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terminal not found'
                ], 404);
            }

            // Check duplicate device_id
            $duplicate = $connection->table('SC_MobileTerminal')
                ->where('device_id', $request->device_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device ID already exists'
                ], 422);
            }

            // Update data
            $data = [
                'device_id' => $request->device_id,
                'device_name' => $request->device_name,
                'model' => $request->model,
                'manufacturer' => $request->manufacturer,
                'serial_number' => $request->serial_number,
                'android_id' => $request->android_id,
                'android_version' => $request->android_version,
                'brand' => $request->brand,
                'device_type' => $request->device_type,
                'terminal_id' => $request->terminal_id,
                'status' => $request->status,
                'synced' => $request->synced ?? 0,
                'printerType' => $request->printerType,
                'updated_at' => now(),
            ];

            $connection->table('SC_MobileTerminal')
                ->where('id', $id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Mobile terminal updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update mobile terminal', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete mobile terminal
     */
    public function destroy($id)
    {
        try {
            $outletId = request()->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected'
                ], 422);
            }

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            $exists = $connection->table('SC_MobileTerminal')
                ->where('id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terminal not found'
                ], 404);
            }

            $connection->table('SC_MobileTerminal')
                ->where('id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mobile terminal deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle sync status
     */
    public function toggleSync($id)
    {
        try {
            $outletId = request()->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected'
                ], 422);
            }

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            $terminal = $connection->table('SC_MobileTerminal')
                ->where('id', $id)
                ->first();

            if (!$terminal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terminal not found'
                ], 404);
            }

            $newSyncStatus = $terminal->synced ? 0 : 1;

            $connection->table('SC_MobileTerminal')
                ->where('id', $id)
                ->update([
                    'synced' => $newSyncStatus,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Sync status updated',
                'synced' => $newSyncStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get device types for filter
     */
    public function getDeviceTypes()
    {
        try {
            $outletId = request()->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected'
                ], 422);
            }

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            $types = $connection->table('SC_MobileTerminal')
                ->select('device_type')
                ->distinct()
                ->whereNotNull('device_type')
                ->pluck('device_type');

            return response()->json([
                'success' => true,
                'device_types' => $types
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get printer types for filter
     */
    public function getPrinterTypes()
    {
        try {
            $outletId = request()->header('X-Outlet-ID') ?? session('active_outlet_id');

            if (!$outletId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No outlet selected'
                ], 422);
            }

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            $types = $connection->table('SC_MobileTerminal')
                ->select('printerType')
                ->distinct()
                ->whereNotNull('printerType')
                ->pluck('printerType');

            return response()->json([
                'success' => true,
                'printer_types' => $types
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
