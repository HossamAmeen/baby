<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartSession extends Model
{
    //
    protected $table = 'cart_session';
    public function Product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
    public function OptionRadio()
    {
        return $this->belongsTo('App\ProductOption','optionradio','id');
    }
    public function OptionCheck()
    {
        return $this->belongsTo('App\ProductOption','optioncheck','id');
    }
}
