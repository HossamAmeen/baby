<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionCart extends Model
{
    //
    protected $table = 'option_cart';
    public function Cart()
    {
        return $this->belongsTo('App\Cart','cart_id','id');
    }
    public function Option()
    {
        return $this->belongsTo('App\ProductOption','option_id','id');
    }
}
