<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\UserRole;
use DB;
use File;
use Image;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $u = User::all();
        return view('admin.users.user',compact('u'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $ur = DB::table('roles')->lists('display_name','id');
        return view('admin.users.createuser',compact('ur'));
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
        $add = new User();
        if ($request->Input('admin')) {
            $add->admin = $request->Input('admin');
        }
        $add->name = $request->Input('name');
        $add->email = $request->Input('email');
        $add->about = $request->Input('about'); 
        $add->delivery = $request->Input('delivery');
        $add->vendor = $request->Input('ventor');
        $add->affilate = $request->Input('affilate');
        
        $add->password = bcrypt($request->Input('password'));
        if ($request->hasFile("user_photo")) {

            $file = $request->file("user_photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);
            
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/user/source/' . $fileName);
            $resize200 = base_path('uploads/user/resize200/' . $fileName);
            $resize800 = base_path('uploads/user/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 200;
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

        if ($request->Input('roles_id')) {
            foreach ($request->Input('roles_id') as $ur) {
                $t = new UserRole();
                $t->user_id = $add->id;
                $t->role_id = $ur;
                $t->save();
            }
        }
        
        return redirect('user');

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
        $user = User::find($id);
        $ur = DB::table('roles')->lists('display_name','id');
        $urt = DB::table('role_user')->where('user_id', '=', $user->id)->lists('role_id');
        return view('admin.users.edituser',compact('user','ur','urt'));
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
        $update = User::find($id);
        $update->admin = $request->Input('admin');
        $update->name = $request->Input('name');
        $update->email = $request->Input('email');
        $update->about = $request->Input('about');
        
        $update->delivery = $request->delivery;
        $update->vendor = $request->ventor;
        $update->affilate = $request->affilate;
	    
            if ($request->Input('password')) {
                $update->password = bcrypt($request->Input('password'));
            }

            if ($request->hasFile("user_photo")) {

            $file = $request->file("user_photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            $img_path = base_path() . '/uploads/user/source/';
            $img_path200 = base_path() . '/uploads/user/resize200/';
            $img_path800 = base_path() . '/uploads/user/resize800/';

            if ($update->image != null) {
                unlink(sprintf($img_path . '%s', $update->image));
                unlink(sprintf($img_path200 . '%s', $update->image));
                unlink(sprintf($img_path800 . '%s', $update->image));
            }
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/user/source/' . $fileName);
            $resize200 = base_path('uploads/user/resize200/' . $fileName);
            $resize800 = base_path('uploads/user/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 200;
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

            $update->image = $fileName;
            }
        $update->save();
        

      
          UserRole::where('user_id',$id)->delete();

        if ($request->roles_id) {
            foreach ($request->roles_id as $ur) {
                $t = new UserRole();
                $t->user_id = $update->id;
                $t->role_id = $ur;
                $t->save();
            }
        }
        return redirect('user'); 
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
            $user = User::findOrFail($id);
            $user->delete();
        }
    }
}
