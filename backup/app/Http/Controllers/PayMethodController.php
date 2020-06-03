<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Paymethod;
use File;
use Image;

class PayMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paymethod = Paymethod::all();
        return view('admin.paymethod.paymethod', compact('paymethod'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.paymethod.createpaymethod');
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
        $add = new Paymethod();
        $add->name = $request->name;
        $add->name_en = $request->name_en;
        $add->status = $request->status;
        $add->details = $request->details;
        $add->price = $request->price;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            // $destinationPath = base_path() . '/uploads/'; // upload path
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/payment/source/' . $fileName);
            $resize200 = base_path('uploads/payment/resize200/' . $fileName);
            $resize800 = base_path('uploads/payment/resize800/' . $fileName);
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

            $add->image = $fileName;
        }
        $add->save();
        return redirect('paymethods');
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
        $pay = Paymethod::find($id);
        return view('admin.paymethod.editpaymethod', compact('pay'));
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
        $add = Paymethod::find($id);
        $add->name = $request->name;
        $add->name_en = $request->name_en;
        $add->status = $request->status;
        $add->details = $request->details;
        $add->price = $request->price;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/payment/source/';
            $img_path200 = base_path() . '/uploads/payment/resize200/';
            $img_path800 = base_path() . '/uploads/payment/resize800/';

            if ($add->image != null) {
                if (file_exists(sprintf($img_path . '%s', $add->image)))
                    unlink(sprintf($img_path . '%s', $add->image));
                if (file_exists(sprintf($img_path200 . '%s', $add->image)))
                    unlink(sprintf($img_path200 . '%s', $add->image));
                if (file_exists(sprintf($img_path800 . '%s', $add->image)))
                    unlink(sprintf($img_path800 . '%s', $add->image));
            }
            // $destinationPath = base_path() . '/uploads/'; // upload path
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/payment/source/' . $fileName);
            $resize200 = base_path('uploads/payment/resize200/' . $fileName);
            $resize800 = base_path('uploads/payment/resize800/' . $fileName);
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

            $add->image = $fileName;
        }
        $add->save();
        return redirect('paymethods');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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
            $m = Paymethod::findOrFail($id);
            $img_path = base_path() . '/uploads/payment/source/';
            $img_path200 = base_path() . '/uploads/payment/resize200/';
            $img_path800 = base_path() . '/uploads/payment/resize800/';

            if ($m->image != null) {
                if (file_exists(sprintf($img_path . '%s', $m->image)))
                    unlink(sprintf($img_path . '%s', $m->image));
                if (file_exists(sprintf($img_path200 . '%s', $m->image)))
                    unlink(sprintf($img_path200 . '%s', $m->image));
                if (file_exists(sprintf($img_path800 . '%s', $m->image)))
                    unlink(sprintf($img_path800 . '%s', $m->image));
            }
            $m->delete();
        }
    }
}
