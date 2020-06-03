<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogItem extends Model
{
    //
    protected $table = 'blogitem';
    public function Blogcat()
    {
        return $this->belongsTo('App\BlogCategory','blogcategory_id','id');
    }
}
