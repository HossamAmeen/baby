<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affilate_Withdrow extends Model
{
    //
    protected $table = 'affilate_drags';
    public $timestamps = false;

    public function affilate()
    {
        return $this->belongsTo('App\Affilate','affilate_id','id');
    }
    
}
