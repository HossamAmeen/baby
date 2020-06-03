<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    
    public function products()
    {
    	return $this->hasMany('App\Product', 'vendor_id', 'id');
    }
    
    public function drags()
    {
    	return $this->hasMany('App\VendorDrags', 'vendor_id', 'id');
    }
}
