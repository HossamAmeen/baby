<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Brand;
Use App\Product;
use App\Configurationsite;
use DB;
use App\OrderStatus;
use App\Delivery;
use App\Address;
use App\Country;
use App\Region;
use App\Area;
use App\User;
use App\ProductOption;
use App\ProductImages;
use App\ProductReview;
use App\Affilate;
use App\Affilate_Product;
use Maatwebsite\Excel\Excel;
use Session;
use App;
use Auth;
use App\BlogItem;
use App\BlogCategory;
use App\Category;
use App\Artist;
use App\Affilate_category;
use App\AffilateOrder;
use App\Order;
use App\Favorite;
use App\RefundedOrder;
use File;
use Image;
use App\Page;
use Yajra\Datatables\Datatables;

class ZizoController extends Controller
{

    public function showOffersSeo()
    {
        $offer = App\Offer::first();
        return view('admin/offer_seo', compact('offer'));
    }

    public function saveOffersSeo(Request $request)
    {
        $offer = App\Offer::first();
        if (!$offer) {
            $offer = new App\Offer();
        }
        $offer->meta_keywords = $request->meta_keywords;
        $offer->meta_description = $request->meta_description;
        $offer->save();
        return redirect('/offers/seo');
    }

    public function getOffers()
    {
        $deals = Product::where('status', 1)
            ->where('stock', '>=', 1)
            ->where('vendor_status', '!=', 0)
            ->where('discount', '>', 0)
            ->orderBy('id', 'desc')
            ->paginate(16);
        $lang = Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $offerSeo = App\Offer::first();
        return view('website/show_offers', compact('deals', 'lan', 'offerSeo'));
    }


    public function load_more()
    {
        $lang = Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }


