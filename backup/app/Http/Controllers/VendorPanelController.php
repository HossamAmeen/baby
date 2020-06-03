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

class VendorPanelController extends Controller
{

	public function categories($id)
	{
		$vendor = Vendor::find($id);
		$categories = Category::where('parent',0) -> get();
		return view('vendorpanel/categories',compact('categories','vendor'));
	}
	
	public function vendor_balance($id)
	{	
		$vendor = Vendor::find($id);
		$drags = $vendor -> drags;
		$order_ids = DB::table('vendor_orders') -> where('vendor_id',$id) -> pluck('order_id');
		$order_date = DB::table('vendor_orders') -> where('vendor_id',$id)->groupBy('order_number') -> pluck('date');
		
		$orders = DB::table('orders')
	                         ->selectRaw('id,number,status_id,sum(one_price*quantity) as total')
	                         ->whereIn('id',$order_ids)
	                         ->groupBy('number')
	                         ->get();
		return view('vendorpanel/balance_operation',compact('vendor','drags','orders','order_date'));
	}
	
	public function  vendordrag(Request $request)
	{
		if(($request -> drag >= 50) && ($request -> drag % 50 == 0)){
			$vendor_drag = new VendorDrags;
			$vendor_drag -> vendor_id = $request -> vendor_id;
			$vendor_drag -> date = date('Y-m-d H:i:s');
			$vendor_drag -> drag_amount = $request -> drag;
			$vendor_drag -> remain = $request -> vendor_balance - $request -> drag;
			$vendor_drag -> save();
			
		        $vendor = Vendor::find($request->vendor_id);
		        $data = array('vendor_name'=>$vendor -> name,'vendor_id' => $vendor -> id,'drag_id' => $vendor_drag->id);
		        
			Mail::send('emails/vendor_balance', $data, function($message) {
			    $confi = Configurationsite::first();
			    $message->to($confi -> email_ads, 'Admin')->subject('vendor add new product');
			    $message->from(config('mail.from.address'),config('mail.from.name'));
			});
        
		}
		return back();
	}
	
	public function vendor_orders($id)
	{
		$vendor = Vendor::find($id);
		$status = OrderStatus::all();
		$deliveries = Delivery::all();
		return view('vendorpanel/orders',compact('vendor','status','deliveries'));
	}
	
	public function display_orders()
	{
		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];
		$vendor_id = $_POST['vendor_id'];
		
