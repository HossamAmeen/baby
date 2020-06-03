<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Delivery;
use DB;
use App\User;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $delivery = Delivery::all();
        return view('admin.deliveries.deliveries',compact('delivery'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = DB::table('users')->lists('name','id');
        return view('admin.deliveries.createdeliveries',compact('user'));
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
        $add = new Delivery();
        $add->user_id = $request->user_id;
        $add->name = $request->name;
        $add->phone = $request->phone;
        $add->email = $request->email;
        $add->address = $request->address;
        $add->save();
        if($add->user_id){
             $user = User::find($add->user_id);
             $user->delivery = 1;
             $user->save();
        }
        return redirect('deliveries');
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
        $delivery = Delivery::find($id);
        $user = DB::table('users')->lists('name','id');
        return view('admin.deliveries.editdeliveries',compact('delivery','user'));
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
        $add = Delivery::find($id);
        if($request->user_id){
             $user = User::find($add->user_id);
             $user->delivery = 0;
             $user->save();
        }
        $add->user_id = $request->user_id;
        $add->name = $request->name;
        $add->phone = $request->phone;
        $add->email = $request->email;
        $add->address = $request->address;
        $add->save();
        if($add->user_id){
             $user = User::find($add->user_id);
             $user->delivery = 1;
             $user->save();
        }
        return redirect('deliveries');
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
            $m = Delivery::findOrFail($id);
            $m->delete();
        }
    }
}
