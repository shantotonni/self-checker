<?php

namespace App\Exports;

use App\Http\Resources\GeneratorInfo\GeneratorInfoCollection;
use App\Models\GeneratorInfo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneratorInfoExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $generator = GeneratorInfo::with('brand')->get();
        return new GeneratorInfoCollection($generator);
    }

    public function headings(): array
    {

        return [
            'id',
            'generator_name',
            'unique_generator_code',
            'generator_model',
            'brand_id',
            'brand_name',
            'rating',
            'generator_serial',
            'engine_brand',
            'engine_serial',
            'alternator_brand',
            'alternator_serial',
            'delivery_status',
        ];
    }
}
