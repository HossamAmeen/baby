<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'category';
    
    public function Parent()
    {
        return $this->belongsTo('App\Category','parent','id');
    }
    
    public function subcat()
    {
    	 return $this->hasMany('App\Category', 'parent', 'id');
    }
}
