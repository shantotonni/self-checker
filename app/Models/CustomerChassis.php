<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerChassis extends Model
{
    use HasFactory;
    protected $table ='customer_chassis';
    public $primaryKey='id';
    protected $guarded = [];
    const CREATED_AT = 'created_at';
    const UPDATED_AT  = 'updated_at';

    public function invoice_details(){
        return $this->hasOne('App\Models\InvoiceDetails','ChassisNo','chassis_no');
    }
    public function mirror_customer(){
        return $this->belongsTo('App\Models\MirrorCustomer','customer_code','CustomerCode')
            ->select('CustomerCode','ThanaCode','DistrictCode','CustomerName1','CustomerName2','Address1','Address2','Mobile');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id','id');
    }
}
