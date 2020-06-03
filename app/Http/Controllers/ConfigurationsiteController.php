<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Configurationsite;
use File;
use Image;

class ConfigurationsiteController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:configration');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cs = Configurationsite::all();
        return view('admin.configurationsite.configurationsite', compact('cs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $con = Configurationsite::find($id);
        return view('admin.configurationsite.editconfigurationsite', compact('con'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $add = Configurationsite::find($id);
        $add->name = $request->name;
        $add->min_order = $request->min_order;
        $add->address = $request->address;
        $add->email = $request->email;
        $add->email_ads = $request->email_ads;
        $add->phone = $request->phone;
        $add->fax = $request->fax;
        $add->facebook = $request->facebook;
        $add->twitter = $request->twitter;
        $add->linkedin = $request->linkedin;
        $add->googleplus = $request->googleplus;
        $add->youtube = $request->youtube;
        $add->instgram = $request->instgram;
        $add->pinterest = $request->pinterest;
        $add->vimeo = $request->vimeo;
        $add->default_lang = $request->lang;
        $add->header_code = $request->header_code;
        $add->footer_code = $request->footer_code;
        $add->ads_link = $request->ads_link;
        $add->short_description = $request->short_description;
        $add->short_description_en = $request->short_description_en;
        \Session::set('lang', $add->default_lang);
        \App::setLocale($add->default_lang);

        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/configurationsite/source/';
            $img_path200 = base_path() . '/uploads/configurationsite/resize200/';
            $img_path800 = base_path() . '/uploads/configurationsite/resize800/';

            if ($add->logo != null) {
                unlink(sprintf($img_path . '%s', $add->logo));
                unlink(sprintf($img_path200 . '%s', $add->logo));
                unlink(sprintf($img_path800 . '%s', $add->logo));
            }
            // $destinationPath = base_path() . '/uploads/'; // upload path
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/configurationsite/source/' . $fileName);
            $resize200 = base_path('uploads/configurationsite/resize200/' . $fileName);
            $resize800 = base_path('uploads/configurationsite/resize800/' . $fileName);
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

            $add->logo = $fileName;
        }
        if ($request->hasFile("photoall")) {

            $file = $request->file("photoall");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/configurationsite/source/';
            $img_path200 = base_path() . '/uploads/configurationsite/resize200/';
            $img_path800 = base_path() . '/uploads/configurationsite/resize800/';

            if ($add->imageall != null) {
                unlink(sprintf($img_path . '%s', $add->imageall));
                unlink(sprintf($img_path200 . '%s', $add->imageall));
                unlink(sprintf($img_path800 . '%s', $add->imageall));
            }
            // $destinationPath = base_path() . '/uploads/'; // upload path
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/configurationsite/source/' . $fileName);
            $resize200 = base_path('uploads/configurationsite/resize200/' . $fileName);
            $resize800 = base_path('uploads/configurationsite/resize800/' . $fileName);
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

            $add->imageall = $fileName;
        }

        if ($request->hasFile("ads_image")) {

            $file = $request->file("ads_image");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/configurationsite/source/';
            $img_path200 = base_path() . '/uploads/configurationsite/resize200/';
            $img_path800 = base_path() . '/uploads/configurationsite/resize800/';

            if ($add->ads_image != null) {
                unlink(sprintf($img_path . '%s', $add->ads_image));
                unlink(sprintf($img_path200 . '%s', $add->ads_image));
                unlink(sprintf($img_path800 . '%s', $add->ads_image));
            }
            // $destinationPath = base_path() . '/uploads/'; // upload path
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/configurationsite/source/' . $fileName);
            $resize200 = base_path('uploads/configurationsite/resize200/' . $fileName);
            $resize800 = base_path('uploads/configurationsite/resize800/' . $fileName);
            //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];

            $width200 = ($widthreal / $heightreal) * 800;
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

            $add->ads_image = $fileName;
        }

        $add->save();

        return redirect("configurationsite/$add->id/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function showConfigSms(){
        $con = Configurationsite::first();
        return view('admin.configurationsite.configurationsms', compact('con'));
    }

    public function saveConfigSms(Request $request){
        $con = Configurationsite::first();
        $con->confirmed_sms = $request->confirmed_sms;
        $con->confirmed_sms_text = $request->confirmed_sms_text;
        $con->delivered_sms = $request->delivered_sms;
        $con->delivered_sms_text = $request->delivered_sms_text;

        $con->page_delivered_sms = $request->page_delivered_sms;
        $con->page_delivered_sms_text = $request->page_delivered_sms_text;
        $con->place_delivered_sms = $request->place_delivered_sms;
        $con->place_delivered_sms_text = $request->place_delivered_sms_text;
        $con->save();
        return redirect()->to('/config/sms');
    }

}
