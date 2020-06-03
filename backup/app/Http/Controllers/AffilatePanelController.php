<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Vendor;
use App\Product;
use App\Category;
use App\Brand;
use File;
use Image;
use DB;
use Auth;
use App\ProductOption;
use App\ProductImages;
use App\OrderStatus;
use App\Delivery;
use App\Order;
use App\VendorDrags;
use Mail;
use App\Configurationsite;
use App\Affilate;
use App\Affilate_category;
use App\Affilate_Product;
use App\Affilate_Withdrow;
use App\AffilateOrder;

class AffilatePanelController extends Controller
{

    public function connect_product($id)
    {
        $affilate = Affilate::find($id);
        $categories = Category::where('parent', 0)->get();
        return view('affilatepanel/connect_product', compact('categories', 'affilate'));
    }

    public function connect_category($id)
    {
        $affilate = Affilate::find($id);
        $categories = Category::where('status', 1)->get();
        return view('affilatepanel/connect_categories', compact('categories', 'affilate'));
    }

    public function savecatconnect()
    {
        $ids = explode(',', $_POST['ids']);
        $affilate_id = $_POST['affilate_id'];
        if (count($ids)) {
            foreach ($ids as $id) {
                $category = Category::find($id);
                $affilate_category = new Affilate_category;
                $affilate_category->affilate_id = $affilate_id;
                $affilate_category->category_id = $category->id;
                $affilate_category->link = url('/') . '/' . $category->link . '/affilate/' . $affilate_id;
                $affilate_category->expire_date = date('Y-m-d H:i:s', strtotime('+30 days', strtotime(date('Y-m-d H:i:s'))));
                //$affilate_category -> commission = $product -> affilate;
                $affilate_category->save();
            }
        }
    }

