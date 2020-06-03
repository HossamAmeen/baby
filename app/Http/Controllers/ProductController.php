<?php

namespace App\Http\Controllers;

use App\Color;
use App\Configurationsite;
use App\Order;
use App\PlaceOrder;
use App\ProductLog;
use App\ProductMove;
use App\StoreOrder;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Product;
use Datatables;
use DB;
use App\OptionProduct;
use App\ProductImages;
use Auth;
use File;
use Image;
use URL;
use Mail;
use App\ProductOption;
use App\Brand;
use App\Category;
use App\Vendor;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product = Product::orderBy('id', 'desc')->get();
        return view('admin.products.product', compact('product'));
    }

    public function anyData()
    {

        $products = Product::all();

        return Datatables::of($products)
            ->editColumn('price', function ($product) {
                if ($product->discount == 0)
                    return $product->price;
                else
                    return '<span style="text-decoration: line-through;">' . $product->price . '</span> <br />' . ($product->price - $product->discount);
            })
            ->editColumn('ordered', function ($product) {
                return \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1, 2, 4))
                        ->groupBy('number')
                        ->get()
                        ->sum('quantity') + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1, 2, 4))
                        ->groupBy('number')
                        ->get()
                        ->sum('quantity');
            })
            ->editColumn('delivered', function ($product) {
                return \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(6))
                        ->groupBy('number')
                        ->get()
                        ->sum('quantity') + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(6))
                        ->groupBy('number')
                        ->get()
                        ->sum('quantity');
            })
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    $x = ' <a href="' . URL::to('/') . '/products/' . $data->id . '/edit"><img src="' . URL::to('/') . '/uploads/product/resize200/' . $data->image . '" width="100" height="100"></a>';
                } else {
                    $x = ' <a href="' . URL::to('/') . '/products/' . $data->id . '/edit"><img src="' . URL::to('/') . '/uploads/product/resize200/200noimage.png" width="100" height="100"></a>';
                }

                return $x;
            })
            ->addColumn('title', function ($data) {

                $x = ' <a href="' . URL::to('/') . '/products/' . $data->id . '/edit">' . $data->title . '</a>';

                return $x;
            })
            ->addColumn('brand_id', function ($data) {

                if ($data->brand) {
                    $x = $data->brand->name;

                    return $x;
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->status == 1) {
                    $x = trans('home.published');
                } else {
                    $x = trans('home.unpublished');
                }
                return $x;
            })
            ->addColumn('check', '<input type="checkbox" name="checkbox"  value="{{$id}}" />')
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $colors = Color::get();
        $currency = DB::table('currencies')->where('status', 1)->lists('name', 'id');
        $category = Category::all('id', 'title');
        $padge = DB::table('padges')->lists('title', 'id');
        $artist = DB::table('makeup_artist')->where('status', 1)->lists('name', 'id');
        $radio = DB::table('product_option')->where('type', 'radio')->where('status', 1)->lists('option', 'id');
        $check = DB::table('product_option')->where('type', 'check')->where('status', 1)->lists('option', 'id');
        $brands = Brand::all('id', 'name');
        $vendors = vendor::all();
        return view('admin.products.createproduct', compact('padge', 'colors',
            'artist', 'category', 'currency', 'radio', 'check', 'brands', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        echo '<pre>';
//        print_r($request->photos);
//        echo '</pre>';
//        return;

        $proindex = 0;
        $titlesAr = $request->title_ar;
        $codes = $request->code;
        foreach ($request->title as $title) {
            $add = new Product();
            $add->title = $title;
            $add->title_ar = $titlesAr[$proindex];
            $link_en = str_replace(" ", "-", $add->title);
            $link = str_replace(" ", "-", $add->title_ar);
            $add->link = $link;
            $add->link_en = $link_en;
            $add->buying_price = isset($request->buying_price) ? $request->buying_price : 0;
            $add->brand_id = $request->brand;
            $add->weight = $request->weight;
            $add->code = $codes[$proindex];
            $add->stock = 0;
            $add->main_stock = 0;
            $add->sub_stock = 0;
            $add->store_stock = 0;
            $add->affilate = $request->affilate;
            $add->price = $request->price;
            $add->discount = $request->discount;
            $add->currency_id = $request->currency_id;
            $add->shipping = $request->shipping;
            $add->max_quantity = $request->max_quantity;
            $add->min_quantity = $request->min_quantity;
            $add->status = $request->status;
            $add->featured = $request->featured;
            $add->short_description = $request->short_description;
            $add->description = $request->description;
            $add->short_desc_en = $request->short_desc_en;
            $add->desc_en = $request->desc_en;
            $add->user_id = Auth::user()->id;
            $add->vendor_id = $request->vendor;
            $add->vendor_status = $request->vendor_status;
            $add->admin_comment = $request->comment;
            $add->meta_key = $request->meta_key;
            $add->meta_desc = $request->meta_desc;

            $add->meta_key_en = $request->meta_key_en;
            $add->meta_desc_en = $request->meta_desc_en;

            $index = 1;

            if ($request->hasFile("photo")) {

                $file = $request->file("photo");
                $mime = File::mimeType($file);
                $mimearr = explode('/', $mime);

                // $destinationPath = base_path() . '/uploads/'; // upload path
                $extension = $mimearr[1]; // getting file extension
                $fileName = $add->title_ar . ($index++) . '.' . 'WebP'; // renameing image
                $path = base_path('uploads/product/source/' . $fileName);
                $resize200 = base_path('uploads/product/resize200/' . $fileName);
                $resize800 = base_path('uploads/product/resize800/' . $fileName);

                Image::make($file->getRealPath())->save($path);
                $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
                $widthreal = $arrayimage['0'];
                $heightreal = $arrayimage['1'];

                $width200 = ($widthreal / $heightreal) * 200;
                $height200 = $width200 / ($widthreal / $heightreal);

                $img200 = Image::canvas($width200, $height200);
                $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img200->insert($image200, 'center');
                $img200->save($resize200);

                $width800 = ($widthreal / $heightreal) * 800;
                $height800 = $width800 / ($widthreal / $heightreal);

                $img800 = Image::canvas($width800, $height800);
                $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img800->insert($image800, 'center');
                $img800->save($resize800);

                $add->image = $fileName;
            }
            $add->save();
//            $uplink = Product::find($add->id);
//            $uplink->link = $uplink->link . $uplink->id;
//            $uplink->save();

            if (count($request->category) > 0) {
                foreach ($request->category as $catid) {
                    DB::table('category_products')->insert(
                        ['product_id' => $add->id, 'category_id' => $catid]
                    );
                }
            }

            if ($request->hasFile('photos')) {

                $files = $request->file('photos');

                foreach ($files as $file) {

                    //$file = $request->file("photo");
                    $mime = File::mimeType($file);
                    $mimearr = explode('/', $mime);

                    // $destinationPath = base_path() . '/uploads/'; // upload path
                    $extension = $mimearr[1]; // getting file extension
                    $fileName = $add->title_ar . ($index++) . '.' . 'WebP'; // renameing image
                    $path = base_path('uploads/product/source/' . $fileName);
                    $resize200 = base_path('uploads/product/resize200/' . $fileName);
                    $resize800 = base_path('uploads/product/resize800/' . $fileName);
                    //  $file->move($destinationPath, $fileName);

                    Image::make($file->getRealPath())->save($path);

                    $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
                    $widthreal = $arrayimage['0'];
                    $heightreal = $arrayimage['1'];

                    $width200 = ($widthreal / $heightreal) * 200;
                    $height200 = $width200 / ($widthreal / $heightreal);

                    $img200 = Image::canvas($width200, $height200);
                    $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });
                    $img200->insert($image200, 'center');
                    $img200->save($resize200);

                    $width800 = ($widthreal / $heightreal) * 800;
                    $height800 = $width800 / ($widthreal / $heightreal);

                    $img800 = Image::canvas($width800, $height800);
                    $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });
                    $img800->insert($image800, 'center');
                    $img800->save($resize800);

                    $add1 = new ProductImages();
                    $add1->product_id = $add->id;
                    $add1->image = $fileName;
                    $add1->save();
                }
            }


            if ($request->Input('radio_name')) {
                $priceoption = $request->Input('radio_price');
                foreach ($request->Input('radio_name') as $k => $ur) {
                    $t = new ProductOption();
                    $t->option = $ur;
                    $t->price = $priceoption[$k];
                    $t->product_id = $add->id;
                    $t->type = 'radio';
                    $t->save();
                }
            }

            if ($request->Input('check_name')) {
                $priceoption = $request->Input('check_price');
                foreach ($request->Input('check_name') as $k => $ur) {
                    $t = new ProductOption();
                    $t->option = $ur;
                    $t->price = $priceoption[$k];
                    $t->product_id = $add->id;
                    $t->type = 'check';
                    $t->save();
                }
            }

            $e = Configurationsite::select('email')->first();
            $emailsales = $e->email;
            $data['subject'] = 'إضافة المنتج';
            $data['message'] = auth()->user()->name . ' قام بإضافة منتج ' . $add->code . ' - ' . $add->title_ar . ' (' . $add->stock . ')';
            try {
                Mail::send('auth.emails.general', ['data' => $data], function ($message) use ($emailsales, $data) {
                    $message->to($emailsales)
                        ->subject($data['subject']);
                });
            } catch (\Exception $ex) {

            }
            $proindex++;
        }


        return redirect('products');
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
        $product = Product::find($id);
        $currency = DB::table('currencies')->where('status', 1)->lists('name', 'id');
        $category = Category::all('id', 'title');
        $artist = DB::table('makeup_artist')->where('status', 1)->lists('name', 'id');
        $radio = ProductOption::where('product_id', $id)->where('type', 'radio')->where('status', 1)->get();
        $check = ProductOption::where('product_id', $id)->where('type', 'check')->where('status', 1)->get();
        $img = ProductImages::where('product_id', $id)->get();
        $padge = DB::table('padges')->lists('title', 'id');
        $brands = Brand::all('id', 'name');
        $catids = DB::table('category_products')->where('product_id', $product->id)->pluck('category_id');
        $vendors = vendor::all();
        return view('admin.products.editproduct', compact('padge', 'artist', 'img', 'product', 'category', 'currency', 'radio', 'check', 'brands', 'catids', 'vendors'));
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
        //
        //dd($request->all());
        $add = Product::find($id);
        $exist = false;
        if ($request->link !== $add->link) {
            $exist = Product::where(['link' => $request->link])
                ->orWhere(['link_en' => $request->link])->first();
        }
        if ($request->link_en !== $add->link_en) {
            $exist = Product::where(['link_en' => $request->link_en])
                ->orWhere(['link' => $request->link_en])->first();
        }

        if ($request->title_ar !== $add->title_ar) {
            $exist = Product::where(['title_ar' => $request->title_ar])->first();
        }

        if ($exist) {
            return redirect('products/' . $id . '/edit');
        }

        $stock = $add->stock;
        $add->title = $request->title;
        $add->title_ar = $request->title_ar;
        $add->link = $request->link;
        $add->link_en = $request->link_en;

        if (isset($request->buying_price) && !empty($request->buying_price)) {
            $add->buying_price = $request->buying_price;
        }
        $add->brand_id = $request->brand;
        $add->weight = $request->weight;
        $add->code = $request->code;
