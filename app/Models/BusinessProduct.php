<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProduct extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'BusinessProduct';
    protected $primaryKey = 'BusinessProductId';
    public $timestamps = false;

    protected $fillable = [
        'Business',
        'ProductCode',
        'ProductName',
        'ProductName2',
        'Active',
        'CreatedAt',
        'UpdatedAt',
    ];

    protected $casts = [
        'Active' => 'boolean',
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
    ];

    public function getFormattedCreatedAtAttribute()
    {
        return $this->CreatedAt ? $this->CreatedAt->format('d M Y H:i') : null;
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->UpdatedAt ? $this->UpdatedAt->format('d M Y H:i') : null;
    }

    public function scopeActive($query)
    {
        return $query->where('Active', 'Y');
    }

    public function scopeInactive($query)
    {
        return $query->where('Active', 'N');
    }

    public function scopeByBusiness($query, $business)
    {
        return $query->where('Business', $business);
    }
}
