<?php

namespace App\Http\Controllers;

use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ProductPriceController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductPrice::query();

        // Filter by product code
        if ($request->has('product_code') && $request->product_code != '') {
            $query->where('ProductCode', $request->product_code);
        }

        // Filter by location
        if ($request->has('location_id') && $request->location_id != '') {
            $query->where('LocationId', $request->location_id);
        }

        // Filter by from date
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('CreatedAt', '>=', $request->from_date);
        }

        // Filter by to date
        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('CreatedAt', '<=', $request->to_date);
        }

        $query->orderBy('UpdatedAt', 'desc');
        $prices = $query->paginate(15);

        return response()->json([
            'data' => $prices->map(function ($price) {
                return $this->formatProductPrice($price);
            }),
            'meta' => [
                'current_page' => $prices->currentPage(),
                'last_page' => $prices->lastPage(),
                'per_page' => $prices->perPage(),
                'total' => $prices->total(),
            ]
        ]);
    }

    public function search(Request $request, $query)
    {
        $prices = ProductPrice::where('ProductCode', 'LIKE', "%{$query}%")
            ->orderBy('UpdatedAt', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $prices->map(function ($price) {
                return $this->formatProductPrice($price);
            }),
            'meta' => [
                'current_page' => $prices->currentPage(),
                'last_page' => $prices->lastPage(),
                'per_page' => $prices->perPage(),
                'total' => $prices->total(),
            ]
        ]);
    }

    public function getProducts()
    {
        $products = DB::table('BusinessProduct')
            ->select('ProductCode as productCode', 'ProductName as productName')
            ->where('Active', 'Y')
            ->orderBy('ProductName')
            ->get();

        return response()->json([
            'products' => $products
        ]);
    }

    public function getLocations()
    {
        $locations = DB::table('Location')
            ->select('LocationId', 'LocationName')
            ->where('Status', 'Y')
            ->orderBy('LocationName')
            ->get();

        return response()->json([
            'locations' => $locations
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productCode' => 'required|string|max:100',
            'locationId' => 'required|integer',
            'wPrice' => 'required|numeric|min:0',
            'rPrice' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check duplicate
        $exists = ProductPrice::where('ProductCode', $request->productCode)
            ->where('LocationId', $request->locationId)
            ->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['productCode' => ['Price already exists for this product and location']]
            ], 422);
        }

        $currentUser = Auth::user()->name ?? Auth::user()->email ?? 'System';

        $price = ProductPrice::create([
            'ProductCode' => $request->productCode,
            'LocationId' => $request->locationId,
            'WPrice' => $request->wPrice,
            'RPrice' => $request->rPrice,
            'CreatedAt' => now(),
            'UpdatedAt' => now(),
            'CreatedBy' => $currentUser,
            'UpdatedBy' => $currentUser,
        ]);

        return response()->json([
            'message' => 'Product price created successfully',
            'data' => $this->formatProductPrice($price)
        ], 201);
    }

    public function show($id)
    {
        $price = ProductPrice::find($id);

        if (!$price) {
            return response()->json(['message' => 'Product price not found'], 404);
        }

        return response()->json([
            'data' => $this->formatProductPrice($price)
        ]);
    }

    public function update(Request $request, $id)
    {
        $price = ProductPrice::find($id);

        if (!$price) {
            return response()->json(['message' => 'Product price not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'wPrice' => 'required|numeric|min:0',
            'rPrice' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $currentUser = Auth::user()->name ?? Auth::user()->email ?? 'System';

        $price->update([
            'WPrice' => $request->wPrice,
            'RPrice' => $request->rPrice,
            'UpdatedAt' => now(),
            'UpdatedBy' => $currentUser,
        ]);

        return response()->json([
            'message' => 'Product price updated successfully',
            'data' => $this->formatProductPrice($price)
        ]);
    }

    public function history($id)
    {
        $price = ProductPrice::find($id);

        if (!$price) {
            return response()->json(['message' => 'Product price not found'], 404);
        }

        $history = [
            [
                'wPrice' => $price->WPrice,
                'rPrice' => $price->RPrice,
                'updatedAt' => $price->UpdatedAt,
                'updatedBy' => $price->UpdatedBy,
            ]
        ];

        return response()->json([
            'history' => $history
        ]);
    }

    public function destroy($id)
    {
        $price = ProductPrice::find($id);

        if (!$price) {
            return response()->json(['message' => 'Product price not found'], 404);
        }

        $price->delete();

        return response()->json([
            'message' => 'Product price deleted successfully'
        ]);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'location_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $locationId = $request->location_id;
            $currentUser = Auth::user()->name ?? Auth::user()->email ?? 'System';

            // Load spreadsheet
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $header = array_shift($rows);

            // Normalize header names (case-insensitive)
            $header = array_map(function($col) {
                return strtolower(trim($col));
            }, $header);

            // Find column indexes
            $productCodeIndex = array_search('productcode', $header);
            $wPriceIndex = array_search('wprice', $header);
            $rPriceIndex = array_search('rprice', $header);

            if ($productCodeIndex === false || $wPriceIndex === false || $rPriceIndex === false) {
                return response()->json([
                    'message' => 'Invalid Excel format. Required columns: ProductCode, WPrice, RPrice'
                ], 422);
            }

            $total = count($rows);
            $imported = 0;
            $updated = 0;
            $failed = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                $productCode = trim($row[$productCodeIndex] ?? '');
                $wPrice = $row[$wPriceIndex] ?? 0;
                $rPrice = $row[$rPriceIndex] ?? 0;

                // Skip empty rows
                if (empty($productCode)) {
                    continue;
                }

                // Validate product code exists
                $productExists = DB::table('BusinessProduct')
                    ->where('ProductCode', $productCode)
                    ->exists();

                if (!$productExists) {
                    $errors[] = "Row {$rowNumber}: Product code '{$productCode}' not found";
                    $failed++;
                    continue;
                }

                // Validate prices
                if (!is_numeric($wPrice) || $wPrice < 0) {
                    $errors[] = "Row {$rowNumber}: Invalid wholesale price";
                    $failed++;
                    continue;
                }

                if (!is_numeric($rPrice) || $rPrice < 0) {
                    $errors[] = "Row {$rowNumber}: Invalid retail price";
                    $failed++;
                    continue;
                }

                // Check if price exists for this product and location
                $existingPrice = ProductPrice::where('ProductCode', $productCode)
                    ->where('LocationId', $locationId)
                    ->first();

                if ($existingPrice) {
                    // Update existing
                    $existingPrice->update([
                        'WPrice' => $wPrice,
                        'RPrice' => $rPrice,
                        'UpdatedAt' => now(),
                        'UpdatedBy' => $currentUser,
                    ]);
                    $updated++;
                } else {
                    // Create new
                    ProductPrice::create([
                        'ProductCode' => $productCode,
                        'LocationId' => $locationId,
                        'WPrice' => $wPrice,
                        'RPrice' => $rPrice,
                        'CreatedAt' => now(),
                        'UpdatedAt' => now(),
                        'CreatedBy' => $currentUser,
                        'UpdatedBy' => $currentUser,
                    ]);
                    $imported++;
                }
            }

            return response()->json([
                'message' => 'Import completed',
                'total' => $total,
                'imported' => $imported,
                'updated' => $updated,
                'failed' => $failed,
                'errors' => array_slice($errors, 0, 10)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sampleExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'ProductCode');
        $sheet->setCellValue('B1', 'WPrice');
        $sheet->setCellValue('C1', 'RPrice');

        // Add sample data
        $sheet->setCellValue('A2', 'PRD001');
        $sheet->setCellValue('B2', '100.00');
        $sheet->setCellValue('C2', '120.00');

        $sheet->setCellValue('A3', 'PRD002');
        $sheet->setCellValue('B3', '200.00');
        $sheet->setCellValue('C3', '250.00');

        // Style header
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCCCC');

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'product_price_sample.xlsx';
        $temp = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($temp);

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function export(Request $request)
    {
        $query = ProductPrice::query();

        if ($request->has('product_code') && $request->product_code != '') {
            $query->where('ProductCode', $request->product_code);
        }
        if ($request->has('location_id') && $request->location_id != '') {
            $query->where('LocationId', $request->location_id);
        }
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('CreatedAt', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('CreatedAt', '<=', $request->to_date);
        }

        $prices = $query->orderBy('UpdatedAt', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = ['SN', 'Product Code', 'Product Name', 'Location', 'Wholesale Price', 'Retail Price', 'Created By', 'Updated By', 'Created At', 'Updated At'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1:J1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Add data
        $row = 2;
        $sn = 1;
        foreach ($prices as $price) {
            $formatted = $this->formatProductPrice($price);

            $sheet->setCellValue('A' . $row, $sn++);
            $sheet->setCellValue('B' . $row, $formatted['productCode']);
            $sheet->setCellValue('C' . $row, $formatted['productName']);
            $sheet->setCellValue('D' . $row, $formatted['locationName']);
            $sheet->setCellValue('E' . $row, $formatted['wPrice']);
            $sheet->setCellValue('F' . $row, $formatted['rPrice']);
            $sheet->setCellValue('G' . $row, $formatted['createdBy']);
            $sheet->setCellValue('H' . $row, $formatted['updatedBy']);
            $sheet->setCellValue('I' . $row, $formatted['createdAt']);
            $sheet->setCellValue('J' . $row, $formatted['updatedAt']);

            $row++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'product_prices_' . date('Y-m-d') . '.xlsx';
        $temp = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($temp);

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function formatProductPrice($price)
    {
        $product = DB::table('BusinessProduct')
            ->where('ProductCode', $price->ProductCode)
            ->first();

        $location = DB::table('Mokam')
            ->where('MokamId', $price->LocationId)
            ->first();

        return [
            'productPriceId' => $price->ProductPriceId,
            'productCode' => $price->ProductCode,
            'productName' => $product->ProductName ?? $price->ProductCode,
            'locationId' => $price->LocationId,
            'locationName' => $location->MokamName ?? $price->LocationId,
            'wPrice' => (float) $price->WPrice,
            'rPrice' => (float) $price->RPrice,
            'createdAt' => $price->CreatedAt,
            'updatedAt' => $price->UpdatedAt,
            'createdBy' => $price->CreatedBy,
            'updatedBy' => $price->UpdatedBy,
        ];
    }
}
