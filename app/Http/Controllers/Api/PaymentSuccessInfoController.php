<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OutletConnectionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentSuccessInfoController extends Controller
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

            $where = "1 = 1";
            $bindings = [];

            // Search
            if ($request->search) {
                $where .= " AND (
                InvoiceNo LIKE ? OR
                CardNo LIKE ? OR
                TerminalId LIKE ? OR
                RefNo LIKE ? OR
                VoucherNo LIKE ? OR
                MerchantName LIKE ? OR
                AuthCode LIKE ?
            )";
                $search = "%{$request->search}%";
                $bindings = array_merge($bindings, array_fill(0, 7, $search));
            }

            if ($request->card_type) {
                $where .= " AND CardType = ?";
                $bindings[] = $request->card_type;
            }

            if ($request->issuer_name) {
                $where .= " AND IssuerName = ?";
                $bindings[] = $request->issuer_name;
            }

            if ($request->terminal_id) {
                $where .= " AND TerminalId = ?";
                $bindings[] = $request->terminal_id;
            }

            if ($request->merchant_id) {
                $where .= " AND MerchantId = ?";
                $bindings[] = $request->merchant_id;
            }

            // Date range (NO whereDate)
            if ($request->from_date) {
                $where .= " AND CreatedAt >= ?";
                $bindings[] = $request->from_date . ' 00:00:00';
            }

            if ($request->to_date) {
                $where .= " AND CreatedAt <= ?";
                $bindings[] = $request->to_date . ' 23:59:59';
            }

            // Amount range (varchar safe)
            if ($request->min_amount) {
                $where .= " AND CAST(REPLACE(Amount, ',', '') AS DECIMAL(18,2)) >= ?";
                $bindings[] = $request->min_amount;
            }

            if ($request->max_amount) {
                $where .= " AND CAST(REPLACE(Amount, ',', '') AS DECIMAL(18,2)) <= ?";
                $bindings[] = $request->max_amount;
            }

            // Total count
            $total = $connection->selectOne(
                "SELECT COUNT(*) AS total FROM SC_PaymentSuccessInfo WHERE $where",
                $bindings
            )->total;

            // Data with ROW_NUMBER pagination
            $rows = $connection->select(
                "
            SELECT * FROM (
                SELECT *,
                       ROW_NUMBER() OVER (ORDER BY CreatedAt DESC) AS row_num
                FROM SC_PaymentSuccessInfo
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
            Log::error('Failed to fetch payment success info', [
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
            'InvoiceNo' => 'required|string|max:100',
            'Amount' => 'required|numeric|min:0',
            'AuthCode' => 'nullable|string|max:50',
            'BatchNo' => 'nullable|string|max:50',
            'CardNo' => 'nullable|string|max:50',
            'CardType' => 'nullable|string|max:50',
            'IssuerName' => 'nullable|string|max:100',
            'MerchantId' => 'nullable|string|max:50',
            'MerchantName' => 'nullable|string|max:200',
            'RefNo' => 'nullable|string|max:100',
            'TerminalId' => 'nullable|string|max:50',
            'TransTime' => 'nullable|string|max:50',
            'VoucherNo' => 'nullable|string|max:50',
            'SdkVersion' => 'nullable|string|max:50',
            'tran_log_id' => 'nullable|string|max:100',
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

            // Check duplicate invoice
            $exists = $connection->table('SC_PaymentSuccessInfo')
                ->where('InvoiceNo', $request->InvoiceNo)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice number already exists in this outlet'
                ], 422);
            }

            // Insert data
            $data = [
                'InvoiceNo' => $request->InvoiceNo,
                'Amount' => $request->Amount,
                'AuthCode' => $request->AuthCode,
                'BatchNo' => $request->BatchNo,
                'CardNo' => $request->CardNo,
                'CardType' => $request->CardType,
                'IssuerName' => $request->IssuerName,
                'MerchantId' => $request->MerchantId,
                'MerchantName' => $request->MerchantName,
                'RefNo' => $request->RefNo,
                'TerminalId' => $request->TerminalId,
                'TransTime' => $request->TransTime,
                'VoucherNo' => $request->VoucherNo,
                'SdkVersion' => $request->SdkVersion,
                'tran_log_id' => $request->tran_log_id,
                'CreatedAt' => now(),
            ];

            $connection->table('SC_PaymentSuccessInfo')->insert($data);

            return response()->json([
                'success' => true,
                'message' => 'Payment info created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create payment success info', [
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

            $payment = $connection->table('SC_PaymentSuccessInfo')
                ->where('Id', $id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment info not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $payment
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
            'InvoiceNo' => 'required|string|max:100',
            'Amount' => 'required|numeric|min:0',
            'AuthCode' => 'nullable|string|max:50',
            'BatchNo' => 'nullable|string|max:50',
            'CardNo' => 'nullable|string|max:50',
            'CardType' => 'nullable|string|max:50',
            'IssuerName' => 'nullable|string|max:100',
            'MerchantId' => 'nullable|string|max:50',
            'MerchantName' => 'nullable|string|max:200',
            'RefNo' => 'nullable|string|max:100',
            'TerminalId' => 'nullable|string|max:50',
            'TransTime' => 'nullable|string|max:50',
            'VoucherNo' => 'nullable|string|max:50',
            'SdkVersion' => 'nullable|string|max:50',
            'tran_log_id' => 'nullable|string|max:100',
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
            $exists = $connection->table('SC_PaymentSuccessInfo')
                ->where('Id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment info not found'
                ], 404);
            }

            // Check duplicate invoice
            $duplicate = $connection->table('SC_PaymentSuccessInfo')
                ->where('InvoiceNo', $request->InvoiceNo)
                ->where('Id', '!=', $id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice number already exists'
                ], 422);
            }

            // Update data
            $data = [
                'InvoiceNo' => $request->InvoiceNo,
                'Amount' => $request->Amount,
                'AuthCode' => $request->AuthCode,
                'BatchNo' => $request->BatchNo,
                'CardNo' => $request->CardNo,
                'CardType' => $request->CardType,
                'IssuerName' => $request->IssuerName,
                'MerchantId' => $request->MerchantId,
                'MerchantName' => $request->MerchantName,
                'RefNo' => $request->RefNo,
                'TerminalId' => $request->TerminalId,
                'TransTime' => $request->TransTime,
                'VoucherNo' => $request->VoucherNo,
                'SdkVersion' => $request->SdkVersion,
                'tran_log_id' => $request->tran_log_id,
            ];

            $connection->table('SC_PaymentSuccessInfo')
                ->where('Id', $id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Payment info updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update payment success info', [
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

            $exists = $connection->table('SC_PaymentSuccessInfo')
                ->where('Id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment info not found'
                ], 404);
            }

            $connection->table('SC_PaymentSuccessInfo')
                ->where('Id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment info deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCardTypes()
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

            $types = $connection->table('SC_PaymentSuccessInfo')
                ->select('CardType')
                ->distinct()
                ->whereNotNull('CardType')
                ->pluck('CardType');

            return response()->json([
                'success' => true,
                'card_types' => $types
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getIssuers()
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

            $issuers = $connection->table('SC_PaymentSuccessInfo')
                ->select('IssuerName')
                ->distinct()
                ->whereNotNull('IssuerName')
                ->pluck('IssuerName');

            return response()->json([
                'success' => true,
                'issuers' => $issuers
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

            $terminals = $connection->table('SC_PaymentSuccessInfo')
                ->select('TerminalId')
                ->distinct()
                ->whereNotNull('TerminalId')
                ->pluck('TerminalId');

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

            $todayStart = now()->startOfDay()->format('Y-m-d H:i:s');
            $todayEnd   = now()->endOfDay()->format('Y-m-d H:i:s');

            $stats = [
                'total_transactions' =>
                    $connection->table('SC_PaymentSuccessInfo')->count(),

                'total_amount' =>
                    $connection->table('SC_PaymentSuccessInfo')
                        ->selectRaw("SUM(CAST(REPLACE(Amount, ',', '') AS DECIMAL(18,2))) as total")
                        ->value('total'),

                'today_transactions' =>
                    $connection->table('SC_PaymentSuccessInfo')
                        ->whereBetween('CreatedAt', [$todayStart, $todayEnd])
                        ->count(),

                'today_amount' =>
                    $connection->table('SC_PaymentSuccessInfo')
                        ->whereBetween('CreatedAt', [$todayStart, $todayEnd])
                        ->selectRaw("SUM(CAST(REPLACE(Amount, ',', '') AS DECIMAL(18,2))) as total")
                        ->value('total'),

                'card_type_breakdown' =>
                    $connection->table('SC_PaymentSuccessInfo')
                        ->select(
                            'CardType',
                            $connection->raw('COUNT(*) as count'),
                            $connection->raw("SUM(CAST(REPLACE(Amount, ',', '') AS DECIMAL(18,2))) as total_amount")
                        )
                        ->whereNotNull('CardType')
                        ->groupBy('CardType')
                        ->get(),

                'issuer_breakdown' =>
                    $connection->table('SC_PaymentSuccessInfo')
                        ->select(
                            'IssuerName',
                            $connection->raw('COUNT(*) as count'),
                            $connection->raw("SUM(CAST(REPLACE(Amount, ',', '') AS DECIMAL(18,2))) as total_amount")
                        )
                        ->whereNotNull('IssuerName')
                        ->groupBy('IssuerName')
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
