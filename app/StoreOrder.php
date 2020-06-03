<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    protected $table = 'store_orders';
    
    public function Product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
    
    public function Status()
    {
        return $this->belongsTo('App\OrderStatus','status_id','id');
    }

    protected $casts = ['status_id' => 'int'];


}
