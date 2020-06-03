<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use App\Paymethod;
use App\Configurationsite;
use App\Address;
use App\Area;
use App\Region;
use Response;
use Auth;
use App\Cart;
use DB;
use App;
use App\Order;
use App\Product;
use App\Coupon;
use App\Vendor;
use Mail;
use App\ProductOption;
use App\Affilate_category;
use App\Currency;

class CalculateOrderController extends Controller
{

    public function currencycahange()
    {
        $currencies = Currency::all();

        foreach ($currencies as $currency) {

            $cSession = curl_init();
            //step2
            curl_setopt($cSession, CURLOPT_URL, "https://v3.exchangerate-api.com/pair/cb431ccfcd1ea60acc1bc4e0/EGP/" . $currency->code);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HEADER, false);
            //step3
            $result = curl_exec($cSession);
            //step4
            curl_close($cSession);
            //step5
            $result = json_decode($result, true);
            $newcurrency = Currency::find($currency->id);
            $newcurrency->rate = $result['rate'];
            $newcurrency->save();
        }
    }

    public function orderdetails()
    {

        //dd($_POST['comment']);
        $ordercart = Cart::where('user_id', Auth::user()->id)->get();
        $number = Order::orderBy('id', 'desc')->select('number')->first();
        $paymentid = $_POST['paymentid'];
        $comment = $_POST['comment'];
        //dd($comment);
        $addressid = Session::get('addressid');
        foreach ($ordercart as $key => $value) {
            $order = new Order();
            if ($number) {
                $order->number = $number->number + 1;
            } else {
                $order->number += 1;
            }
            $order->user_id = $value->user_id;
            $order->product_id = $value->product_id;
            $order->coupon_price = $value->coupon_price;
            $order->quantity = $value->count;
            if ($value->Product->discount) {
                $order->one_price = $value->Product->price - $value->Product->discount;
            } else {
                $order->one_price = $value->Product->price;
            }
            if ($key == 0) {
                $order->shipping_price = Session::get('shipping');
                $order->comment = $comment;
            } else {
                $order->shipping_price = 0;
            }

            $order->payment_id = $paymentid;
            $order->address_id = $addressid;
            $pay = Paymethod::find($paymentid);
            if ($key == 0) {
                $order->payment_price = $pay->price;
            } else {
                $order->payment_price = 0;
            }

            $order->option_price = $value->optionprice;

            $optionids = DB::table('option_cart')->where('cart_id', $value->id)->pluck('option_id');
            $order->option_ids = implode(",", $optionids);
            $xxx = 0;
            if (Session::has('coupon_price')) {
                $xxx = session()->pull('coupon_price', 'default');

                if ($xxx > 0) {
                    $order->total_price = (((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price)) - $xxx);
                    if ($order->total_price < 0) {
                        $order->total_price = 0;
                    }
                    $xxx -= ((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price));
                    if ($xxx > 0) {
                        session()->put('coupon_price', $xxx);
                    }
                }
            } else {
                $order->total_price = (((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price)) - $order->coupon_price);
            }
            $order->save();

            $product = Product::find($order->product_id);
            $product->stock -= $value->count;
            $product->ordered += $value->count;
            $product->save();

            if ($product->vendor) {
                $vendor = Vendor::find($product->vendor->id);
                $data = array('vendor_name' => $vendor->name, 'product_code' => $product->code, 'order_number' => $order->number);
                Mail::send('emails/vendor_order', $data, function ($message) use ($vendor) {
                    $message->to($vendor->email, 'Vendor')->subject('vendor add new product');
                    $message->from(config('mail.from.address'), config('mail.from.name'));
                });
            }

            if (session()->has('affilate')) {
                $affilate = session()->get('affilate');

                if (session()->has('category')) {
                    $category_id = session()->get('category');

                    //$product_ids = DB::table('category_products')->where('category_id',$category_id)->pluck('product_id');
                    //if(in_array($product -> id, $product_ids)){

                    $affilate_category = Affilate_category::where('affilate_id', $affilate->id)->where('category_id', $category_id)->first();
                    $affilate_category->orders += 1;
                    $affilate_category->save();

                    DB::table('affilate_orders')->insert(
                        ['affilate_id' => $affilate->id, 'order_id' => $order->id, 'order_number' => $order->number,
                            'date' => date('Y-m-d H:i:s'), 'commission' => ($product->affilate / 100) * $order->one_price * $order->quantity]
                    );
                    //}
                } else {

                    $affilate_product = Affilate_Product::where('affilate_id', $affilate->id)->where('product_id', $product->id)->first();

                    if ($affilate_product) {
                        $affilate_product->orders += 1;
                        $affilate_product->save();
                    }

                    DB::table('affilate_orders')->insert(
                        ['affilate_id' => $affilate->id, 'order_id' => $order->id, 'order_number' => $order->number,
                            'date' => date('Y-m-d H:i:s'), 'commission' => ($product->affilate / 100) * $order->one_price * $order->quantity]
                    );
                }

            }


        }
        $var = session()->pull('shipping_coupon_id', 'default');
        session()->pull('affilate', 'default');
        session()->pull('category', 'default');

        $ordercartcount = Cart::where('user_id', Auth::user()->id)->count();
        $order = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->take($ordercartcount)->get();
        Cart::where('user_id', Auth::user()->id)->delete();
        $optionproduct = ProductOption::where('status', 1)->get();
        $lang = Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $aa = $lan;

        $paymentprice = $order[0]->payment_price;
        App::setLocale($aa);
        $html = view('orderfinal', ['order' => $order, 'lan' => $lan, 'optionproduct' => $optionproduct])->render();
        $ordertotal = DB::table('orders')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->take($ordercartcount)->groupBy('number')->sum('total_price');
        return Response::json([$html]);
    }

    public function continueorder()
    {
        $lang = Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        if (Auth::check()) {
            $totalproductprice = 0;
            $ordercart = Cart::where('user_id', Auth::user()->id)->get();
            foreach ($ordercart as $key => $value) {
                if ($value->Product->discount) {
                    $totalproductprice += (($value->Product->price - $value->Product->discount) + $value->optionprice) * $value->count;
                } else {
                    $totalproductprice += ($value->Product->price + $value->optionprice) * $value->count;
                }
            }

            $totalproductprice = $totalproductprice * Session::get('currencychange');
            $min_order = $con->min_order * Session::get('currencychange');
            if($totalproductprice < $min_order){
                session()->put('error', trans('site.min_order_value_error')
                    . ' '. $min_order . ' '.Session::get('currencysymbol'));
                return redirect('my-cart');
            }

            $address = Address::where('user_id', Auth::user()->id)->orderBy('id' , 'desc')->get();
            $paymethods = Paymethod::where('status', 1)->get();

            $links = session()->has('links') ? session('links') : [];
            $currentLink = request()->path(); // Getting current URI like 'category/books/'
            array_unshift($links, $currentLink); // Putting it in the beginning of links array
            session(['links' => $links]); // Saving links array to the session
            if (count($address) > 0) {
                return view('website.continueorder', compact('lan', 'address', 'paymethods', 'ordercart'));
            } else {
                return redirect('addaddress');
            }

        } else {

            return view('auth.login');
        }
    }

    public function getpayment()
    {
        $addressid = $_POST['addressid'];
        $totalpro = $_POST['totalpro'];
        Session::set('addressid', $addressid);
        $paymethods = Paymethod::where('status', 1)->get();
        $lang = Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }

        $address = Address::find($addressid);
        //dd($address);
        $area = Area::where('id', $address->area_id)->first();
        $ordercart = Cart::where('user_id', Auth::user()->id)->get();
        $shipingarr = array();

        if ($area->shipping != '0.00') {
            foreach ($ordercart as $cartpro) {
                $shipingarr[] = $area->shipping * $cartpro->Product->shipping;
            }
            $shiping = max($shipingarr);
            $shiping1 = $shiping * Session::get('currencychange');
        } else {
            $region = Region::where('id', $address->region_id)->first();
            foreach ($ordercart as $cartpro) {
                $shipingarr[] = $region->shipping * $cartpro->Product->shipping;
            }
            $shiping = max($shipingarr);

        }

        if (Session::has('shipping_coupon_id')) {
            $coupon = Coupon::find(session()->get('shipping_coupon_id'));
            $shipping_regions = DB::table('coupon_region')->where('coupon_id', $coupon->id)->pluck('region_id');
            $shipping_region = DB::table('coupon_region')->where('coupon_id', $coupon->id)->where('region_id', $address->region_id)->first();
            if (count($shipping_regions) > 0) {
                if ($shipping_region) {
                    if ($coupon->value > 0) {
                        $shiping -= $coupon->value;
                    } else {
                        $shiping = 0;
                    }
                }
            } else {
                if ($coupon->value > 0) {
                    $shiping -= $coupon->value;
                } else {
                    $shiping = 0;
                }
            }
        }
        $shiping1 = $shiping * Session::get('currencychange');
        Session::set('shipping', $shiping);

        $html = view('website.getpayment', ['totalpro' => $totalpro, 'shiping' => $shiping, 'lan' => $lan, 'paymethods' => $paymethods])->render();
        return Response::json([$html, $shiping1, $shiping]);
    }

}