//        $add->stock = $request->stock;
//        $add->store_stock = $request->store_stock;
        $add->affilate = $request->affilate;
        $add->price = $request->price;
        $add->discount = $request->discount;
        $add->currency_id = $request->currency_id;

        $add->shipping = $request->shipping;
        $add->max_quantity = $request->max_quantity;
        $add->min_quantity = $request->min_quantity;
        $add->status = $request->status;
        $add->featured = $request->featured;

        $add->short_description = $request->short_description;
        $add->description = $request->description;
        $add->short_desc_en = $request->short_desc_en;
        $add->desc_en = $request->desc_en;
        $add->meta_key = $request->meta_key;
        $add->meta_desc = $request->meta_desc;

        $add->meta_key_en = $request->meta_key_en;
        $add->meta_desc_en = $request->meta_desc_en;

        $add->user_id = Auth::user()->id;
        //$add->vendor_id = $request->vendor;
        $add->vendor_status = $request->vendor_status;
        $add->admin_comment = $request->comment;
        $index = 1;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);


            $img_path = base_path() . '/uploads/product/source/';
            $img_path200 = base_path() . '/uploads/product/resize200/';
            $img_path800 = base_path() . '/uploads/product/resize800/';

            if ($add->image != null) {
                if (file_exists(sprintf($img_path . '%s', $add->image)))
                    unlink(sprintf($img_path . '%s', $add->image));
                if (file_exists(sprintf($img_path200 . '%s', $add->image)))
                    unlink(sprintf($img_path200 . '%s', $add->image));
                if (file_exists(sprintf($img_path800 . '%s', $add->image)))
                    unlink(sprintf($img_path800 . '%s', $add->image));
            }


            // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = $add->title_ar . ($index++) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/product/source/' . $fileName);
            $resize200 = base_path('uploads/product/resize200/' . $fileName);
            $resize800 = base_path('uploads/product/resize800/' . $fileName);
            //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];

            $width200 = ($widthreal / $heightreal) * 200;
            $height200 = $width200 / ($widthreal / $heightreal);

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);

            $width800 = ($widthreal / $heightreal) * 800;
            $height800 = $width800 / ($widthreal / $heightreal);

            $img800 = Image::canvas($width800, $height800);
            $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img800->insert($image800, 'center');
            $img800->save($resize800);

            $add->image = $fileName;
        }else{
            $img_path = base_path() . '/uploads/product/source/';
            $img_path200 = base_path() . '/uploads/product/resize200/';
            $img_path800 = base_path() . '/uploads/product/resize800/';
            $fileName = $add->title_ar . ($index++) . '.' . 'WebP';
            if(file_exists($img_path . $add->image)){
                rename($img_path . $add->image , $img_path . $fileName);
            }
            if(file_exists($img_path200 . $add->image)){
                rename($img_path200 . $add->image , $img_path200 . $fileName);
            }
            if(file_exists($img_path800 . $add->image)){
                rename($img_path800 . $add->image , $img_path800 . $fileName);
            }
            $add->image = $fileName;
        }
        $add->save();
