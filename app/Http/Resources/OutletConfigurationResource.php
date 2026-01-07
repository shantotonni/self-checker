<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutletConfigurationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'ConfigID' => $this->ConfigID,
            'OutletID' => $this->OutletID,

            'OutletCode' => $this->outlet->OutletCode,
            'OutletName' => $this->outlet->OutletName,
            'IPAddress' => $this->outlet->IPAddress,
            'OutletStatus' => $this->outlet->Status,

            'ConfigType' => $this->ConfigType,
            'Description' => $this->Description,
            'SqlScript' => $this->SqlScript,
            'Parameters' => $this->Parameters,
            'Priority' => $this->Priority,

            'DatabaseName' => $this->DatabaseName,
            'DatabasePort' => $this->DatabasePort,
            'DatabaseUser' => $this->DatabaseUser,
            'HasCustomCredentials' => !empty($this->DatabaseName) || !empty($this->DatabaseUser),

            'ExecutionStatus' => $this->ExecutionStatus,
            'ExecutionResult' => $this->ExecutionResult,
            'ExecutedAt' => $this->ExecutedAt->format('Y-m-d H:i:s'),
            'ScheduledAt' => $this->ScheduledAt->format('Y-m-d\TH:i'),

            'Remarks' => $this->Remarks,

            'CreatedBy' => $this->CreatedBy,
            'UpdatedBy' => $this->UpdatedBy,
            'ExecutedBy' => $this->ExecutedBy,
            'CreatedAt' => $this->created_at->format('Y-m-d H:i:s'),
            'UpdatedAt' => $this->updated_at->format('Y-m-d H:i:s'),

            'CanEdit' => $this->ExecutionStatus !== 'Executed',
            'CanExecute' => $this->ExecutionStatus === 'Pending' || $this->ExecutionStatus === 'Failed',
        ];
    }
}
