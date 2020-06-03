<?php

namespace App\Http\Controllers;

use App\Address;
use App\Affilate;
use App\Area;
use App\Country;
use App\Delivery;
use App\Http\Controllers\Admin\SMSProvider;
use App\OrderUserOpertation;
use App\Paymethod;
use App\ProductLog;
use App\Region;
use App\Repositories\OrderRepository;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\StoreOrder;
use App\Order;
use App\ProductOption;
use App\Configurationsite;
use DB;
use Auth;
use App\OrderStatus;
use App\Product;

class StoreOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $order = StoreOrder::select(DB::raw('id,admin_name,status_date,shipping_price,delivery_id,notes,
        admin_id,address_id,number,from_place,name,phone,product_id,created_at,sum(total_price) as total, status_id'))
            ->whereIn('status_id', array(1, 2))
            ->where(['from_place' => 0])
            ->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();
        $orderNu = StoreOrder::whereIn('status_id', array(1, 2))
            ->where(['from_place' => 0])
            ->orderBy('id', 'desc')
            ->get();
        return view('admin/store_orders/store_orders', compact('order', 'orderNu'));
    }

    public function showPlaceStoreOrders()
    {
        $order = StoreOrder::select(DB::raw('id,admin_name,status_date,shipping_price,delivery_id,notes,
        admin_id,address_id,number,from_place,name,phone,product_id,created_at,sum(total_price) as total, status_id'))
            ->whereIn('status_id', array(1, 2))
            ->where(['from_place' => 1])
            ->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();
        $orderNu = StoreOrder::whereIn('status_id', array(1, 2))
            ->where(['from_place' => 1])
            ->orderBy('id', 'desc')
            ->get();
        $place = true;
        return view('admin/store_orders/store_orders', compact('order', 'orderNu' , 'place'));
    }

    public function showFinishedPlaceStoreOrders()
    {
        $order = StoreOrder::select(DB::raw('id,admin_name,status_date,shipping_price,delivery_id,notes,
        admin_id,address_id,number,from_place,name,phone,product_id,created_at,sum(total_price) as total, status_id'))
            ->whereIn('status_id', array(3,4,5,6))
            ->where(['from_place' => 1])
            ->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();
        $orderNu = StoreOrder::whereIn('status_id', array(3,4,5,6))
            ->where(['from_place' => 1])
            ->orderBy('id', 'desc')
            ->get();
        $place = true;
        return view('admin/store_orders/store_orders', compact('order', 'orderNu' , 'place'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$users = User::all();
        $products = Product::where('stock', '>=', 1)->get(['id', 'title_ar', 'code']);
        $paymethods = Paymethod::all();
        $counteries = Country::all();
        $areas = Area::all();
        $regions = Region::all();
        $deliveries = DB::table('deliveries')->lists('name', 'id');
        $admins = User::where('admin', 1)->get();
        return view('admin/store_orders/store_order_add', compact('products', 'paymethods',
            'counteries', 'areas', 'regions', 'deliveries', 'admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $storeOrder = StoreOrder::orderBy('id', 'desc')->first();
        $number = $this->createNumber($storeOrder);


        $products = $request->products;
        $payment = Paymethod::find($request->paymethod);
        $quantities = $request->amounts;

        for ($i = 0; $i < count($products); $i++) {
            $product = Product::find($products[$i]);
            $order = new StoreOrder();
            $order->number = $number;
            $order->name = $request->customer_name;
            $order->notes = $request->notes;
            $order->phone = $request->customer_phone;
            $order->product_id = $product->id;
            $order->status_date = date('Y-m-d');

            $order->quantity = $quantities[$i];
            $order->coupon_price = 0;
            $order->main_stock = 0;
            $order->sub_stock = 0;
            $order->store_stock = 0;

            ProductLog::create([
                'product_id' => $product->id,
                'quantity' => $order->quantity,
                'stock' => $product->stock,
                'main_stock' => $product->main_stock,
                'store_stock' => $product->store_stock,
                'sub_stock' => $product->sub_stock,
                'action' => 'add_store_order',
                'user_id' => auth()->user()->id
            ]);
            OrderRepository::takeProductQuantity($order, $product, $order->quantity);

            if ($product->discount) {
                $order->one_price = $product->price - $product->discount;
            } else {
                $order->one_price = $product->price;
            }
            if ($i == 0) {
                $order->shipping_price = $request->shipping_price;
                $order->payment_price = ($payment) ? $payment->price : 0;
            } else {
                $order->shipping_price = 0;
                $order->payment_price = 0;
            }


            $order->option_price = 0;
            //$optionids = DB::table('option_cart')->where('cart_id',$value->id)->pluck('option_id');

            $address = new Address();

            $address->country_id = $request->country;
            $address->region_id = $request->region;
            $address->address = $request->address;
            $address->area_id = $request->area;
            $address->save();

            $order->payment_id = $payment->id;
            $order->address_id = $address->id;

            $order->admin_id = auth()->user()->id;
            $order->admin_name = $request->admin_name;

            $order->option_ids = '';
            $order->total_price = (((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price)) - $order->coupon_price);
            $order->save();

            $product->ordered += $quantities[$i];
            $product->save();
        }

        if (isset($order) && $order)
            return redirect()->route('store_orders.edit', ['id' => $order->number]);
        else
            return redirect()->route('store_orders.create');
    }

    public function createNumber($storeOrder)
    {
        if ($storeOrder) {
            if (substr($storeOrder->number, 0, 2) === 'st') {
                $number = 'st' . (intval(substr($storeOrder->number, 2)) + 1);
            } else {
                $number = 'st' . (intval($storeOrder->number) + 1);
            }
        } else {
            $number = 'st120';
        }

        $order = StoreOrder::where(['number' => $number])->first();
        if ($order) {
            $number = $this->createNumber($order);
        }
        return $number;
    }

    public function createPlaceNumber($storeOrder)
    {
        if ($storeOrder) {
            if (substr($storeOrder->number, 0, 2) === 'sp') {
                $number = 'sp' . (intval(substr($storeOrder->number, 2)) + 1);
            } else {
                $number = 'sp' . (intval($storeOrder->number) + 1);
            }
        } else {
            $number = 'sp120';
        }

        $order = StoreOrder::where(['number' => $number])->first();
        if ($order) {
            $number = $this->createPlaceNumber($order);
        }
        return $number;
    }

    public function showAddPlaceStoreOrder(Request $request)
    {
        $products = Product::where('stock', '>=', 1)->get(['id', 'title_ar', 'code']);
        $paymethods = Paymethod::all();
        $counteries = Country::all();
        $areas = Area::all();
        $regions = Region::all();
        $deliveries = DB::table('deliveries')->lists('name', 'id');
        $admins = User::where('admin', 1)->get();
        return view('admin/store_orders/place_store_order_add', compact('products', 'paymethods',
            'counteries', 'areas', 'regions', 'deliveries', 'admins'));
    }

    public function saveAddPlaceStoreOrder(Request $request)
    {

        $storeOrder = StoreOrder::orderBy('id', 'desc')->first();
        $number = $this->createPlaceNumber($storeOrder);


        $products = $request->products;
        $payment = Paymethod::find($request->paymethod);
        $quantities = $request->amounts;

        for ($i = 0; $i < count($products); $i++) {
            $product = Product::find($products[$i]);

            $order = new StoreOrder();
            $order->number = $number;
            $order->name = $request->customer_name;
            $order->notes = $request->notes;
            $order->phone = $request->customer_phone;
            $order->product_id = $product->id;
            $order->status_date = date('Y-m-d');

            $order->quantity = $quantities[$i];
            $order->coupon_price = 0;
            $order->main_stock = 0;
            $order->sub_stock = 0;
            $order->store_stock = 0;

            ProductLog::create([
                'product_id' => $product->id,
                'quantity' => $order->quantity,
                'stock' => $product->stock,
                'main_stock' => $product->main_stock,
                'store_stock' => $product->store_stock,
                'sub_stock' => $product->sub_stock,
                'action' => 'add_place_store_order',
                'user_id' => auth()->user()->id
            ]);

            OrderRepository::takePlaceProductQuantity($order, $product, $order->quantity);

            if ($product->discount) {
                $order->one_price = $product->price - $product->discount;
            } else {
                $order->one_price = $product->price;
            }

            $order->shipping_price = 0;
            $order->payment_price = 0;


            $order->option_price = 0;
            //$optionids = DB::table('option_cart')->where('cart_id',$value->id)->pluck('option_id');

            $address = new Address();

            $address->country_id = $request->country;
            $address->region_id = $request->region;
            $address->address = $request->address;
            $address->area_id = $request->area;
            $address->save();

            $order->payment_id = $payment->id;
            $order->address_id = $address->id;

            $order->admin_id = auth()->user()->id;
            $order->admin_name = $request->admin_name;

            $order->option_ids = '';
            $order->total_price = (((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price)) - $order->coupon_price);

            $order->from_place = 1;
            $order->save();

            $product->ordered += $quantities[$i];
            $product->save();
        }

        if (isset($order) && $order)
            return redirect()->to(url('/place/store_order/edit/' . $order->number));
        else
            return redirect()->to(url('/place/store_order/create'));
    }

    public function showEditPlaceStoreOrder($id, Request $request)
    {
        $order = StoreOrder::where('number', $id)->get();
        $productIds = StoreOrder::where('number', $id)->pluck('product_id');

        $products = Product::where('stock', '>=', 1)
            ->whereNotIn('id', $productIds)
            ->get(['id', 'title_ar', 'code']);

        $address = Address::where(['id' => $order[0]->address_id])->first();

        $option = ProductOption::where('status', 1)->get();
        $con = Configurationsite::first();
//        $products = Product::all('id', 'title_ar', 'code');
        $paymethods = Paymethod::all();
        $counteries = Country::all();
        $areas = Area::all();
        $regions = Region::all();
        $status = OrderStatus::where('id', '>=', $order[0]->status_id)
            ->where('id', '!=', 4)->get();
        if ($order[0]->status_id === 4) {
            $status[] = OrderStatus::where(['name' => 'confirmed'])->first();
        } else if ($order[0]->status_id === 6) {
            $status[] = OrderStatus::where(['name' => 'refunded'])->first();
        }
        $deliveries = Delivery::get(['name', 'id']);
        $admins = User::where('admin', 1)->get();
        $orderoperation = OrderUserOpertation::where('order_number', $id)->get();
        return view('admin/store_orders/place_store_order_edit', compact('order', 'option', 'con'
            , 'paymethods', 'counteries', 'areas', 'regions', 'orderoperation',
            'deliveries', 'admins', 'products', 'address', 'status'));

    }

    public function saveEditPlaceStoreOrder($id, Request $request)
    {
        $id = $request->id;
        $order = StoreOrder::where('number', $id)->get();
        $number = $id;
        $products = (isset($request->products)) ? $request->products : [];

        $payment = Paymethod::find($request->paymethod);
        $quantities = $request->amounts;

        $newAmounts = $request->addamounts;
        $sendSMS = false;

        if (count($order) > 0) {
            if ($order[0]->status_id != $request->status) {
                $operation = new OrderUserOpertation();
                $operation->order_number = $number;
                $operation->user_id = \Illuminate\Support\Facades\Auth::user()->id;
                $operation->message = 'status change from ' . OrderStatus::find($order[0]->status_id)->name . ' to ' . OrderStatus::find($request->status)->name;
                $operation->date_time = date('Y-m-d H:i:s');
                $operation->save();
            }
            foreach ($products as $key => $productId) {
                $or = new StoreOrder();
                $or->number = $number;
                $or->quantity = $newAmounts[$key];
                $product = Product::find(intval($productId));
                $or->product_id = $product->id;
                $or->main_stock = 0;
                $or->sub_stock = 0;
                $or->store_stock = 0;
                ProductLog::create([
                    'product_id' => $product->id,
                    'quantity' => $order->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'action' => 'edit_place_store_order',
                    'user_id' => auth()->user()->id
                ]);
                OrderRepository::takePlaceProductQuantity($or, $product, $or->quantity);
                $product->ordered += $or->quantity;
                $product->save();
                $or->delivery_id = $request->delivery_id;
                $or->payment_id = $request->paymethod;
                $or->status_date = date('Y-m-d');

                $or->name = $request->customer_name;
                $or->notes = $request->notes;
                $or->phone = $request->customer_phone;
                $address = Address::where(['id' => $or->address_id])->first();
                if (!$address)
                    $address = new Address();
                $address->country_id = $request->country;
                $address->region_id = $request->region;
                $address->address = $request->address;
                $address->area_id = $request->area;
                $address->save();
                $or->payment_id = $payment->id;
                $or->address_id = $address->id;
                $or->admin_name = $request->admin_name;
                $or->status_id = $request->status;

                $or->coupon_price = 0;


                if ($product->discount) {
                    $or->one_price = $product->price - $product->discount;
                } else {
                    $or->one_price = $product->price;
                }
                $or->shipping_price = 0;
                $or->payment_price = 0;
                $or->option_price = 0;
                $or->total_price = (((($or->one_price + $or->option_price) * $or->quantity) + ($or->shipping_price + $or->payment_price)) - $or->coupon_price);
                $or->admin_id = auth()->user()->id;
                $or->save();
            }

            foreach ($order as $key => $value) {
                $or = StoreOrder::find($value->id);
                $product = Product::find($or->product_id);

                if (intval($quantities[$key]) > 0 && intval($quantities[$key]) !== intval($value->quantity)) {
                    if (intval($quantities[$key]) > intval($value->quantity)) {
                        $quantity = $quantities[$key] - $value->quantity;
                        ProductLog::create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'stock' => $product->stock,
                            'main_stock' => $product->main_stock,
                            'store_stock' => $product->store_stock,
                            'sub_stock' => $product->sub_stock,
                            'action' => 'edit_place_store_order',
                            'user_id' => auth()->user()->id
                        ]);
                        OrderRepository::takePlaceProductQuantity($or, $product, $quantity);
                        $product->ordered += $quantity;
                        $product->save();
                        $or->quantity = $or->quantity + $quantity;
                    } else if (intval($quantities[$key]) < intval($value->quantity)) {
                        $quantity = $value->quantity - $quantities[$key];
                        ProductLog::create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'stock' => $product->stock,
                            'main_stock' => $product->main_stock,
                            'store_stock' => $product->store_stock,
                            'sub_stock' => $product->sub_stock,
                            'action' => 'edit_place_store_order',
                            'user_id' => auth()->user()->id
                        ]);
                        $or->quantity = $quantities[$key];
                        OrderRepository::backPlaceProductQuantity($or, $product, $quantity);
                        $product->save();
                    }
                }

                $or->delivery_id = $request->delivery_id;
                $or->payment_id = $request->paymethod;
                $or->status_date = date('Y-m-d');
                if ($key == 0) {
                    $payment = Paymethod::find($request->paymethod);
                    $or->shipping_price = 0;
                    $or->actual_shipping_price = 0;
                    $or->payment_price = 0;
                    $or->total_price = (((($or->one_price + $or->option_price) * $or->quantity) + ($or->shipping_price + $or->payment_price)) - $or->coupon_price);
                }

                $or->name = $request->customer_name;
                $or->notes = $request->notes;
                $or->phone = $request->customer_phone;
                $address = Address::where(['id' => $or->address_id])->first();
                if (!$address)
                    $address = new Address();
                $address->country_id = $request->country;
                $address->region_id = $request->region;
                $address->address = $request->address;
                $address->area_id = $request->area;
                $address->save();

                $or->payment_id = $payment->id;
                $or->address_id = $address->id;

//                $or->admin_id = auth()->user()->id;

                $old_status = intval($or->status_id);

                $or->admin_name = $request->admin_name;
                $or->status_id = $request->status;
                $or->save();

                 if (intval($or->status_id) === 2 && !$sendSMS) { // delivered
                    $configuration = Configurationsite::first();
                    if(intval($configuration->page_delivered_sms) === 1) {
                        $phones = str_replace([
                            ' ',
                            '/'
                        ], [
                            '',
                            ','
                        ], $or->phone);
                        $configuration->page_delivered_sms_text = str_replace('#' , $or->number ,
                            $configuration->page_delivered_sms_text);
                        $sendSMS = SMSProvider::sendSMS($configuration->page_delivered_sms_text, $phones);
                    }
                }

                if (intval($or->status_id) === 6 && !$sendSMS) { // delivered
                    $configuration = Configurationsite::first();
                    if(intval($configuration->place_delivered_sms) === 1) {
                        $phones = str_replace([
                            ' ',
                            '/'
                        ], [
                            '',
                            ','
                        ], $or->phone);
                        $configuration->place_delivered_sms_text = str_replace('#' , $or->number ,
                            $configuration->place_delivered_sms_text);
                        $sendSMS = SMSProvider::sendSMS($configuration->place_delivered_sms_text, $phones);
                    }
                }

                if (intval($or->status_id) === 3) {
                    ProductLog::create([
                        'product_id' => $product->id,
                        'quantity' => $or->quantity,
                        'stock' => $product->stock,
                        'main_stock' => $product->main_stock,
                        'store_stock' => $product->store_stock,
                        'sub_stock' => $product->sub_stock,
                        'action' => 'cancel_place_store_order',
                        'user_id' => auth()->user()->id
                    ]);
                    if ($old_status === 1) {
                        $product->main_stock += $or->main_stock;
                        $product->sub_stock += $or->sub_stock;
                        $product->store_stock += $or->store_stock;
                    } else {
                        $product->store_stock += $or->main_stock + $or->sub_stock + $or->store_stock;
                    }
                    $product->stock += $or->quantity;
                    $product->ordered -= $or->quantity;
                    $product->save();
                }

                if ($or->status_id === 4) {
                }

                if (intval($or->status_id) === 5) {
                    ProductLog::create([
                        'product_id' => $product->id,
                        'quantity' => $or->quantity,
                        'stock' => $product->stock,
                        'main_stock' => $product->main_stock,
                        'store_stock' => $product->store_stock,
                        'sub_stock' => $product->sub_stock,
                        'action' => 'return_place_store_order',
                        'user_id' => auth()->user()->id
                    ]);
                    if ($old_status === 1) {
                        $product->main_stock += $or->main_stock;
                        $product->sub_stock += $or->sub_stock;
                        $product->store_stock += $or->store_stock;
                    } else {
                        $product->store_stock += $or->main_stock + $or->sub_stock + $or->store_stock;
                    }
                    if ($old_status === 6) {
                        $product->delivered -= $or->quantity;
                    }
                    $product->stock += $or->quantity;
                    $product->ordered -= $or->quantity;
                    $product->save();
                }

                if (intval($or->status_id) === 6) {
                    $product->ordered -= $or->quantity;
                    $product->delivered += $or->quantity;
                    $product->save();
                    if ($product->vendor) {
                        DB::table('vendor_orders')->insert(
                            ['vendor_id' => $product->vendor->id, 'order_id' => $or->id, 'order_number' => $or->number, 'date' => date('Y-m-d H:i:s')]
                        );

                        $vendor = Vendor::find($product->vendor->id);
                        $xxx = ($or->one_price + $or->option_price) * $or->quantity;
                        $vendor->balance += $xxx - ($xxx * ($vendor->commission / 100));
                        $vendor->save();
                    }

                    $affilate_order = DB::table('affilate_orders')->where('order_id', $or->id)->first();

                    if ($affilate_order) {
                        $affilate = Affilate::find($affilate_order->affilate_id);

                        $affilate->balance += $affilate_order->commission;
                        $affilate->save();
                    }
                }
            }
        }

        if ($request->status == 4) {
            return redirect('/place/store_orders/finished');
        } else if ($request->status == 1 | $request->status == 2) {
            return redirect('/place/store_orders');
        } else {
            return redirect('/place/store_orders/finished');
        }
    }

    public function showPrintPlaceStoreOrder($id, Request $request)
    {
        $order = StoreOrder::where(['number' => $id])->get();
        if($order->count() > 0) {
            $order[0]->final_total = $order->sum('total_price');
        }
        return view('admin/place_orders/print', compact('order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function getProductDetails(Request $request)
    {
        $product = Product::where(['id' => $request->id])->first();
        if ($product && $product->stock > 0) {
            $product->image = url('uploads/product/resize800') . '/' . $product->image;
            return response()->json([
                'data' => $product
            ], 200);
        }
        return response()->json([], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $order = StoreOrder::where('number', $id)->get();
        $productIds = StoreOrder::where('number', $id)->pluck('product_id');

        $products = Product::where('stock', '>=', 1)
            ->whereNotIn('id', $productIds)
            ->get(['id', 'title_ar', 'code']);

        $address = Address::where(['id' => $order[0]->address_id])->first();

        $option = ProductOption::where('status', 1)->get();
        $con = Configurationsite::first();
//        $products = Product::all('id', 'title_ar', 'code');
        $paymethods = Paymethod::all();
        $counteries = Country::all();
        $areas = Area::all();
        $regions = Region::all();
        $status = OrderStatus::where('id', '>=', $order[0]->status_id)->get();
        if ($order[0]->status_id === 4) {
            $status[] = OrderStatus::where(['name' => 'confirmed'])->first();
        } else if ($order[0]->status_id === 6) {
            $status[] = OrderStatus::where(['name' => 'refunded'])->first();
        }
        $deliveries = Delivery::get(['name', 'id']);
        $admins = User::where('admin', 1)->get();
        $orderoperation = OrderUserOpertation::where('order_number', $id)->get();
        return view('admin/store_orders/store_order_edit', compact('order', 'option', 'con'
            , 'paymethods', 'counteries', 'areas', 'regions', 'deliveries', 'orderoperation' ,
            'admins', 'products', 'address', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sendSMS = false;
        $id = $request->id;
        $order = StoreOrder::where('number', $id)->get();
        $number = $id;
        $products = (isset($request->products)) ? $request->products : [];

        $payment = Paymethod::find($request->paymethod);
        $quantities = $request->amounts;

        $newAmounts = $request->addamounts;

        if (count($order) > 0) {
            if ($order[0]->status_id != $request->status) {
                $operation = new OrderUserOpertation();
                $operation->order_number = $number;
                $operation->user_id = \Illuminate\Support\Facades\Auth::user()->id;
                $operation->message = 'status change from ' . OrderStatus::find($order[0]->status_id)->name . ' to ' . OrderStatus::find($request->status)->name;
                $operation->date_time = date('Y-m-d H:i:s');
                $operation->save();
            }
            foreach ($products as $key => $productId) {
                $or = new StoreOrder();
                $or->number = $number;
                $or->quantity = $newAmounts[$key];
                $product = Product::find(intval($productId));
                $or->product_id = $product->id;
                $or->main_stock = 0;
                $or->sub_stock = 0;
                $or->store_stock = 0;
                ProductLog::create([
                    'product_id' => $product->id,
                    'quantity' => $order->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'action' => 'edit_store_order',
                    'user_id' => auth()->user()->id
                ]);
                OrderRepository::takeProductQuantity($or, $product, $or->quantity);
                $product->ordered += $or->quantity;
                $product->save();
                $or->delivery_id = $request->delivery_id;
                $or->payment_id = $request->paymethod;
                $or->status_date = date('Y-m-d');

                $or->name = $request->customer_name;
                $or->notes = $request->notes;
                $or->phone = $request->customer_phone;
                $address = Address::where(['id' => $or->address_id])->first();
                if (!$address)
                    $address = new Address();
                $address->country_id = $request->country;
                $address->region_id = $request->region;
                $address->address = $request->address;
                $address->area_id = $request->area;
                $address->save();
                $or->payment_id = $payment->id;
                $or->address_id = $address->id;
                $or->admin_name = $request->admin_name;
                $or->status_id = $request->status;

                $or->coupon_price = 0;


                if ($product->discount) {
                    $or->one_price = $product->price - $product->discount;
                } else {
                    $or->one_price = $product->price;
                }
                $or->shipping_price = 0;
                $or->payment_price = 0;
                $or->option_price = 0;
                $or->total_price = (((($or->one_price + $or->option_price) * $or->quantity) + ($or->shipping_price + $or->payment_price)) - $or->coupon_price);
                $or->admin_id = auth()->user()->id;
                $or->save();
            }

            foreach ($order as $key => $value) {
                $or = StoreOrder::find($value->id);
                $product = Product::find($or->product_id);


                if (intval($quantities[$key]) > 0 && intval($quantities[$key]) !== intval($value->quantity)) {
                    if (intval($quantities[$key]) > intval($value->quantity)) {
                        $quantity = $quantities[$key] - $value->quantity;
                        ProductLog::create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'stock' => $product->stock,
                            'main_stock' => $product->main_stock,
                            'store_stock' => $product->store_stock,
                            'sub_stock' => $product->sub_stock,
                            'action' => 'edit_store_order',
                            'user_id' => auth()->user()->id
                        ]);
                        OrderRepository::takeProductQuantity($or, $product, $quantity);
                        $product->ordered += $quantity;
                        $product->save();
                        $or->quantity = $or->quantity + $quantity;
                    } else if (intval($quantities[$key]) < intval($value->quantity)) {
                        $quantity = $value->quantity - $quantities[$key];
                        ProductLog::create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'stock' => $product->stock,
                            'main_stock' => $product->main_stock,
                            'store_stock' => $product->store_stock,
                            'sub_stock' => $product->sub_stock,
                            'action' => 'edit_store_order',
                            'user_id' => auth()->user()->id
                        ]);
                        $or->quantity = $quantities[$key];
                        OrderRepository::backProductQuantity($or, $product, $quantity);
                        $product->save();
                    }
                }

                $or->delivery_id = $request->delivery_id;
                $or->payment_id = $request->paymethod;
                $or->status_date = date('Y-m-d');
                if ($key == 0) {
                    $payment = Paymethod::find($request->paymethod);
                    $or->shipping_price = $request->shipping_price;
                    $or->actual_shipping_price = $request->actual_shipping_price;
                    $or->payment_price = $payment->price;
                    $or->total_price = (((($or->one_price + $or->option_price) * $or->quantity) + ($or->shipping_price + $or->payment_price)) - $or->coupon_price);
                }

                $or->name = $request->customer_name;
                $or->notes = $request->notes;
                $or->phone = $request->customer_phone;
                $address = Address::where(['id' => $or->address_id])->first();
                if (!$address)
                    $address = new Address();
                $address->country_id = $request->country;
                $address->region_id = $request->region;
                $address->address = $request->address;
                $address->area_id = $request->area;
                $address->save();

                $or->payment_id = $payment->id;
                $or->address_id = $address->id;

//                $or->admin_id = auth()->user()->id;

                $old_status = intval($or->status_id);

                $or->admin_name = $request->admin_name;
                $or->status_id = $request->status;
                $or->save();



                // confirmed
                if (intval($or->status_id) === 2 && !$sendSMS) {
                    $configuration = Configurationsite::first();
                    if(intval($configuration->confirmed_sms) === 1) {
                        $phones = $stripped = str_replace([
                            ' ',
                            '/'
                        ], [
                            '',
                            ','
                        ], $or->phone);
                        $configuration->confirmed_sms_text = str_replace('#' , $or->number ,
                            $configuration->confirmed_sms_text);
                        $sendSMS = SMSProvider::sendSMS($configuration->confirmed_sms_text, $phones);
                    }
                }else if (intval($or->status_id) === 6 && !$sendSMS) { // delivered
                    $configuration = Configurationsite::first();
                    if(intval($configuration->delivered_sms) === 1) {
                        $phones = $stripped = str_replace([
                            ' ',
                            '/'
                        ], [
                            '',
                            ','
                        ], $or->phone);
                        $configuration->delivered_sms_text = str_replace('#' , $or->number ,
                            $configuration->delivered_sms_text);
                        $sendSMS = SMSProvider::sendSMS($configuration->delivered_sms_text, $phones);
                    }
                }

                if (intval($or->status_id) === 3) {
                    ProductLog::create([
                        'product_id' => $product->id,
                        'quantity' => $or->quantity,
                        'stock' => $product->stock,
                        'main_stock' => $product->main_stock,
                        'store_stock' => $product->store_stock,
                        'sub_stock' => $product->sub_stock,
                        'action' => 'cancel_store_order',
                        'user_id' => auth()->user()->id
                    ]);

                    if ($old_status === 1) {
                        $product->main_stock += $or->main_stock;
                        $product->sub_stock += $or->sub_stock;
                        $product->store_stock += $or->store_stock;
                    } else {
                        $product->main_stock += $or->main_stock + $or->sub_stock + $or->store_stock;
                    }
                    $product->stock += $or->quantity;
                    $product->ordered -= $or->quantity;
                    $product->save();
                }

                if ($or->status_id === 4) {
                }

                if (intval($or->status_id) === 5) {
                    ProductLog::create([
                        'product_id' => $product->id,
                        'quantity' => $or->quantity,
                        'stock' => $product->stock,
                        'main_stock' => $product->main_stock,
                        'store_stock' => $product->store_stock,
                        'sub_stock' => $product->sub_stock,
                        'action' => 'return_store_order',
                        'user_id' => auth()->user()->id
                    ]);

                    if ($old_status === 1) {
                        $product->main_stock += $or->main_stock;
                        $product->sub_stock += $or->sub_stock;
                        $product->store_stock += $or->store_stock;
                    } else {
                        $product->main_stock += $or->main_stock + $or->sub_stock + $or->store_stock;
                    }
                    if ($old_status === 6) {
                        $product->delivered -= $or->quantity;
                    }
                    $product->stock += $or->quantity;
                    $product->ordered -= $or->quantity;
                    $product->save();
                }

                if (intval($or->status_id) === 6) {
                    ProductLog::create([
                        'product_id' => $product->id,
                        'quantity' => $or->quantity,
                        'stock' => $product->stock,
                        'main_stock' => $product->main_stock,
                        'store_stock' => $product->store_stock,
                        'sub_stock' => $product->sub_stock,
                        'action' => 'delivered_store_order',
                        'user_id' => auth()->user()->id
                    ]);

                    $product->ordered -= $or->quantity;
                    $product->delivered += $or->quantity;
                    $product->save();
                    if ($product->vendor) {
                        DB::table('vendor_orders')->insert(
                            ['vendor_id' => $product->vendor->id, 'order_id' => $or->id, 'order_number' => $or->number, 'date' => date('Y-m-d H:i:s')]
                        );

                        $vendor = Vendor::find($product->vendor->id);
                        $xxx = ($or->one_price + $or->option_price) * $or->quantity;
                        $vendor->balance += $xxx - ($xxx * ($vendor->commission / 100));
                        $vendor->save();
                    }

                    $affilate_order = DB::table('affilate_orders')->where('order_id', $or->id)->first();

                    if ($affilate_order) {
                        $affilate = Affilate::find($affilate_order->affilate_id);

                        $affilate->balance += $affilate_order->commission;
                        $affilate->save();
                    }
                }
            }
        }

        if ($request->status == 4) {
            return redirect('incompleted/orders');
        } else if ($request->status == 1 | $request->status == 2) {
            return redirect('store_orders');
        } else
            return redirect('/order/store_orders');

    }

    public function deleteOrderProduct(Request $request)
    {
        $order = StoreOrder::where(['id' => $request->id])->first();
        if ($order) {
            $product = Product::where(['id' => $order->product_id])->first();
            if ($order->status_id === 1) {
                $product->main_stock += $order->main_stock;
                $product->sub_stock += $order->sub_stock;
                $product->store_stock += $order->store_stock;
            } else if ($order->status_id === 2 && intval($order->from_place) === 0) {
                $product->main_stock += $order->main_stock + $order->sub_stock + $order->store_stock;
            } else if ($order->status_id === 2 && intval($order->from_place) === 1) {
                $product->store_stock += $order->main_stock + $order->sub_stock + $order->store_stock;
            }
            $product->stock += $order->quantity;
            $product->ordered -= $order->quantity;
            $product->save();
            $order->forceDelete();
            return response()->json([], 200);
        }
        return response()->json([], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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
            $order = StoreOrder::where('number', $id)->get();
            foreach ($order as $key => $value) {
                $s = StoreOrder::findOrFail($value->id);
                $s->delete();
            }
        }
    }
}
