<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = ['stock' , 'main_stock' , 'sub_stock' , 'store_stock'];
    //
    public function Currency()
    {
        return $this->belongsTo('App\Currency','currency_id','id');
    }
    
    public function Padge()
    {
        return $this->belongsTo('App\Padge','padge_id','id');
    }
    
    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id', 'id');
    }
    
    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id', 'id');
    }
}
