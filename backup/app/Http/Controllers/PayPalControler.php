<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Paypal;
use Session;
use App\Cart;
use App\Order;
use App\Paymethod;
use App\Product;
use App\Vendor;
use DB;
use Auth;
use App\Affilate_category;
use App\Affilate_Product;
use App\ProductOption;
use App\Configurationsite;
use App;
use Response;
use App\Address;
use Mail;
use App\Repositories\OrderRepository;

class PayPalControler extends Controller
{
    private $_apiContext;

    public function __construct()
    {
        $this->_apiContext = PayPal::ApiContext(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        );
        $this->_apiContext->setConfig(array(
            /*'mode' => 'live',

              'service.EndPoint' => 'https://api.paypal.com',

              'http.ConnectionTimeOut' => 30,

              'log.LogEnabled' => true,

              'log.FileName' => storage_path('logs/paypal.log'),

              'log.LogLevel' => 'INFO'*/
            'mode' => 'sandbox',
            'service.EndPoint' => 'https://api.sandbox.paypal.com',
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'FINE'
        ));
    }


    public function payPremium()
    {
        return view('payPremium');
    }

    public function finishorder()
    {
        $con = Configurationsite::first();
        $order = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->take(1)->get();
        return view('orderfinal', compact('con', 'order'));
    }

