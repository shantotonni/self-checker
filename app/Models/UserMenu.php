<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMenu extends Model
{
    use HasFactory;

    protected $table = 'UserMenu';
    protected $primaryKey = 'MenuID';
    protected $guarded = [];
    public $timestamps = false;

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
}
