<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mokam extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'Mokam';
    protected $primaryKey = 'MokamId';
    public $timestamps = false;

    protected $fillable = [
        'DistrictCode',
        'MokamName',
        'Active',
        'CreatedAt',
        'UpdatedAt',
    ];

    protected $casts = [
        'DistrictCode' => 'string',
    ];

    public function scopeActive($query)
    {
        return $query->where('Active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('Active', false);
    }

    public function scopeByDistrict($query, $districtCode)
    {
        return $query->where('DistrictCode', $districtCode);
    }

}
