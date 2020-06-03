<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affilate_Product extends Model
{
    //
    public $timestamps = false;
    protected $table = 'affilate_products';
    
    public function affilate()
    {
        return $this->belongsTo('App\Affilate','affilate_id','id');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
}
