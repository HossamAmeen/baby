<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    //
    protected $table = 'reviews';
    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
