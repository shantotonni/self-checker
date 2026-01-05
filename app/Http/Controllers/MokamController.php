<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Mokam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MokamController extends Controller
{
    public function index(Request $request)
    {
        $query = Mokam::query();

        // Filter by district
        if ($request->filled('district_code') && $request->district_code != '') {
            $query->where('DistrictCode', $request->district_code);
        }

        // Filter by active status
        if ($request->filled('active') && $request->active !== '') {
            $query->where('Active', $request->active === 'Y' ? 'Y' : 'N');
        }

        // Order by latest
        $query->orderBy('CreatedAt', 'desc');

        // Paginate
        $mokams = $query->paginate(15);

        return response()->json([
            'data' => $mokams->map(function ($mokam) {
                return $this->formatMokam($mokam);
            }),
            'meta' => [
                'current_page' => $mokams->currentPage(),
                'last_page' => $mokams->lastPage(),
                'per_page' => $mokams->perPage(),
                'total' => $mokams->total(),
            ]
        ]);
    }

    public function search(Request $request, $query)
    {
        $mokams = Mokam::where('MokamName', 'LIKE', "%{$query}%")
            ->orWhere('DistrictCode', 'LIKE', "%{$query}%")
            ->orderBy('CreatedAt', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $mokams->map(function ($mokam) {
                return $this->formatMokam($mokam);
            }),
            'meta' => [
                'current_page' => $mokams->currentPage(),
                'last_page' => $mokams->lastPage(),
                'per_page' => $mokams->perPage(),
                'total' => $mokams->total(),
            ]
        ]);
    }

    public function getDistricts()
    {
        $districts = District::query()
            ->select('DistrictCode','DistrictName')
            ->get()
            ->map(function ($item) {
                return [
                    'districtCode' => str_pad($item->DistrictCode, 2, '0', STR_PAD_LEFT),
                    'districtName' => $item->DistrictName
                ];
            });

        return response()->json([
            'districts' => $districts
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'districtCode' => 'required|string|max:50',
            'mokamName' => 'required|string|max:255',
            'active' => 'boolean',
        ], [
            'districtCode.required' => 'District is required',
            'mokamName.required' => 'Mokam name is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mokam = Mokam::create([
            'DistrictCode' => $request->districtCode,
            'MokamName' => $request->mokamName,
            'Active' => $request->active ?? true,
            'CreatedAt' => now(),
            'UpdatedAt' => now(),
        ]);

        return response()->json([
            'message' => 'Mokam created successfully',
            'data' => $this->formatMokam($mokam)
        ], 201);
    }

    public function show($id)
    {
        $mokam = Mokam::find($id);

        if (!$mokam) {
            return response()->json(['message' => 'Mokam not found'], 404);
        }

        return response()->json([
            'data' => $this->formatMokam($mokam)
        ]);
    }

    public function update(Request $request, $id)
    {
        $mokam = Mokam::find($id);

        if (!$mokam) {
            return response()->json(['message' => 'Mokam not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'districtCode' => 'required|string|max:50',
            'mokamName' => 'required|string|max:255',
            'active' => 'boolean',
        ], [
            'districtCode.required' => 'District is required',
            'mokamName.required' => 'Mokam name is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mokam->update([
            'DistrictCode' => $request->districtCode,
            'MokamName' => $request->mokamName,
            'Active' => $request->active ?? $mokam->Active,
            'UpdatedAt' => now(),
        ]);

        return response()->json([
            'message' => 'Mokam updated successfully',
            'data' => $this->formatMokam($mokam)
        ]);
    }

    public function toggleStatus($id)
    {
        $mokam = Mokam::find($id);

        if (!$mokam) {
            return response()->json(['message' => 'Mokam not found'], 404);
        }

        $mokam->update([
            'Active' => !$mokam->Active,
            'UpdatedAt' => now(),
        ]);

        return response()->json([
            'message' => 'Mokam status updated successfully',
            'data' => $this->formatMokam($mokam)
        ]);
    }

    public function destroy($id)
    {
        $mokam = Mokam::find($id);

        if (!$mokam) {
            return response()->json(['message' => 'Mokam not found'], 404);
        }

        $mokam->delete();

        return response()->json([
            'message' => 'Mokam deleted successfully'
        ]);
    }

    private function formatMokam($mokam)
    {
        return [
            'mokamId' => $mokam->MokamId,
            'districtCode' => $mokam->DistrictCode,
            'districtName' => $mokam->district->DistrictName ?? $mokam->DistrictCode,
            'mokamName' => $mokam->MokamName,
            'active' => (bool) $mokam->Active,
            'createdAt' => $mokam->CreatedAt,
            'updatedAt' => $mokam->UpdatedAt,
        ];
    }
}
