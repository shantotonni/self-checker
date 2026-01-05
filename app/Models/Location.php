<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'Location';
    protected $primaryKey = 'LocationId';
    public $guarded = [];
    public $timestamps = false;
}
