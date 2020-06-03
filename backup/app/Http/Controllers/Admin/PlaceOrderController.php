<?php

namespace App\Http\Controllers\Admin;

use App\Configurationsite;
use App\OrderStatus;
use App\OrderUserOpertation;
use App\Paymethod;
use App\PlaceOrder;
use App\Product;
use App\ProductLog;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class PlaceOrderController extends Controller
{
    //
    public function index()
    {
        //
        $order = PlaceOrder::whereIn('status', array(1, 2))
            ->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();
        $orderNu = PlaceOrder::whereIn('status', array(1, 2))->orderBy('id', 'desc')->get();

        $saleOrdersTotal = PlaceOrder::where(['type' => 1])->whereIn('status', array(1, 2))
            ->orderBy('id', 'desc')->groupBy('number')->get()->sum('total_price');

        $returnOrdersTotal = PlaceOrder::where(['type' => 0])->whereIn('status', array(1, 2))
            ->orderBy('id', 'desc')->groupBy('number')->get()->sum('total_price');

        return view('admin/place_orders/place_orders',
            compact('order', 'orderNu', 'saleOrdersTotal', 'returnOrdersTotal'));
    }

    public function getFinishedOrders(Request $request)
    {

        $saleOrdersTotal = PlaceOrder::where(['type' => 1])->whereIn('status', array(6));
        if (isset($request->from) && !empty($request->from)) {
            $saleOrdersTotal = $saleOrdersTotal->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->from)));
        }
        if (isset($request->to) && !empty($request->to)) {
            $saleOrdersTotal = $saleOrdersTotal->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to)));
        }
        $saleOrdersTotal = $saleOrdersTotal->orderBy('id', 'desc')
            ->groupBy('number')->get(['total_price'])->sum('total_price');

        $saleOrdersTotal = ($saleOrdersTotal !== null) ? $saleOrdersTotal : 0;

        $returnOrdersTotal = PlaceOrder::where(['type' => 0])->whereIn('status', array(6));
        if (isset($request->from) && !empty($request->from)) {
            $returnOrdersTotal = $returnOrdersTotal->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->from)));
        }
        if (isset($request->to) && !empty($request->to)) {
            $returnOrdersTotal = $returnOrdersTotal->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to)));
        }

        $returnOrdersTotal = $returnOrdersTotal->orderBy('id', 'desc')
            ->groupBy('number')->get(['total_price'])->sum('total_price');

        $returnOrdersTotal = ($returnOrdersTotal !== null) ? $returnOrdersTotal : 0;
        $total = $saleOrdersTotal - $returnOrdersTotal;
        if (!isset($request->json)) {
            return view('admin/place_orders/finished_place_orders',
                compact('saleOrdersTotal', 'returnOrdersTotal', 'total'));
        } else {
            return response()->json([
                'data' => [
                    'returnOrdersTotal' => $returnOrdersTotal,
                    'saleOrdersTotal' => $saleOrdersTotal,
                    'total' => $total
                ]
            ], 200);
        }
    }

    public function getFinishedOrdersData(Request $request)
    {
        $orders = PlaceOrder::select('id', 'number', 'product_id', 'quantity',
            'customer_phone', 'customer_name', 'discount', 'total_price', 'paymethod',
            'status', 'notes', 'admin_id', 'type', 'return', 'order_id',
            'created_at', 'created_at AS date')
            ->whereIn('status', array(6));
        if (isset($request->from) && !empty($request->from)) {
            $orders = $orders->whereDate('created_at', '>=',
                date('Y-m-d', strtotime($request->from)));
        }
        if (isset($request->to) && !empty($request->to)) {
            $orders = $orders->whereDate('created_at', '<=',
                date('Y-m-d', strtotime($request->to)));
        }
        $orders = $orders->orderBy('id', 'desc')
            ->groupBy('number');

        return Datatables::of($orders)
            ->addColumn('date', function ($order) {
                return date('Y-m-d', strtotime($order->created_at));
            })
            ->editColumn('number', function ($order) {
                return '<a target="_blank" class="btn btn-success" href="' . url('/place_order/printOrder/' . $order->number) . '">' . $order->number . '</a>';
            })
            ->addColumn('type', function ($order) {
                if ($order->type === 1) {
                    return 'فاتورة';
                } else {
                    return 'مرتجع';
                }
            })
            ->addColumn('customer_name', function ($order) {
                if ($order->customer_name !== null) {
                    return $order->customer_name;
                } else {
                    return '-------';
                }
            })
            ->addColumn('customer_phone', function ($order) {
                if ($order->customer_phone !== null) {
                    return $order->customer_phone;
                } else {
                    return '-------';
                }
            })
            ->addColumn('products', function ($order) {
                $orders = PlaceOrder::where(['number' => $order->number])->get();
                $productsStr = '';
                foreach ($orders as $order) {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $productsStr .= $product->title_ar . ' - ' . $product->code . ' <hr />';
                }
                return $productsStr;
            })
            ->addColumn('price', function ($order) {
                $orders = PlaceOrder::where(['number' => $order->number])->get();
                $productsStr = '';
                foreach ($orders as $order) {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $product->price = (intval($product->discount) !== 0)
                        ? $product->price : $product->price - $product->discount;
                    $productsStr .= $product->price . ' <hr />';
                }
                return $productsStr;
            })
            ->addColumn('quantities', function ($order) {
                $orders = PlaceOrder::where(['number' => $order->number])->get();
                $quantities = '';
                foreach ($orders as $order) {
                    $quantities .= $order->quantity . ' <hr />';
                }
                return $quantities;
            })
            ->addColumn('paymethod', function ($order) {
                return Paymethod::where(['id' => $order->paymethod])->first()['name'];
            })
            ->addColumn('admin', function ($order) {
                $admin = User::where(['id' => $order->admin_id])->first();
                return $admin->name;
            })
            ->filterColumn('products', function ($query, $keyword) {
                $productIds = Product::where('code', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('title_ar', 'LIKE', '%' . $keyword . '%')
                    ->pluck('id')->toArray();
                if (count($productIds) > 0) {
                    $query->orWhereIn('product_id', $productIds);
                }
            })
            ->filterColumn('customer_phone', function ($query, $keyword) {
                $query->orWhere('customer_phone', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->orWhere('customer_name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('total_price', function ($query, $keyword) {
                $query->orWhere('total_price', 'LIKE', '%' . $keyword . '%');
            })
            ->make(true);
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user->can('add_place_orders')) {
            abort(404);
        }
        $products = Product::where('store_stock', '>=', 1)->get(['id', 'title_ar', 'code']);
        $paymethods = Paymethod::where(['id' => 1])->orWhere(['id' => 4])->get();
        $status = OrderStatus::where(['id' => 1])->orWhere(['id' => 2])->orWhere(['id' => 6])->get();
        return view('admin/place_orders/place_order_add', compact('products', 'paymethods',
            'status'));
    }

    public function getProductDetails(Request $request)
    {
        $product = Product::where(['id' => $request->id])->first();
        if ($product && $product->stock > 0) {
            $product->image = url('uploads/product/resize800') . '/' . $product->image;
            $product->price = (intval($product->discount) === 0)
                ? $product->price : $product->price - $product->discount;
            return response()->json([
                'data' => $product
            ], 200);
        }
        return response()->json([], 404);
    }

    public function getProductDetailsByNumber(Request $request)
    {
        $product = Product::where(['code' => $request->number])->first();
        if ($product && $product->store_stock > 0) {
            $product->price = (intval($product->dicsount) !== 0)
                ? $product->price : $product->price - $product->discount;
            $product->image = url('uploads/product/resize800') . '/' . $product->image;
            return response()->json([
                'data' => $product
            ], 200);
        }
        return response()->json([], 404);
    }


    public function store(Request $request)
    {
        $order = PlaceOrder::orderBy('id', 'desc')->first();
        if ($order) {
            $number = 'a' . (intval(substr($order->number, 1)) + 1);
        } else {
            $number = 'a120';
        }


        $quantities = explode(',', $request->quantities[0]);
        $allProducts = explode(',', $request->productsIds[0]);
        $index = 0;
        foreach ($allProducts as $productsId) {
            $product = Product::where(['id' => $productsId])->first();
            $quantities[$index] = intval($quantities[$index]);
            if ($product && $product->store_stock >= $quantities[$index]) {

                ProductLog::create([
                    'product_id' => $product->id,
                    'quantity' => $quantities[$index],
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'action' => 'add_place_order',
                    'user_id' => auth()->user()->id
                ]);

                $product->store_stock -= $quantities[$index];
                $product->stock -= $quantities[$index];
                $product->ordered += $quantities[$index];
                $product->save();

                if ($index === 0) {
                    $operation = new OrderUserOpertation();
                    $operation->order_number = $number;
                    $operation->user_id = Auth::user()->id;
                    $operation->message = 'start order';
                    $operation->date_time = date('Y-m-d H:i:s');
                    $operation->save();
                }

                $placeOrder = new PlaceOrder();
                $placeOrder->number = $number;
                $placeOrder->product_id = $productsId;
                $placeOrder->quantity = $quantities[$index++];
                $placeOrder->customer_phone = (isset($request->customer_phone) && !empty($request->customer_phone)) ?
                    $request->customer_phone : null;
                $placeOrder->customer_name = (isset($request->customer_name) && !empty($request->customer_name)) ?
                    $request->customer_name : null;
                $placeOrder->discount = (isset($request->discount) && !empty($request->discount)) ?
                    $request->discount : 0;
                $placeOrder->total_price = $request->total;
                $placeOrder->paymethod = $request->paymethod;
                $placeOrder->status = $request->status;
                $placeOrder->notes = (isset($request->notes) && !empty($request->notes)) ?
                    $request->notes : null;
                $placeOrder->admin_id = auth()->user()->id;
                $placeOrder->save();


            }
        }
        return response()->json(['data' => [
            'id' => $number
        ]], 200);
    }

    public function printOrder($id)
    {
        $order = PlaceOrder::where(['number' => $id])->get();
        if ($order->count() > 0) {
            $order[0]->final_total = $order[0]->total_price;
        }
        return view('admin/place_orders/print', compact('order'));
    }

    public function FinsishOrder(Request $request)
    {
        $sendSMS = '';
        $ids = explode(',', $request->ids[0]);

//        $placeOrderStatus = PlaceOrder::whereIn('number', $ids)->where('status', '!=', 6)
//            ->first();
//        if($placeOrderStatus){
//            $placeOrderStatus = $placeOrderStatus->status;
//        }

        PlaceOrder::whereIn('number', $ids)->where('status', '!=', 6)
            ->update(['status' => 6]);

        $orders = PlaceOrder::whereIn('number', $ids)->get();
        foreach ($orders as $order) {
            if (intval($order->return) !== 1 && intval($order->type) === 1
                && intval($order->status) === 6 && $sendSMS !== $order->number) { // delivered
//                $operation = new OrderUserOpertation();
//                $operation->order_number = $order[0]->number;
//                $operation->user_id = Auth::user()->id;
//                $operation->message = 'status change from ' . OrderStatus::find($placeOrderStatus)->name . ' to ' . OrderStatus::find(6)->name;;
//                $operation->date_time = date('Y-m-d H:i:s');
//                $operation->save();
                $configuration = Configurationsite::first();
                if (intval($configuration->place_delivered_sms) === 1) {
                    $phones = str_replace([
                        ' ',
                        '/'
                    ], [
                        '',
                        ','
                    ], $order->customer_phone);
                    $configuration->place_delivered_sms_text = str_replace('#', $order->number,
                        $configuration->place_delivered_sms_text);
                    $sendSMS = $order->number;
                    SMSProvider::sendSMS($configuration->place_delivered_sms_text, $phones);
                }
            }
            $product = Product::where(['id' => $order->product_id])->first();
            if (intval($order->type) === 1) {
                $product->ordered -= $order->quantity;
                $product->delivered += $order->quantity;
            } else {
                ProductLog::create([
                    'product_id' => $product->id,
                    'quantity' => $order->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'action' => 'return_place_order',
                    'user_id' => auth()->user()->id
                ]);
                $product->store_stock += $order->quantity;
                $product->stock += $order->quantity;
                $product->ordered -= $order->quantity;
                $product->delivered -= $order->quantity;
            }
            $product->save();
        }
        return response()->json([], 200);
    }

    public function returnOrder(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('add_place_orders')) {
            abort(404);
        }
        return view('admin/place_orders/place_order_return');
    }

    public function getPlaceOrder(Request $request)
    {
        $order = PlaceOrder::where(['number' => $request->number, 'type' => 1, 'order_id' => null])->get();
        if ($order->count() > 0) {
            return view('admin/place_orders/return_table'
                , compact('order'));
        }
    }

    public function saveReturnOrder(Request $request)
    {
        $index = 0;
        $placeOrder = PlaceOrder::orderBy('id', 'desc')->first();
        if ($placeOrder) {
            $number = 'a' . (intval(substr($placeOrder->number, 1)) + 1);
        } else {
            $number = 'a121';
        }

        $quantities = explode(',', $request->quantities[0]);
        $allProducts = explode(',', $request->productsIds[0]);
        foreach ($allProducts as $orderId) {
            $order = PlaceOrder::where(['id' => $orderId, 'type' => 1])->first();
            $order->return = 1;
            $order->save();
            $placeOrder = new PlaceOrder();
            $placeOrder->order_id = $order->id;
            $placeOrder->number = $number;
            $placeOrder->quantity = $quantities[$index++];
            $placeOrder->type = 0;
            $placeOrder->product_id = $order->product_id;
            $placeOrder->customer_phone = $order->customer_phone;
            $placeOrder->customer_name = $order->customer_name;
            $placeOrder->discount = 0;
            $placeOrder->total_price = 0;
            $placeOrder->paymethod = null;
            $placeOrder->status = 2;
            $placeOrder->notes = null;
            $placeOrder->admin_id = auth()->user()->id;
            $placeOrder->save();
        }
        $orders = PlaceOrder::where(['number' => $number])->get();
        $total = 0;
        foreach ($orders as $order) {
            $total += (($order->Product->discount > 0) ?
                    ($order->Product->price - $order->Product->discount) : $order->Product->price) * $order->quantity;
        }
        PlaceOrder::where(['number' => $number])->update(['total_price' => $total]);
        return response()->json([], 200);
    }
}
