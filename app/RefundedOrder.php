<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefundedOrder extends Model
{
    //
    protected $table = 'refunded_orders';
    public $timestamps = false;
    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    
}
