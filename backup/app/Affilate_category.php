<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affilate_category extends Model
{
    //
    public $timestamps = false;
    protected $table = 'affilate_categories';
    
    public function affilate()
    {
        return $this->belongsTo('App\Affilate','affilate_id','id');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Category','category_id','id');
    }
}
