<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Services\OutletConnectionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutletConnectionController extends Controller
{
    protected $connectionManager;

    public function __construct(OutletConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    public function getActiveOutlets()
    {

        $outlets = Outlet::where('Status', 'Active')
            ->where('IsDeleted', 0)
            ->select('OutletID', 'OutletCode', 'OutletName', 'City', 'District', 'IPAddress')
            ->orderBy('OutletName')
            ->get();

        return response()->json([
            'success' => true,
            'outlets' => $outlets
        ]);
    }

    public function testConnection(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:Outlets,OutletID'
        ]);

        try {
            $result = $this->connectionManager->connectToOutlet($request->outlet_id);

            return response()->json([
                'success' => true,
                'message' => 'Successfully connected to ' . $result['outlet']->OutletName,
                'outlet' => $result['outlet']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function setActiveOutlet(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:Outlets,OutletID'
        ]);

        try {
            // ✅ Connect to outlet database
            $result = $this->connectionManager->connectToOutlet($request->outlet_id);

            // ✅ Store in session
            session([
                'active_outlet_id' => $request->outlet_id,
                'active_outlet_name' => $result['outlet']->OutletName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Outlet switched successfully',
                'outlet' => $result['outlet']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
