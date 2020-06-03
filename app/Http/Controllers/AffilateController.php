<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;
use App\Affilate;
use App\Affilate_Product;
use App\Affilate_Withdrow;
use App\AffilateOrder;


class AffilateController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $affilates = Affilate::all();
        return view('admin/affilate/affilates',compact('affilates'));
    }
    
    public function affilate_categories($id)
    {
    	$affilate = Affilate::find($id);
    	$categories = $affilate -> categories;
		
	return view('admin/affilate/affilate_category',compact('categories','affilate'));
    }
    
    public function affilate_products($id)
    {
    	$affilate = Affilate::find($id);
    	$products = $affilate -> affilate_product;
		
	return view('admin/affilate/affilate_products',compact('products','affilate'));
    }
    
    public function affilate_orders($id)
    {
    	$affilate = Affilate::find($id);
	$orders = $affilate -> orders;
	
	return view('admin/affilate/affilate_orders',compact('affilate','orders'));
    }
    
    public function affilate_drags($id)
    {
    	$affilate = Affilate::find($id);
    	
    	$withdrow = $affilate -> withdrow;
    	
    	return view('admin/affilate/affilate_withdrow',compact('affilate','withdrow'));
    }
    
    public function drag_status($ids)
    {
    	
    	$ids = explode(',', $ids);
        if ($ids[0] == 'on') {
            unset($ids[0]);
        }
        foreach ($ids as $id) {
            $withdrow = Affilate_Withdrow::findOrFail($id);
            $affilate = Affilate::findOrFail($withdrow -> affilate_id);
            if($withdrow -> status == 1)
            {
            	$withdrow -> status = 0;
            	$withdrow -> save();
            	$affilate -> balance += $withdrow -> drag_amount;
            	$affilate -> save();
            }
            else
            {
            	if($affilate -> balance - $withdrow -> drag_amount >= 0)
            	{
	            	$withdrow -> status = 1;
	            	$withdrow -> save();
	            	$affilate -> balance -= $withdrow -> drag_amount;
	            	$affilate -> save();
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
        return view('admin/affilate/affilate_add',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $affilate = new Affilate;
        $affilate -> user_id = $request -> user;
        $affilate -> name = $request -> name;
        $affilate -> email = $request -> email;
        $affilate -> phone = $request -> phone;
        $affilate -> address = $request -> address;
        $affilate -> status = $request -> status;
        $affilate -> save();
        
        $user = User::find($affilate -> user_id);
        $user -> affilate = 1;
        $user -> save();
        return redirect() -> route('affilates.index');
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
        $affilate = Affilate::find($id);
        return view('admin/affilate/affilate_edit',compact('users','affilate'));
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
        $affilate = Affilate::find($id);
        $affilate -> user_id = $request -> user;
        $affilate -> name = $request -> name;
        $affilate -> email = $request -> email;
        $affilate -> phone = $request -> phone;
        $affilate -> address = $request -> address;
        $affilate -> status = $request -> status;
        $affilate -> save();
        return redirect() -> route('affilates.index');
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
            $user = Affilate::findOrFail($id);
            $user->delete();
        }
    }
}
