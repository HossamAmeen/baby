<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    
    public function region()
    {
        return $this->belongsTo('App\Region','region_id','id');
    }
}
