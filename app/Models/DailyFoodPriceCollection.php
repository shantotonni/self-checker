<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyFoodPriceCollection extends Model
{
    use HasFactory;
    protected $table ='DailyFoodPriceCollection';
    public $primaryKey='CollectionId';
    protected $guarded = [];
}
