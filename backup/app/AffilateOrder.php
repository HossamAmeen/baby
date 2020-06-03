<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffilateOrder extends Model
{
    //
    public $timestamps = false;
    protected $table = 'affilate_orders';
    
    public function affilate()
    {
        return $this->belongsTo('App\Affilate','affilate_id','id');
    }
    
    public function order()
    {
        return $this->belongsTo('App\Order','order_id','id');
    }
}
