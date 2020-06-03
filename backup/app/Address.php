<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $table = 'address';
    
    public function area()
    {
        return $this->belongsTo('App\Area','area_id','id');
    }
    
    public function region()
    {
        return $this->belongsTo('App\Region','region_id','id');
    }
    
    public function country()
    {
        return $this->belongsTo('App\Country','country_id','id');
    }
    
}
