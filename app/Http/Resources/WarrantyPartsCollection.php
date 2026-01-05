<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarrantyPartsCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($parts) {
                return [
                    'Id' => $parts->Id,
                    'PartsNumber' => $parts->PartsNumber,
                    'PartsName' => $parts->PartsName,
                    'Quantity' => $parts->Quantity,
                    'Price' => $parts->Price,
                    'WorkingHour' => $parts->WorkingHour,
                    'WarrantyclaiminfoId' => $parts->WarrantyclaiminfoId,
                    'CustomerName' => isset($parts->warranty_claim) ? $parts->warranty_claim->CustomerName : '',
                ];
            })
        ];
    }
}
