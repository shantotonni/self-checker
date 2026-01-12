<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OutletConnectionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppVersionInfoController extends Controller
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
            $query = $connection->table('AppVersionInfo');

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('AppName', 'like', "%{$search}%")
                        ->orWhere('PackageName', 'like', "%{$search}%")
                        ->orWhere('VersionName', 'like', "%{$search}%");
                });
            }

            // Filter by active status
            if ($request->has('active_status') && $request->active_status !== '') {
                $query->where('ActiveStatus', $request->active_status);
            }

            // Filter by package name
            if ($request->has('package_name') && $request->package_name) {
                $query->where('PackageName', $request->package_name);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $appVersions = $query->orderBy('ID', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $appVersions->items(),
                'meta' => [
                    'current_page' => $appVersions->currentPage(),
                    'last_page' => $appVersions->lastPage(),
                    'per_page' => $appVersions->perPage(),
                    'total' => $appVersions->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch app version info', [
                'error' => $e->getMessage(),
                'outlet_id' => $outletId ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'AppName' => 'required|string|max:200',
            'PackageName' => 'required|string|max:200',
            'VersionName' => 'required|string|max:50',
            'VersionCode' => 'required|integer',
            'MinVersion' => 'nullable|integer',
            'CurrentVersion' => 'nullable|integer',
            'DownloadLink' => 'nullable|string|max:500',
            'ActiveStatus' => 'required|in:0,1',
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

            // Check duplicate package name
            $exists = $connection->table('AppVersionInfo')
                ->where('PackageName', $request->PackageName)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package name already exists in this outlet'
                ], 422);
            }

            // Insert data
            $data = [
                'AppName' => $request->AppName,
                'PackageName' => $request->PackageName,
                'VersionName' => $request->VersionName,
                'VersionCode' => $request->VersionCode,
                'MinVersion' => $request->MinVersion,
                'CurrentVersion' => $request->CurrentVersion,
                'DownloadLink' => $request->DownloadLink,
                'ActiveStatus' => $request->ActiveStatus,
            ];

            $connection->table('AppVersionInfo')->insert($data);

            return response()->json([
                'success' => true,
                'message' => 'App version info created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create app version info', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

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

            $appVersion = $connection->table('AppVersionInfo')
                ->where('ID', $id)
                ->first();

            if (!$appVersion) {
                return response()->json([
                    'success' => false,
                    'message' => 'App version info not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $appVersion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'AppName' => 'required|string|max:200',
            'PackageName' => 'required|string|max:200',
            'VersionName' => 'required|string|max:50',
            'VersionCode' => 'required|integer',
            'MinVersion' => 'nullable|integer',
            'CurrentVersion' => 'nullable|integer',
            'DownloadLink' => 'nullable|string|max:500',
            'ActiveStatus' => 'required|in:0,1',
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

            // Check if exists
            $exists = $connection->table('AppVersionInfo')
                ->where('ID', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'App version info not found'
                ], 404);
            }

            // Check duplicate package name
            $duplicate = $connection->table('AppVersionInfo')
                ->where('PackageName', $request->PackageName)
                ->where('ID', '!=', $id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package name already exists'
                ], 422);
            }

            // Update data
            $data = [
                'AppName' => $request->AppName,
                'PackageName' => $request->PackageName,
                'VersionName' => $request->VersionName,
                'VersionCode' => $request->VersionCode,
                'MinVersion' => $request->MinVersion,
                'CurrentVersion' => $request->CurrentVersion,
                'DownloadLink' => $request->DownloadLink,
                'ActiveStatus' => $request->ActiveStatus,
            ];

            $connection->table('AppVersionInfo')
                ->where('ID', $id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'App version info updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update app version info', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

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

            $exists = $connection->table('AppVersionInfo')
                ->where('ID', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'App version info not found'
                ], 404);
            }

            $connection->table('AppVersionInfo')
                ->where('ID', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'App version info deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPackageNames()
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

            $packages = $connection->table('AppVersionInfo')
                ->select('PackageName')
                ->distinct()
                ->whereNotNull('PackageName')
                ->pluck('PackageName');

            return response()->json([
                'success' => true,
                'packages' => $packages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getStats()
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

            $stats = [
                'total_apps' => $connection->table('AppVersionInfo')->count(),
                'active_apps' => $connection->table('AppVersionInfo')->where('ActiveStatus', 'Y')->count(),
                'inactive_apps' => $connection->table('AppVersionInfo')->where('ActiveStatus', 'N')->count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
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

            $appVersion = $connection->table('AppVersionInfo')
                ->where('ID', $id)
                ->first();

            if (!$appVersion) {
                return response()->json([
                    'success' => false,
                    'message' => 'App version info not found'
                ], 404);
            }

            $newStatus = $appVersion->ActiveStatus ? 0 : 1;

            $connection->table('AppVersionInfo')
                ->where('ID', $id)
                ->update(['ActiveStatus' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
