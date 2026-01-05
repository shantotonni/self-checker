<?php

namespace App\Http\Controllers;

use App\Models\BusinessProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessProductController extends Controller
{
    public function index(Request $request)
    {
        $query = BusinessProduct::query();

        // Filter by business
        if ($request->has('business') && $request->business != '') {
            $query->where('Business', $request->business);
        }

        // Filter by active status
        if ($request->has('active') && $request->active !== '') {
            $query->where('Active', $request->active === 'true' ? 1 : 0);
        }

        // Order by latest
        $query->orderBy('CreatedAt', 'desc');

        // Paginate
        $products = $query->paginate(15);

        return response()->json([
            'data' => $products->map(function ($product) {
                return $this->formatProduct($product);
            }),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    public function search(Request $request, $query)
    {
        $products = BusinessProduct::where('ProductName', 'LIKE', "%{$query}%")
            ->orWhere('ProductCode', 'LIKE', "%{$query}%")
            ->orWhere('ProductName2', 'LIKE', "%{$query}%")
            ->orWhere('Business', 'LIKE', "%{$query}%")
            ->orderBy('CreatedAt', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $products->map(function ($product) {
                return $this->formatProduct($product);
            }),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    public function getBusinesses()
    {
        $businesses = BusinessProduct::distinct()
            ->pluck('Business')
            ->filter()
            ->values();

        return response()->json([
            'businesses' => $businesses
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business' => 'required|string|max:255',
            'productCode' => 'required|string|max:100|unique:BusinessProduct,ProductCode',
            'productName' => 'required|string|max:255',
            'productName2' => 'nullable|string|max:255',
            'active' => 'boolean',
        ], [
            'business.required' => 'Business name is required',
            'productCode.required' => 'Product code is required',
            'productCode.unique' => 'This product code already exists',
            'productName.required' => 'Product name is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = BusinessProduct::create([
            'Business' => $request->business,
            'ProductCode' => $request->productCode,
            'ProductName' => $request->productName,
            'ProductName2' => $request->productName2,
            'Active' => $request->active ?? true,
            'CreatedAt' => now(),
            'UpdatedAt' => now(),
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $this->formatProduct($product)
        ], 201);
    }

    public function show($id)
    {
        $product = BusinessProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'data' => $this->formatProduct($product)
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = BusinessProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'business' => 'required|string|max:255',
            'productCode' => 'required|string|max:100|unique:BusinessProduct,ProductCode,' . $id . ',BusinessProductId',
            'productName' => 'required|string|max:255',
            'productName2' => 'nullable|string|max:255',
            'active' => 'boolean',
        ], [
            'business.required' => 'Business name is required',
            'productCode.required' => 'Product code is required',
            'productCode.unique' => 'This product code already exists',
            'productName.required' => 'Product name is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update([
            'Business' => $request->business,
            'ProductCode' => $request->productCode,
            'ProductName' => $request->productName,
            'ProductName2' => $request->productName2,
            'Active' => $request->active ?? $product->Active,
            'UpdatedAt' => now(),
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $this->formatProduct($product)
        ]);
    }

    public function toggleStatus($id)
    {
        $product = BusinessProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update([
            'Active' => !$product->Active,
            'UpdatedAt' => now(),
        ]);

        return response()->json([
            'message' => 'Product status updated successfully',
            'data' => $this->formatProduct($product)
        ]);
    }

    public function destroy($id)
    {
        $product = BusinessProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    private function formatProduct($product)
    {
        return [
            'businessProductId' => $product->BusinessProductId,
            'business' => $product->Business,
            'productCode' => $product->ProductCode,
            'productName' => $product->ProductName,
            'productName2' => $product->ProductName2,
            'active' => (bool) $product->Active,
            'createdAt' => $product->CreatedAt,
            'updatedAt' => $product->UpdatedAt,
        ];
    }
}
