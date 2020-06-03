<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Slideshow;
use DB;
use File;
use Image;

class SlideshowController extends Controller
{

public function __construct()
    {
        $this->middleware('permission:slideshow');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $s = Slideshow::all();
        return view('admin.slideshow.slideshow',compact('s'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.slideshow.createslideshow');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $add = new Slideshow();
        $add->link = $request->slideshow_link;
        $add->status = $request->status;
        if ($request->hasFile("slideshow_photo")) {

            $file = $request->file("slideshow_photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);
            
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/slideshow/source/' . $fileName);
             $resize200 = base_path('uploads/slideshow/resize200/' . $fileName);
             $resize1200 = base_path('uploads/slideshow/resize1200/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 200;
            $height200 = $width200 / ($widthreal / $heightreal);

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width1200 = 1140;
            $height1200 = ($heightreal * 1140) / $width1200;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                $c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);

            $add->image = $fileName;
            }
        $add->save();
        return redirect('slideshow');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $s = Slideshow::findOrFail($id);
        return view('admin.slideshow.editslideshow',compact('s'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $add = Slideshow::findOrFail($id);
        $add->link = $request->slideshow_link;
        $add->status = $request->status;
        if ($request->hasFile("slideshow_photo")) {

            $file = $request->file("slideshow_photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/slideshow/source/';
            $img_path200 = base_path() . '/uploads/slideshow/resize200/';
            $img_path1200 = base_path() . '/uploads/slideshow/resize1200/';

            if ($add->name != null) {
                unlink(sprintf($img_path . '%s', $add->name));
                 unlink(sprintf($img_path200 . '%s', $add->name));
                 unlink(sprintf($img_path1200 . '%s', $add->name));
            }
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/slideshow/source/' . $fileName);
             $resize200 = base_path('uploads/slideshow/resize200/' . $fileName);
             $resize1200 = base_path('uploads/slideshow/resize1200/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

        


            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 200;
            $height200 = $width200 / ($widthreal / $heightreal);

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width1200 = 1140;
            $height1200 = ($heightreal * 1140) / $width1200;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                $c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);
           

            $add->image = $fileName;
            }
        $add->save();
        return redirect('slideshow');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ids)
    {
        //
        $ids = explode(',', $ids);
        if ($ids[0] == 'on') {
            unset($ids[0]);
        }
        foreach ($ids as $id) {
            $s = Slideshow::findOrFail($id);
            $s->delete();
        }
    }
}
