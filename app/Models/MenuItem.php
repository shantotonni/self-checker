<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'MenuItem';
    public $primaryKey = 'Id';
    protected $guarded = [];
    public $timestamps = false;

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    public function menu(){
        return $this->belongsTo(Menu::class,'MenuID','MenuID');
    }
}
