<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affilate extends Model
{
    //
    protected $table = 'affilates';
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    
    public function affilate_product()
    {
    	return $this->hasMany('App\Affilate_Product','affilate_id','id');
    }
    
    public function categories()
    {
    	return $this->hasMany('App\Affilate_category', 'affilate_id', 'id');
    }
    
    public function orders()
    {
    	return $this->hasMany('App\AffilateOrder', 'affilate_id', 'id');
    }
    
    public function withdrow()
    {
    	return $this->hasMany('App\Affilate_Withdrow', 'affilate_id', 'id');
    }
}
