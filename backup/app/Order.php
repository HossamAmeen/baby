<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public function Product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
    
    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    
    public function Status()
    {
        return $this->belongsTo('App\OrderStatus','status_id','id');
    }
    
    public function paymethod()
    {
        return $this->belongsTo('App\Paymethod','payment_id','id');
    }
    
    public function Address()
    {
        return $this->belongsTo('App\Address','address_id','id');
    }
    
    public function affilate_order()
    {
        return $this->hasOne('App\AffilateOrder','order_id','id');
    }

    protected $casts = ['status_id' => 'int'];
}