    public function paymod($fundata)
    {
        $curl = curl_init();
        $data = [];
        $data['api_key'] = "ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnVZVzFsSWpvaWFXNXBkR2xoYkNJc0ltTnNZWE56SWpvaVRXVnlZMmhoYm5RaUxDSndjbTltYVd4bFgzQnJJam94TnpZMGZRLm5Dd1dTanRFVFRydjVkRUNEeWk5T2RLQlowU3podmJvcXZzWHBWek9wVDNxQjRDSzA5NTU2MnA1M1M4VmR4bEtOQk5XcXVQMEVfenFoNHhoUjFYaW53";
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://accept.paymobsolutions.com/api/auth/tokens",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $res = json_decode($response);
            $user = Auth::user();
            $address = Address::find($fundata['addressid']);
            //dd($address);
            $token = $res->token;
            $data = [];
            $data['auth_token'] = $token;
            $data['delivery_needed'] = 'false';
            $data['merchant_id'] = '1764';
            $data['amount_cents'] = $fundata['total'] * 100;
            $data['currency'] = 'EGP';
            $data['merchant_order_id'] = $fundata['ordernum'];
            $data['items'] = [];
            $data['shipping_data'] = ['apartment' => '',
                'email' => $user->email,
                'floor' => '',
                'first_name' => $user->name,
                'street' => '',
                'building' => '',
                'phone_number' => $address->phone,
                'postal_code' => '',
                'city' => $address->region->name,
                'country' => $address->country->name,
                'last_name' => 'user',
                'state' => ''];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://accept.paymobsolutions.com/api/ecommerce/orders",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $res = json_decode($response);

                $merchant_id = $res->id;
                $data = [];
                $data['auth_token'] = $token;
                $data['amount_cents'] = $fundata['total'] * 100;
                $data['expiration'] = '36000';
                $data['order_id'] = $merchant_id;
                $data['currency'] = 'EGP';
//	   	$data['integration_id'] = '2592'; // test
                $data['integration_id'] = '3302'; // live
                $data['lock_order_when_paid'] = 'false';
                $data['billing_data'] = ['apartment' => '101',
                    'email' => $user->email,
                    'floor' => '10',
                    'first_name' => $user->name,
                    'street' => 'Hasan Mohammed',
                    'building' => '8',
                    'phone_number' => $address->phone,
                    'postal_code' => '01898',
                    'city' => $address->region->name,
                    'country' => $address->country->name,
                    'last_name' => 'user',
                    'state' => 'Utah'];
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://accept.paymobsolutions.com/api/acceptance/payment_keys",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $res = json_decode($response);
                    //dd($res);
                    $payment_token = $res->token;
                    header('location: https://accept.paymobsolutions.com/api/acceptance/iframes/4965?payment_token=' . $payment_token);
                }
            }
        }
    }

    public function orderdetails()
    {
        $ordercart = Cart::where('user_id', Auth::user()->id)->get();
        $number = Order::orderBy('id', 'desc')->select('number')->first();
        $paymentid = Session::get('paymentid');
        $addressid = Session::get('addressid');
        foreach ($ordercart as $key => $value) {
            $order = new Order();
            if ($number) {
                $order->number = $number->number + 1;
            } else {
                $order->number += 1;
            }

            $orderNumber = $order->number;
            if ($paymentid == 3) {
                $order->payment_status = 1;
            }
            $order->user_id = $value->user_id;
            $order->product_id = $value->product_id;
            if (session('coupon_price') != null)
                $order->coupon_price = session('coupon_price');
            else
                $order->coupon_price = 0;
            $order->quantity = $value->count;
            if ($value->Product->discount) {
                $order->one_price = $value->Product->price - $value->Product->discount;
            } else {
                $order->one_price = $value->Product->price;
            }
            if ($key == 0) {
                $order->shipping_price = Session::get('shipping');
                //$order->comment = $comment;
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


            $product = Product::find($order->product_id);
            App\ProductLog::create([
                'product_id' => $product->id,
                'quantity' => $value->count,
                'stock' => $product->stock,
                'main_stock' => $product->main_stock,
                'store_stock' => $product->store_stock,
                'sub_stock' => $product->sub_stock,
                'action' => 'add_order',
                'user_id' => auth()->user()->id
            ]);
            OrderRepository::takeProductQuantity($order , $product , $value->count);
            $product->ordered += $value->count;
            $product->save();
            $order->save();

            if ($product->vendor) {
                $vendor = Vendor::find($product->vendor->id);
                $data = array('vendor_name' => $vendor->name, 'product_code' => $product->code, 'order_number' => $order->number);
                /*Mail::send('emails/vendor_order', $data, function($message) use ($vendor) {
                    $message->to($vendor -> email, 'Vendor')->subject('vendor add new product');
                    $message->from(config('mail.from.address'),config('mail.from.name'));
                });*/
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


        $e = Configurationsite::select('email')->first();
        $emailsales = $e->email;
        $data['to'] = Auth::user()->email;
        $data['subject'] = 'BabyMumz طلب جديد';
        $data['message'] = 'مرحبا بك ' . Auth::user()->name . '<br />' .
            'لقد قمت بطلب جديد رقم #' . $orderNumber . ' ' . 'لمتابعة الطلب اضغط علي الرابط التالي' . ' <a href="' . url('/my-orders/0') . '">طلباتي</a>';
        try {
            Mail::send('auth.emails.template', ['data' => $data], function ($message) use ($emailsales, $data) {
                $message->from($emailsales)
                    ->to($data['to'])
                    ->subject($data['subject']);
            });
        } catch (\Exception $ex) {

        }


        $paymentprice = $order[0]->payment_price;
        App::setLocale($aa);
        $ordertotal = DB::table('orders')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->take($ordercartcount)->groupBy('number')->sum('total_price');
        Session::forget('paymentid');
        Session::forget('addressid');
        $con = Configurationsite::first();
        if ($paymentid == 4) {
            return $this->paymod(['total' => $ordertotal, 'ordernum' => $order[0]->number, 'addressid' => $addressid]);
        } else {
            return view('orderfinal', ['con' => $con, 'order' => $order, 'lan' => $lan, 'optionproduct' => $optionproduct]);
        }

        //return Response::json([$html]);
    }


    public function getCheckout(Request $request)
    {
        Session::set('paymentid', $request->optradio);
        if ($request->optradio == 3) {
            $payer = PayPal::Payer();
            $payer->setPaymentMethod('paypal');

            $amount = PayPal::Amount();
            $amount->setCurrency('USD');
            $amount->setTotal($request->total);

            $transaction = PayPal::Transaction();
            $transaction->setAmount($amount);
            $transaction->setDescription('pay for this ' . $request->total);

            $redirectUrls = PayPal:: RedirectUrls();
            $redirectUrls->setReturnUrl(route('getDone'));
            $redirectUrls->setCancelUrl(route('getCancel'));

            $payment = PayPal::Payment();
            $payment->setIntent('sale');
            $payment->setPayer($payer);
            $payment->setRedirectUrls($redirectUrls);
            $payment->setTransactions(array($transaction));

            $response = $payment->create($this->_apiContext);
            $redirectUrl = $response->links[1]->href;

            return redirect()->to($redirectUrl);
        } else {
            return $this->orderdetails();
        }
    }


    public function getDone(Request $request)
    {
        $id = $request->get('paymentId');
        $token = $request->get('token');
        $payer_id = $request->get('PayerID');

        $payment = PayPal::getById($id, $this->_apiContext);

        $paymentExecution = PayPal::PaymentExecution();

        $paymentExecution->setPayerId($payer_id);
        $executePayment = $payment->execute($paymentExecution, $this->_apiContext);

        //dd($executePayment);
        return $this->orderdetails();
    }


    public function getCancel()
    {
        return redirect('my-cart');
    }

}
