<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Vendor;
use App\VendorDrags;
use DB;


class VendorController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $vendors = Vendor::all();
        //dd($vendors);
        return view('admin/vendors/vendors',compact('vendors'));
    }
    
    public function vendor_orders($id)
    {
    	$vendor = Vendor::find($id);
	
	$order_ids = DB::table('vendor_orders') -> where('vendor_id',$id) -> pluck('order_id');
	$order_date = DB::table('vendor_orders') -> where('vendor_id',$id)->groupBy('order_number') -> pluck('date');
		
	$orders = DB::table('orders')
	              ->selectRaw('id,number,status_id,sum(one_price*quantity) as total')
	              ->whereIn('id',$order_ids)
	              ->groupBy('number')
	              ->get();
	return view('admin/vendors/orders',compact('vendor','orders','order_date'));
    }
    
    public function vendor_drags($id)
    {
    	$vendor = Vendor::find($id);
    	$drags = $vendor -> drags;
    	return view('admin/vendors/drags',compact('vendor','drags'));
    }
    
    public function drag_status($ids)
    {
    	
    	$ids = explode(',', $ids);
        if ($ids[0] == 'on') {
            unset($ids[0]);
        }
        foreach ($ids as $id) {
            $drag = VendorDrags::findOrFail($id);
            $vendor = Vendor::findOrFail($drag -> vendor_id);
            if($drag -> status == 1)
            {
            	$drag -> status = 0;
            	$drag -> save();
            	$vendor -> balance += $drag -> drag_amount;
            	$vendor -> save();
            }
            else
            {
            	if($vendor -> balance - $drag -> drag_amount >= 0)
            	{
	            	$drag -> status = 1;
	            	$drag -> save();
	            	$vendor -> balance -= $drag -> drag_amount;
	            	$vendor -> save();
            	}
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::all();
        return view('admin/vendors/vendors_add',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendor = new Vendor;
        $vendor -> user_id = $request -> user;
        $vendor -> name = $request -> name;
        $vendor -> email = $request -> email;
        $vendor -> phone = $request -> phone;
        $vendor -> address = $request -> address;
        $vendor -> status = $request -> status;
        $vendor -> commission = $request -> commission;
        $vendor -> save();
        
        $user = User::find($vendor -> user_id);
        $user -> vendor = 1;
        $user -> save();
        
        return redirect() -> route('vendors.index');
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
        $users = User::all();
        $vendor = Vendor::find($id);
        return view('admin/vendors/vendors_edit',compact('users','vendor'));
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
        $vendor = Vendor::find($id);
        $vendor -> user_id = $request -> user;
        $vendor -> name = $request -> name;
        $vendor -> email = $request -> email;
        $vendor -> phone = $request -> phone;
        $vendor -> address = $request -> address;
        $vendor -> status = $request -> status;
        $vendor -> commission = $request -> commission;
        $vendor -> save();
        return redirect() -> route('vendors.index');
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
            $user = Vendor::findOrFail($id);
            $user->delete();
        }
    }
}
