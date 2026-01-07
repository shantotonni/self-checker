<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OutletConfiguration extends Model
{
    use HasFactory;

    protected $table = 'OutletConfiguration';
    protected $primaryKey = 'ConfigID';

    protected $fillable = [
        'OutletID',
        'ConfigType',
        'Description',
        'SqlScript',
        'Parameters',
        'Priority',
        'DatabaseName',
        'DatabasePort',
        'DatabaseUser',
        'DatabasePassword',
        'ExecutionStatus',
        'ExecutionResult',
        'ExecutedAt',
        'ScheduledAt',
        'Remarks',
        'CreatedBy',
        'UpdatedBy',
        'ExecutedBy',
    ];

    protected $casts = [
        'ExecutedAt' => 'datetime',
        'ScheduledAt' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'DatabasePassword',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'OutletID', 'OutletID');
    }

    public function setDatabasePasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['DatabasePassword'] = Crypt::encryptString($value);
        } else {
            $this->attributes['DatabasePassword'] = null;
        }
    }

    public function getDatabasePasswordAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function getParametersArrayAttribute()
    {
        if ($this->Parameters) {
            return json_decode($this->Parameters, true) ?? [];
        }
        return [];
    }

    public function scopePending($query)
    {
        return $query->where('ExecutionStatus', 'Pending');
    }

    public function scopeByOutlet($query, $outletId)
    {
        return $query->where('OutletID', $outletId);
    }

    public function scopeScheduledForExecution($query)
    {
        return $query->where('ExecutionStatus', 'Pending')
            ->whereNotNull('ScheduledAt')
            ->where('ScheduledAt', '<=', now());
    }

    public function isExecuted(): bool
    {
        return $this->ExecutionStatus === 'Executed';
    }

    public function markAsExecuted(string $result, string $executedBy): void
    {
        $this->update([
            'ExecutionStatus' => 'Executed',
            'ExecutionResult' => $result,
            'ExecutedAt' => now(),
            'ExecutedBy' => $executedBy,
        ]);
    }

    public function markAsFailed(string $error, string $executedBy): void
    {
        $this->update([
            'ExecutionStatus' => 'Failed',
            'ExecutionResult' => $error,
            'ExecutedAt' => now(),
            'ExecutedBy' => $executedBy,
        ]);
    }

}