//        $uplink = Product::find($add->id);
//        $uplink->link = $uplink->id . '-' . $uplink->link;
//        $uplink->save();
        if (count($request->category) > 0) {
            DB::table('category_products')->where('product_id', $add->id)->delete();
            foreach ($request->category as $catid) {
                DB::table('category_products')->insert(
                    ['product_id' => $add->id, 'category_id' => $catid]
                );
            }
        }

        if ($request->hasFile('photos')) {

            $productImages = ProductImages::where(['product_id' => $add->id])->get();
            foreach ($productImages as $productImage) {
                if ($productImage->image != null) {
                    $img_path = base_path() . '/uploads/product/source/';
                    $img_path200 = base_path() . '/uploads/product/resize200/';
                    $img_path800 = base_path() . '/uploads/product/resize800/';

                    if (file_exists(sprintf($img_path . '%s', $productImage->image)))
                        unlink(sprintf($img_path . '%s', $productImage->image));
                    if (file_exists(sprintf($img_path200 . '%s', $productImage->image)))
                        unlink(sprintf($img_path200 . '%s', $productImage->image));
                    if (file_exists(sprintf($img_path800 . '%s', $productImage->image)))
                        unlink(sprintf($img_path800 . '%s', $productImage->image));
                }
                $productImage->forceDelete();
            }
            $files = $request->file('photos');

            foreach ($files as $file) {

                //$file = $request->file("photo");
                $mime = File::mimeType($file);
                $mimearr = explode('/', $mime);

                // $destinationPath = base_path() . '/uploads/'; // upload path   
                $extension = $mimearr[1]; // getting file extension
                $fileName = $add->title_ar . ($index++) . '.' . 'WebP'; // renameing image
                $path = base_path('uploads/product/source/' . $fileName);
                $resize200 = base_path('uploads/product/resize200/' . $fileName);
                $resize800 = base_path('uploads/product/resize800/' . $fileName);
                //  $file->move($destinationPath, $fileName);

                Image::make($file->getRealPath())->save($path);

                $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
                $widthreal = $arrayimage['0'];
                $heightreal = $arrayimage['1'];

                $width200 = ($widthreal / $heightreal) * 200;
                $height200 = $width200 / ($widthreal / $heightreal);

                $img200 = Image::canvas($width200, $height200);
                $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img200->insert($image200, 'center');
                $img200->save($resize200);

                $width800 = ($widthreal / $heightreal) * 800;
                $height800 = $width800 / ($widthreal / $heightreal);

                $img800 = Image::canvas($width800, $height800);
                $image800 = Image::make($file->getRealPath())->resize($width800, $height800, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img800->insert($image800, 'center');
                $img800->save($resize800);
                $add1 = new ProductImages();
                $add1->product_id = $add->id;
                $add1->image = $fileName;
                $add1->save();
            }
        }else{
            $productImages = ProductImages::where(['product_id' => $add->id])->get();
            foreach ($productImages as $productImage) {
                $img_path = base_path() . '/uploads/product/source/';
                $img_path200 = base_path() . '/uploads/product/resize200/';
                $img_path800 = base_path() . '/uploads/product/resize800/';
                $fileName = $add->title_ar . ($index++) . '.' . 'WebP';
                if (file_exists($img_path . $productImage->image)) {
                    rename($img_path . $productImage->image, $img_path . $fileName);
                }
                if (file_exists($img_path200 . $productImage->image)) {
                    rename($img_path200 . $productImage->image, $img_path200 . $fileName);
                }
                if (file_exists($img_path800 . $productImage->image)) {
                    rename($img_path800 . $productImage->image, $img_path800 . $fileName);
                }
                $productImage->image = $fileName;
                $productImage->save();
            }
        }


        ProductOption::where('product_id', $id)->delete();
        if ($request->Input('radio_name')) {
            $priceoption = $request->Input('radio_price');
            foreach ($request->Input('radio_name') as $k => $ur) {
                $t = new ProductOption();
                $t->option = $ur;
                $t->price = $priceoption[$k];
                $t->product_id = $add->id;
                $t->type = 'radio';
                $t->save();
            }
        }

        if ($request->Input('check_name')) {
            $priceoption = $request->Input('check_price');
            foreach ($request->Input('check_name') as $k => $ur) {
                $t = new ProductOption();
                $t->option = $ur;
                $t->price = $priceoption[$k];
                $t->product_id = $add->id;
                $t->type = 'check';
                $t->save();
            }
        }

        $e = Configurationsite::select('email')->first();
        $emailsales = $e->email;
        $data['subject'] = 'تعديل المنتج';
        $data['message'] = auth()->user()->name . ' قام بالتعديل علي منتج ' . $add->code . ' - ' . $add->title_ar . ' (' . $stock . ' إلي ' . $request->stock . ')';
        try {
            Mail::send('auth.emails.general', ['data' => $data], function ($message) use ($emailsales, $data) {
                $message->to($emailsales)
                    ->subject($data['subject']);
            });
        } catch (\Exception $ex) {
        }

        return redirect('products');
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
            $s = Product::findOrFail($id);
            $e = Configurationsite::select('email')->first();
            $emailsales = $e->email;
            $data['subject'] = 'حذف المنتج';
            $data['message'] = auth()->user()->name . ' قام بحذف المنتج ' . $s->title_ar;
            try {
                Mail::send('auth.emails.general', ['data' => $data], function ($message) use ($emailsales, $data) {
                    $message->to($emailsales)
                        ->subject($data['subject']);
                });
            } catch (\Exception $ex) {

            }
            $img_path = base_path() . '/uploads/product/source/';
            $img_path200 = base_path() . '/uploads/product/resize200/';
            $img_path800 = base_path() . '/uploads/product/resize800/';

            if ($s->image != null) {
                unlink(sprintf($img_path . '%s', $s->image));
                unlink(sprintf($img_path200 . '%s', $s->image));
                unlink(sprintf($img_path800 . '%s', $s->image));
            }
            $s->delete();
        }
    }

    public function patchUpdateProducts(Request $request)
    {
        $products = Product::get();
        foreach ($products as $product) {
            $product->main_stock = $product->stock;
            $product->sub_stock = 0;
            $product->store_stock = 0;
            $product->link = 'product_' . $product->id;
            $product->save();
        }
    }

    public function patchUpdateProductsLinks(Request $request)
    {
        $products = Product::get();
        foreach ($products as $product) {
            $product->link_en = str_replace(' ', '-', $product->title);
            $product->save();
        }
        return response()->json([
            'message' => 'success'
        ]);
    }


    public function showPermissionProductAdd()
    {
        return view('admin.products.permission_product_add');
    }


    public function getProductDetails(Request $request)
    {
        $product = Product::where(['code' => $request->code])->first();

        if ($product) {
            if (isset($request->bar_code) && !isset($request->show)) {
                $product->price = (intval($product->dicsount) !== 0)
                    ? $product->price : $product->price - $product->discount;
                if (isset($request->new_title) && !empty($request->new_title)) {
                    $product->title_ar = $request->new_title;
                }
                return view('admin.products.barcode_print_raw', compact('product'));
            } else {
                $product->price = (intval($product->dicsount) !== 0)
                    ? $product->price : $product->price - $product->discount;
                return response()->json([
                    'data' => $product
                ], 200);
            }
        }
        if (isset($request->create) && !empty($request->create)) {
            return response()->json([], 200);
        }
        return response()->json([], 404);
    }

    public function savePermissionProduct(Request $request)
    {
        $productMove = ProductMove::where(['type' => $request->type])->orderBy('id', 'desc')->first();

        $number = '';
        if (intval($request->type) === 0) {
            $number = 'pa';
        } else if (intval($request->type) === 1) {
            $number = 'ps';
        } else if (intval($request->type) === 2) {
            $number = 'pc';
        }

        if ($productMove) {
            $number = $number . (intval(substr($productMove->number, 2)) + 1);
        } else {
            $number = $number . '1';
        }
        $products = json_decode($request->products, true);
        if (intval($request->type) === 2) {
            foreach ($products as $productPojo) {
                if (intval($request->from) === 0) {
                    $search = ['main_stock', '>=', $productPojo['quantity']];
                } else if (intval($request->from) === 1) {
                    $search = ['sub_stock', '>=', $productPojo['quantity']];
                } else if (intval($request->from) === 2) {
                    $search = ['store_stock', '>=', $productPojo['quantity']];
                }
                $product = Product::where([$search, 'id' => $productPojo['id']])->first();
                if (!$product) {
                    return response()->json([], 404);
                }
            }
        }
        foreach ($products as $productPojo) {
            $productMove = new ProductMove();
            $productMove->number = $number;
            $productMove->product_id = $productPojo['id'];
            $productMove->type = $request->type;
            $productMove->to = $request->to;
            $productMove->from = (isset($request->from)) ? $request->from : null;
            $productMove->quantity = $productPojo['quantity'];
            $productMove->price = (isset($productPojo['buying_price']) && !empty($productPojo['buying_price'])
                && $productPojo['buying_price'] !== 0) ? $productPojo['buying_price'] : null;
            $productMove->notes = (isset($request->notes) && !empty(isset($request->notes)))
                ? isset($request->notes) : null;
            $productMove->admin_id = auth()->user()->id;
            $productMove->save();

            $productMove->type = intval($productMove->type);
            $productMove->to = intval($productMove->to);
            $productMove->from = ($productMove->from !== null) ? intval($productMove->from) : null;
            $productLogData = [];
            if ($productMove->type === 0) {
                // add
                $product = Product::where(['id' => $productPojo['id']])->first();
                if ($productMove->price !== null) {
                    $product->buying_price = (($product->buying_price * $product->stock)
                            + ($productMove->quantity * $productMove->price)) /
                        ($product->stock + $productMove->quantity);
                }
                $productLogData = [
                    'product_id' => $product->id,
                    'quantity' => $productMove->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'user_id' => auth()->user()->id
                ];
                $product->stock += $productMove->quantity;
                if ($productMove->to === 0) { //main_stock
                    $productLogData['action'] = 'add_main_quantity';
                    $product->main_stock += $productMove->quantity;
                } else if ($productMove->to === 1) { //sub_stock
                    $productLogData['action'] = 'add_sub_quantity';
                    $product->sub_stock += $productMove->quantity;
                } else if ($productMove->to === 2) { //store_stock
                    $productLogData['action'] = 'add_store_quantity';
                    $product->store_stock += $productMove->quantity;
                }
                $product->save();
            } else if ($productMove->type === 1) {
                // sarf
                $product = Product::where(['id' => $productPojo['id']])->first();
                $productLogData = [
                    'product_id' => $product->id,
                    'quantity' => $productMove->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'user_id' => auth()->user()->id
                ];
                $product->stock -= $productMove->quantity;
                if ($productMove->to === 0) { //main_stock
                    $productLogData['action'] = 'sarf_main_quantity';
                    $product->main_stock -= $productMove->quantity;
                } else if ($productMove->to === 1) { //sub_stock
                    $productLogData['action'] = 'sarf_sub_quantity';
                    $product->sub_stock -= $productMove->quantity;
                } else if ($productMove->to === 2) { //store_stock
                    $productLogData['action'] = 'sarf_store_quantity';
                    $product->store_stock -= $productMove->quantity;
                }
                $product->save();
            } else if ($productMove->type === 2) {
                $product = Product::where(['id' => $productPojo['id']])->first();
                $productLogData = [
                    'product_id' => $product->id,
                    'quantity' => $productMove->quantity,
                    'stock' => $product->stock,
                    'main_stock' => $product->main_stock,
                    'store_stock' => $product->store_stock,
                    'sub_stock' => $product->sub_stock,
                    'user_id' => auth()->user()->id
                ];

                if ($productMove->from === 0) { //main_stock
                    $productLogData['action'] = 'change_main_';
                    $product->main_stock -= $productMove->quantity;
                } else if ($productMove->from === 1) { //sub_stock
                    $productLogData['action'] = 'change_sub_';
                    $product->sub_stock -= $productMove->quantity;
                } else if ($productMove->from === 2) { //store_stock
                    $productLogData['action'] = 'change_store_';
                    $product->store_stock -= $productMove->quantity;
                }

                if ($productMove->to === 0) { //main_stock
                    $productLogData['action'] .= 'main';
                    $product->main_stock += $productMove->quantity;
                } else if ($productMove->to === 1) { //sub_stock
                    $productLogData['action'] .= 'sub';
                    $product->sub_stock += $productMove->quantity;
                } else if ($productMove->to === 2) { //store_stock
                    $productLogData['action'] .= 'store';
                    $product->store_stock += $productMove->quantity;
                }
                $product->save();
            }

            ProductLog::create($productLogData);
        }
        return response()->json([], 200);
    }

    public function showMinusProduct()
    {
        return view('admin.products.minus_product');
    }

    public function saveMinusProduct(Request $request)
    {
        $productMove = ProductMove::orderBy('id', 'desc')->first();
        if ($productMove) {
            $number = 'm' . (intval(substr($productMove->number, 1)) + 1);
        } else {
            $number = 'm130';
        }
        $products = json_decode($request->products, true);
        foreach ($products as $productPojo) {
            if (intval($request->to) === 0) {
                $search = ['main_stock', '>=', $productPojo['quantity']];
            } else if (intval($request->to) === 1) {
                $search = ['sub_stock', '>=', $productPojo['quantity']];
            } else if (intval($request->to) === 2) {
                $search = ['store_stock', '>=', $productPojo['quantity']];
            }
            $product = Product::where([$search, 'id' => $productPojo['id']])->first();
            if (!$product) {
                return response()->json([], 404);
            }
        }
        foreach ($products as $productPojo) {
            $productMove = new ProductMove();
            $productMove->number = $number;
            $productMove->product_id = $productPojo['id'];
            $productMove->type = $request->type;
            $productMove->to = $request->to;
            $productMove->from = null;
            $productMove->quantity = $productPojo['quantity'];
            $productMove->price = null;
            $productMove->notes = (isset($request->notes) && !empty(isset($request->notes)))
                ? isset($request->notes) : null;
            $productMove->admin_id = auth()->user()->id;
            $productMove->save();

            $productMove->type = intval($productMove->type);
            $productMove->to = intval($productMove->to);

            // sarf
            $product = Product::where(['id' => $productPojo['id']])->first();
            $productLogData = [
                'product_id' => $product->id,
                'quantity' => $productMove->quantity,
                'stock' => $product->stock,
                'main_stock' => $product->main_stock,
                'store_stock' => $product->store_stock,
                'sub_stock' => $product->sub_stock,
                'user_id' => auth()->user()->id
            ];
            $product->stock -= $productMove->quantity;
            if ($productMove->to === 0) { //main_stock
                $productLogData['action'] = 'minus_main';
                $product->main_stock -= $productMove->quantity;
            } else if ($productMove->to === 1) { //sub_stock
                $productLogData['action'] = 'minus_sub';
                $product->sub_stock -= $productMove->quantity;
            } else if ($productMove->to === 2) { //store_stock
                $productLogData['action'] = 'minus_store';
                $product->store_stock -= $productMove->quantity;
            }
            $product->save();
            ProductLog::create($productLogData);
        }
        return response()->json([], 200);
    }

    public function showReturnProduct(Request $request)
    {
        return view('admin.products.return_product');
    }

    public function saveReturnProduct(Request $request)
    {
        $productMove = ProductMove::orderBy('id', 'desc')->first();
        if ($productMove) {
            $number = 'm' . (intval(substr($productMove->number, 1)) + 1);
        } else {
            $number = 'm130';
        }
        $products = json_decode($request->products, true);

        foreach ($products as $productPojo) {
            $productMove = new ProductMove();
            $productMove->number = $number;
            $productMove->product_id = $productPojo['id'];
            $productMove->type = $request->type;
            $productMove->to = $request->to;
            $productMove->from = null;
            $productMove->quantity = $productPojo['quantity'];
            $productMove->price = null;
            $productMove->notes = (isset($request->notes) && !empty(isset($request->notes)))
                ? isset($request->notes) : null;
            $productMove->admin_id = auth()->user()->id;
            $productMove->save();

            $productMove->type = intval($productMove->type);
            $productMove->to = intval($productMove->to);

            // return
            $product = Product::where(['id' => $productPojo['id']])->first();
            $productLogData = [
                'product_id' => $product->id,
                'quantity' => $productMove->quantity,
                'stock' => $product->stock,
                'main_stock' => $product->main_stock,
                'store_stock' => $product->store_stock,
                'sub_stock' => $product->sub_stock,
                'user_id' => auth()->user()->id
            ];
            $product->stock += $productMove->quantity;
            if ($productMove->to === 0) { //main_stock
                $productLogData['action'] = 'return_main';
                $product->main_stock += $productMove->quantity;
            } else if ($productMove->to === 1) { //sub_stock
                $productLogData['action'] = 'return_sub';
                $product->sub_stock += $productMove->quantity;
            } else if ($productMove->to === 2) { //store_stock
                $productLogData['action'] = 'return_store';
                $product->store_stock += $productMove->quantity;
            }
            ProductLog::create($productLogData);
            $product->save();
        }
        return response()->json([], 200);
    }

    public function showProductExchange()
    {
        return view('admin.products.exchange_product');
    }

    public function showPermissionProductAddReport(Request $request)
    {
        $search = [];
        if (isset($request->stock) && !empty($request->stock)) {
            $search['to'] = $request->stock;
        }
        if (isset($request->type) && (!empty($request->type) || intval($request->type) === 0)) {
            $search['type'] = $request->type;
        }
        if (isset($request->admin_id) && !empty($request->admin_id)) {
            $search['admin_id'] = $request->admin_id;
        }
        $orders = ProductMove::whereIn('type', array(0, 1));
        if (count($search) > 0) {
            $orders = $orders->where($search);
        }
        if (isset($request->FromData) && !empty($request->FromData)) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->FromData)));
        }
        if (isset($request->toData) && !empty($request->toData)) {
            $orders = $orders->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->toData)));
        }
        $orders = $orders->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();
        $addTotal = 0;
        $sarfTotal = 0;
        $total = 0;
        foreach ($orders as $order1) {
            $allOrders = ProductMove::where(['number' => $order1->number])->get();
            foreach ($allOrders as $order) {
                if ($order->price !== null) {
                    $price = $order->price;
                } else {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $price = $product->buying_price;
                }
                if (intval($order->type) === 0) {
                    $addTotal += ($price * $order->quantity);
                } else if (intval($order->type) === 1) {
                    $sarfTotal += ($price * $order->quantity);
                }
            }
        }

        $total = $addTotal - $sarfTotal;

        $admins = User::where(['admin' => 1])->get();
        if (!isset($request->json)) {
            return view('admin.products.permission_report',
                compact('addTotal', 'sarfTotal', 'total', 'admins'));
        } else {
            return response()->json([
                'data' => [
                    'addTotal' => $addTotal,
                    'sarfTotal' => $sarfTotal,
                    'total' => $total
                ]
            ], 200);
        }
    }

    public function getPermissionProductAddReportData(Request $request)
    {
        $search = [];
        if (isset($request->stock) && !empty($request->stock)) {
            $search['to'] = $request->stock;
        }
        if (isset($request->type) && (!empty($request->type) || intval($request->type) === 0)) {
            $search['type'] = $request->type;
        }
        if (isset($request->admin_id) && !empty($request->admin_id)) {
            $search['admin_id'] = $request->admin_id;
        }
        $orders = ProductMove::whereIn('type', array(0, 1));
        if (count($search) > 0) {
            $orders = $orders->where($search);
        }
        if (isset($request->FromData) && !empty($request->FromData)) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->FromData)));
        }
        if (isset($request->toData) && !empty($request->toData)) {
            $orders = $orders->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->toData)));
        }
        $orders = $orders->orderBy('id', 'desc')
            ->groupBy('number');

        return Datatables::of($orders)
            ->editColumn('type', function ($order) {
                if (intval($order->type) === 0)
                    return 'إضافة';
                else
                    return 'صرف';
            })
            ->addColumn('date', function ($order) {
                return date('Y-m-d', strtotime($order->created_at));
            })
            ->editColumn('to', function ($order) {
                if (intval($order->to) === 0)
                    return 'رئيسي';
                else if (intval($order->to) === 1)
                    return 'فرعى';
                else
                    return 'محل';
            })
            ->addColumn('products', function ($order) {
                $orders = ProductMove::where(['number' => $order->number])->get();
                $productsStr = '';
                foreach ($orders as $order) {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $productsStr .= $product->title_ar . ' - ' . $product->code . ' <hr />';
                }
                return $productsStr;
            })
            ->addColumn('quantities', function ($order) {
                $orders = ProductMove::where(['number' => $order->number])->get();
                $quantities = '';
                foreach ($orders as $order) {
                    $quantities .= $order->quantity . ' <hr />';
                }
                return $quantities;
            })
            ->addColumn('price', function ($order) {
                $orders = ProductMove::where(['number' => $order->number])->get();
                $total = 0;
                foreach ($orders as $order) {
                    if ($order->price !== null) {
                        $price = $order->price;
                    } else {
                        $product = Product::where(['id' => $order->product_id])->first();
                        $price = $product->buying_price;
                    }
                    $total += ($order->quantity * $price);
                }
                return $total;
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
            ->make(true);
    }

    public function showExchangeProductReport(Request $request)
    {
        $search = [];
        if (isset($request->stock) && !empty($request->stock)) {
            $search['to'] = $request->stock;
        }
        if (isset($request->from) && !empty($request->from)) {
            $search['from'] = $request->from;
        }
        if (isset($request->admin_id) && !empty($request->admin_id)) {
            $search['admin_id'] = $request->admin_id;
        }
        $orders = ProductMove::whereIn('type', array(2));

        if (count($search) > 0) {
            $orders = $orders->where($search);
        }

        if (isset($request->FromData) && !empty($request->FromData)) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->FromData)));
        }
        if (isset($request->toData) && !empty($request->toData)) {
            $orders = $orders->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->toData)));
        }
        $orders = $orders->orderBy('id', 'desc')
            ->groupBy('number')
            ->get();
        $addTotal = 0;
        $sarfTotal = 0;
        $total = 0;
        foreach ($orders as $order1) {
            $allOrders = ProductMove::where(['number' => $order1->number])->get();
            foreach ($allOrders as $order) {
                $product = Product::where(['id' => $order->product_id])->first();
                $price = (intval($product->discount) !== 0) ? $product->price :
                    $product->price - $product->discount;
                $total += ($price * $order->quantity);
            }
        }

        $admins = User::where(['admin' => 1])->get();
        if (!isset($request->json)) {
            return view('admin.products.exchange_report',
                compact('total', 'admins'));
        } else {
            return response()->json([
                'data' => [
                    'total' => $total
                ]
            ], 200);
        }
    }

    public function getExchangeProductReportData(Request $request)
    {
        $search = [];
        if (isset($request->stock) && !empty($request->stock)) {
            $search['to'] = $request->stock;
        }
        if (isset($request->from) && !empty($request->from)) {
            $search['from'] = $request->from;
        }
        if (isset($request->admin_id) && !empty($request->admin_id)) {
            $search['admin_id'] = $request->admin_id;
        }
        $orders = ProductMove::whereIn('type', array(2));

        if (count($search) > 0) {
            $orders = $orders->where($search);
        }

        if (isset($request->FromData) && !empty($request->FromData)) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->FromData)));
        }
        if (isset($request->toData) && !empty($request->toData)) {
            $orders = $orders->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->toData)));
        }
        $orders = $orders->orderBy('id', 'desc')
            ->groupBy('number');
