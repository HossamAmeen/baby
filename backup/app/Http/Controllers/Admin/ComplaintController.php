<?php

namespace App\Http\Controllers\Admin;

use App\ClientOpinion;
use App\Complaint;
use App\Order;
use App\PlaceOrder;
use App\Product;
use App\StoreOrder;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class ComplaintController extends Controller
{

    public function showComplaints(Request $request)
    {
        return view('admin.complaints.complaint');
    }

    public function getComplaintsData(Request $request)
    {
        $complaints = Complaint::where([
            'status' => 'new'
        ])->orWhere([
            'status' => 'process'
        ])->get();
        return Datatables::of($complaints)
            ->editColumn('status', function ($complaint) {
                $a = '<a href="/complaints/edit/' . $complaint->id . '" ';
                if ($complaint->status === 'new')
                    $a .= 'class="btn btn-danger" >' . 'لم تحل';
                else if ($complaint->status === 'process')
                    $a .= 'class="btn btn-warning" >' . 'يتم الحل';

                $a .= '</a>';
                return $a;
            })
            ->addColumn('input', function ($complaint) {
                return '<input type="checkbox" onchange="onchangeSelected(this , ' . $complaint->id . ')" />';
            })
            ->addColumn('date', function ($complaint) {
                return $complaint->created_at;
            })
            ->addColumn('admin', function ($complaint) {
                $admin = User::where(['id' => $complaint->admin_id])->first();
                return ($admin) ? $admin->name : '';
            })
            ->make(true);
    }

    public function showComplaintAdd(Request $request)
    {
        return view('admin.complaints.create_complaint');
    }

    public function saveComplaintAdd(Request $request)
    {
        $complaint = new Complaint();
        $complaint->name = $request->name;
        $complaint->phone = $request->phone;
        $complaint->order_id = $request->order_id;
        $complaint->message = $request->message;
        $complaint->notes = $request->notes;
        $complaint->status = $request->status;
        $complaint->admin_id = auth()->user()->id;
        $complaint->save();
        return redirect('/complaints');
    }

    public function showComplaintEdit($id, Request $request)
    {
        $complaint = Complaint::where(['id' => $id])->first();
        return view('admin.complaints.edit_complaint', compact('complaint'));
    }

    public function saveComplaintEdit($id, Request $request)
    {
        $complaint = Complaint::where(['id' => $id])->first();
        $complaint->name = $request->name;
        $complaint->phone = $request->phone;
        $complaint->order_id = $request->order_id;
        $complaint->message = $request->message;
        $complaint->notes = $request->notes;
        $complaint->status = $request->status;
        $complaint->save();
        return redirect('/complaints');
    }

    public function processComplaint(Request $request)
    {
        Complaint::whereIn('id', $request->ids)->update(['status' => 'process']);
        return response()->json();
    }

    public function solveComplaint(Request $request)
    {
        Complaint::whereIn('id', $request->ids)->update(['status' => 'solved']);
        return response()->json();
    }

    public function showComplaintsReport(Request $request)
    {
        return view('admin.complaints.complaint_report');
    }

    public function getComplaintsReportData(Request $request)
    {
        $complaints = Complaint::where([
            'status' => 'solved'
        ])->get();
        return Datatables::of($complaints)
            ->editColumn('status', function ($complaint) {
                if ($complaint->status === 'new')
                    return 'لم تحل';
                else if ($complaint->status === 'process')
                    return 'يتم الحل';
                else if ($complaint->status === 'solved')
                    return 'تم الحل';
            })
            ->addColumn('input', function ($complaint) {
                return '<input type="checkbox" onchange="onchangeSelected(this , ' . $complaint->id . ')" />';
            })
            ->addColumn('date', function ($complaint) {
                return date('Y-m-d', strtotime($complaint->created_at));
            })
            ->addColumn('admin', function ($complaint) {
                $admin = User::where(['id' => $complaint->admin_id])->first();
                return ($admin) ? $admin->name : '';
            })
            ->make(true);
    }

    public function showInventoryReport(Request $request)
    {
        return view('admin.reports.inventory.inventory_report');
    }

    public function inventoryReportInventory(Request $request)
    {
        $productsData = json_decode($request->products, true);
        $stock = intval($request->stock);

        $products = Product::get()
            ->each(function ($product) use ($productsData, $stock) {
                $key = array_search($product->code, array_column($productsData, 'code'));
                if ($stock === 0) {
                    $product->quantity =
                        $product->main_stock
                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1, 2, 4))
                            ->where(['main_stock' => null, 'sub_stock' => null, 'store_stock' => null])
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1, 2, 4))
                            ->where(['main_stock' => null, 'sub_stock' => null, 'store_stock' => null])
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity')
                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1, 2, 4))
                            ->groupBy('number')
                            ->get()
                            ->sum('main_stock')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->where(function ($query) {
                                $query->where(function ($query) {
                                    $query->whereIn('status_id', array(1, 2, 4))
                                        ->where('from_place', '=', 0);
                                });
                                $query->orWhere(function ($query) {
                                    $query->whereIn('status_id', array(1))
                                        ->where('from_place', '=', 1);
                                });
                            })
                            ->groupBy('number')
                            ->get()
                            ->sum('main_stock')
                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->where('from_place', '=', 0)
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock')

                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->where('from_place', '=', 0)
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock');
                }
                else if ($stock === 1) {
                    $product->quantity = $product->sub_stock
                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1))
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock')
                        +
                        \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1))
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock');
                }
                else if ($stock === 2) {
                    $product->quantity = $product->store_stock;
                    $total =
                        \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1))
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock')
                        +
                        \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->where(function ($query) {
                                $query->where(function ($query) {
                                    $query->whereIn('status_id', array(1))
                                        ->where('from_place', '=', 0);
                                });
                                $query->orWhere(function ($query) {
                                    $query->whereIn('status_id', array(1, 2))
                                        ->where('from_place', '=', 1);
                                });
                            })
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock')
                        +
                        \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2))
                            ->where('from_place', '=', 1)
                            ->groupBy('number')
                            ->get()
                            ->sum('main_stock')
                        +
                        \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2))
                            ->where('from_place', '=', 1)
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock')
                        +
                        \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id', '=', $product->id)
                            ->whereIn('status', array(1, 2))
                            ->where(['type' => 1])
                            ->orderBy('id', 'desc')
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity');
                    $orders = \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->whereIn('status', array(1, 2))
                        ->where(['type' => 1])
                        ->orderBy('id', 'desc')
                        ->get();
                    foreach ($orders as $order) {
                        $total -= \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('order_id', '=', $order->id)
                            ->whereIn('status', array(6))
                            ->get()
                            ->sum('quantity');
                    }

                    $product->quantity += $total;
                }
                if ($key === false) {
                    $product->quantity = -$product->quantity;
                } else {
                    $product->quantity = $productsData[$key]['quantity'] - $product->quantity;
                }
            });
        $products = collect($products)->filter(function ($key, $value) {
            return $key->quantity !== 0;
        });
        return view('admin.reports.inventory.inventory_table', compact('products'));
    }

    public function applyReportInventory(Request $request)
    {
        $productsData = json_decode($request->products, true);
        $stock = intval($request->stock);

        $products = Product::get()
            ->each(function ($product) use ($productsData, $stock) {
                $key = array_search($product->code, array_column($productsData, 'code'));
                $quantity = 0;
                if (intval($stock) === 0) {
                    $quantity = $product->main_stock
                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1, 2, 4))
                            ->where(['main_stock' => null, 'sub_stock' => null, 'store_stock' => null])
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1, 2, 4))
                            ->where(['main_stock' => null, 'sub_stock' => null, 'store_stock' => null])
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity')
                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1, 2, 4))
                            ->groupBy('number')
                            ->get()
                            ->sum('main_stock')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->where(function ($query) {
                                $query->where(function ($query) {
                                    $query->whereIn('status_id', array(1, 2, 4))
                                        ->where('from_place', '=', 0);
                                });
                                $query->orWhere(function ($query) {
                                    $query->whereIn('status_id', array(1))
                                        ->where('from_place', '=', 1);
                                });
                            })
                            ->groupBy('number')
                            ->get()
                            ->sum('main_stock')
                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->where('from_place', '=', 0)
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock')

                        + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock')
                        + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2, 4))
                            ->where('from_place', '=', 0)
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock');
                    $stock = 'main_stock';
                }
                else if (intval($stock) === 1) {
                    $quantity = $product->sub_stock
                    + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1))
                        ->groupBy('number')
                        ->get()
                        ->sum('sub_stock')
                    +
                    \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1))
                        ->groupBy('number')
                        ->get()
                        ->sum('sub_stock');
                    $stock = 'sub_stock';
                }
                else if (intval($stock) === 2) {
                    $quantity = $product->store_stock;
                    $total =
                        \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(1))
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock')
                        +
                        \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->where(function ($query) {
                                $query->where(function ($query) {
                                    $query->whereIn('status_id', array(1))
                                        ->where('from_place', '=', 0);
                                });
                                $query->orWhere(function ($query) {
                                    $query->whereIn('status_id', array(1, 2))
                                        ->where('from_place', '=', 1);
                                });
                            })
                            ->groupBy('number')
                            ->get()
                            ->sum('store_stock')
                        +
                        \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2))
                            ->where('from_place', '=', 1)
                            ->groupBy('number')
                            ->get()
                            ->sum('main_stock')
                        +
                        \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                            ->where('product_id', '=', $product->id)
                            ->orderBy('id', 'desc')
                            ->whereIn('status_id', array(2))
                            ->where('from_place', '=', 1)
                            ->groupBy('number')
                            ->get()
                            ->sum('sub_stock')
                        +
                        \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id', '=', $product->id)
                            ->whereIn('status', array(1, 2))
                            ->where(['type' => 1])
                            ->orderBy('id', 'desc')
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity');
                    $orders = \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->whereIn('status', array(1, 2))
                        ->where(['type' => 1])
                        ->orderBy('id', 'desc')
                        ->get();
                    foreach ($orders as $order) {
                        $total -= \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('order_id', '=', $order->id)
                            ->whereIn('status', array(6))
                            ->get()
                            ->sum('quantity');
                    }

                    $quantity += $total;
                    $stock = 'store_stock';
                }
                if ($key === false) {
                    $product->update([
                        $stock => 0,
                        'stock' => $product->stock - $quantity
                    ]);
                    $product->quantity = 0;
                } else {
                    $productsData[$key]['quantity'] = intval($productsData[$key]['quantity']);

                    $product->update([
                        $stock => $productsData[$key]['quantity'],
                        'stock' => ($product->stock - $quantity) +
                            $productsData[$key]['quantity']
                    ]);
                    $product->quantity = $productsData[$key]['quantity'];
                }
            });

        $products = collect($products)->filter(function ($key, $value) {
            return $key->quantity !== 0;
        });
        return view('admin.reports.inventory.inventory_table', compact('products'));
    }

    public function showClientsOpinion(Request $request)
    {
        return view('admin.clients.opinion');
    }

    public function getOrderDetails(Request $request)
    {
        $orderProductIds = Order::where(['number' => $request->orderNumber])
            ->where(function ($query) {
                $query->where(['status_id' => 6]);
                $query->orWhere(['status_id' => 5]);
            })->pluck('product_id')->toArray();
        $storeOrderProductIds = StoreOrder::where(['number' => $request->orderNumber])
            ->where(function ($query) {
                $query->where(['status_id' => 6]);
                $query->orWhere(['status_id' => 5]);
            })->pluck('product_id')->toArray();
        $placeOrderProductIds = PlaceOrder::where(['number' => $request->orderNumber, 'status' => 6])
            ->pluck('product_id')->toArray();

        $products = Product::whereIn('id', $orderProductIds)
            ->orWhereIn('id', $storeOrderProductIds)
            ->orWhereIn('id', $placeOrderProductIds)
            ->get(['id', 'code', 'title_ar']);
        if ($products->count() > 0) {
            return response()->json(['products' => $products], 200);
        }
        return response()->json([], 404);
    }

    public function saveClientOpinion(Request $request)
    {
        $opinion = new ClientOpinion();
        $opinion->orderNumber = $request->orderNumber;
        $opinion->productsOpinion = $request->productsOpinion;
        $opinion->chargeOpinion = $request->chargeOpinion;
        $opinion->customerServiceOpinion = $request->customerServiceOpinion;

        $opinion->againOpinion = $request->againOpinion;
        $opinion->positives = $request->positives;
        $opinion->negatives = $request->negatives;
        $opinion->comment = $request->comment;
        $opinion->notes = $request->notes;
        $opinion->save();
        return response()->json();
    }

}
