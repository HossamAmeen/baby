<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slideshowimages extends Model
{
    //
    protected $table = 'slideshow_images';
    public function Slide()
    {
        return $this->belongsTo('App\Slideshow','slideshow_id','id');
    }
    public function Project()
    {
        return $this->belongsTo('App\Project','project_id','id');
    }
}
