<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutletResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'OutletID' => $this->OutletID,
            'OutletCode' => $this->OutletCode,
            'OutletName' => $this->OutletName,
            'IPAddress' => $this->IPAddress,
            'City' => $this->City,
            'District' => $this->District,
            'OpeningDate' => $this->OpeningDate ? $this->OpeningDate->format('Y-m-d') : null,
            'ClosingDate' => $this->ClosingDate ? $this->ClosingDate->format('Y-m-d') : null,
            'Status' => $this->Status,
            'CreatedBy' => $this->CreatedBy,
            'DatabaseName' => $this->DatabaseName,
            'DatabaseUser' => $this->DatabaseUser,
            'DatabasePassword' => $this->DatabasePassword,
            'UpdatedBy' => $this->UpdatedBy,
            'CreatedAt' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'UpdatedAt' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'DaysInOperation' => $this->days_in_operation,
            'FullLocation' => $this->full_location,
            'IsActive' => $this->isActive(),
        ];
    }
}
