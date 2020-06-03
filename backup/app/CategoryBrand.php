<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryBrand extends Model
{
    //
    public $timestamps = false;
    protected $table = 'category_brand';
    public function Brand()
    {
        return $this->belongsTo('App\Brand','brand_id','id');
    }
}