		$product_ids = Product::where('vendor_id',$vendor_id)->pluck('id');
		//dd($product_ids);
		$status = $_POST['status'];
		$delivery = '';
		$orders = [];
		if($status != '' && $delivery != ''){
			$orders = DB::table('orders')
	                         ->selectRaw('id,number,user_id,status_id,GROUP_CONCAT(quantity SEPARATOR ",") as quantities,sum(quantity * one_price) as total,GROUP_CONCAT(product_id SEPARATOR ",") as products')
	                         ->whereRaw('created_at >= ? and created_at <= ?',[$startdate,$enddate])
	                         ->where('status_id',$status) 
	                         ->where('delivery_id',$delivery)
	                         ->whereIn('product_id',$product_ids)
	                         ->groupBy('number')
	                         ->get();
		}
		else{
			if($status != ''){
				$orders = DB::table('orders')
	                         ->selectRaw('id,number,user_id,status_id,GROUP_CONCAT(quantity SEPARATOR ",") as quantities,sum(quantity * one_price) as total,GROUP_CONCAT(product_id SEPARATOR ",") as products')
	                         ->whereRaw('created_at >= ? and created_at <= ?',[$startdate,$enddate])
	                         ->where('status_id',$status) 
	                         ->whereIn('product_id',$product_ids)
	                         ->groupBy('number')
	                         ->get();
			}
			else if($delivery != ''){
				$orders = DB::table('orders')
	                         ->selectRaw('id,number,user_id,status_id,GROUP_CONCAT(quantity SEPARATOR ",") as quantities,sum(quantity * one_price) as total,GROUP_CONCAT(product_id SEPARATOR ",") as products')
	                         ->whereRaw('created_at >= ? and created_at <= ?',[$startdate,$enddate])
	                         ->where('delivery_id',$delivery)
	                         ->whereIn('product_id',$product_ids)
	                         ->groupBy('number')
	                         ->get();
			}
			else{
				$orders = DB::table('orders')
	                         ->selectRaw('id,number,user_id,status_id,GROUP_CONCAT(quantity SEPARATOR ",") as quantities,sum(quantity * one_price) as total,GROUP_CONCAT(product_id SEPARATOR ",") as products')
	                         ->whereRaw('created_at >= ? and created_at <= ?',[$startdate,$enddate])
	                         ->whereIn('product_id',$product_ids)
	                         ->groupBy('number')
	                         ->get();
 
			}
		}
		
		
		return response() -> view('vendorpanel/orders_report', compact('orders','vendor_id'));
	}
	
	public function showproduct($id)
	{
		
		$product = Product::find($id);
	        $currency = DB::table('currencies')->where('status',1)->lists('name','id');
	        $category = Category::all();
	        //$artist = DB::table('makeup_artist')->where('status',1)->lists('name','id');
	        $radio = ProductOption::where('product_id',$id)->where('type','radio')->where('status',1)->get();
	        $check = ProductOption::where('product_id',$id)->where('type','check')->where('status',1)->get();
	        $img = ProductImages::where('product_id',$id)->get();
	        $padge = DB::table('padges')->get();
	        $brands = Brand::all('id','name');
	        $catids = DB::table('category_products') -> where('product_id',$product -> id) -> pluck('category_id');
	        $vendor = Vendor::find($product -> vendor_id);
		return view('vendorpanel/product_edit',compact('padge','img','product','category','currency','radio','check','brands','catids','vendor'));
	}
	
	public function addproduct()
	{
		
		$cat_id = $_POST['cat_id'];
		$vendor_id = $_POST['vendor_id'];
		$str = $_POST['ids'];
		
		if(strlen($str))
		{
		    $cat_ids = explode(',',$str);
		    $category = Category::whereIn('id',$cat_ids) -> get();
		    
		}
		else
		{
		    $category = Category::where('id',$cat_id) -> get();
		}
		$brands = Brand::all();
		$currency = DB::table('currencies')->where('status',1)->lists('name','id');
	        $padge = DB::table('padges')->get();
	        
	        //$artist = DB::table('makeup_artist')->where('status',1)->lists('name','id');
	        //$radio = DB::table('product_option')->where('type','radio')->where('status',1)->lists('option','id');
	        //$check = DB::table('product_option')->where('type','check')->where('status',1)->lists('option','id');
	        //$data = array('padge' => $padge,'category' => $category,'currency' => $currency,'radio' => $radio,'check' => $check,'brands' => $brands);
	        //dd($data);
		return response() -> view('vendorpanel/product_add',compact('padge','category','currency','brands','vendor_id'),200);
	}
	
	public function updateproduct($id,Request $request)
	{
		$add = Product::find($id);
        $add->title = $request->title;
        $add->title_ar = $request->title_ar;
        $add->link = str_replace(['/',' ','-'],"_",$request->title);
        $add->brand_id = $request->brand;
        $add->weight = $request->weight;
        $add->code = $request->code;
        $add->stock = $request->stock;
        $add->price = $request->price;
        $add->discount = $request->discount;
        //$add->currency_id = $request->currency_id;
        $add->padge_id = $request->padge_id;
        //$add->shipping = $request->shipping;
        $add->max_quantity = $request->max_quantity;
        $add->min_quantity = $request->min_quantity;
        $add->vendor_status = $request->status;
        //$add->featured = $request->featured;
        //$add->special = $request->special;
        $add->short_description = $request->short_description;
        $add->description = $request->description;
        $add->user_id = Auth::user()->id;
        
        
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);


            $img_path = base_path() . '/uploads/product/source/';
            $img_path200 = base_path() . '/uploads/product/resize200/';
            $img_path800 = base_path() . '/uploads/product/resize800/';

            if ($add->image != null) {
                unlink(sprintf($img_path . '%s', $add->image));
                 unlink(sprintf($img_path200 . '%s', $add->image));
                 unlink(sprintf($img_path800 . '%s', $add->image));
            }

            
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/product/source/' . $fileName);
            $resize200 = base_path('uploads/product/resize200/' . $fileName);
            $resize1200 = base_path('uploads/product/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = 80;
            $height200 = 100;

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
             $width1200 = 270;
            $height1200 = 354;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                $c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);

            $add->image = $fileName;
            }
        $add->save();
        
        if(count($request->category) > 0){
        	DB::table('category_products')->where('product_id', $add -> id)->delete();
        	foreach($request->category as $catid){
        		DB::table('category_products')->insert(
			     ['product_id' => $add -> id, 'category_id' => $catid]
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
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/product/source/' . $fileName);
            $resize200 = base_path('uploads/product/resize200/' . $fileName);
            $resize1200 = base_path('uploads/product/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = 80;
            $height200 = 100;

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width1200 = 270;
            $height1200 = 354;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                $c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);
            $add1 = new ProductImages();
            $add1->product_id = $add->id;
            $add1->image = $fileName;
            $add1->save();
            }
    }    
        

    ProductOption::where('product_id',$id)->delete();
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
        
        return redirect() -> route('vendorproducts',['id' => Auth::user()->id]);
	}
	
	public function saveproduct(Request $request)
	{
	
	$add = new Product();
        $add->title = $request->title;
        $add->title_ar = $request->title_ar;
        $add->link = str_replace(['/',' ','-'],"_",$request->title);
        $add->brand_id = $request->brand;
        $add->weight = $request->weight;
        $add->code = $request->code;
        $add->stock = $request->stock;
        $add->price = $request->price;
        $add->discount = $request->discount;
        //$add->currency_id = $request->currency_id;
        //$add->shipping = $request->shipping;
        $add->max_quantity = $request->max_quantity;
        $add->min_quantity = $request->min_quantity;
        $add->padge_id = $request->padge_id;
        $add->vendor_status = $request->status;
        //$add->featured = $request->featured;
        //$add->special = $request->special;
        $add->short_description = $request->short_description;
        $add->description = $request->description;
        $add->user_id = Auth::user()->id;
        $add->vendor_id = $request->vendor_id;
        if ($request->hasFile("photo")) {

            $file = $request->file("photo");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);
            
           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/product/source/' . $fileName);
             $resize200 = base_path('uploads/product/resize200/' . $fileName);
             $resize1200 = base_path('uploads/product/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = 80;
            $height200 = 100;

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                
                //$c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width1200 = 270;
            $height1200 = 354;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                //$c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);

            $add->image = $fileName;
            }
        $add->save();
        
        if(count($request->category) > 0){
        	foreach($request->category as $catid){
        		DB::table('category_products')->insert(
			     ['product_id' => $add -> id, 'category_id' => $catid]
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
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            $path = base_path('uploads/product/source/' . $fileName);
            $resize200 = base_path('uploads/product/resize200/' . $fileName);
            $resize1200 = base_path('uploads/product/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = 80;
            $height200 = 100;

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file->getRealPath())->resize($width200, $height200, function ($c) {
                
                //$c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width1200 = 270;
            $height1200 = 354;

            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file->getRealPath())->resize($width1200, $height1200, function ($c) {
                
                //$c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);
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
        
        $vendor = Vendor::find($request->vendor_id);
        $data = array('vendor_name'=>$vendor -> name,'vendor_id' => $vendor -> id,'product_id' => $add->id);
	Mail::send('emails/vendor_product', $data, function($message) {
	    $confi = Configurationsite::first();
	    $message->to($confi -> email_ads, 'Admin')->subject('vendor add new product');
	    $message->from(config('mail.from.address'),config('mail.from.name'));
	});
        
        return redirect() -> route('vendorproducts',['id' => Auth::user()->id]);
	}
	
	
	
	public function getsubcategory()
	{
		
		$cat_id = $_POST['cat_id'];
		$status = $_POST['status'];
		$category = Category::find($cat_id);
		$subcat = $category -> subcat;
		
		$html = '';
		if(count($subcat) > 0)
		{
		   if(!$status)
		   {
		   	
		   	foreach($subcat as $cat)
			{
			   $html .= '<li class="main main0"><h4><a href="javasvript:" onclick="getsubcat('.$cat -> id.',1)">'.$cat -> title.'</a></h4></li>';
			}
		   }
		   else
		   {
		   	$html .= '<li class="main main0"><h4><input class="input_check" type="checkbox" name="categories[]" value="'.$subcat[0] -> id.'">'.$subcat[0] -> title.'</h4></li>';
		   	for($i = 1; $i < count($subcat);$i++)
			{
			   $html .= '<li class="main main0"><h4><input class="input_check" type="checkbox" name="categories[]" value="'.$subcat[i] -> id.'">'.$subcat[i] -> title.'</h4></li>';
			}
		   }
		   
		}
		else
		{
		    if(!$status)
		        $html .= '<li class="main main0"><h4><a href="javasvript:" onclick="getsubcat('.$category -> id.',1)">'.$category -> title.'</a></h4></li>';
		    else
		    	$html .= '<li class="main main0"><h4><input class="input_check" type="checkbox" name="categories[]" value="'.$category -> id.'" checked>'.$category -> title.'</h4></li>';
		}
		
		return $html;
		
	}
	
	public function vendor_products($id)
	{
		$vendor = Vendor::where('user_id',$id) -> first();
		$products = $vendor -> products;
		
		return view('vendorpanel/vendor_products',compact('products','vendor'));
	}
	
	public function edit_product()
	{
		$price = $_POST['price'];
		$quantity = $_POST['quantity'];
		$product_id = $_POST['pro_id'];
		$product = Product::find($product_id);
		$product -> price = $price;
		$product -> stock = $quantity;
		$product -> save();
	}
	
	public function delete_product()
	{
		$product_id = $_POST['pro_id'];
		$product = Product::find($product_id);
		$product -> delete();
	}
}