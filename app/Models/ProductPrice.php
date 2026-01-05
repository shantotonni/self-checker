<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'ProductPrice';
    protected $primaryKey = 'ProductPriceId';
    public $timestamps = false;

    protected $fillable = [
        'ProductCode',
        'LocationId',
        'WPrice',
        'RPrice',
        'CreatedAt',
        'UpdatedAt',
        'CreatedBy',
        'UpdatedBy',
    ];

    protected $casts = [
        'WPrice' => 'decimal:2',
        'RPrice' => 'decimal:2',
        'LocationId' => 'integer',
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(BusinessProduct::class, 'ProductCode', 'ProductCode');
    }

    public function location()
    {
        return $this->belongsTo(Mokam::class, 'LocationId', 'MokamId');
    }

    public function getPriceDifferenceAttribute()
    {
        return $this->RPrice - $this->WPrice;
    }

    public function getMarkupPercentageAttribute()
    {
        if ($this->WPrice > 0) {
            return (($this->RPrice - $this->WPrice) / $this->WPrice) * 100;
        }
        return 0;
    }

    public function scopeByProduct($query, $productCode)
    {
        return $query->where('ProductCode', $productCode);
    }

    public function scopeByLocation($query, $locationId)
    {
        return $query->where('LocationId', $locationId);
    }

    public function scopeRecentlyUpdated($query, $days = 7)
    {
        return $query->where('UpdatedAt', '>=', now()->subDays($days));
    }
}
