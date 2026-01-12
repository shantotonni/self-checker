<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OutletConnectionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentREQInfoLogController extends Controller
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

            $this->connectionManager->connectToOutlet($outletId);
            $connection = $this->connectionManager->getOutletConnection();

            $page    = max((int) $request->get('page', 1), 1);
            $perPage = (int) $request->get('per_page', 15);
            $offset  = ($page - 1) * $perPage;

            // Base where conditions
            $where = "1 = 1";
            $bindings = [];

            if ($request->search) {
                $where .= " AND (
                terminal_id LIKE ? OR
                tran_id LIKE ? OR
                tran_log_id LIKE ? OR
                payment_type LIKE ?
            )";
                $search = "%{$request->search}%";
                $bindings = array_merge($bindings, [$search, $search, $search, $search]);
            }

            if ($request->payment_type) {
                $where .= " AND payment_type = ?";
                $bindings[] = $request->payment_type;
            }

            if ($request->terminal_id) {
                $where .= " AND terminal_id = ?";
                $bindings[] = $request->terminal_id;
            }

            if ($request->from_date) {
                $where .= " AND created_at >= ?";
                $bindings[] = $request->from_date . ' 00:00:00';
            }

            if ($request->to_date) {
                $where .= " AND created_at <= ?";
                $bindings[] = $request->to_date . ' 23:59:59';
            }

            if ($request->min_amount) {
                $where .= " AND CAST(REPLACE(amount, ',', '') AS DECIMAL(18,2)) >= ?";
                $bindings[] = $request->min_amount;
            }

            if ($request->max_amount) {
                $where .= " AND CAST(REPLACE(amount, ',', '') AS DECIMAL(18,2)) <= ?";
                $bindings[] = $request->max_amount;
            }

            // Total count
            $total = $connection->selectOne(
                "SELECT COUNT(*) AS total FROM SC_PaymentREQInfoLog WHERE $where",
                $bindings
            )->total;

            // Data query (ROW_NUMBER pagination)
            $rows = $connection->select(
                "
            SELECT * FROM (
                SELECT *,
                       ROW_NUMBER() OVER (ORDER BY created_at DESC) AS row_num
                FROM SC_PaymentREQInfoLog
                WHERE $where
            ) t
            WHERE t.row_num > ? AND t.row_num <= ?
            ORDER BY t.row_num
            ",
                array_merge($bindings, [$offset, $offset + $perPage])
            );

            return response()->json([
                'success' => true,
                'data' => $rows,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch payment logs', [
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
            'terminal_id' => 'required|string|max:100',
            'payment_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'tran_log_id' => 'nullable|string|max:100',
            'tran_id' => 'nullable|string|max:100',
            'tran_req_log' => 'nullable|string',
            'tran_res_log' => 'nullable|string',
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

            // Insert data
            $data = [
                'terminal_id' => $request->terminal_id,
                'payment_type' => $request->payment_type,
                'amount' => $request->amount,
                'tran_log_id' => $request->tran_log_id,
                'tran_id' => $request->tran_id,
                'tran_req_log' => $request->tran_req_log,
                'tran_res_log' => $request->tran_res_log,
                'created_at' => now(),
            ];

            $connection->table('SC_PaymentREQInfoLog')->insert($data);

            return response()->json([
                'success' => true,
                'message' => 'Payment log created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create payment log', [
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

            $log = $connection->table('SC_PaymentREQInfoLog')
                ->where('id', $id)
                ->first();

            if (!$log) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment log not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $log
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
            'terminal_id' => 'required|string|max:100',
            'payment_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'tran_log_id' => 'nullable|string|max:100',
            'tran_id' => 'nullable|string|max:100',
            'tran_req_log' => 'nullable|string',
            'tran_res_log' => 'nullable|string',
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

            // Check if log exists
            $exists = $connection->table('SC_PaymentREQInfoLog')
                ->where('id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment log not found'
                ], 404);
            }

            // Update data
            $data = [
                'terminal_id' => $request->terminal_id,
                'payment_type' => $request->payment_type,
                'amount' => $request->amount,
                'tran_log_id' => $request->tran_log_id,
                'tran_id' => $request->tran_id,
                'tran_req_log' => $request->tran_req_log,
                'tran_res_log' => $request->tran_res_log,
            ];

            $connection->table('SC_PaymentREQInfoLog')
                ->where('id', $id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Payment log updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update payment log', [
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

            $exists = $connection->table('SC_PaymentREQInfoLog')
                ->where('id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment log not found'
                ], 404);
            }

            $connection->table('SC_PaymentREQInfoLog')
                ->where('id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment log deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentTypes()
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

            $types = $connection->table('SC_PaymentREQInfoLog')
                ->select('payment_type')
                ->distinct()
                ->whereNotNull('payment_type')
                ->pluck('payment_type');

            return response()->json([
                'success' => true,
                'payment_types' => $types
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTerminals()
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

            $terminals = $connection->table('SC_PaymentREQInfoLog')
                ->select('terminal_id')
                ->distinct()
                ->whereNotNull('terminal_id')
                ->pluck('terminal_id');

            return response()->json([
                'success' => true,
                'terminals' => $terminals
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

            $todayStart = now()->startOfDay();
            $todayEnd   = now()->endOfDay();

            $stats = [
                'total_transactions' => $connection->table('SC_PaymentREQInfoLog')->count(),

                'total_amount' => $connection->table('SC_PaymentREQInfoLog')->selectRaw('SUM(CAST(amount AS DECIMAL(18,2))) as total')->value('total'),

                'today_transactions' => $connection->table('SC_PaymentREQInfoLog')
                    ->whereBetween('created_at', [$todayStart, $todayEnd])
                    ->count(),

                'today_amount' => $connection->table('SC_PaymentREQInfoLog')
                    ->whereBetween('created_at', [$todayStart, $todayEnd])
                    ->selectRaw('SUM(CAST(amount AS DECIMAL(18,2))) as total')
                    ->value('total'),

                'payment_type_breakdown' => $connection->table('SC_PaymentREQInfoLog')
                    ->select(
                        'payment_type',
                        $connection->raw('COUNT(*) as count'),
                        $connection->raw('SUM(CAST(amount AS DECIMAL(18,2))) as total_amount')
                    )
                    ->groupBy('payment_type')
                    ->get(),
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
}
