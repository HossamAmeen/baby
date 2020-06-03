<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //
    public function Country()
    {
        return $this->belongsTo('App\Country','country_id','id');
    }
}
