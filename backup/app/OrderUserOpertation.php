<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderUserOpertation extends Model
{
    
    protected $table = 'order_user_operation';
    public $timestamps = false;
    protected $fillable = [
       'order_number','user_id','message','date_time',
    ];
    
    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    
    
}