    public function category_products()
    {
        if(isset($_POST['cat_id'])) {
            $cat_id = $_POST['cat_id'];
            $pro_ids = DB::table('category_products')->where('category_id', $cat_id)->pluck('product_id');
            $products = Product::whereIn('id', $pro_ids)->where('affilate', '>', 0)->get();
        } else if(isset($_POST['code'])) {
            $code = $_POST['code'];
            $products = Product::where('code', 'LIKE', '%'.$code.'%')->where('affilate', '>', 0)->get();
        }
        $html = '';
        if (count($products)) {
            $html .= '<thead class="theadd">
                <tr class="trow">
                <th class="thead" scope="col">#</th>
                    <th class="thead" scope="col">' . trans('home.code') . '</th>
                    <th class="thead" scope="col">' . trans('home.name') . '</th>
                    <th class="thead" scope="col">' . trans('home.image') . '</th>
                    <th class="thead" scope="col">' . trans('site.commission') . '</th>
                </tr>
                </thead>';
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td class="tdata">
                            <input class="input_check" type="checkbox" name="product_ids[]" value="' . $products[0]->id .
                            '" checked>
                      </td>
                      <td class="tdata">' . $products[0]->code . '</td>
                      <td class="tdata"><h4>' . $products[0]->title . '</h4></td>
                      <td class="tdata" >
                                <div><img class="img-responsive" style="width: 75px;height: 75px"
                                          src="'.url('uploads/product/resize800') .'/'. $products[0]->image .'"/>
                                </div>
                      </td>
                      <td class="tdata">Commission: ' . $products[0]->affilate . '% </td>';
            $html .= '</tr>';
            for ($i = 1; $i < count($products); $i++) {
                $html .= '<tr>';
                $html .= '<td class="tdata">
                            <input class="input_check" type="checkbox" name="product_ids[]" value="' . $products[$i]->id .
                    '">
                      </td>
                      <td class="tdata"> ' . $products[$i]->code . ' </td>
                      <td class="tdata"> ' . $products[$i]->title . ' </td>
                      <td class="tdata">
                                <div><img class="img-responsive" style="width: 75px;height: 75px"
                                          src="'.url('uploads/product/resize800') .'/'.$products[$i]->image.'"/>
                                </div>
                      </td>
                      <td class="tdata"> Commission: ' . $products[$i]->affilate . '% </td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        } else
            $html = 'No Products';
        return $html;
    }

    public function getsubcategory()
    {

        $cat_id = $_POST['cat_id'];

        $category = Category::find($cat_id);
        $subcat = $category->subcat;

        $html = '';
        if (count($subcat) > 0) {
            foreach ($subcat as $cat) {
                $html .= '<li class="main main0"><h4><a href="#" onclick="getproducts(' . $cat->id . ');return false;">' . $cat->title . '</a></h4></li>';
            }

        } else {
            $html .= '<li class="main main0"><h4><a href="#" onclick="getproducts(' . $category->id . ');return false;">' . $category->title . '</a></h4></li>';
        }

        return $html;

    }

    public function affilate_balance($id)
    {
        $affilate = Affilate::find($id);
        $withdrows = $affilate->withdrow;

        $order_ids = DB::table('affilate_orders')->where('affilate_id', $id)->pluck('order_id');

        $orders = DB::table('orders')
            ->whereIn('id', $order_ids)
            ->where('status_id', 6)
            ->get();

        return view('affilatepanel/balance', compact('affilate', 'withdrows', 'orders'));
    }

    public function affilatedrag(Request $request)
    {
        if (($request->drag >= 50) && ($request->drag % 50 == 0)) {
            $affilate_withdrow = new Affilate_Withdrow;
            $affilate_withdrow->affilate_id = $request->affilate_id;
            $affilate_withdrow->date = date('Y-m-d H:i:s');
            $affilate_withdrow->drag_amount = $request->drag;
            $affilate_withdrow->remain = $request->affilate_balance - $request->drag;
            $affilate_withdrow->save();

            /*$affilate = Affilate::find($request->affilate_id);
            $data = array('affilate_name'=>$vendor -> name,'vendor_id' => $vendor -> id,'drag_id' => $vendor_drag->id);

        Mail::send('emails/vendor_balance', $data, function($message) {
            $confi = Configurationsite::first();
            $message->to($confi -> email_ads, 'Admin')->subject('vendor add new product');
            $message->from('xyz@gmail.com','Virat Gandhi');
        });*/

        }
        return back();
    }

    public function affilate_orders($id)
    {
        $affilate = Affilate::find($id);
        $status = OrderStatus::all();

        return view('affilatepanel/orders', compact('affilate', 'status'));
    }

    public function display_orders()
    {
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];
        $affialte_id = $_POST['affialte_id'];
        $status = $_POST['status'];

        if ($affialte_id != "") {
            $order_ids = AffilateOrder::where('affilate_id', $affialte_id)->pluck('order_id');
            $affilate_orders = AffilateOrder::where('affilate_id', $affialte_id)->get();
        } else {
            $order_ids = AffilateOrder::pluck('order_id');
            $affilate_orders = AffilateOrder::all();
        }

        $delivery = '';
        $orders = [];
        if ($status != '' && $delivery != '') {
            $orders = Order::whereIn('id', $order_ids)
                ->whereBetween('created_at', [$startdate, $enddate])
                ->where('status_id', $status)
                ->where('delivery_id', $delivery)
                ->get();
        } else {
            if ($status != '') {
                $orders = Order::whereIn('id', $order_ids)
                    ->whereBetween('created_at', [$startdate, $enddate])
                    ->where('status_id', $status)
                    ->get();
            } else if ($delivery != '') {
                $orders = Order::whereIn('id', $order_ids)
                    ->whereBetween('created_at', [$startdate, $enddate])
                    ->where('delivery_id', $delivery)
                    ->get();
            } else {
                $orders = Order::whereIn('id', $order_ids)
                    ->whereBetween('created_at', [$startdate, $enddate])
                    ->get();
            }
        }
        //dd([$startdate,$enddate]);

        return response()->view('affilatepanel/orders_dispaly', compact('orders', 'affilate_orders'));
    }


    public function saveconnect()
    {
        $ids = explode(',', $_POST['ids']);
        $affilate_id = $_POST['affilate_id'];
        if (count($ids)) {
            foreach ($ids as $id) {
                $product = Product::find($id);
                $affilate_product = new Affilate_Product;
                $affilate_product->affilate_id = $affilate_id;
                $affilate_product->product_id = $product->id;
                $affilate_product->link = url('/') . '/' . $product->link . '/affilate/' . $affilate_id;
                //$affilate_product -> link = 'http://hyper-design.com/baby/'.$product -> link.'/affilate/'.$affilate_id;
                //dd(date('Y-m-d H:i:s',strtotime('+30 days',strtotime(date('Y-m-d H:i:s')))));
                $affilate_product->expire_date = date('Y-m-d H:i:s', strtotime('+30 days', strtotime(date('Y-m-d H:i:s'))));
                $affilate_product->commission = $product->affilate;
                $affilate_product->save();
            }
        }
    }

    public function affilate_products($id)
    {
        $affilate = Affilate::where('user_id', $id)->first();
        $products = $affilate->affilate_product;
        $categories = $affilate->categories;

        return view('affilatepanel/products', compact('categories', 'products', 'affilate'));
    }


}