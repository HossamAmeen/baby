<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Artist;
use DB;
use File;
use Image;
use Auth;

class ArtistController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:artist');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $artist = Artist::orderBy('id','desc')->get();
        return view('admin.artist.artist',compact('artist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //$blogcategory = DB::table('blogcategory')->where('status',1)->lists('title','id');
        return view('admin.artist.createartist');
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
        
        $artist = new Artist();
        $artist->name = $request->name;
        $artist->facebook = $request->facebook;
        $artist->twitter = $request->twitter;
        $artist->google = $request->google;
        $artist->pinterest = $request->pinterest;
        $artist->instagram = $request->instagram;
        $link = str_replace(" ","-",$artist->name);
        $artist->link = str_replace("/","-",$link);
        $artist->user_id = Auth::user()->id;
        $artist->status = $request->status;
        $artist->meta_keywords = $request->meta_keywords;
        $artist->meta_description = $request->meta_description;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/artist/source/' . $fileName);
            $resize200 = base_path('uploads/artist/resize200/' . $fileName);
            $resize800 = base_path('uploads/artist/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 150;
            $height200 = $width200 / ($widthreal / $heightreal);

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width800 = ($widthreal / $heightreal) * 800;
            $height800 = $width800 / ($widthreal / $heightreal);

            $img800 = Image::canvas($width800, $height800);
            $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img800->insert($image800, 'center');
            $img800->save($resize800);

            $artist->image = $fileName;
        }
        $artist->save();
        return redirect('artist');
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
        $artist = Artist::find($id);
        $user = DB::table('users')->lists('name','id');
        return view('admin.artist.editartist',compact('user','artist'));
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
       
        $artist = Artist::find($id);
        $artist->name = $request->name;
        $artist->facebook = $request->facebook;
        $artist->twitter = $request->twitter;
        $artist->google = $request->google;
        $artist->pinterest = $request->pinterest;
        $artist->instagram = $request->instagram;
        $link = str_replace(" ","-",$request->link);
        $artist->link = str_replace("/","-",$link);
        $artist->user_id = $request->user_id;
        $artist->status = $request->status;
        $artist->meta_keywords = $request->meta_keywords;
        $artist->meta_description = $request->meta_description;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/artist/source/';
            $img_path200 = base_path() . '/uploads/artist/resize200/';
            $img_path800 = base_path() . '/uploads/artist/resize800/';

            if ($artist->image != null) {
                unlink(sprintf($img_path . '%s', $artist->image));
                unlink(sprintf($img_path200 . '%s', $artist->image));
                unlink(sprintf($img_path800 . '%s', $artist->image));
            }
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/artist/source/' . $fileName);
            $resize200 = base_path('uploads/artist/resize200/' . $fileName);
            $resize800 = base_path('uploads/artist/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 150;
            $height200 = $width200 / ($widthreal / $heightreal);

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width800 = ($widthreal / $heightreal) * 800;
            $height800 = $width800 / ($widthreal / $heightreal);

            $img800 = Image::canvas($width800, $height800);
            $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img800->insert($image800, 'center');
            $img800->save($resize800);

            $artist->image = $fileName;
        }
        $artist->save();
        return redirect('artist');

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
            $m = Artist::findOrFail($id);
            $img_path = base_path() . '/uploads/artist/source/';
            $img_path200 = base_path() . '/uploads/artist/resize200/';
            $img_path800 = base_path() . '/uploads/artist/resize800/';

            if ($m->image != null) {
                unlink(sprintf($img_path . '%s', $m->image));
                unlink(sprintf($img_path200 . '%s', $m->image));
                unlink(sprintf($img_path800 . '%s', $m->image));
            }
            $m->delete();
        }
    }
}
