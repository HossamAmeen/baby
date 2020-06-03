<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorDrags extends Model
{
    //
    public $timestamps = false;
    
    public function vendor()
    {
        return $this->belongsTo('App\Vendor','vendor_id','id');
    }

}