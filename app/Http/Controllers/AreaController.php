<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Area;
use DB;
use App\Country;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $area = Area::all();
        return view('admin.areas.areas',compact('area'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $countries = Country::all();
        //$region = DB::table('regions')->get('name','id');
        return view('admin.areas.createareas',compact('countries'));
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
        $add = new Area();
        $add->name = $request->name;
        $add->shipping = $request->shipping;
        $add->region_id = $request->region_id;
        $add->save();
        return redirect('areas');
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
        $area = Area::find($id);
        $country = DB::table('countries')->lists('name' ,'id');
        $regions = Region::get(['name','shipping','id']);
        return view('admin.areas.editareas',compact('country','regions','area'));
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
        $add = Area::find($id);
        $add->name = $request->name;
        $add->shipping = $request->shipping;
        $add->region_id = $request->region_id;
        $add->save();
        return redirect('areas');
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
            $m = Area::findOrFail($id);
            $m->delete();
        }
    }
}
