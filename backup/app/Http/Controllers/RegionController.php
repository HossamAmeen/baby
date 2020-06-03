<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Region;
use DB;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $region = Region::all();
        return view('admin.regions.regions',compact('region'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $country = DB::table('countries')->lists('name','id');
        return view('admin.regions.createregions',compact('country'));
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
        $add = new Region();
        $add->name = $request->name;
        $add->shipping = $request->shipping;
        $add->shiping_time = $request->shiping_time ;
        $add->country_id = $request->country_id;
        $add->save();
        return redirect('regions');
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
        $region = Region::find($id);
        $country = DB::table('countries')->lists('name','id');
        return view('admin.regions.editregions',compact('country','region'));
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
        $add = Region::find($id);
        $add->name = $request->name;
        $add->shipping = $request->shipping;
        $add->shiping_time = $request->shiping_time ;
        $add->country_id = $request->country_id;
        $add->save();
        return redirect('regions');
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
            $m = Region::findOrFail($id);
            $m->delete();
        }
    }
}
