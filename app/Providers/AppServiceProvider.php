<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use view;
use App\Configurationsite;
use DB;
use App\Currency;
use App\Product;
use App\Category;
use App\Cart;
use Auth;
use App\Artist;
use App\CartSession;
use App\Sponsor;
use App\CategoryBrand;
use App\Page;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    
    public function boot()
    {

        date_default_timezone_set('Africa/Cairo');

        if(empty(\Session::get('currencychange'))){
            \Session::set('currencychange' , 1);
        }

        if(empty(\Session::get('currencysymbol'))){
            \Session::set('currencysymbol' , 'LE');
        }


        view()->composer('layouts.app', function($view) {

            \Session::set('lang' , 'ar');
//            $lang = \Session::get('lang');
            $con = Configurationsite::first();
//            if ($lang == null) {
//                $la = $con->default_lang;
//            }else{
//                $la = $lang;
//            }
//            $aa = $la;
            $la = 'ar';
            \App::setLocale('ar');

            if(Auth::check()){
                $cart = Cart::where('user_id',Auth::user()->id)->get();
            }else{
                $number = session()->get('cartsessionnumber');
                $cart = CartSession::where('session_number',$number)->get();

            }

            $currency = Currency::where('status',1)->get();
            $categorynoparent = Category::where('parent',0)->where('status',1)->get();
            $categoryparentids = DB::table('category')->where('parent','!=',0)->where('status',1)->pluck('parent');    
            $bestorder = 
            $bestorderids = DB::table('orders')->groupBy('product_id')->select('product_id',DB::raw('COUNT(id) as count_id'))->orderBy('count_id','DESC')->take(8)->pluck('product_id');
            $bestorder = Product::where('status',1)->whereIN('id',$bestorderids)->take(8)->get();
            
            $morediscount = Product::where('status',1)->orderBy('discount','desc')->take(8)->get();
            $catbrands = CategoryBrand::all();
            $pages = Page::where('lang',\App::getLocale())->get();
            
            $x = array();
            $y = array();
            
            $xx = array();
            $yy = array();

            foreach ($morediscount as $key => $value) {
                
                if($key >=4){
                    $y[$key] = $value;
                }else{
                    $x[$key] = $value;
                }
                
            }
            foreach ($bestorder as $key => $value) {
                
                if($key >=4){
                    $yy[$key] = $value;
                }else{
                    $xx[$key] = $value;
                }
                
            }
            array_filter($x);
            array_filter($y);
            array_filter($xx);
            array_filter($yy);
            $artist = Artist::where('status',1)->get();
            $rating = DB::table('rates')->groupBy('product_id')->select(DB::raw(DB::raw("SUM( value )/count(*) AS finalRate")),'product_id')->pluck('finalRate','product_id');
            $sponsor = Sponsor::all();
            $view->with('cart',$cart)
                 ->with('currency',$currency)
                 ->with('lan',$la)
                 ->with('catbrands',$catbrands)
                 ->with('pages',$pages)
                 ->with('categorynoparent',$categorynoparent)
                 ->with('categoryparentids',$categoryparentids)
                 ->with('x',$x)
                 ->with('y',$y)
                 ->with('xx',$xx)
                 ->with('yy',$yy)
                 ->with('con',$con)
                 ->with('sponsor',$sponsor)
                 ->with('rating',$rating)
                 ->with('artist',$artist)
                 ->with('cartcount',count($cart));
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