//            ->get();
        return Datatables::of($orders)
            ->addColumn('number', function ($order) {
                return '<a href="' . url('/exchange/print/' . $order->number) . '" 
                class="btn btn-success" target="_blank">' . $order->number . '</a>';
            })
            ->addColumn('date', function ($order) {
                return date('Y-m-d', strtotime($order->created_at));
            })
            ->editColumn('from', function ($order) {
                if (intval($order->from) === 0)
                    return 'رئيسي';
                else if (intval($order->from) === 1)
                    return 'فرعى';
                else
                    return 'محل';
            })
            ->editColumn('to', function ($order) {
                if (intval($order->to) === 0)
                    return 'رئيسي';
                else if (intval($order->to) === 1)
                    return 'فرعى';
                else
                    return 'محل';
            })
            ->addColumn('products', function ($order) {
                $orders = ProductMove::where(['number' => $order->number])->get();
                $productsStr = '';
                foreach ($orders as $order) {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $productsStr .= $product->title_ar . ' - ' . $product->code . ' <hr />';
                }
                return $productsStr;
            })
            ->addColumn('quantities', function ($order) {
                $orders = ProductMove::where(['number' => $order->number])->get();
                $quantities = '';
                foreach ($orders as $order) {
                    $quantities .= $order->quantity . ' <hr />';
                }
                return $quantities;
            })
            ->addColumn('price', function ($order) {
                $orders = ProductMove::where(['number' => $order->number])->get();
                $total = 0;
                foreach ($orders as $order) {
                    $product = Product::where(['id' => $order->product_id])->first();
                    $price = (intval($product->discount) !== 0) ? $product->price :
                        $product->price - $product->discount;
                    $total += ($order->quantity * $price);
                }
                return $total;
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
            ->make(true);
    }

    ///
    public function showMinusProductReport(Request $request)
    {
        $products = Product::get()->each(function ($product) use ($request) {
            $search = [];
            if (isset($request->stock) && !empty($request->stock)) {
                $search['to'] = $request->stock;
            }
            if (isset($request->type) && (!empty($request->type) || intval($request->type) === 3)) {
                $search['type'] = $request->type;
            }
            if (isset($request->admin_id) && !empty($request->admin_id)) {
                $search['admin_id'] = $request->admin_id;
            }
            $orders = ProductMove::whereIn('type', array(3, 4));
            if (count($search) > 0) {
                $orders = $orders->where($search);
            }
            if (isset($request->FromData) && !empty($request->FromData)) {
                $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->FromData)));
            }
            if (isset($request->toData) && !empty($request->toData)) {
                $orders = $orders->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->toData)));
            }
            $product->halek = $orders->where(['product_id' => $product->id, 'type' => 3])->get(['quantity'])->sum('quantity');
            $product->halek -= $orders->where(['product_id' => $product->id, 'type' => 4])->get(['quantity'])->sum('quantity');
        });
        $addTotal = 0;
        $sarfTotal = 0;
        $total = 0;
        $products = collect($products)->filter(function ($key, $value)
        use (&$addTotal, &$sarfTotal) {
            if ($key->halek > 0) {
                $addTotal += $key->price;
                $sarfTotal += $key->buying_price;
                return $key->halek > 0;
            }
            return false;
        });

        $total = $addTotal - $sarfTotal;

        $admins = User::where(['admin' => 1])->get();
        if (!isset($request->json)) {
            return view('admin.products.minus_report',
                compact('addTotal', 'sarfTotal', 'total', 'admins'));
        } else {
            return response()->json([
                'data' => [
                    'addTotal' => $addTotal,
                    'sarfTotal' => $sarfTotal,
                    'total' => $total
                ]
            ], 200);
        }
    }

    public function getMinusProductReportData(Request $request)
    {

        $products = Product::get()->each(function ($product) use ($request) {
            $search = [];
            if (isset($request->stock) && !empty($request->stock)) {
                $search['to'] = $request->stock;
            }
            if (isset($request->type) && (!empty($request->type) || intval($request->type) === 3)) {
                $search['type'] = $request->type;
            }
            if (isset($request->admin_id) && !empty($request->admin_id)) {
                $search['admin_id'] = $request->admin_id;
            }
            $orders = ProductMove::whereIn('type', array(3, 4));
            if (count($search) > 0) {
                $orders = $orders->where($search);
            }
            if (isset($request->FromData) && !empty($request->FromData)) {
                $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->FromData)));
            }
            if (isset($request->toData) && !empty($request->toData)) {
                $orders = $orders->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->toData)));
            }
            $product->halek = $orders->where(['product_id' => $product->id, 'type' => 3])->get(['quantity'])->sum('quantity');
            $product->halek -= $orders->where(['product_id' => $product->id, 'type' => 4])->get(['quantity'])->sum('quantity');
        });

        $products = collect($products)->filter(function ($key, $value) {
            return $key->halek > 0;
        });

        return Datatables::of($products)
            ->make(true);
    }

    ////

    public function printExchangeProductOrder($id, Request $request)
    {
        $order = ProductMove::where(['number' => $id])->get();
        return view('admin.products.print_exchange',
            compact('order'));
    }

    public function substockreport()
    {
        return view('admin/reports/sub_stock_report');
    }

    public function getSubstockreportData(Request $request)
    {
        $products = Product::orderBy('sub_stock', 'DESC')->get();
        return Datatables::of($products)
            ->editColumn('image', function ($product) {
                return '<img width="50" src="' . url('uploads/product/resize200') . '/' . $product->image . '">';
            })
            ->editColumn('price', function ($product) {
                if ($product->discount == 0) {
                    return $product->price;
                } else {
                    return '<span style="text-decoration: line-through;">' . $product->price . '</span>' .
                        '<br/>' . ($product->price - $product->discount);
                }
            })
            ->addColumn('ordered', function ($product) {
                return \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
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
            })
            ->make(true);
    }

    public function store_stock_report()
    {
        $products = Product::get();
        $productsData = [];
        foreach ($products as $product) {
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
                + \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
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
            $product->ordered = $total;
            if ($product->ordered > 0 || $product->store_stock) {
                $productsData[] = $product;
            }
        }
        return view('admin/reports/store_stock_report', compact('productsData'));
    }

    public function getStoreStockreportData(Request $request)
    {
        $products = Product::get()
            ->each(function ($product) {
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
                $product->ordered = $total;
            });
        return Datatables::of($products)
            ->editColumn('image', function ($product) {
                return '<img width="50" src="' . url('uploads/product/resize200') . '/' . $product->image . '">';
            })
            ->editColumn('price', function ($product) {
                if ($product->discount == 0) {
                    return $product->price;
                } else {
                    return '<span style="text-decoration: line-through;">' . $product->price . '</span>' .
                        '<br/>' . ($product->price - $product->discount);
                }
            })
            ->make(true);
    }

    public function main_stock_report()
    {
        return view('admin/reports/main_stock_report');
    }

    public function getMainStockreportData(Request $request)
    {
        $products = Product::orderBy('store_stock', 'DESC')->get();
        return Datatables::of($products)
            ->editColumn('image', function ($product) {
                return '<img width="50" src="' . url('uploads/product/resize200') . '/' . $product->image . '">';
            })
            ->editColumn('price', function ($product) {
                if ($product->discount == 0) {
                    return $product->price;
                } else {
                    return '<span style="text-decoration: line-through;">' . $product->price . '</span>' .
                        '<br/>' . ($product->price - $product->discount);
                }
            })
            ->editColumn('ordered', function ($product) {
                $total = \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
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
                return $total;
            })
            ->make(true);
    }

    public function showProductMoveReport()
    {
        return view('admin/reports/product_move_search_report');
    }

    public function showProductMoveReportData(Request $request)
    {
        $data['products'] = ProductLog::join('products', 'products.id', '=', 'product_logs.product_id')
            ->orderBy('product_logs.id', 'DESC')
            ->where(['code' => $request->code])
            ->get(['code', 'product_logs.quantity', 'product_logs.stock', 'product_logs.main_stock',
                'product_logs.store_stock', 'product_logs.sub_stock', 'action', 'product_logs.user_id'
                , 'product_logs.created_at'])->each(function ($product) {
                if ($product->action === ProductLog::add_order) {
                    $product->action = 'إضافة طلب من الموقع';
                } else if ($product->action === ProductLog::edit_order) {
                    $product->action = 'تعديل طلب من الموقع';
                } else if ($product->action === ProductLog::add_place_order) {
                    $product->action = 'إضافة طلب من المحل';
                } else if ($product->action === ProductLog::return_place_order) {
                    $product->action = 'إرجاع طلب من المحل';
                } else if ($product->action === ProductLog::add_place_store_order) {
                    $product->action = 'إضافة طلب الصفحة من المحل';
                } else if ($product->action === ProductLog::edit_place_store_order) {
                    $product->action = 'تعديل طلب الصفحة من المحل';
                } else if ($product->action === ProductLog::add_store_order) {
                    $product->action = 'إضافة طلب من الصفحة';
                } else if ($product->action === ProductLog::edit_store_order) {
                    $product->action = 'تعديل طلب من الصفحة';
                } else if ($product->action === ProductLog::add_main_quantity) {
                    $product->action = 'إضافة كمية للرئيسى';
                } else if ($product->action === ProductLog::add_store_quantity) {
                    $product->action = 'إضافة كمية للمحل';
                } else if ($product->action === ProductLog::add_sub_quantity) {
                    $product->action = 'إضافة كمية للفرعى';
                } else if ($product->action === ProductLog::sarf_main_quantity) {
                    $product->action = ' صرف من الرئيسى';
                } else if ($product->action === ProductLog::sarf_store_quantity) {
                    $product->action = 'صرف من المحل';
                } else if ($product->action === ProductLog::sarf_sub_quantity) {
                    $product->action = 'صرف من الفرعى';
                } else if ($product->action === ProductLog::change_main_main) {
                    $product->action = 'تحويل من الرئيسى للرئيسى';
                } else if ($product->action === ProductLog::change_main_sub) {
                    $product->action = 'تحويل من الرئيسى للفرعى';
                } else if ($product->action === ProductLog::change_main_store) {
                    $product->action = 'تحويل من الرئيسى للمحل';
                } else if ($product->action === ProductLog::change_sub_main) {
                    $product->action = 'تحويل من الفرعى للرئيسى';
                } else if ($product->action === ProductLog::change_sub_sub) {
                    $product->action = 'تحويل من الفرعى للفرعى';
                } else if ($product->action === ProductLog::change_sub_store) {
                    $product->action = 'تحويل من الفرعى للمحل';
                } else if ($product->action === ProductLog::change_store_main) {
                    $product->action = 'تحويل من المحل للرئيسى';
                } else if ($product->action === ProductLog::change_store_sub) {
                    $product->action = 'تحويل من المحل للفرعى';
                } else if ($product->action === ProductLog::change_store_store) {
                    $product->action = 'تحويل من المحل للمحل';
                } else if ($product->action === ProductLog::minus_main) {
                    $product->action = 'هالك رئيسى';
                } else if ($product->action === ProductLog::minus_sub) {
                    $product->action = 'هالك فرعى';
                } else if ($product->action === ProductLog::minus_store) {
                    $product->action = 'هالك محل';
                } else if ($product->action === ProductLog::return_main) {
                    $product->action = 'مرتجع هالك رئيسى';
                } else if ($product->action === ProductLog::return_sub) {
                    $product->action = 'مرتجع هالك فرعى';
                } else if ($product->action === ProductLog::return_store) {
                    $product->action = 'مرتجع هالك محل';
                } else if ($product->action === ProductLog::return_order) {
                    $product->action = 'مرتجع طلب موقع';
                } else if ($product->action === ProductLog::cancel_order) {
                    $product->action = 'إلغاء طلب موقع';
                } else if ($product->action === ProductLog::return_store_order) {
                    $product->action = 'مرتجع طلب صفحة';
                } else if ($product->action === 'delivered_store_order') {
                    $product->action = 'تسليم طلب صفحة';
                } else if ($product->action === ProductLog::cancel_store_order) {
                    $product->action = 'إلغاء طلب صفحة';
                } else if ($product->action === ProductLog::return_place_store_order) {
                    $product->action = 'مرتجع طلب صفحة من محل';
                } else if ($product->action === ProductLog::cancel_place_store_order) {
                    $product->action = 'إلغاء طلب صفحة من محل';
                }
                if ($product->user_id !== null)
                    $product->admin_name = User::where(['id' => $product->user_id])->first()['name'];
            });

        return view('admin/reports/product_move_report')->with($data);
    }

    public function getProductMoveReport(Request $request)
    {
        $products = ProductLog::join('products', 'products.id', '=', 'product_logs.product_id')
            ->orderBy('product_logs.id', 'DESC')
            ->get(['code', 'product_logs.quantity', 'product_logs.stock', 'product_logs.main_stock',
                'product_logs.store_stock', 'product_logs.sub_stock', 'action', 'product_logs.user_id'
                , 'product_logs.created_at']);

        return Datatables::of($products)
            ->editColumn('action', function ($product) {
                if ($product->action === ProductLog::add_order) {
                    return 'إضافة طلب من الموقع';
                } else if ($product->action === ProductLog::edit_order) {
                    return 'تعديل طلب من الموقع';
                } else if ($product->action === ProductLog::add_place_order) {
                    return 'إضافة طلب من المحل';
                } else if ($product->action === ProductLog::return_place_order) {
                    return 'إرجاع طلب من المحل';
                } else if ($product->action === ProductLog::add_place_store_order) {
                    return 'إضافة طلب الصفحة من المحل';
                } else if ($product->action === ProductLog::edit_place_store_order) {
                    return 'تعديل طلب الصفحة من المحل';
                } else if ($product->action === ProductLog::add_store_order) {
                    return 'إضافة طلب من الصفحة';
                } else if ($product->action === ProductLog::edit_store_order) {
                    return 'تعديل طلب من الصفحة';
                } else if ($product->action === ProductLog::add_main_quantity) {
                    return 'إضافة كمية للرئيسى';
                } else if ($product->action === ProductLog::add_store_quantity) {
                    return 'إضافة كمية للمحل';
                } else if ($product->action === ProductLog::add_sub_quantity) {
                    return 'إضافة كمية للفرعى';
                } else if ($product->action === ProductLog::sarf_main_quantity) {
                    return ' صرف من الرئيسى';
                } else if ($product->action === ProductLog::sarf_store_quantity) {
                    return 'صرف من المحل';
                } else if ($product->action === ProductLog::sarf_sub_quantity) {
                    return 'صرف من الفرعى';
                } else if ($product->action === ProductLog::change_main_main) {
                    return 'تحويل من الرئيسى للرئيسى';
                } else if ($product->action === ProductLog::change_main_sub) {
                    return 'تحويل من الرئيسى للفرعى';
                } else if ($product->action === ProductLog::change_main_store) {
                    return 'تحويل من الرئيسى للمحل';
                } else if ($product->action === ProductLog::change_sub_main) {
                    return 'تحويل من الفرعى للرئيسى';
                } else if ($product->action === ProductLog::change_sub_sub) {
                    return 'تحويل من الفرعى للفرعى';
                } else if ($product->action === ProductLog::change_sub_store) {
                    return 'تحويل من الفرعى للمحل';
                } else if ($product->action === ProductLog::change_store_main) {
                    return 'تحويل من المحل للرئيسى';
                } else if ($product->action === ProductLog::change_store_sub) {
                    return 'تحويل من المحل للفرعى';
                } else if ($product->action === ProductLog::change_store_store) {
                    return 'تحويل من المحل للمحل';
                } else if ($product->action === ProductLog::minus_main) {
                    return 'هالك رئيسى';
                } else if ($product->action === ProductLog::minus_sub) {
                    return 'هالك فرعى';
                } else if ($product->action === ProductLog::minus_store) {
                    return 'هالك محل';
                } else if ($product->action === ProductLog::return_main) {
                    return 'مرتجع هالك رئيسى';
                } else if ($product->action === ProductLog::return_sub) {
                    return 'مرتجع هالك فرعى';
                } else if ($product->action === ProductLog::return_store) {
                    return 'مرتجع هالك محل';
                }
            })
            ->addColumn('admin_name', function ($product) {
                if ($product->user_id !== null)
                    return User::where(['id' => $product->user_id])->first()['name'];
            })
            ->make(true);
    }

    public function printProductBarcode()
    {
        return view('admin.products.barcode');
    }

    public function showProductsReport()
    {
        return view('admin.reports.products_report');
    }

    public function getProductsReportData(Request $request)
    {
        $startDate = $_POST['startdate'];
        $endDate = $_POST['enddate'];

        $addStartDate = $_POST['addStartDate'];
        $addEndDate = $_POST['addEndDate'];

        $ordered = $_POST['ordered'];
        $stock = $_POST['stock'];

        $totalPrice = 0;
        $totalBuyingPrice = 0;
        $totalProfit = 0;
        $totalProfitPercent = 0;


        $products = Product::get(['id', 'code', 'title_ar', 'image', 'buying_price', 'price'
            , 'stock', 'discount'])
            ->each(function ($product) use (
                $startDate, $endDate, $ordered, $stock
            ) {
                $total = 0;

                if (intval($stock) === 3 || empty($stock)) {
                    $orders = Order::where(['product_id' => $product->id, 'status_id' => 6]);
                    $orders = $orders->whereBetween('created_at', array($startDate, $endDate));
                    $orders = $orders->get(['quantity'])->sum('quantity');

                    $total += $orders;
                }

                if (intval($stock) === 2 || empty($stock)) {
                    $placeOrders = PlaceOrder::where(['product_id' => $product->id
                        , 'status' => 6]);
                    $placeOrders = $placeOrders->whereBetween('created_at', array($startDate, $endDate));
                    $placeOrders = $placeOrders->get(['quantity'])->sum('quantity');

                    $placeStoreOrders = StoreOrder::where(['product_id' => $product->id,
                        'from_place' => 1, 'status_id' => 6]);
                    $placeStoreOrders = $placeStoreOrders->whereBetween('created_at', array($startDate, $endDate));
                    $placeStoreOrders = $placeStoreOrders->get(['quantity'])->sum('quantity');

                    $placeOrders = $placeOrders + $placeStoreOrders;
                    $total += $placeOrders;
                }

                if (intval($stock) === 1 || empty($stock)) {
                    $storeOrders = StoreOrder::where(['product_id' => $product->id,
                        'from_place' => 0, 'status_id' => 6]);
                    $storeOrders = $storeOrders->whereBetween('created_at', array($startDate, $endDate));
                    $storeOrders = $storeOrders->get(['quantity'])->sum('quantity');
                    $total += $storeOrders;
                }

                $product->totalOrderd = $total;
                $product->totalStock = $product->stock;

                if (intval($ordered) === 1 || intval($ordered) === 2) {
                    $product->totalPrice = $product->totalOrderd *
                        (($product->discount === 0) ? $product->price : $product->price - $product->discount);
                    $product->totalBuyingPrice = $product->totalOrderd * $product->buying_price;
                } else {
                    $product->totalPrice = $product->totalStock *
                        (($product->discount === 0) ? $product->price : $product->price - $product->discount);
                    $product->totalBuyingPrice = $product->totalStock * $product->buying_price;
                }

                $product->profit = $product->totalPrice - $product->totalBuyingPrice;
                $product->profitPercent = ($product->totalPrice > 0) ?
                    number_format(($product->profit / $product->totalPrice) * 100
                        , 2) : 0;


            });

        if (intval($ordered) === 1) {

        } else if (intval($ordered) === 2) {
            $products = collect($products)->sortByDesc('totalOrderd');
        } else if (intval($ordered) === 3) {
            $products = collect($products)->sortBy('totalOrderd');
        }

        $products = collect($products)->filter(function ($key, $value)
        use (
            $ordered, $addStartDate, $addEndDate
            , &$totalPrice, &$totalBuyingPrice, &$totalProfit
        ) {
            $product = $key;
            if (intval($ordered) === 1 && $key->totalOrderd > 0) {
                $totalPrice += $product->totalPrice;
                $totalBuyingPrice += $product->totalBuyingPrice;
                $totalProfit += $product->profit;
                return $key->totalOrderd > 0;
            } else if (intval($ordered) === 2 && $key->totalOrderd >= 2) {
                $totalPrice += $product->totalPrice;
                $totalBuyingPrice += $product->totalBuyingPrice;
                $totalProfit += $product->profit;
                return $key->totalOrderd >= 2;
            } else {
                $productMove = ProductMove::where(['type' => 0, 'product_id' => $product->id])
                    ->whereBetween('created_at', [$addStartDate, $addEndDate])->first();
                if (!$productMove && $key->totalOrderd === 0 && $key->totalStock > 0) {
                    $totalPrice += $product->totalPrice;
                    $totalBuyingPrice += $product->totalBuyingPrice;
                    $totalProfit += $product->profit;
                    $key->totalOrderd2 = $key->totalOrderd;
                    return ($key->totalOrderd2 === 0 && $key->totalStock > 0);
                }
            }
        });

        $totalProfitPercent = ($totalPrice > 0) ?
            number_format(($totalProfit / $totalPrice) * 100
                , 2) : 0;

        return response()->view('admin/reports/display_product',
            [
                'products' => $products,
                'ordered' => $ordered,
                'totalPrice' => $totalPrice,
                'totalBuyingPrice' => $totalBuyingPrice,
                'totalProfit' => $totalProfit,
                'totalProfitPercent' => $totalProfitPercent
            ]);
    }


    public function showProductsAvailableReport()
    {
        return view('admin.reports.available_products_report');
    }

    public function getProductsAvailableReport(Request $request)
    {


        $products = Product::get(['id', 'code', 'title_ar', 'image', 'buying_price', 'price'
            , 'stock', 'discount']);

        return Datatables::of($products)
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    $x = ' <a href="' . URL::to('/') . '/products/' . $data->id . '/edit"><img src="' . URL::to('/') . '/uploads/product/resize200/' . $data->image . '" width="100" height="100"></a>';
                } else {
                    $x = ' <a href="' . URL::to('/') . '/products/' . $data->id . '/edit"><img src="' . URL::to('/') . '/uploads/product/resize200/200noimage.png" width="100" height="100"></a>';
                }

                return $x;
            })
            ->addColumn('available', function ($data) {
                if ($data->stock > 0) {
                    return 'متاح';
                } else {
                    return 'غير متاح';
                }
            })
            ->make(true);

    }

}
