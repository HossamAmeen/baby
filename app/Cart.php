<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    protected $table = 'cart';
    public function Product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
