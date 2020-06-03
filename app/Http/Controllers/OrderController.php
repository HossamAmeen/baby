<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\SMSProvider;
use App\ProductLog;
use App\Repositories\OrderRepository;
use App\StoreOrder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\ProductOption;
use App\Configurationsite;
use DB;
use Auth;
use App\User;
use App\Country;
use App\Area;
use App\Region;
use App\Paymethod;
use App\Address;
use App\OrderStatus;
use App\Product;
use App\Vendor;
use App\Affilate;
use App\OrderUserOpertation;
use Yajra\Datatables\Datatables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $order = Order::select(DB::raw('id,number,status_date,user_id,product_id,address_id,created_at,sum(total_price) as total, status_id,user_name,user_phone,user_address'))
            ->orderBy('id', 'desc')
            ->whereIn('status_id', array(3, 5, 6))
            ->groupBy('number')
            ->get();
        //dd($order);
        $orderNu = Order::orderBy('id', 'desc')->get();

        $storeOrders = StoreOrder::select(DB::raw('id,admin_name,status_date,shipping_price,delivery_id,admin_id,address_id,number,name,phone,product_id,created_at,sum(total_price) as total, status_id'))
            ->whereIn('status_id', array(3, 5, 6))
            ->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();

        $storeOrderNu = StoreOrder::orderBy('id', 'desc')->get();

        //return 'test';

        return view('admin/order/new_order2', compact('order', 'orderNu', 'storeOrders', 'storeOrderNu'));
    }

    public function storeOrders()
    {

//        $storeOrders = StoreOrder::select(DB::raw('id,admin_name,status_date,shipping_price,delivery_id,admin_id,address_id,number,name,phone,product_id,created_at,sum(total_price) as total, status_id'))
//            ->whereIn('status_id', array(3, 5, 6))
//            ->orderBy('id', 'desc')
//            ->groupBy('number')
//            ->get();
//        $storeOrderNu = StoreOrder::orderBy('id', 'desc')->get();

        //return 'test';

        return view('admin/order/new_order');
    }

    public function getStoreOrdersData(Request $request)
    {
        $storeOrders = StoreOrder::select(DB::raw('id,admin_name,status_date,shipping_price,delivery_id,
        created_at as date,notes, quantity , main_stock , sub_stock , store_stock ,
        admin_id,address_id,number,from_place,name,phone,product_id,created_at,sum(total_price) as total,
         status_id'))
            ->where('from_place' , '=' , 0)
            ->whereIn('status_id', array(3, 5, 6));
        $storeOrders = $storeOrders
            ->groupBy('number');
//            ->get();

//        return $storeOrders;

        return Datatables::of($storeOrders)
            ->addColumn('date', function ($order) {
                return date('Y/m/j', strtotime($order->created_at));
            })
            ->editColumn('status_date', function ($order) {
                if ($order->status_date != null) {
                    return date('Y/m/j', strtotime($order->status_date));
                }
            })
            ->editColumn('from_place_text', function ($order) {
                if (intval($order->from_place) === 1) {
                    return 'المحل';
                } else {
                    return 'الصفحة';
                }
            })
            ->editColumn('name', function ($order) {
                if (intval($order->from_place) === 0) {
                    return '<a href="' . route('store_orders.edit', $order->number) . '">'
                        . $order->name . '</a>';
                } else {
                    return '<a href="' . url('/place/store_order/edit/' . $order->number) . '">'
                        . $order->name . '</a>';
                }
            })
            ->addColumn('address', function ($order) {
                $address = \App\Address::where(['id' => $order->address_id])->first();
                $country = \App\Country::where(['id' => $address['country_id']])->first();
                $region = \App\Region::where(['id' => $address['region_id']])->first();
                $area = \App\Area::where(['id' => $address['area_id']])->first();
                return $country['name'] . ' ' . $region['name'] . ' ' . $area['name'] . ' ' . $address['address'];
            })
            ->addColumn('products', function ($order) {
                $orders = StoreOrder::where(['number' => $order->number])->get();
                $productsStr = '';
                foreach ($orders as $order) {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $productsStr .= $product->title_ar . ' - ' . $product->code . ' <hr />';
                }
                return $productsStr;
            })
            ->addColumn('quantities', function ($order) {
                $quantities = '<ul>'
                    . '<li>'
                    . trans('home.quantity') . ': ' . $order->quantity
                    . ' - ' . trans('home.main_stock') . ': ' . $order->main_stock
                    . ' - ' . trans('home.sub_stock') . ': ' . $order->sub_stock
                    . ' - ' . trans('home.store_stock_quan') . ': ' . $order->store_stock
                    . '</li>'
                    . '</ul> <hr />';
                return $quantities;
            })
            ->addColumn('price', function ($order) {
                $orders = StoreOrder::where(['number' => $order->number])->get();
                $price = '';
                foreach ($orders as $order) {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $price .= (($product->discount > 0) ? ($product->price - $product->discount) :
                            $product->price) . '<br /> ';
                }
                return $price;
            })
            ->editColumn('total', function ($order) {
                return $order->total;
            })
            ->addColumn('delivery', function ($order) {
                return \App\Delivery::where(['id' => $order->delivery_id])->first()['name'];
            })
            ->addColumn('admin', function ($order) {
                $admin = User::where(['id' => $order->admin_id])->first();
                if ($admin)
                    return $admin->name;
            })
            ->addColumn('status', function ($order) {
                return trans("home." . \App\OrderStatus::where('id', '=', $order->status_id)->first()['name']);
            })
            ->filterColumn('products', function ($query, $keyword) {
                $productIds = Product::where('code', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('title_ar', 'LIKE', '%' . $keyword . '%')
                    ->pluck('id')->toArray();
                if (count($productIds) > 0) {
                    $query->orWhereIn('product_id', $productIds);
                }
            })
            ->filterColumn('total', function ($query, $keyword) {
                $query->orWhere('total_price', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('notes', function ($query, $keyword) {
                $query->orWhere('notes', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('address', function ($query, $keyword) {

                $country = \App\Country::where('name', 'LIKE', '%' . $keyword . '%')
                    ->pluck('id')->toArray();
                $region = \App\Region::where('name', 'LIKE', '%' . $keyword . '%')
                    ->pluck('id')->toArray();
                $area = \App\Area::where('name', 'LIKE', '%' . $keyword . '%')
                    ->pluck('id')->toArray();

                $addressIds = \App\Address::where('address', 'LIKE', '%' . $keyword . '%');
                if (count($country) > 0)
                    $addressIds = $addressIds->orWhereIn('country_id', $country);
                if (count($region) > 0)
                    $addressIds = $addressIds->orWhereIn('region_id', $region);
                if (count($area) > 0)
                    $addressIds = $addressIds->orWhereIn('area_id', $area);
                $addressIds = $addressIds->pluck('id')->toArray();
                if(count($addressIds) > 0)
                $query->orWhereIn('address_id', $addressIds);
            })
            ->make(true);
    }

    public function index2()
    {
        //
        $order = Order::select(DB::raw('id,number,status_date,user_id,product_id,address_id,created_at,sum(total_price) as total, status_id,user_name,user_phone,user_address'))
            ->orderBy('id', 'desc')
            ->whereIn('status_id', array(1, 2, 4))
            ->groupBy('number')
            ->get();
        //dd($order);
        $orderNu = Order::orderBy('id', 'desc')->get();

        $storeOrders = StoreOrder::select(DB::raw('id,admin_name,status_date,shipping_price,delivery_id,admin_id,address_id,number,name,phone,product_id,created_at,sum(total_price) as total, status_id,notes'))
            ->whereIn('status_id', array(4))
            ->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();

        $storeOrderNu = StoreOrder::orderBy('id', 'desc')->get();

        return view('admin/order/order', compact('order', 'orderNu', 'storeOrders', 'storeOrderNu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $products = Product::all('id', 'title_ar');
        $paymethods = Paymethod::all();
        return view('admin/order/createorder', compact('users', 'products', 'paymethods'));
    }

    public function createuser()
    {
        $counteries = Country::all();
        $areas = Area::all();
        $regions = Region::all();
        $products = Product::all('id', 'title_ar');
        $paymethods = Paymethod::all();
        return view('admin/order/createuserorder', compact('counteries', 'areas', 'regions', 'products', 'paymethods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $number = Order::orderBy('id', 'desc')->select('number')->first();
        $payment = Paymethod::find($request->paymethod);
        $address = Address::find($request->address);
        $products = $request->products;
        $quantities = $request->amounts;
        for ($i = 0; $i < count($products); $i++) {
            $product = Product::find($products[$i]);
            $order = new Order();
            if ($number) {
                $order->number = $number->number + 1;
            } else {
                $order->number += 1;
            }
            $order->status_id = $request->status;
            $order->user_id = $request->customer;
            $order->product_id = $product->id;
            $order->coupon_price = 0;
            $order->quantity = $quantities[$i];
            ProductLog::create([
                'product_id' => $product->id,
                'quantity' => $order->quantity,
                'stock' => $product->stock,
                'main_stock' => $product->main_stock,
                'store_stock' => $product->store_stock,
                'sub_stock' => $product->sub_stock,
                'action' => 'add_order',
                'user_id' => auth()->user()->id
            ]);
            $order->status_date = date('Y-m-d');
            if ($product->discount) {
                $order->one_price = $product->price - $product->discount;
            } else {
                $order->one_price = $product->price;
            }
            if ($i == 0) {
                $order->shipping_price = ($address->region->shipping + $address->area->shipping) * $product->shipping;
                $order->payment_price = $payment->price;
            } else {
                $order->shipping_price = 0;
                $order->payment_price = 0;
            }

            $order->payment_id = $payment->id;
            $order->address_id = $address->id;


            $order->option_price = 0;
            //$optionids = DB::table('option_cart')->where('cart_id',$value->id)->pluck('option_id');
            $order->option_ids = '';
            $order->total_price = (((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price)) - $order->coupon_price);


            OrderRepository::takeProductQuantity($order, $product, $quantities[$i]);
            $product->ordered += $quantities[$i];
            $product->save();
            $order->save();
        }
        return redirect()->route('orders.index');

    }


    public function store2(Request $request)
    {
        $number = Order::orderBy('id', 'desc')->select('number')->first();
        $payment = Paymethod::find($request->paymethod);
        $region = Region::find($request->region);
        $area = Area::find($request->area);
        $products = $request->products;
        $quantities = $request->amounts;
        for ($i = 0; $i < count($products); $i++) {
            $product = Product::find($products[$i]);
            $order = new Order();
            if ($number) {
                $order->number = $number->number + 1;
            } else {
                $order->number += 1;
            }
            $order->user_id = Auth::user()->id;
            $order->product_id = $product->id;
            $order->coupon_price = 0;
            $order->quantity = $quantities[$i];
            ProductLog::create([
                'product_id' => $product->id,
                'quantity' => $order->quantity,
                'stock' => $product->stock,
                'main_stock' => $product->main_stock,
                'store_stock' => $product->store_stock,
                'sub_stock' => $product->sub_stock,
                'action' => 'add_order',
                'user_id' => auth()->user()->id
            ]);
            $order->user_name = $request->name;
            //$order-> user_email = $request -> email;
            $order->user_phone = $request->phone;
            $order->country_id = $request->country;
            $order->region_id = $request->region;
            $order->area_id = $request->area;
            $order->user_address = $request->address;
            $order->status_date = date('Y-m-d');
            if ($product->discount) {
                $order->one_price = $product->price - $product->discount;
            } else {
                $order->one_price = $product->price;
            }
            if ($i == 0) {
                $order->shipping_price = ($region->shipping + $area->shipping) * $product->shipping;
                $order->payment_price = $payment->price;
            } else {
                $order->shipping_price = 0;
                $order->payment_price = 0;
            }
            $order->payment_id = $payment->id;

            $order->option_price = 0;
            //$optionids = DB::table('option_cart')->where('cart_id',$value->id)->pluck('option_id');
            $order->option_ids = '';
            $order->total_price = (((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price)) - $order->coupon_price);


            OrderRepository::takeProductQuantity($order, $product, $quantities[$i]);
            $product->ordered += $quantities[$i];
            $product->save();
            $order->save();
        }
        return redirect()->route('orders.index');

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $order = Order::where('number', $id)->get();

        $deliveries = DB::table('deliveries')->lists('name', 'id');
        $option = ProductOption::where('status', 1)->get();
        $con = Configurationsite::first();
        $status = OrderStatus::where('id', '>=', $order[0]->status_id)->get();
        if ($order[0]->status_id === 4) {
            $status[] = OrderStatus::where(['name' => 'confirmed'])->first();
        } else if ($order[0]->status_id === 6) {
            $status[] = OrderStatus::where(['name' => 'refunded'])->first();
        }
        $orderoperation = OrderUserOpertation::where('order_number', $id)->get();
        $paymethods = Paymethod::all();
        return view('admin.order.editorder', compact('paymethods', 'order', 'option', 'con', 'status', 'deliveries', 'orderoperation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update_quantity()
    {
        $order_id = $_POST['order_id'];
        $quantity = $_POST['quantity'];
        $order = Order::find($order_id);
        if ($order) {
            $product = Product::where(['id' => $order->product_id])->first();
            if ($product) {
                if ($quantity !== $order->quantity) {
                    if ($quantity > $order->quantity) {
                        $quantity = $quantity - $order->quantity;
                        ProductLog::create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'stock' => $product->stock,
                            'main_stock' => $product->main_stock,
                            'store_stock' => $product->store_stock,
                            'sub_stock' => $product->sub_stock,
                            'action' => 'edit_order',
                            'user_id' => auth()->user()->id
                        ]);
                        OrderRepository::takeProductQuantity($order, $product, $quantity);
                        $product->ordered += $quantity;
                        $product->save();
                        $order->quantity = $order->quantity + $quantity;
                    } else if ($quantity < $order->quantity) {
                        $quantity = $order->quantity - $quantity;
                        ProductLog::create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'stock' => $product->stock,
                            'main_stock' => $product->main_stock,
                            'store_stock' => $product->store_stock,
                            'sub_stock' => $product->sub_stock,
                            'action' => 'edit_order',
                            'user_id' => auth()->user()->id
                        ]);
                        $order->quantity = $quantity;
                        OrderRepository::backProductQuantity($or, $product, $quantity);
                        $product->save();
                    }
                }
                $order->total_price = (((($order->one_price + $order->option_price) * $order->quantity) + ($order->shipping_price + $order->payment_price)) - $order->coupon_price);
                $order->save();
            }
        }
    }

    public function update(Request $request, $id)
    {
        //

        $sendSMS = false;

        $order = Order::where('number', $id)->get();
        if (intval($order[0]->status_id) != intval($request->status)) {
            $operation = new OrderUserOpertation();
            $operation->order_number = $order[0]->number;
            $operation->user_id = Auth::user()->id;
            $operation->message = 'status change from ' . OrderStatus::find($order[0]->status_id)->name . ' to ' . OrderStatus::find($request->status)->name;
            $operation->date_time = date('Y-m-d H:i:s');
            $operation->save();
        }

        foreach ($order as $key => $value) {
            $or = Order::find($value->id);
            $old_status = intval($or->status_id);



            $or->status_id = $request->status;
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
            $or->save();
            $product = Product::find($or->product_id);



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
            }
            else if (intval($or->status_id) === 6 && !$sendSMS) { // delivered
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


            if ($or->status_id == 3) {
                ProductLog::create([
                    'product_id' => $product->id,
                    'quantity' => $or->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'action' => 'cancel_order',
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

            if ($or->status_id == 4) {
            }

            if ($or->status_id == 5) {
                ProductLog::create([
                    'product_id' => $product->id,
                    'quantity' => $or->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'action' => 'return_order',
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

            if ($or->status_id == 6) {
                if (($product->ordered - $or->quantity) >= 0)
                    $product->ordered -= $or->quantity;
                else {
                    $product->ordered = 0;
                    $product->stock -= $or->quantity;
                }
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

        if ($request->status == 1 | $request->status == 2 || $request->status == 4) {
            return redirect('incompleted/orders');
        } else
            return redirect('orders');
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
            $order = Order::where('number', $id)->get();
            foreach ($order as $key => $value) {
                $s = Order::findOrFail($value->id);
                $s->delete();
            }
        }
    }
}
