<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'viewDistrict';
    protected $primaryKey = 'DistrictCode';
    public $timestamps = false;
}
