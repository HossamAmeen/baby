<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ImageHome;
use File;
use Image;

class ImageHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $images = ImageHome::all();
        return view('admin.imagehome.imagehome',compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.imagehome.createimagehome');
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
        $add = new ImageHome();
        $add->title = $request->title;
        $add->link = $request->link;
        $add->status = $request->status;

        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);
            
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/imagehome/source/' . $fileName);
             $resize200 = base_path('uploads/imagehome/resize200/' . $fileName);
             $resize1200 = base_path('uploads/imagehome/resize800/' . $fileName);
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
        
            $width1200 = 800;
            $height1200 = ($heightreal * 800) / $width1200;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                $c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);

            $add->image = $fileName;
            }
        $add->save();
        return redirect('image-home');
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
        $image = ImageHome::find($id);
        return view('admin.imagehome.editimagehome',compact('image'));
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
        $add = ImageHome::find($id);
        $add->title = $request->title;
        $add->link = $request->link;
        $add->status = $request->status;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/imagehome/source/';
            $img_path200 = base_path() . '/uploads/imagehome/resize200/';
            $img_path1200 = base_path() . '/uploads/imagehome/resize800/';

            if ($add->image != null) {
                unlink(sprintf($img_path . '%s', $add->image));
                 unlink(sprintf($img_path200 . '%s', $add->image));
                 unlink(sprintf($img_path1200 . '%s', $add->image));
            }
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/imagehome/source/' . $fileName);
             $resize200 = base_path('uploads/imagehome/resize200/' . $fileName);
             $resize1200 = base_path('uploads/imagehome/resize800/' . $fileName);
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
        
            $width1200 = 800;
            $height1200 = ($heightreal * 800) / $width1200;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                $c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);
           

            $add->image = $fileName;
            }
        $add->save();
        return redirect('image-home');
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
            $img_path = base_path() . '/uploads/imagehome/source/';
            $img_path200 = base_path() . '/uploads/imagehome/resize200/';
            $img_path1200 = base_path() . '/uploads/imagehome/resize800/';

        foreach ($ids as $id) {
            $s = ImageHome::findOrFail($id);
            if ($s->image != null) {
                unlink(sprintf($img_path . '%s', $s->image));
                 unlink(sprintf($img_path200 . '%s', $s->image));
                 unlink(sprintf($img_path1200 . '%s', $s->image));
            }
            $s->delete();
        }
    }
}
