<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Brand;
use DB;
use File;
use Image;

class BrandController extends Controller
{

public function __construct()
    {
        $this->middleware('permission:brands');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $s = Brand::all();
        return view('admin.brands.brands',compact('s'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.brands.createbrands');
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
        $add = new Brand();
        $add->name = $request->name;
        $add->meta_description = $request->meta_description;
        $add->meta_keywords = $request->meta_keywords;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);
            
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/brands/source/' . $fileName);
             $resize200 = base_path('uploads/brands/resize200/' . $fileName);
             $resize800 = base_path('uploads/brands/resize800/' . $fileName);
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
        
            $width800 = 800;
            $height800 = ($heightreal * 800) / $width800;

            $img800 = Image::canvas($width800, $height800);
            $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                
                $c->upsize();
            });
            $img800->insert($image800, 'center');
            $img800->save($resize800);

            $add->image = $fileName;
            }
        $add->save();
        return redirect('brands');
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
        $s = Brand::findOrFail($id);
        return view('admin.brands.editbrands',compact('s'));
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
        $add = Brand::findOrFail($id);
        $add->name = $request->name;
        $add->meta_description = $request->meta_description;
        $add->meta_keywords = $request->meta_keywords;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/brands/source/';
            $img_path200 = base_path() . '/uploads/brands/resize200/';
            $img_path800 = base_path() . '/uploads/brands/resize800/';

            if ($add->image != null) {
                unlink(sprintf($img_path . '%s', $add->image));
                 unlink(sprintf($img_path200 . '%s', $add->image));
                 unlink(sprintf($img_path800 . '%s', $add->image));
            }
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/brands/source/' . $fileName);
             $resize200 = base_path('uploads/brands/resize200/' . $fileName);
             $resize800 = base_path('uploads/brands/resize800/' . $fileName);
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
        
            $width800 = 800;
            $height800 = ($heightreal * 800) / $width800;

            $img800 = Image::canvas($width800, $height800);
            $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                
                $c->upsize();
            });
            $img800->insert($image800, 'center');
            $img800->save($resize800);
           

            $add->image = $fileName;
            }
        $add->save();
        return redirect('brands');
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
            $s = Brand::findOrFail($id);
            $s->delete();
        }
    }
}
