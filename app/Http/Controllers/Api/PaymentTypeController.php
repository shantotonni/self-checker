<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OutletConnectionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentTypeController extends Controller
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
            $query = $connection->table('SC_PaymentType');

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where('terminal_id', 'like', "%{$search}%");
            }

            // Filter by terminal
            if ($request->has('terminal_id') && $request->terminal_id) {
                $query->where('terminal_id', $request->terminal_id);
            }

            // Filter by CITY_POS enabled
            if ($request->has('city_pos') && $request->city_pos !== '') {
                $query->where('CITY_POS', $request->city_pos);
            }

            // Filter by BKASH_QR enabled
            if ($request->has('bkash_qr') && $request->bkash_qr !== '') {
                $query->where('BKASH_QR', $request->bkash_qr);
            }

            // Filter by EBL_POS enabled
            if ($request->has('ebl_pos') && $request->ebl_pos !== '') {
                $query->where('EBL_POS', $request->ebl_pos);
            }

            // Filter by BANGLA_QR_MTB enabled
            if ($request->has('bangla_qr_mtb') && $request->bangla_qr_mtb !== '') {
                $query->where('BANGLA_QR_MTB', $request->bangla_qr_mtb);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $paymentTypes = $query->orderBy('id', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $paymentTypes->items(),
                'meta' => [
                    'current_page' => $paymentTypes->currentPage(),
                    'last_page' => $paymentTypes->lastPage(),
                    'per_page' => $paymentTypes->perPage(),
                    'total' => $paymentTypes->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch payment types', [
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
     * Store new payment type
     */
    public function store(Request $request)
    {
        $request->validate([
            'terminal_id' => 'required|string|max:100',
            'CITY_POS' => 'required|in:0,1',
            'BKASH_QR' => 'required|in:0,1',
            'EBL_POS' => 'required|in:0,1',
            'BANGLA_QR_MTB' => 'required|in:0,1',
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

            // Check duplicate terminal_id
            $exists = $connection->table('SC_PaymentType')
                ->where('terminal_id', $request->terminal_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terminal ID already exists in this outlet'
                ], 422);
            }

            // Insert data
            $data = [
                'terminal_id' => $request->terminal_id,
                'CITY_POS' => $request->CITY_POS,
                'BKASH_QR' => $request->BKASH_QR,
                'EBL_POS' => $request->EBL_POS,
                'BANGLA_QR_MTB' => $request->BANGLA_QR_MTB,
            ];

            $connection->table('SC_PaymentType')->insert($data);

            return response()->json([
                'success' => true,
                'message' => 'Payment type created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create payment type', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single payment type
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

            $paymentType = $connection->table('SC_PaymentType')
                ->where('id', $id)
                ->first();

            if (!$paymentType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment type not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $paymentType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment type
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'terminal_id' => 'required|string|max:100',
            'CITY_POS' => 'required|in:0,1',
            'BKASH_QR' => 'required|in:0,1',
            'EBL_POS' => 'required|in:0,1',
            'BANGLA_QR_MTB' => 'required|in:0,1',
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
            $exists = $connection->table('SC_PaymentType')
                ->where('id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment type not found'
                ], 404);
            }

            // Check duplicate terminal_id
            $duplicate = $connection->table('SC_PaymentType')
                ->where('terminal_id', $request->terminal_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terminal ID already exists'
                ], 422);
            }

            // Update data
            $data = [
                'terminal_id' => $request->terminal_id,
                'CITY_POS' => $request->CITY_POS,
                'BKASH_QR' => $request->BKASH_QR,
                'EBL_POS' => $request->EBL_POS,
                'BANGLA_QR_MTB' => $request->BANGLA_QR_MTB,
            ];

            $connection->table('SC_PaymentType')
                ->where('id', $id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Payment type updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update payment type', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete payment type
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

            $exists = $connection->table('SC_PaymentType')
                ->where('id', $id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment type not found'
                ], 404);
            }

            $connection->table('SC_PaymentType')
                ->where('id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment type deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get terminals for filter
     */
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

            $terminals = $connection->table('SC_PaymentType')
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

            $stats = [
                'total_terminals' =>
                    $connection->table('SC_PaymentType')->count(),

                'city_pos_enabled' =>
                    $connection->table('SC_PaymentType')
                        ->where('CITY_POS', 'Y')
                        ->count(),

                'bkash_qr_enabled' =>
                    $connection->table('SC_PaymentType')
                        ->where('BKASH_QR', 'Y')
                        ->count(),

                'ebl_pos_enabled' =>
                    $connection->table('SC_PaymentType')
                        ->where('EBL_POS', 'Y')
                        ->count(),

                'bangla_qr_mtb_enabled' =>
                    $connection->table('SC_PaymentType')
                        ->where('BANGLA_QR_MTB', 'Y')
                        ->count(),
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


    public function togglePaymentMethod(Request $request, $id)
    {
        $request->validate([
            'method' => 'required|in:CITY_POS,BKASH_QR,EBL_POS,BANGLA_QR_MTB'
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

            $paymentType = $connection->table('SC_PaymentType')
                ->where('id', $id)
                ->first();

            if (!$paymentType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment type not found'
                ], 404);
            }

            $method = $request->method;
            $currentValue = $paymentType->$method;
            $newValue = $currentValue ? 0 : 1;

            $connection->table('SC_PaymentType')
                ->where('id', $id)
                ->update([$method => $newValue]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method toggled successfully',
                'new_value' => $newValue
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
