<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponCart extends Model
{
    //
    protected $table = 'coupon_cart';
    public function Cart()
    {
        return $this->belongsTo('App\Cart','cart_id','id');
    }
    public function Coupon()
    {
        return $this->belongsTo('App\Coupon','coupon_id','id');
    }
}
