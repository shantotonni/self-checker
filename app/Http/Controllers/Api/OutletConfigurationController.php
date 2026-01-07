<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutletConfigurationRequest;
use App\Http\Resources\OutletConfigurationResource;
use App\Models\Outlet;
use App\Models\OutletConfiguration;
use App\Services\OutletDatabaseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutletConfigurationController extends Controller
{
    protected OutletDatabaseService $databaseService;

    public function __construct(OutletDatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function index(Request $request)
    {
        $query = OutletConfiguration::with('outlet')
            ->select('OutletConfiguration.*')
            ->join('outlets', 'outlets.OutletID', '=', 'OutletConfiguration.OutletID');

        // Filter by outlet
        if ($request->filled('outlet_id')) {
            $query->where('OutletConfiguration.OutletID', $request->outlet_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('OutletConfiguration.ExecutionStatus', $request->status);
        }

        // Order by latest first
        $query->orderBy('OutletConfiguration.created_at', 'desc');

        $configurations = $query->paginate($request->get('per_page', 15));

        return OutletConfigurationResource::collection($configurations);
    }

    public function store(OutletConfigurationRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $config = OutletConfiguration::create(array_merge($request->validated(), [
                'CreatedBy' => Auth::user()->name ?? 'System',
                'ExecutionStatus' => 'Pending',
            ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Configuration saved successfully.',
                'data' => new OutletConfigurationResource($config->load('outlet')),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save configuration.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(OutletConfiguration $outletConfiguration)
    {
        return response()->json([
            'success' => true,
            'data' => new OutletConfigurationResource($outletConfiguration->load('outlet')),
        ]);
    }

    public function update(OutletConfigurationRequest $request, OutletConfiguration $outletConfiguration): JsonResponse
    {
        // Prevent editing executed configurations
        if ($outletConfiguration->isExecuted()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit already executed configuration.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $outletConfiguration->update(array_merge($request->validated(), [
                'UpdatedBy' => Auth::user()->name ?? 'System',
            ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Configuration updated successfully.',
                'data' => new OutletConfigurationResource($outletConfiguration->load('outlet')),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update configuration.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(OutletConfiguration $outletConfiguration): JsonResponse
    {
        try {
            $outletConfiguration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Configuration deleted successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete configuration.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search configurations
     */
    public function search(Request $request, string $query)
    {
        $configurations = OutletConfiguration::with('outlet')
            ->whereHas('outlet', function ($q) use ($query) {
                $q->where('OutletName', 'LIKE', "%{$query}%")
                    ->orWhere('OutletCode', 'LIKE', "%{$query}%");
            })
            ->orWhere('Description', 'LIKE', "%{$query}%")
            ->orWhere('ConfigType', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return OutletConfigurationResource::collection($configurations);
    }

    /**
     * Test database connection to outlet
     */
    public function testConnection(Request $request): JsonResponse
    {
        $request->validate([
            'OutletID' => 'required|exists:outlets,OutletID',
        ]);

        $outlet = Outlet::find($request->OutletID);

        if (!$outlet->IPAddress) {
            return response()->json([
                'success' => false,
                'message' => 'Outlet IP address is not configured.',
            ]);
        }

        // ✅ Form থেকে আসা credentials use করবে
        $customCredentials = [
            'DatabaseName' => $request->DatabaseName,
            'DatabasePort' => $request->DatabasePort ?? '1433',
            'DatabaseUser' => $request->DatabaseUser,
            'DatabasePassword' => $request->DatabasePassword,
        ];

        $result = $this->databaseService->testConnection($outlet, $customCredentials);

        return response()->json($result);
    }

    /**
     * Execute a configuration
     */
    public function execute(OutletConfiguration $outletConfiguration): JsonResponse
    {
        // Check if already executed
        if ($outletConfiguration->isExecuted()) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration has already been executed.',
            ], 422);
        }

        // Execute the script
        $result = $this->databaseService->executeScript($outletConfiguration);

        // Update configuration status
        $executedBy = Auth::user()->name ?? 'System';

        if ($result['success']) {
            $outletConfiguration->markAsExecuted($result['message'], $executedBy);
        } else {
            $outletConfiguration->markAsFailed($result['message'], $executedBy);
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => new OutletConfigurationResource($outletConfiguration->fresh()->load('outlet')),
        ]);
    }

    /**
     * Bulk execute on multiple outlets
     */
    public function bulkExecute(Request $request): JsonResponse
    {
        $request->validate([
            'selectedOutlets' => 'required|array|min:1',
            'selectedOutlets.*' => 'exists:outlets,OutletID',
            'ConfigType' => 'required|string',
            'Description' => 'required|string|max:255',
            'SqlScript' => 'required|string',
        ]);

        $createdBy = Auth::user()->name ?? 'System';

        $results = $this->databaseService->bulkExecute(
            $request->selectedOutlets,
            $request->SqlScript,
            $request->ConfigType,
            $request->Description,
            $createdBy
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk execution completed.",
            'success_count' => $results['success_count'],
            'failed_count' => $results['failed_count'],
            'details' => $results['details'],
        ]);
    }

    /**
     * Get pending configurations count
     */
    public function getPendingCount(): JsonResponse
    {
        $count = OutletConfiguration::pending()->count();

        return response()->json([
            'pending_count' => $count,
        ]);
    }

    /**
     * Get configurations by status
     */
    public function getByStatus(string $status)
    {
        $configurations = OutletConfiguration::with('outlet')
            ->where('ExecutionStatus', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return OutletConfigurationResource::collection($configurations);
    }

    /**
     * Retry failed configuration
     */
    public function retry(OutletConfiguration $outletConfiguration): JsonResponse
    {
        if ($outletConfiguration->ExecutionStatus !== 'Failed') {
            return response()->json([
                'success' => false,
                'message' => 'Only failed configurations can be retried.',
            ], 422);
        }

        // Reset status to pending
        $outletConfiguration->update([
            'ExecutionStatus' => 'Pending',
            'ExecutionResult' => null,
            'ExecutedAt' => null,
        ]);

        // Execute again
        return $this->execute($outletConfiguration);
    }
}
