<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    //
    protected $table = 'makeup_artist';
    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
