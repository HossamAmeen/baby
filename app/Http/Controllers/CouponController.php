<?php

namespace App\Http\Controllers;

use App\Configurationsite;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Coupon;
use DB;
use App\CouponArtist;
use App\CouponProduct;
use App\CouponUser;
use App\CouponRegion;
use App\CouponCategory;

class CouponController extends Controller
{


public function __construct()
    {
        $this->middleware('permission:coupon');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $coupon = Coupon::orderBy('id','desc')->get();
        return view('admin.coupon.coupon',compact('coupon'));
    }

    public function print_coupon($id)
    {
        //
        $ids = explode('_' , $id);
        $coupons = Coupon::whereIn('id' ,$ids)->get();
        if($coupons->count() === 0)
            return redirect('coupon');

        $setting = Configurationsite::first();

        return view('admin.coupon.print_coupon',compact('coupons' , 'setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function generateRandomString($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function create()
    {
        //
        while(true){
            $code = $this->generateRandomString(6);
            if(!Coupon::where(['code' => $code])->first())
                break;
        }

        return view('admin.coupon.createcoupon',compact('code' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->expire_date = $request->expire_date;
        $coupon->code = $request->code;
        $coupon->mini_order = $request->mini_order;
        $coupon->value = $request->value;
        $coupon->type = $request->type;
        $coupon->count = $request->count;
        $coupon->percent = $request->percent;
        $coupon->save();

        if ($request->Input('region_id')) {
            foreach ($request->Input('region_id') as $ur) {
                $t = new CouponRegion();
                $t->region_id = $ur;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }

        if ($request->Input('product_id')) {
            foreach ($request->Input('product_id') as $ur) {
                $t = new CouponProduct();
                $t->product_id = $ur;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }
        
        if ($request->Input('category_id')) {
            foreach ($request->Input('category_id') as $category_id) {
                $t = new CouponCategory();
                $t->category_id = $category_id;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
            $product_ids = DB::table('category_products')->whereIn('category_id',$request->Input('category_id'))->pluck('product_id');
            foreach ($product_ids as $ur) {
                $t = new CouponProduct();
                $t->product_id = $ur;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }

        if ($request->Input('user_id')) {
            foreach ($request->Input('user_id') as $ur) {
                $t = new CouponUser();
                $t->user_id = $ur;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }
        return redirect('coupon');
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
        $coupon = Coupon::find($id);
        $product = DB::table('products')->where('status',1)->get();
        $categories = DB::table('category')->where('status',1)->get();
        $user = DB::table('users')->get();
        $artist = DB::table('regions')->get();
        
        $couponcategory = DB::table('coupon_category')->where('coupon_id',$id)->pluck('category_id');
        $couponproduct = DB::table('coupon_product')->where('coupon_id',$id)->pluck('product_id');
        $couponuser = DB::table('coupon_user')->where('coupon_id',$id)->pluck('user_id');
        $couponartist = DB::table('coupon_region')->where('coupon_id',$id)->lists('region_id');
        $c = [
            [
                'id' => 1,
                'name' => trans('home.once')
            ],
            [
                'id' => 2,
                'name' => trans('home.more_once')
            ]
        ];
        return view('admin.coupon.editcoupon',compact('couponcategory','categories','couponproduct','couponuser','couponartist','coupon','product','user','artist' , 'c'));
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
        
        $coupon = Coupon::find($id);
        $coupon->name = $request->name;
        $coupon->expire_date = $request->expire_date;
        $coupon->code = $request->code;
        $coupon->value = $request->value;
        $coupon->type = $request->type;
        $coupon->count = $request->count;
        $coupon->percent = $request->percent;
        $coupon->save();

        CouponRegion::where('coupon_id',$id)->delete();
        if ($request->Input('region_id')) {
            foreach ($request->Input('region_id') as $ur) {
                $t = new CouponRegion();
                $t->region_id = $ur;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }

        CouponProduct::where('coupon_id',$id)->delete();
        if ($request->Input('product_id')) {
            foreach ($request->Input('product_id') as $ur) {
                $t = new CouponProduct();
                $t->product_id = $ur;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }
        
        CouponCategory::where('coupon_id',$id)->delete();
        if ($request->Input('category_id')) {
            foreach ($request->Input('category_id') as $category_id) {
                $t = new CouponCategory();
                $t->category_id = $category_id;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }
        
        CouponUser::where('coupon_id',$id)->delete();
        if ($request->Input('user_id')) {
            foreach ($request->Input('user_id') as $ur) {
                $t = new CouponUser();
                $t->user_id = $ur;
                $t->coupon_id = $coupon->id;
                $t->save();
            }
        }
        return redirect('coupon');
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
            $m = Coupon::findOrFail($id);
            CouponRegion::where('coupon_id',$id)->delete();
            CouponProduct::where('coupon_id',$id)->delete();
            CouponUser::where('coupon_id',$id)->delete();
            CouponCategory::where('coupon_id',$id)->delete();
            $m->delete();
        }
    }
}
