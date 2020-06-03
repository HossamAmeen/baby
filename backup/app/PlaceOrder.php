<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaceOrder extends Model
{
    //

    public function Product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }

    protected $casts = ['status' => 'int' , 'type' => 'int'];
}