        App::setLocale($lan);
        $step = $_POST['step'];
        $ids = $_POST['id'];
        $producthome = Product::where('status', 1)
            ->where('stock', '>=', 1)
            ->where('vendor_status', '!=', 0)
//            ->where(['featured' =>  0])
            ->where('discount', '=', 0)
            ->whereNotIn('id', $ids)
            ->orderBy(DB::raw('RAND()'))
            ->orderByRaw("FIELD(featured , '1')")
            ->skip($step * 16)
            ->take(16)
            ->get();
        return response()->view('website/load_more', compact('producthome', 'lan'));
    }

    public function refunded_order(Request $request)
    {
        $new = new RefundedOrder;
        $new->reason = $request->reason;
        $new->comment = $request->comment;
        $new->order_id = $request->order_num;
        $new->user_id = Auth::user()->id;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

            // $destinationPath = base_path() . '/uploads/'; // upload path
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/refundedorders/source/' . $fileName);

            Image::make($file->getRealPath())->save($path);

            $new->img = $fileName;
        }
        $new->save();
        return redirect()->back();
    }

    public function add_favorite()
    {
        $product_id = $_POST['product_id'];
        //dd($product_id);
        if (Auth::user()->isFavorite($product_id)) {
            $favorite = new Favorite;
            $favorite->user_id = Auth::user()->id;
            $favorite->product_id = $product_id;
            $favorite->save();
        }

    }

    public function affilate_orders()
    {
        $affilates = Affilate::all('id', 'name');
        $status = OrderStatus::all();

        return view('admin.reports.affilate_report', compact('affilates', 'status'));
    }

    public function page_show($id, $link)
    {
        $lang = \Session::get('lang');
        $link = substr($link, 0, strlen($link) - 2) . $lang;
        $page = Page::where(['link' => $link])->first();

        if (!$page)
            $page = Page::find($id);

        return view('website.showPage', compact('page'));
    }

    public function display_orders()
    {
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];
        $affialte_id = $_POST['affilate'];
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

        return response()->view('admin.reports.affilate_display', compact('orders', 'affilate_orders'));
    }

    public function allroute($value, Request $request)
    {
        
        $lang = Session::get('lang');
        $con = Configurationsite::first();
//        if ($lang == null) {
//            $lan = $con->default_lang;
//        } else {
//            $lan = $lang;
//        }

        $lan = 'ar';


        App::setLocale($lan);

        $item = BlogItem::where('link', $value)->where('status', 1)->first();
        $blogcategory = BlogCategory::where('link', $value)->where('status', 1)->first();

        $category = Category::where('link', $value)->where('status', 1)->first();

        $regex = 'product_';
        if (strpos($value, $regex) !== false) {
            $id = substr($value, strlen($regex));
            $product = Product::find($id);
            if ($product && $product->link !== $regex.$product->id) {
                return redirect()->to(url('/') . '/' . $product->link);
            }
        }

        $product = Product::where(function ($query) use ($value) {
            $query->where(['link' => $value])
                ->orWhere(['link_en' => $value]);
        })
            ->where('status', 1)
            ->where('vendor_status', '!=', 0)->first();

        $artist = Artist::where('link', $value)->where('status', 1)->first();
        $rating = DB::table('rates')->groupBy('product_id')->select(DB::raw(DB::raw("SUM( value )/count(*) AS finalRate")), 'product_id')->pluck('finalRate', 'product_id');

        //$con = Configurationsite::first();
        if ($item) {
            return view('website.blogitem', compact('item'));
        } elseif ($blogcategory) {
            $blogitem = BlogItem::where('blogcategory_id', $blogcategory->id)->where('status', 1)->get();
            $cats = Category::where('status', 1)->get();
            return view('website.blogcategory', compact('blogcategory', 'blogitem',
                'cats', 'con'));
        } elseif ($category) {

            $ids = DB::table('category_products')->where('category_id', $category->id)->pluck('product_id');
            $categoryIds = Category::where(['parent' => $category->id])->pluck('id');
            $categoryProductids = DB::table('category_products')
                ->whereIn('category_id', $categoryIds)
                ->pluck('product_id');
            $ids = array_merge($ids, $categoryProductids);
            $searchtext = (isset($request->search)) ? $request->search : '';

            if (!empty($searchtext)) {
                $ids = [];
            }

            $char = [
                'ة', 'أ', 'إ',
                'ي', 'ؤ', 'ء'
            ];
            $char2 = [
                'ه', 'ا', 'ا',
                'ى', 'و', 'ئ'
            ];
            $searchtext2 = str_replace($char[0], $char2[0], $searchtext);
            $searchtext3 = str_replace($char[1], $char2[1], $searchtext);
            $searchtext4 = str_replace($char[2], $char2[2], $searchtext);
            $searchtext5 = str_replace($char2[0], $char[0], $searchtext);
            $searchtext6 = str_replace($char2[1], $char[1], $searchtext);
            $searchtext7 = str_replace($char2[2], $char[2], $searchtext);

            $searchtext8 = str_replace($char[3], $char2[3], $searchtext);
            $searchtext9 = str_replace($char[4], $char2[4], $searchtext);
            $searchtext10 = str_replace($char[5], $char2[5], $searchtext);
            $searchtext11 = str_replace($char2[3], $char[3], $searchtext);
            $searchtext12 = str_replace($char2[4], $char[4], $searchtext);
            $searchtext13 = str_replace($char2[5], $char[5], $searchtext);
            $product = Product::where('status', 1)
                ->where(function ($query) use (
                    $searchtext, $searchtext2, $searchtext3
                    , $searchtext4, $searchtext5, $searchtext6, $searchtext7
                    , $searchtext8, $searchtext9, $searchtext10, $searchtext11
                    , $searchtext12, $searchtext13
                ) {
                    if (!empty($searchtext)) {
                        $query->where('title', 'LIKE', '%' . $searchtext . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext2 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext2 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext2 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext2 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext2 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext3 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext3 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext3 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext3 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext3 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext4 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext4 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext4 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext4 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext4 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext5 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext5 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext5 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext5 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext5 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext6 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext6 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext6 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext6 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext6 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext7 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext7 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext7 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext7 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext7 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext8 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext8 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext8 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext8 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext8 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext9 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext9 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext9 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext9 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext9 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext10 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext10 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext10 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext10 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext10 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext11 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext11 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext11 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext11 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext11 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext12 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext12 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext12 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext12 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext12 . '%');

                        $query->orWhere('title', 'LIKE', '%' . $searchtext13 . '%');
                        $query->orWhere('title_ar', 'LIKE', '%' . $searchtext13 . '%');
                        $query->orWhere('short_description', 'LIKE', '%' . $searchtext13 . '%');
                        $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext13 . '%');
                        $query->orWhere('meta_key', 'LIKE', '%' . $searchtext13 . '%');
                    }
                })->where(function ($query) use ($ids) {
                    $query->whereIn('id', $ids);
                })
                ->where('stock', '>=', 1)
                ->where('vendor_status', '!=', 0)->orderBy('id', 'desc')
                ->paginate(16);

            //dd($product);
            return view('website.showcategory', compact('category', 'lan', 'rating', 'product', 'con', 'searchtext'));
        } elseif ($product) {

            $optionradio = ProductOption::where('product_id', $product->id)->where('type', 'radio')->where('status', 1)->get();
            $optioncheck = ProductOption::where('product_id', $product->id)->where('type', 'check')->where('status', 1)->get();
            $productimage = ProductImages::where('product_id', $product->id)->get();
            $productreview = ProductReview::where('product_id', $product->id)->get();

            $ids = DB::table('category_products')->where('product_id', $product->id)->pluck('category_id');
            $productids = DB::table('category_products')->whereIn('category_id', $ids)->pluck('product_id');
            $categories = Category::whereIn('id', $ids)->where('status', 1)->get();
            $views = Product::whereIn('id', $productids)
                ->where('id', '!=', $product->id)
                ->where('status', 1)
                ->where('stock', '>=', 1)
                ->where('vendor_status', '!=', 0)
                ->orderBy('visits', 'desc')
                ->take(8)
                ->get();
            return view('website.showcourse', compact('views', 'categories',
                'productreview', 'rating', 'product', 'con', 'lan', 'optionradio', 'optioncheck',
                'productimage'));
        } elseif ($artist) {
            $product = Product::where('artist_id', $artist->id)->where('status', 1)->paginate(10);
            return view('website.showartist', compact('artist', 'lan', 'rating', 'product', 'con'));
        } else {
            return view('errors.404');
        }
    }

    public function affilate_product($link, $id)
    {
        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        //\Session::set('lang',$lan);
        $aa = \Session::get('lang');
        \App::setLocale($aa);
        $rating = DB::table('rates')->groupBy('product_id')->select(DB::raw(DB::raw("SUM( value )/count(*) AS finalRate")), 'product_id')->pluck('finalRate', 'product_id');
        $product = Product::where('link', $link)->where('status', 1)->where('stock', '>=', 1)->where('vendor_status', '!=', 0)->first();
        $category = Category::where('link', $link)->where('status', 1)->first();
        $affilate = Affilate::find($id);
        if ($product) {
            $optionradio = ProductOption::where('product_id', $product->id)->where('type', 'radio')->where('status', 1)->get();
            $optioncheck = ProductOption::where('product_id', $product->id)->where('type', 'check')->where('status', 1)->get();
            $productimage = ProductImages::where('product_id', $product->id)->get();
            $productreview = ProductReview::where('product_id', $product->id)->get();

            $ids = DB::table('category_products')->where('product_id', $product->id)->pluck('category_id');
            $productids = DB::table('category_products')->whereIn('category_id', $ids)->pluck('product_id');
            $categories = Category::whereIn('id', $ids)->where('status', 1)->get();
            $views = Product::whereIn('id', $productids)->where('status', 1)->where('stock', '>=', 1)->where('vendor_status', '!=', 0)->orderBy('visits', 'desc')->take(8)->get();

            $affilate_product = Affilate_Product::where('affilate_id', $affilate->id)
                ->where('product_id', $product->id)
//                ->where('expire_date', '>=', date('Y-m-d H:i:s'))
                ->first();
            if ($affilate_product) {
                $affilate_product->visits += 1;
                $affilate_product->save();
                session(['affilate' => $affilate]);
                return view('website.showcourse', compact('views', 'categories', 'productreview', 'rating', 'product', 'con', 'lan', 'optionradio', 'optioncheck', 'productimage'));
            } else {
                return view('errors/404');
            }
        } elseif ($category) {
            $ids = DB::table('category_products')->where('category_id', $category->id)->pluck('product_id');
            $product = Product::whereIn('id', $ids)->where('status', 1)->where('stock', '>=', 1)->where('vendor_status', '!=', 0)->paginate(16);

            $affilate_category = Affilate_category::where('affilate_id', $affilate->id)
                ->where('category_id', $category->id)
                ->where('expire_date', '>=', date('Y-m-d H:i:s'))
                ->first();
            if ($affilate_category) {
                $affilate_category->visits += 1;
                $affilate_category->save();
                session(['affilate' => $affilate]);
                session(['category' => $category->id]);
                return view('website.showcategory', compact('category', 'lan', 'rating', 'product', 'con'));
            } else {
                return view('errors/404');
            }

        } else {
            return view('errors.404');
        }


    }


    public function getShippingPrice(Request $request)
    {
        $region_id = $request->region_id;
        $area_id = $request->area_id;
        $product_id = json_decode($request->product_id);

        $products = Product::whereIn('id', $product_id)->get(['price', 'shipping'])->toArray();

        $products = max($products);
        $region = Region::where(['id' => $region_id])->first();
        $area = Area::where(['id' => $area_id])->first();
        if ($area && $area->shipping > 0) {
            $shipping_price = $area->shipping * ((isset($products['shipping'])) ? $products['shipping'] : 0);
        }
        if ($region && $region->shipping > 0 && !isset($shipping_price)) {
            $shipping_price = $region->shipping * ((isset($products['shipping'])) ? $products['shipping'] : 0);
        }

        return $shipping_price;
    }

    public function useraddresses($id)
    {

        $addresses = Address::where('user_id', $id)->get();

        $html = '';
        foreach ($addresses as $address) {
            $html .= '<option value="' . $address->id . '">' . $address->address . '</option>';
        }

        return $html;
    }

    public function userdata($id)
    {

        $user = User::find($id);
        $response = '{"name":"' . $user->name . '","email":"' . $user->email . '","phone":"' . $user->phone . '","address":"' . $user->address . '"}';

        return $response;
    }

    public function country_regions($id)
    {

        $regions = Region::where('country_id', $id)->get();

        $html = '<option></option>';
        foreach ($regions as $region) {
            $html .= '<option id="' . $region->shipping . '" value="' . $region->id . '">' . $region->name . '</option>';
        }

        return $html;
    }

    public function region_areas($id)
    {

        $areas = Area::where('region_id', $id)->get();

        $html = '<option></option>';
        foreach ($areas as $area) {
            $html .= '<option value="' . $area->id . '">' . $area->name . '</option>';
        }

        return $html;
    }

    public function country_regions2($id, $addid)
    {

        $regions = Region::where('country_id', $id)->get();
        $address = Address::find($addid);

        $html = '<option></option>';
        foreach ($regions as $region) {
            $html .= '<option value="' . $region->id . '"' . ($region->id == $address->region_id) ? 'selected' : '' . '>' . $region->name . '</option>';
        }
        //dd($html);
        return $html;
    }

    public function region_areas2($id, $addid)
    {

        $areas = Area::where('region_id', $id)->get();
        $address = Address::find($addid);
        $html = '<option></option>';
        foreach ($areas as $area) {
            $html .= '<option value="' . $area->id . '"' . ($area->id == $address->area_id) ? 'selected' : '' . '>' . $area->name . '</option>';
        }

        return $html;
    }

    public function brandproducts($id)
    {
        $brand = Brand::find($id);
        $products = Product::where('brand_id', $brand->id)
            ->where('stock', '>=', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(16);
        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $rating = DB::table('rates')->groupBy('product_id')->select(DB::raw(DB::raw("SUM( value )/count(*) AS finalRate")), 'product_id')->pluck('finalRate', 'product_id');
        return view('website/brand_products', compact('brand', 'products', 'lan', 'rating', 'con'));
    }

    public function stockreport()
    {
        $products = Product::orderBy('stock', 'asc')->get();
        return view('admin/reports/stock_report', ['products' => $products]);
    }

    public function showAdminStockreport()
    {
        $products = Product::orderBy('stock', 'asc')->get();
        return view('admin/reports/stock_report2', ['products' => $products]);
    }


    public function showMaintainancePage()
    {
        return view('website.maintainance');
    }


    public function orderreport()
    {
        $status = OrderStatus::all();
        $deliveries = Delivery::all();
        $products = Product::all('id', 'title_ar', 'code');
        $methods = App\Paymethod::all();
        $users = User::all();
        return view('admin/reports/order_report', compact('status', 'deliveries', 'products', 'users', 'methods'));
    }

    public function displayorders(Request $request)
    {
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];

        $status = $_POST['status'];
        $delivery = $_POST['delivery'];
        $user_id = $_POST['user'];
        $product_id = $_POST['product'];
        $paymethod = $_POST['paymethod'];
        $ordered = $_POST['ordered'];


        $orders = DB::table('orders')
            ->selectRaw('id,number,user_id,delivery_id,coupon_price,status_id,actual_shipping_price
            ,shipping_price as totalshipping,user_address , address_id,
            total_price total,GROUP_CONCAT(product_id SEPARATOR ",") as products, created_at');
        $storeOrders = DB::table('store_orders')
            ->selectRaw('id,number,name,admin_id,delivery_id,status_id,actual_shipping_price,shipping_price as totalshipping,
            total_price as total,GROUP_CONCAT(product_id SEPARATOR ",") as products, created_at, address_id')
            ->where(['from_place' => 0]);
        $placeStoreOrders = DB::table('store_orders')
            ->selectRaw('id,number,name,admin_id,delivery_id,status_id,actual_shipping_price,shipping_price as totalshipping,
            total_price as total,GROUP_CONCAT(product_id SEPARATOR ",") as products, created_at, address_id')
            ->where(['from_place' => 1]);
        $placeOrders = DB::table('place_orders')
            ->selectRaw('id,number,admin_id,status,customer_name, 
            total_price as total,GROUP_CONCAT(product_id SEPARATOR ",") as products, created_at');

        if ($request->statusDate == 0) {
            $orders = $orders->whereBetween('created_at', array($startdate, $enddate));
            $storeOrders = $storeOrders->whereBetween('created_at', array($startdate, $enddate));
            $placeStoreOrders = $placeStoreOrders->whereBetween('created_at', array($startdate, $enddate));
            $placeOrders = $placeOrders->whereBetween('created_at', array($startdate, $enddate));
        } else if ($request->statusDate == 1) {
            $orders = $orders->whereBetween('status_date', array($startdate, $enddate));
            $storeOrders = $storeOrders->whereBetween('status_date', array($startdate, $enddate));

            $placeStoreOrders = $placeStoreOrders->whereBetween('status_date', array($startdate, $enddate));
            $placeOrders = $placeOrders->whereBetween('created_at', array($startdate, $enddate));
        }
        if ($status != '') {
            $orders = $orders->where(['status_id' => $status]);
            $storeOrders = $storeOrders->where(['status_id' => $status]);
            $placeStoreOrders = $placeStoreOrders->where(['status_id' => $status]);
            if ($status === 6) {
                $placeOrders = $placeOrders->where(['status' => $status, 'type' => 1]);
            } else if ($status === 5) {
                $placeOrders = $placeOrders->where(['status' => 6, 'type' => 0]);
            } else {
                $placeOrders = $placeOrders->where(['status' => $status]);
            }

        }
        if ($delivery != '') {
            $orders = $orders->where(['delivery_id' => $delivery]);
            $storeOrders = $storeOrders->where(['delivery_id' => $delivery]);
        }

        if ($user_id != '') {
            $orders = $orders->where(['user_id' => $user_id]);
            $storeOrders = $storeOrders->where(['admin_id' => $user_id]);

            $placeStoreOrders = $placeStoreOrders->where(['admin_id' => $user_id]);
            $placeOrders = $placeOrders->where(['admin_id' => $user_id]);
        }
        if ($product_id != '') {
            $orders = $orders->where(['product_id' => $product_id]);
            $storeOrders = $storeOrders->where(['product_id' => $product_id]);
            $placeStoreOrders = $placeStoreOrders->where(['product_id' => $product_id]);
            $placeOrders = $placeOrders->where(['product_id' => $product_id]);
            $product_id = $product_id;
        } else
            $product_id = null;

        if ($paymethod != '') {
            $orders = $orders->where(['payment_id' => $paymethod]);
            $storeOrders = $storeOrders->where(['payment_id' => $paymethod]);
            $placeStoreOrders = $placeStoreOrders->where(['payment_id' => $paymethod]);
            $placeOrders = $placeOrders->where(['paymethod' => $paymethod]);
        }

        $orders = collect($orders->groupBy('number')
            ->get());
        $storeOrders = collect($storeOrders->groupBy('number')
            ->get());
        $placeStoreOrders = collect($placeStoreOrders->groupBy('number')
            ->get());
        $placeOrders = collect($placeOrders->groupBy('number')
            ->get());


        $response = [
            'product_id' => $product_id
        ];

        if (intval($ordered) === 0) {
            $response = array_merge($response, [
                'orders' => $orders,
                'storeOrders' => $storeOrders,
                'placeStoreOrders' => $placeStoreOrders,
                'placeOrders' => $placeOrders
            ]);
        } else if (intval($ordered) === 1) {
            $response = array_merge($response, [
                'orders' => $orders,
                'storeOrders' => [],
                'placeStoreOrders' => [],
                'placeOrders' => []
            ]);
        } else if (intval($ordered) === 2) {
            $response = array_merge($response, [
                'placeStoreOrders' => [],
                'placeOrders' => $placeOrders,
                'orders' => [],
                'storeOrders' => [],
            ]);
        } else if (intval($ordered) === 3) {
            $response = array_merge($response, [
                'placeStoreOrders' => $placeStoreOrders,
                'orders' => [],
                'storeOrders' => [],
                'placeOrders' => []
            ]);
        } else if (intval($ordered) === 4) {
            $response = array_merge($response, [
                'storeOrders' => $storeOrders,
                'placeStoreOrders' => [],
                'placeOrders' => [],
                'orders' => [],
            ]);
        }
        return response()->view('admin/reports/display_order', $response);
    }

    public function showClients(Request $request)
    {
        $status = OrderStatus::all();
        return view('admin.reports.clients', compact('status'));
    }

    public function getClientsData(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $status = $request->status;
        $ordered = $request->ordered;

        $export = (isset($request->export)) ? intval($request->export) : 0;

        $orders = DB::table('orders')
            ->selectRaw('id,user_id,user_address , address_id');
        $storeOrders = DB::table('store_orders')
            ->selectRaw('id,name,phone, address_id')
            ->where(['from_place' => 0]);
        $placeStoreOrders = DB::table('store_orders')
            ->selectRaw('id,name,phone, address_id')
            ->where(['from_place' => 1]);
        $placeOrders = DB::table('place_orders')
            ->selectRaw('id,customer_name, customer_phone');

        $orders = $orders->whereBetween('created_at', array($startdate, $enddate));
        $storeOrders = $storeOrders->whereBetween('created_at', array($startdate, $enddate));
        $placeStoreOrders = $placeStoreOrders->whereBetween('created_at', array($startdate, $enddate));
        $placeOrders = $placeOrders->whereBetween('created_at', array($startdate, $enddate));

        if ($status != '') {
            $orders = $orders->where(['status_id' => $status]);
            $storeOrders = $storeOrders->where(['status_id' => $status]);
            $placeStoreOrders = $placeStoreOrders->where(['status_id' => $status]);
            $placeOrders = $placeOrders->where(['status' => $status]);
        }
        $allUsersData = [];
        $orders = collect($orders->groupBy('user_id')
            ->get());
        if (intval($ordered) === 0 || intval($ordered) === 1) {
            foreach ($orders as $order) {
                $user = User::where(['id' => $order->user_id])->first();
                if ($order->user_address == null) {
                    $address = \App\Address::where(['id' => $order->address_id])->first();
                    $country = \App\Country::where(['id' => $address['country_id']])->first();
                    $region = \App\Region::where(['id' => $address['region_id']])->first();
                    $area = \App\Area::where(['id' => $address['area_id']])->first();
                }
                if ($order->user_address != null) {
                    $addressText = $order->user_address;
                } else {
                    $addressText = $country['name'] . ' - ' . $region['name'] . ' - ' . $area['name']
                        . ' - ' . $address['address'];
                }
                $allUsersData[] = [
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'address' => $addressText
                ];
            }
        }
        $storeOrders = collect($storeOrders->groupBy('phone')
            ->get());

        $index = 0;
        if (intval($ordered) === 0 || intval($ordered) === 3) {
            foreach ($storeOrders as $order) {


                $addressText = null;
                if ($order->address_id !== null) {
                    $address = \App\Address::where(['id' => $order->address_id])->first();
                    $country = \App\Country::where(['id' => $address['country_id']])->first();
                    $region = \App\Region::where(['id' => $address['region_id']])->first();
                    $area = \App\Area::where(['id' => $address['area_id']])->first();
                    $addressText = $country['name'] . ' - ' . $region['name'] . ' - ' . $area['name']
                        . ' - ' . $address['address'];
                }

                $allUsersData[] = [
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'email' => null,
                    'address' => $addressText
                ];
            }
        }
        $placeStoreOrders = collect($placeStoreOrders->groupBy('phone')
            ->get());
        if (intval($ordered) === 0 || intval($ordered) === 2) {
            foreach ($placeStoreOrders as $order) {
                $addressText = null;
                if ($order->address_id !== null) {
                    $address = \App\Address::where(['id' => $order->address_id])->first();
                    $country = \App\Country::where(['id' => $address['country_id']])->first();
                    $region = \App\Region::where(['id' => $address['region_id']])->first();
                    $area = \App\Area::where(['id' => $address['area_id']])->first();
                    $addressText = $country['name'] . ' - ' . $region['name'] . ' - ' . $area['name']
                        . ' - ' . $address['address'];
                }

                $allUsersData[] = [
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'email' => null,
                    'address' => $addressText
                ];
            }
        }
        $placeOrders = collect($placeOrders->groupBy('customer_phone')
            ->get());
        if (intval($ordered) === 0 || intval($ordered) === 2) {
            foreach ($placeOrders as $order) {
                $allUsersData[] = [
                    'name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                    'email' => null,
                    'address' => null
                ];
            }
        }

        $allUsersData = collect($allUsersData)->toArray();

        $temp = [];
        foreach ($allUsersData as $user) {
            $index = 0;
            $orders = DB::table('orders')
                ->selectRaw('id,number,user_id,user_address , address_id, total_price');
            $storeOrders = DB::table('store_orders')
                ->selectRaw('id,number,name,phone, address_id, total_price')
                ->where(['from_place' => 0]);
            $placeStoreOrders = DB::table('store_orders')
                ->selectRaw('id,number,name,phone, address_id, total_price')
                ->where(['from_place' => 1]);
            $placeOrders = DB::table('place_orders')
                ->selectRaw('id,number,customer_name, customer_phone, total_price');

            $orders = $orders->whereBetween('created_at', array($startdate, $enddate));
            $storeOrders = $storeOrders->whereBetween('created_at', array($startdate, $enddate));
            $placeStoreOrders = $placeStoreOrders->whereBetween('created_at', array($startdate, $enddate));
            $placeOrders = $placeOrders->whereBetween('created_at', array($startdate, $enddate));

            if ($status != '') {
                $orders = $orders->where(['status_id' => $status]);
                $storeOrders = $storeOrders->where(['status_id' => $status]);
                $placeStoreOrders = $placeStoreOrders->where(['status_id' => $status]);
                $placeOrders = $placeOrders->where(['status' => $status]);
            }
            $ordersCount = 0;
            $sum = 0;
            if (intval($ordered) === 0 || intval($ordered) === 1) {
                if ($user['phone'] === null) {
                    $userObj = User::where(['phone' => $user['phone']])->first();
                } else {
                    $userObj = User::where(['email' => $user['email']])->first();
                }
                if ($userObj) {
                    $orders = collect($orders->where(['user_id' => $userObj->id])
                        ->groupBy('number')->get());
                    $sum += $orders->sum('total_price');
                    $ordersCount += $orders->count();
                }

            }
            if (intval($ordered) === 0 || intval($ordered) === 2) {
                $placeOrders = collect($placeOrders->where(['customer_phone' => $user['phone']])
                    ->groupBy('number')->get());
                $ordersCount += $placeOrders->count();
                $sum += $placeOrders->sum('total_price');
                $placeStoreOrders = collect($placeStoreOrders->where(['phone' => $user['phone']])
                    ->groupBy('number')->get());
                $ordersCount += $placeStoreOrders->count();
                $sum += $placeStoreOrders->sum('total_price');
            }
            if (intval($ordered) === 0 || intval($ordered) === 3) {
                $storeOrders = collect($storeOrders->where(['phone' => $user['phone']])
                    ->groupBy('number')->get());
                $ordersCount += $storeOrders->count();
                $sum += $storeOrders->sum('total_price');
            }
            $user['ordersCount'] = $ordersCount;
            $user['total_price'] = $sum;

            $exist = false;
            foreach ($temp as $item) {

                if (strpos(
                        ((empty($item['phone'])) ?
                            'gggg' : str_replace(' ', '', $item['phone'])),
                        ((empty($user['phone'])) ?
                            'test' : str_replace(' ', '', $user['phone']))
                    ) !== false) {
                    $exist = true;
                    $temp[$index]['ordersCount'] += $user['ordersCount'];
                    break;
                }
                $index++;
            }
            if ($exist) continue;

            if (!array_search($user['phone'], array_column($temp, 'phone'))) {
                $temp[] = $user;
            }
        };
        $fromPrice = $request->fromPrice;
        $toPrice = $request->toPrice;
        if (!empty($toPrice) && !empty($toPrice)) {
            $temp = collect($temp)->filter(function ($key, $value) use ($fromPrice, $toPrice) {
                return ($key['total_price'] >= $fromPrice && $key['total_price'] <= $toPrice);
            });
        }

        if ($export === 1) {
            $title = 'بيانات العملاء';
            $data = [];
            $users = $temp;
            $columns = $request->columns;
            foreach ($users as $user) {
                $arr = [];
                if ($columns === 'all' || $columns === 'name') {
                    $arr['إسم العميل'] = $user['name'];
                }
                if ($columns === 'all' || $columns === 'phone') {
                    $arr['تليفون العميل'] = $user['phone'];
                }
                if ($columns === 'all' || $columns === 'address') {
                    $arr['عنوان'] = $user['address'];
                }
                if ($columns === 'all' || $columns === 'email') {
                    $arr['البريد الإلكترونى'] = $user['email'];
                }
                if ($columns === 'all' || $columns === 'ordersCount') {
                    $arr['عدد الطلبات'] = $user['ordersCount'];
                }
                $data[] = $arr;
            }
            \Maatwebsite\Excel\Facades\Excel::create($title, function ($excel) use ($data) {
                $excel->sheet('sheet_name', function ($sheet) use ($data) {
                    $sheet->fromArray($data, null, null, false, true);
                });
            })->download('xls');
        } else {
            return Datatables::of(collect($temp))
                ->make(true);
        }
    }

    public function showSpendingAdd(Request $request)
    {
        return view('admin.spending.spending_add');
    }

    public function saveSpendingAdd(Request $request)
    {
        $spending = new App\Spending();
        $spending->date = date('Y-m-d', strtotime($request->date));
        $spending->spending_type = $request->spending_type;
        $spending->spending_name = $request->spending_name;
        $spending->amount = $request->amount;
        $spending->notes = (isset($request->notes)) ? $request->notes : null;
        $spending->save();
        return redirect()->to('/spending');
    }

    public function showSpending(Request $request)
    {
        if (isset($request->json)) {

            $mainSpending = App\Spending::whereBetween('date', [$request->startdate, $request->enddate])
                ->where(['spending_type' => 1])
                ->get(['amount'])->sum('amount');
            $subSpending = App\Spending::whereBetween('date', [$request->startdate, $request->enddate])
                ->where(['spending_type' => 2])
                ->get(['amount'])->sum('amount');
            $spendings = App\Spending::whereBetween('date', [$request->startdate, $request->enddate])
                ->get(['amount'])->sum('amount');
            return response()->json([
                'data' => [
                    'spendings' => $spendings,
                    'mainSpending' => $mainSpending,
                    'subSpending' => $subSpending
                ]
            ], 200);
        } else {

            $mainSpending = App\Spending::whereBetween('date', [date('Y-m-d'), date('Y-m-d')])
                ->where(['spending_type' => 1])
                ->get(['amount'])->sum('amount');
            $subSpending = App\Spending::whereBetween('date', [date('Y-m-d'), date('Y-m-d')])
                ->where(['spending_type' => 2])
                ->get(['amount'])->sum('amount');
            $spendings = App\Spending::whereBetween('date', [date('Y-m-d'), date('Y-m-d')])
                ->get(['amount'])->sum('amount');
            return view('admin.spending.spending'
                , compact('spendings', 'mainSpending', 'subSpending'));
        }
    }

    public function getSpendingData(Request $request)
    {
        $spendings = App\Spending::whereBetween('date', [$request->startdate, $request->enddate])
            ->get();
        return Datatables::of($spendings)
            ->addColumn('spending', function ($spending) {
                if (intval($spending->spending_type) === 1) {
                    return 'أساسية' . ' - ' . $spending->spending_name;
                } else if (intval($spending->spending_type) === 2) {
                    return 'فرعية' . ' - ' . $spending->spending_name;
                }
            })
            ->make(true);
    }

}