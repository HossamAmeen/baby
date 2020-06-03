<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Configurationsite;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Response;
use Mail;
use File;
use Image;
use PDF;
use App\Slideshow;
use App\Testomnial;
use App\Search;
use DB;
use App\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\BlogCategory;
use App\BlogItem;
use App\Contactus;
use App\Gallery;
use App\EmailMessage;
use App\Currency;
use App\ProductOption;
use App\ProductImages;
use App\Product;
use App\ProductReview;
use App\ProductRate;
use App\Artist;
use App\Favorite;
use App\Cart;
use App\OptionCart;
use App\Coupon;
use App\CouponCart;
use App\Order;
use App\OrderOption;
use App\CartSession;
use App\Address;
use App\Paymethod;
use App\Sponsor;
use App\Region;
use App\Area;
use App\Delivery;
use App\Brand;
use App\Country;
use App\Vendor;
use App\Affilate;
use App\Affilate_Product;

class WebsiteController extends Controller
{

    //
    public function __construct()
    {
        if (!\Session::has('currencychange')) {
            \Session::set('currencychange', 1);
        }
        if (!\Session::has('currencysymbol')) {
            \Session::set('currencysymbol', 'LE');
        }
    }

    public function clearCache()
    {
        $exitCode = \Illuminate\Support\Facades\Artisan::call('route:clear');
        $exitCode = \Illuminate\Support\Facades\Artisan::call('cache:clear');
        $exitCode = \Illuminate\Support\Facades\Artisan::call('config:clear');
        $exitCode = \Illuminate\Support\Facades\Artisan::call('view:clear');
        return 'Routes cache cleared';
    }

    public function editSliderImages()
    {
        $adds = Slideshow::get();
        foreach ($adds as $add) {
            $file = base_path('/uploads/slideshow/source/' . $add->image);

            $fileName = rand(11111, 99999) . '.' . 'WebP'; // renameing image
            $path = base_path('uploads/slideshow/source/' . $fileName);
            $resize200 = base_path('uploads/slideshow/resize200/' . $fileName);
            $resize1200 = base_path('uploads/slideshow/resize1200/' . $fileName);
            //  $file->move($destinationPath, $fileName);
            Image::make($file)->save($path);
            $arrayimage = list($width, $height) = getimagesize($file);
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];

            $width200 = ($widthreal / $heightreal) * 200;
            $height200 = $width200 / ($widthreal / $heightreal);
            $file = base_path('/uploads/slideshow/resize200/' . $add->image);
            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make($file)->resize($width200, $height200, function ($c) {
                $c->upsize();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);

            $width1200 = 1140;
            $height1200 = ($heightreal * 1140) / $width1200;
            $file = base_path('/uploads/slideshow/resize1200/' . $add->image);
            $img1200 = Image::canvas($width1200, $height1200);
            $image1200 = Image::make($file)->resize($width1200, $height1200, function ($c) {
                $c->upsize();
            });
            $img1200->insert($image1200, 'center');
            $img1200->save($resize1200);


            unlink(base_path('/uploads/slideshow/resize200/' . $add->image));
            unlink(base_path('/uploads/slideshow/source/' . $add->image));
            unlink(base_path('/uploads/slideshow/resize1200/' . $add->image));
            $add->image = $fileName;
            $add->save();
        }
    }

    public function editProductImages()
    {
        $notIds = [206, 272, 273, 323, 362, 415, 434, 468, 565, 601, 819, 852,
            853, 914, 1025, 1092, 1122, 1171, 1190, 1192, 1223, 1224, 1229,
            1232, 1234, 1239, 1240, 1241, 1242, 1243, 1245, 1246, 1249,
            1250, 1251, 1252, 1253, 1254, 1255, 1258, 1259, 1260, 1262,
            1263, 1268, 1269, 1273, 1275, 1277, 1278, 1279, 1280, 1281,
            1283, 1383, 1437, 1438, 1439, 1452, 1454, 1456, 1457, 1458,
            1459, 1463, 1466, 1467, 1469, 1473, 1480, 1484, 1487, 1490,
            1498, 1504, 1505, 1512, 1515, 1516, 1526, 1541, 1546, 1563,
            1735, 1755, 1854, 1855, 1895, 1899, 1921, 1922, 1945, 1953,
            2007, 2028, 2029, 2060, 2106, 2107, 2125, 2141, 2153, 2186,
            2187, 2189, 2190, 2210, 2373, 2499, 2501, 2502, 2503, 2504,
            2553, 2583, 2602, 2735, 2843, 2844, 2939, 2941, 2942, 3023,
            3026, 3027, 3029, 3031, 3045, 3058, 3074, 3075, 3078, 3124,
            3139, 3143, 3144, 3145, 3148, 3158, 3174, 3225, 3228, 3231,
            3232, 3243, 3255, 3258, 3260, 3261, 3264, 3265, 3268, 3271,
            3272, 3273, 3274, 3275, 3276, 3277, 3279, 3281, 3279, 3281,
            3282, 3283, 3284, 3287, 3290, 3291, 3293, 3294, 3327, 3332,
            3376, 3419, 3421, 3422, 3446, 3469, 3470, 3471, 3292, 3295,
            3296, 3298, 3299, 3300, 3301, 3302, 3303, 3304, 3305, 3306,
            3307, 3310, 3311, 3312, 3315, 3316, 3320, 3324, 3325, 3372,
            3453, 3483, 3484, 3519, 83, 91, 162, 215, 243, 271, 359, 416,
            418, 439, 490, 492, 497, 592, 598, 603, 623, 631, 697, 747,
            748, 854, 858, 893, 902, 905, 911, 928, 962, 975, 995, 1007,
            1026, 1042, 1046, 1085, 1087, 1097, 1110, 1111, 1169, 1173,
            1193, 1231, 1238, 1244, 1248, 1265, 1267, 1272, 1274, 1327,
            1392, 1397, 1415, 1465, 1468, 1483, 1493, 1495, 1550, 1585,
            1684, 1724, 1730, 1751, 1766, 1894, 1923, 1924, 1991, 1992,
            2008, 2146, 2285, 2290, 2312, 2377, 2532, 2550, 2853, 2854,
            2855, 2858, 2922, 2924, 2932, 2958, 3034, 3077, 3140, 3141,
            3201, 3259, 3263, 3309, 3322, 3333, 3374, 3375, 3420, 3464,
            3465, 3467, 3468, 3480, 3487, 3490, 3491, 3492, 3493, 3494,
            3495, 3497, 3498, 3520, 1401, 1417, 1450, 1502, 1517, 1530,
            1539, 1552, 1715, 1748, 1797, 1800, 1802, 1814, 1819, 1823,
            1841, 1896, 1944, 1962, 1967, 1981, 2033, 2056, 2077, 2137,
            2139, 2157, 2232, 2256, 2259, 2280, 2282, 2286, 2287, 2289,
            2344, 2371, 2391, 2474, 2475, 2554, 2776, 2841, 2857, 2920,
            2948, 2952, 2959, 3028, 3032, 3083, 3084, 3085, 3090, 3122,
            3159, 3168, 3170, 3224, 3230, 3248, 3250, 3297, 3308, 3314,
            3318, 3321, 3337, 3340, 3341, 3342, 3344, 3345, 3346, 3347,
            3348, 3349, 3350, 3351, 3352, 3356, 3365, 3366, 3389, 3414,
            3443, 3448, 3456, 3482, 3485, 3488, 3499, 3501, 3512, 3516,
            3517, 3518, 46, 65, 75, 78, 90, 123, 202, 203, 214, 281,
            290, 312, 360, 380, 390, 398, 399, 417, 433, 435, 437, 438,
            452, 455, 460, 461, 485, 540, 556, 600, 610, 624, 626, 628,
            645, 656, 721, 744, 756, 758, 759, 763, 822, 825, 830, 835,
            849, 856, 857, 888, 891, 894, 899, 924, 926, 961, 966, 968,
            974, 985, 987, 1040, 1041, 1047, 1075, 1093, 1099, 1114, 1185,
            1186, 1230, 1236, 1237, 1247, 1264, 1276, 1284, 1303, 1313,
            1316, 1323, 1326, 1341, 1388, 1390, 1422, 1433, 1470, 1471,
            1478, 1481, 1485, 1488, 1506, 1534, 1577, 1578, 1579, 1580,
            1616, 1617, 1618, 1626, 1629, 1630, 1631, 1642, 1645, 1646,
            1685, 1716, 1717, 1746, 1760, 1764, 1765, 1773, 1787, 1791,
            1795, 1796, 1806, 1824, 1829, 1873, 1909, 1910, 1918, 1961,
            1971, 1979, 1980, 1982, 1988, 2026, 2037, 2053, 2054, 2083,
            2087, 2089, 2091, 2092, 2105, 2138, 2158, 2192, 2193, 2196,
            2211, 2227, 2229, 2233, 2249, 2250, 2253, 2254, 2255, 2257,
            2258, 2281, 2283, 2284, 2288, 2307, 2317, 2323, 2329, 2330,
            2332, 2335, 2337, 2345, 2346, 2348, 2363, 2372, 2397, 2460,
            2461, 2462, 2463, 2478, 2480, 2487, 2510, 2637, 2664, 2711,
            2715, 2719, 2772, 2775, 2832, 2842, 2900, 2902, 2903, 2931,
            2936, 2938, 2944, 2949, 2950, 2957, 2992, 3020, 3056, 3082,
            3092, 3097, 3134, 3146, 3149, 3150, 3169, 3171, 3172, 3179,
            3180, 3182, 3183, 3187, 3191, 3193, 3223, 3227, 3229, 3247,
            3256, 3278, 3286, 3329, 3336, 3338, 3353, 3354, 3355, 3367,
            3368, 3369, 3373, 3377, 3378, 3380, 3381, 3385, 3412, 3416,
            3436, 3441, 3445, 3447, 3451, 3452, 3454, 3457, 3466, 3472,
            3473, 3474, 3475, 3479, 3481, 3486, 3489, 3502, 3503, 3505,
            3508, 3510, 3511, 3513, 3514, 3515, 33, 41, 50, 51, 54, 79,
            92, 96, 101, 102, 111, 116, 117, 124, 125, 136, 141, 201,
            207, 235, 240, 249, 253, 256, 264, 266, 269, 274, 275, 301,
            305, 307, 318, 328, 350, 353, 354, 355, 358, 379, 389, 397,
            400, 413, 428, 430, 441, 446, 453, 458, 465, 467, 469, 470,
            475, 477, 488, 495, 517, 525, 541, 547, 557, 562, 567, 573,
            577, 580, 614, 577, 580, 614, 617, 618, 625, 639, 643, 647,
            649, 651, 659, 671, 682, 685, 732, 757, 760, 764, 772, 781,
            782, 813, 843, 848, 850, 859, 876, 886, 889, 898, 903, 916,
            925, 927, 930, 957, 963, 988, 991, 1010, 1013, 1015, 1017,
            1018, 1021, 1024, 1039, 1051, 1052, 1063, 1073, 1078,
            1079, 1088, 1095, 1096, 1098, 1104, 1106, 1112, 1120, 1148,
            1151, 1152, 1155, 1162, 1164, 1167, 1179, 1187, 1188, 1191,
            1203, 1204, 1216, 1233, 1235, 1270, 1271, 1290, 1291, 1294,
            1296, 1299, 1302, 1304, 1305, 1307, 1310, 1314, 1315, 1335,
            1336, 1338, 1339, 1343, 1344, 1345, 1347, 1349, 1350, 1359,
            1360, 1365, 1367, 1369, 1371, 1373, 1374, 1376, 1379, 1380,
            1381, 1385, 1389, 1400, 1404, 1409, 1412, 1413, 1425, 1427,
            1432, 1436, 1455, 1460, 1472, 1474, 1475, 1476, 1492, 1507,

        ];
        $products = Product::where('stock', '>=', 1)
            ->whereNotIn('id', $notIds)
            ->get();
        print_r(Product::where('stock', '>=', 1)
            ->whereNotIn('id', $notIds)
            ->limit(4)->pluck('id')->toArray());
        echo '<br />' . $products->count() . ' - ';


        $i = 0;
        $length = 3;
        $ids = [];

        foreach ($products as $product) {
            if ($i === $length) break;
            $i++;
            $ids[] = $product->id;
            try {
                // $destinationPath = base_path() . '/uploads/'; // upload path
                $extension = 'WebP'; // getting file extension
                $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                $path = base_path('uploads/product/source/' . $fileName);
                $resize200 = base_path('uploads/product/resize200/' . $fileName);
                $resize800 = base_path('uploads/product/resize800/' . $fileName);
                //  $file->move($destinationPath, $fileName);

                $file = base_path('uploads/product/source/' . $product->image);
                Image::make($file)->save($path);

                $arrayimage = list($width, $height) = getimagesize($file);
                $widthreal = $arrayimage['0'];
                $heightreal = $arrayimage['1'];

                $width200 = ($widthreal / $heightreal) * 200;
                $height200 = $width200 / ($widthreal / $heightreal);
                $file = base_path('uploads/product/resize200/' . $product->image);
                $img200 = Image::canvas($width200, $height200);
                $image200 = Image::make($file)->resize($width200, $height200, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img200->insert($image200, 'center');
                $img200->save($resize200);

                $width800 = ($widthreal / $heightreal) * 800;
                $height800 = $width800 / ($widthreal / $heightreal);
                $file = base_path('uploads/product/resize800/' . $product->image);
                $img800 = Image::canvas($width800, $height800);
                $image800 = Image::make($file)->resize($width800, $height800, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img800->insert($image800, 'center');
                $img800->save($resize800);

                $img_path = base_path() . '/uploads/product/source/';
                $img_path200 = base_path() . '/uploads/product/resize200/';
                $img_path800 = base_path() . '/uploads/product/resize800/';

                if ($product->image != null) {
                    if (file_exists(sprintf($img_path . '%s', $product->image)))
                        unlink(sprintf($img_path . '%s', $product->image));
                    if (file_exists(sprintf($img_path200 . '%s', $product->image)))
                        unlink(sprintf($img_path200 . '%s', $product->image));
                    if (file_exists(sprintf($img_path800 . '%s', $product->image)))
                        unlink(sprintf($img_path800 . '%s', $product->image));
                }

                $product->image = $fileName;
                $product->save();

                $productImages = ProductImages::where(['product_id' => $product->id])->get();

//                foreach ($productImages as $productImage) {
//
//                    $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
//                    $path = base_path('uploads/product/source/' . $fileName);
//                    $resize200 = base_path('uploads/product/resize200/' . $fileName);
//                    $resize800 = base_path('uploads/product/resize800/' . $fileName);
//                    //  $file->move($destinationPath, $fileName);
//
//                    $file = base_path('uploads/product/source/' . $productImage->image);
//                    Image::make($file)->save($path);
//
//                    $arrayimage = list($width, $height) = getimagesize($file);
//                    $widthreal = $arrayimage['0'];
//                    $heightreal = $arrayimage['1'];
//
//                    $width200 = ($widthreal / $heightreal) * 200;
//                    $height200 = $width200 / ($widthreal / $heightreal);
//                    $file = base_path('uploads/product/resize200/' . $productImage->image);
//                    $img200 = Image::canvas($width200, $height200);
//                    $image200 = Image::make($file)->resize($width200, $height200, function ($c) {
//                        $c->aspectRatio();
//                        $c->upsize();
//                    });
//                    $img200->insert($image200, 'center');
//                    $img200->save($resize200);
//
//                    $width800 = ($widthreal / $heightreal) * 800;
//                    $height800 = $width800 / ($widthreal / $heightreal);
//                    $file = base_path('uploads/product/resize800/' . $productImage->image);
//                    $img800 = Image::canvas($width800, $height800);
//                    $image800 = Image::make($file)->resize($width800, $height800, function ($c) {
//                        $c->aspectRatio();
//                        $c->upsize();
//                    });
//                    $img800->insert($image800, 'center');
//                    $img800->save($resize800);
//
//                    $img_path = base_path() . '/uploads/product/source/';
//                    $img_path200 = base_path() . '/uploads/product/resize200/';
//                    $img_path800 = base_path() . '/uploads/product/resize800/';
//
//                    if (file_exists(sprintf($img_path . '%s', $productImage->image)))
//                        unlink(sprintf($img_path . '%s', $productImage->image));
//                    if (file_exists(sprintf($img_path200 . '%s', $productImage->image)))
//                        unlink(sprintf($img_path200 . '%s', $productImage->image));
//                    if (file_exists(sprintf($img_path800 . '%s', $productImage->image)))
//                        unlink(sprintf($img_path800 . '%s', $productImage->image));
//
//                    $productImage->image = $fileName;
//                    $productImage->save();
//                }
            } catch (\Exception $ex) {
            }
        }

        print_r($ids);
    }

    public function editBrandImages()
    {

        $brands = Brand::get();
        foreach ($brands as $brand) {
            if ($brand->image !== null) {
                $extension = 'webp'; // getting file extension
                $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                $path = base_path('uploads/brands/source/' . $fileName);
                $resize200 = base_path('uploads/brands/resize200/' . $fileName);
                $resize800 = base_path('uploads/brands/resize800/' . $fileName);

                $file = base_path('uploads/brands/source/' . $brand->image);

                Image::make($file)->save($path);

                $arrayimage = list($width, $height) = getimagesize($file);
                $widthreal = $arrayimage['0'];
                $heightreal = $arrayimage['1'];

                $width200 = 131;
                $height200 = 72;
                $file = base_path('uploads/brands/resize200/' . $brand->image);

                $img200 = Image::canvas($width200, $height200);
                $image200 = Image::make($file)->resize($width200, $height200, function ($c) {

                    $c->upsize();
                });
                $img200->insert($image200, 'center');
                $img200->save($resize200);

                $width800 = 800;
                $height800 = ($heightreal * 800) / $width800;
                $file = base_path('uploads/brands/resize800/' . $brand->image);
                $img800 = Image::canvas($width800, $height800);
                $image800 = Image::make($file)->resize($width800, $height800, function ($c) {

                    $c->upsize();
                });
                $img800->insert($image800, 'center');
                $img800->save($resize800);

                $brand->image = $fileName;
                $brand->save();
            }
        }
    }

    public function index()
    {
        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $aa = $lan;
        \App::setLocale($aa);

//        $rating = DB::table('rates')->groupBy('product_id')->select(DB::raw(DB::raw("SUM( value )/count(*) AS finalRate")), 'product_id')->pluck('finalRate', 'product_id');

        $slider = Slideshow::where('status', 1)->get(['id' , 'image' , 'link']);
        $deals = Product::where('status', 1)
            ->where('stock', '>=', 1)
            ->where('vendor_status', '!=', 0)
            ->where('discount', '>', 0)
            ->orderBy(DB::raw('RAND()'))
            ->take(8)
            ->get(['id' , 'image' , 'title' , 'title_ar' , 'link' , 'link_en'
                , 'code' , 'price' , 'discount']);

        $producthome = Product::where('status', 1)
            ->where('stock', '>=', 1)
            ->where('vendor_status', '!=', 0)
            ->where('featured', 1)
            ->where('discount', '=', 0)
            ->orderBy(DB::raw('RAND()'))
            ->take(12)
            ->get(['id' , 'image' , 'title' , 'title_ar' , 'link' , 'link_en'
                , 'code' , 'price' , 'discount']);
//        $artist = Artist::where('status', 1)->get();
//        $lastproduct = Product::where('status', 1)
//            ->where('stock', '>=', 1)
//            ->where('vendor_status', '!=', 0)
//            ->where('featured', 1)
//            ->orderBy('created_at', 'desc')
//            ->take(10)
//            ->get();

//        $bestorderids = DB::table('orders')->groupBy('product_id')->select('product_id', DB::raw('COUNT(id) as count_id'))->orderBy('count_id', 'DESC')->take(8)->pluck('product_id');
//        $bestorder = Product::where('status', 1)
//            ->where('stock', '>=', 1)
//            ->where('vendor_status', '!=', 0)
//            ->whereIN('id', $bestorderids)->take(10)->get();
//        $blogitem = Blogitem::where('status', 1)->orderBy('created_at', 'desc')->take(5)->get();
//        $sponsor = Sponsor::all();
        $brands = Brand::get(['id' , 'image' , 'name']);

        return view('home', compact( 'con', 'slider', 'lan',
            'deals', 'producthome', 'brands'));
    }

    public function getmore()
    {
        $ids = $_POST['id'];
        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $proids = [];
        $products = Product::where('status', 1)
            ->where('stock', '>=', 1)
            ->where('vendor_status', '!=', 0)
            ->where(['featured' => 0])
            ->where('discount', '=', 0)
            ->whereNotIn('id', $ids)
            ->with('currency')
            ->orderBy(DB::raw('RAND()'))
            ->take(16)->get();
        foreach ($products as $key => $value) {
            array_push($proids, $value->id);
        }
        $proids = array_merge($ids, $proids);
        $countpro = Product::where('status', 1)
            ->where('stock', '>=', 1)
            ->where('vendor_status', '!=', 0)
            ->where(['featured' => 0])
            ->where('discount', '=', 0)
            ->whereNotIn('id', $proids)
            ->orderBy('id', 'desc')
            ->count();
        return response()->json([$products, $lan, $countpro]);
    }

    public function admin()
    {

        $words = DB::table('search')->orderBy('count', 'desc')->orderBy('id', 'desc')->take(5)->get();
        return view('welcomeadmin', compact('words'));
    }

    function convertCurrency($amount, $from, $to)
    {
        $url = 'http://finance.google.com/finance/converter?a=' . $amount . '&from=' . $from . '&to=' . $to;
        $data = file_get_contents($url);
        preg_match_all("/<span class=bld>(.*)<\/span>/", $data, $converted);
        $final = preg_replace("/[^0-9.]/", "", $converted[1][0]);
        return round($final, 3);
    }

    public function changecurrency()
    {

        $code = $_POST['code'];
        $currency = Currency::where('code', $code)->first();
        \Session::set('currencysymbol', $currency->symbol);
        \Session::set('currencycode', $currency->code);
        \Session::set('currencychange', $currency->rate);
        /*if($code != 'EGP'){
              $value = $this->convertCurrency(1, 'EGP', $code);
              \Session::forget('currencycode');
              \Session::forget('currencychange');
              $newcode = \Session::set('currencycode',$code);
              $oldcode = \Session::get('currencycode');
              \Session::set('currencychange',$value);
              $newvalue = \Session::get('currencychange');
        
        }else{
              \Session::forget('currencycode');
              \Session::set('currencychange','1');
        }*/
    }

    public function menu($menu)
    {
        $m = Menu::where('link', $menu)->select('name', 'type', 'value', 'meta_keywords', 'meta_description')->first();
        $langm = \Session::get('lang');
        $deflangm = Settingsite::select('default_lang')->first();


        if ($m->type == 'blogcategory') {
            $cat = Blogcategory::where('id', $m->value)->first();

            if ($langm == null) {
                $item = Blogitem::where('category_id', $cat->id)->where('lang', $deflangm->default_lang)->where('published', 1)->orderBy('created_at', 'desc')->Paginate(5);
                $catan = Blogcategory::where('id', '!=', $cat->id)->where('published', 1)->where('lang', $deflangm->default_lang)->select('name', 'id')->get();
                $links = str_replace('/?', '?', $item->render());
            } else {
                $item = Blogitem::where('category_id', $cat->id)->where('lang', $langm)->where('published', 1)->orderBy('created_at', 'desc')->Paginate(5);
                $catan = Blogcategory::where('id', '!=', $cat->id)->where('published', 1)->where('lang', $langm)->select('name', 'id')->get();
                $links = str_replace('/?', '?', $item->render());
            }

            return view('blogcat', compact('item', 'links', 'catan', 'cat', 'm'));
        }
        if ($m->type == 'blogitem') {
            $item = Blogitem::where('id', $m->value)->get();
            return view('menuselect', compact('item', 'm'));
        }
        if ($m->type == 'about') {

            return view('about', compact('m'));
        }
        if ($m->type == 'certificate') {
            $certificate = Certificate::where('published', 1)->get();
            return view('certificates', compact('certificate', 'm'));
        }
        if ($m->type == 'conferences') {
            return view('conferences', compact('m'));
        }
        if ($m->type == 'gallery') {
            $gallery = Gallery::where('published', 1)->get();
            return view('gallery', compact('gallery', 'm'));
        }

        if ($m->type == 'contactus') {
            $map = Settingsite::first();
            $te = '';
            return view('contactus', compact('map', 'm', 'te'));
        }
        if ($m->type == 'question') {
            if ($langm == null) {
                $question = Question::where('lang', $deflangm->default_lang)->where('published', 1)->orderBy('order', 'asc')->get();
                return view('question', compact('question', 'm'));
            } else {
                $question = Question::where('lang', $langm)->where('published', 1)->orderBy('order', 'asc')->get();
                return view('question', compact('question', 'm'));
            }

            return view('contactus', compact('map', 'm'));
        }


        return view('menuselect');
    }

    public function updatestatus($name, $ids)
    {
        $ids = explode(',', $ids);
        if ($ids[0] == 'on') {
            unset($ids[0]);
        }
        foreach ($ids as $x) {

            if ($name == 'slideshow') {
                $update = Slideshow::findOrFail($x);
            }

            if ($name == 'category') {
                $update = Category::findOrFail($x);
            }
            if ($name == 'currencies') {
                $update = Currency::findOrFail($x);
            }
            if ($name == 'product-option') {
                $update = ProductOption::findOrFail($x);
            }
            if ($name == 'blogcategory') {
                $update = BlogCategory::findOrFail($x);
            }
            if ($name == 'blogitem') {
                $update = BlogItem::findOrFail($x);
            }
            if ($name == 'galleries') {
                $update = Gallery::findOrFail($x);
            }
            if ($name == 'products') {
                $update = Product::findOrFail($x);
            }
            if ($name == 'artist') {
                $update = Artist::findOrFail($x);
            }
            if ($name == 'image-home') {
                $update = ImageHome::findOrFail($x);
            }

            if ($name == 'affilates') {
                $update = Affilate::findOrFail($x);
            }

            if ($name == 'vendors') {
                $update = Vendor::findOrFail($x);
            }

            if ($update->status == 0) {
                $update->status = 1;
                $update->save();
            } else {
                $update->status = 0;
                $update->save();
            }
        }

    }

    public function searchword()
    {
        $words = DB::table('search')->get();
        return view('website.search', compact('words'));
    }

    public function newsletter()
    {
        $newsletter = DB::table('newsletter')->get();
        return view('website.newsletter', compact('newsletter'));
    }

    public function deletenewsletter()
    {
        $ids = $_POST['id'];

        if ($ids[0] == 'on') {
            unset($ids[0]);
        }
        foreach ($ids as $id) {

            DB::table('newsletter')->where('id', $id)->delete();

        }
    }

    public function newnewsletter()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $news = new Newsletter();
        $news->name = $name;
        $news->email = $email;
        $news->phone = $phone;
        $news->save();
        $message_emails = EmailMessage::first();
        $data = array(
            'news' => $news,
            'message_emails' => $message_emails,
        );
        $e = Configurationsite::select('email_sales')->first();
        $emailsales = $e->email_sales;
        $email = $news->email;
        Mail::send('auth.emails.newnewsletteradmin', $data, function ($message) use ($emailsales, $message_emails) {

            $message->to($emailsales)->subject("$message_emails->adminnewsletter");
        });
        Mail::send('auth.emails.newnewsletter', $data, function ($message) use ($email, $message_emails) {

            $message->to($email)->subject("$message_emails->clientnewsletter");
        });
        $msg = 'تم أرسال طلبك بنجاح';
        return response()->json($msg);
    }

    public function newadsregister()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $id = $_POST['id'];

        $news = new AdRegister();
        $news->name = $name;
        $news->ads_id = $id;
        $news->email = $email;
        $news->phone = $phone;
        $news->date = date('Y-m-d');
        $news->save();

        $message_emails = EmailMessage::first();
        $data = array(
            'news' => $news,
            'message_emails' => $message_emails,
        );
        $e = Configurationsite::select('email_sales', 'email_ads')->first();
        $emailsales = $e->email_sales;
        $emailads = $e->email_ads;
        $email = $news->email;
        Mail::send('auth.emails.newadsadmin', $data, function ($message) use ($emailsales, $message_emails) {

            $message->to($emailsales)->subject("$message_emails->adminads");
        });
        Mail::send('auth.emails.newadsadmin', $data, function ($message) use ($emailads, $message_emails) {

            $message->to($emailads)->subject("$message_emails->adminads");
        });
        Mail::send('auth.emails.newads', $data, function ($message) use ($email, $message_emails) {

            $message->to($email)->subject("$message_emails->clientads");
        });
        $msg = 'تم التسجيل بنجاح';
        return response()->json($msg);
    }

    public function search(Request $request)
    {

        $searchtext = $request->searchtext;
        $con = Configurationsite::select('imageall')->first();
        $char = [
            'ة', 'أ', 'إ',
            'ي', 'ؤ', 'ء'
        ];
        $char2 = [
            'ه', 'ا', 'ا',
            'ى', 'و', 'ئ'
        ];
        $a = array();
        $new = '';

        if ($searchtext != '') {
            foreach ($char as $k => $c) {
                $check = strpos($searchtext, $c);
                if ($k == 0) {
                    $new = str_replace($c, $char2[$k], $searchtext);
                } else {
                    $new = str_replace($c, $char2[$k], $new);
                }
                if ($check) {
                    array_push($a, $searchtext, str_replace($c, $char2[$k], $searchtext));
                } else {
                    array_push($a, $searchtext);
                    array_push($a, $searchtext, str_replace($char2[$k], $c, $searchtext));
                }
            }
            array_push($a, $new);


            $searchdb = strtolower($searchtext);
            $test = Search::where('name', $searchtext)->first();

            if ($test != null) {
                $add = Search::find($test->id);
                $add->count = ($test->count) + 1;
                $add->save();
            } else {
                $add = new Search();
                $add->name = $searchdb;
                $add->count = 1;
                $add->save();
            }
        }


        $aa = implode(" ", $a);
        $aa = explode(" ", $aa);


        foreach ($aa as $k => $v) {

            if (($kt = array_search($v, $aa)) !== false and $k != $kt) {
                unset($aa[$kt]);
            }

        }


        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        \Session::set('lang', $lan);
        $aaa = \Session::get('lang');
        \App::setLocale($aaa);

        if (!empty($searchtext)) {

            $brand = Brand::where('name', 'LIKE', '%' . $searchtext . '%')->first();
            if ($brand) {
                $products1 = Product::where('brand_id', $brand->id)
                    ->where('status', 1)
                    ->where('stock', '>=', 1)
                    ->where('vendor_status', '!=', 0)
                    ->get();
            } else {
                $products1 = $this->filterProduct($searchtext);
            }

            if ($searchtext == 'address' || $searchtext == 'العنوان') {
                return view('contact');
            } else {
                $rating = DB::table('rates')->groupBy('product_id')->select(DB::raw(DB::raw("SUM( value )/count(*) AS finalRate")), 'product_id')->pluck('finalRate', 'product_id');
                $lang = \Session::get('lang');
                $con = Configurationsite::first();
                if ($lang == null) {
                    $lan = $con->default_lang;
                } else {
                    $lan = $lang;
                }
                $aa = $lan;
                return view('website.searchresult', compact('products1', 'lan', 'rating', 'searchtext', 'con'));
            }


        } else {
            return view('website.searchresult', compact('searchtext', 'con'));
        }

    }

    public function filterProduct($searchtext)
    {
        $char = [
            'ة', 'أ', 'إ',
            'ي', 'ؤ', 'ء',
            'ال'
        ];
        $char2 = [
            'ه', 'ا', 'ا',
            'ى', 'و', 'ئ',
            ''
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

        $searchtext14 = str_replace($char[6], $char2[6], $searchtext);

        $products1 = Product::where(function ($query) use (
            $searchtext, $searchtext2, $searchtext3
            , $searchtext4, $searchtext5, $searchtext6, $searchtext7
            , $searchtext8, $searchtext9, $searchtext10, $searchtext11
            , $searchtext12, $searchtext13, $searchtext14
        ) {
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

            $query->orWhere('title', 'LIKE', '%' . $searchtext14 . '%');
            $query->orWhere('title_ar', 'LIKE', '%' . $searchtext14 . '%');
            $query->orWhere('short_description', 'LIKE', '%' . $searchtext14 . '%');
            $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext14 . '%');
            $query->orWhere('meta_key', 'LIKE', '%' . $searchtext14 . '%');
        })
            ->where('status', 1)
            ->where('stock', '>=', 1)
            ->where('vendor_status', '!=', 0)
            ->get();
        return $products1;
    }

    public function homeautocomplete2(Request $request)
    {
        $search = $request->term;
        $locale = 'en';

        if ($this->is_arabic($search)) {
            $locale = 'ar';
        }

        $categories = Category::where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('title_ar', 'LIKE', '%' . $search . '%')
            ->orWhere('meta_keywords', 'LIKE', '%' . $search . '%')
            ->orWhere('meta_description', 'LIKE', '%' . $search . '%')
            ->select(['title' . (($locale === 'ar') ? '_ar' : '') . ' AS title', 'link'])
            ->get();

        $brands = Brand::where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('meta_keywords', 'LIKE', '%' . $search . '%')
            ->orWhere('meta_description', 'LIKE', '%' . $search . '%')
            ->get()
            ->each(function ($brand) {
                $brand->title = $brand->name;
                $brand->link = 'brand/products/' . $brand->id . '/' . $brand->name;
            });

        if (count($categories) > 0) {
            $test = Search::where('name', $search)->first();
            if ($test) {
                $test->count = $test->count + 1;
                $test->save();
            } else {
                $searchObj = new Search();
                $searchObj->name = $search;
                $searchObj->count = 1;
                $searchObj->type = 'category';
                $searchObj->save();
            }
            return response()->json([
                'data' => [
                    'sources' => $categories
                ]
            ], 200, ['Content-type' => 'application/json;charset=utf-8'], JSON_UNESCAPED_UNICODE);
        } else if (count($brands) > 0) {
            $test = Search::where('name', $search)->first();
            if ($test) {
                $test->count = $test->count + 1;
                $test->save();
            } else {
                $searchObj = new Search();
                $searchObj->name = $search;
                $searchObj->count = 1;
                $searchObj->type = 'brand';
                $searchObj->save();
            }
            return response()->json([
                'data' => [
                    'sources' => $brands
                ]
            ], 200, ['Content-type' => 'application/json;charset=utf-8'], JSON_UNESCAPED_UNICODE);
        } else {
            $searchtext = $search;
            $char = [
                'ة', 'أ', 'إ',
                'ي', 'ؤ', 'ء',
                'ال'
            ];
            $char2 = [
                'ه', 'ا', 'ا',
                'ى', 'و', 'ئ',
                ''
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

            $searchtext14 = str_replace($char[6], $char2[6], $searchtext);

            $products = Product::where(function ($query) use (
                $searchtext, $searchtext2, $searchtext3
                , $searchtext4, $searchtext5, $searchtext6, $searchtext7
                , $searchtext8, $searchtext9, $searchtext10, $searchtext11
                , $searchtext12, $searchtext13, $searchtext14
            ) {
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

                $query->orWhere('title', 'LIKE', '%' . $searchtext14 . '%');
                $query->orWhere('title_ar', 'LIKE', '%' . $searchtext14 . '%');
                $query->orWhere('short_description', 'LIKE', '%' . $searchtext14 . '%');
                $query->orWhere('short_desc_en', 'LIKE', '%' . $searchtext14 . '%');
                $query->orWhere('meta_key', 'LIKE', '%' . $searchtext14 . '%');
            })
                ->where('status', 1)
                ->where('stock', '>=', 1)
                ->where('vendor_status', '!=', 0)
                ->pluck('id');
            $categoryProductids = \Illuminate\Support\Facades\DB::table('category_products')
                ->whereIn('product_id', $products)
                ->pluck('category_id');
            $categories = Category::whereIn('id', $categoryProductids)
                ->select(['title' . (($locale === 'ar') ? '_ar' : '') . ' AS title', 'link'])
                ->get();

            if ($locale === 'ar') {
                $search .= ' ' . 'فى قسم';
            } else {
                $search .= ' ' . 'at category';
            }
            if (count($categories) > 0) {
                return response()->json([
                    'data' => [
                        'sources' => $categories,
                        'search' => $search
                    ]
                ], 200, ['Content-type' => 'application/json;charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
            $test = Search::where('name', 'LIKE', '%' . $searchtext . '%')
                ->orderBy('count', 'DESC')
                ->limit(6)
                ->get();

            return response()->json([
                'data' => [
                    'sources' => $test,
                ]
            ], 200, ['Content-type' => 'application/json;charset=utf-8'], JSON_UNESCAPED_UNICODE);

        }
    }

    function uniord($u)
    {
        // i just copied this function fron the php.net comments, but it should work fine!
        $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }

    function is_arabic($str)
    {
        if (mb_detect_encoding($str) !== 'UTF-8') {
            $str = mb_convert_encoding($str, mb_detect_encoding($str), 'UTF-8');
        }

        /*
        $str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
        $str = preg_split('//u',$str); <- this function woulrd probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
        */
        preg_match_all('/.|\n/u', $str, $matches);
        $chars = $matches[0];
        $arabic_count = 0;
        $latin_count = 0;
        $total_count = 0;
        foreach ($chars as $char) {
            //$pos = ord($char); we cant use that, its not binary safe
            $pos = $this->uniord($char);
//            echo $char ." --> ".$pos.PHP_EOL;

            if ($pos >= 1536 && $pos <= 1791) {
                $arabic_count++;
            } else if ($pos > 123 && $pos < 123) {
                $latin_count++;
            }
            $total_count++;
        }
        if (($arabic_count / $total_count) > 0.6) {
            // 60% arabic chars, its probably arabic
            return true;
        }
        return false;
    }

    public function homeautocomplete(Request $request)
    {
        $term = Str::lower($request->phrase);
        $results = array();

        if ($term != '') {
            $queries = DB::table("course")->select('title')
                ->where('title', 'LIKE', '%' . $term . '%')->groupBy('title')->take(15)->get();
            //$queries2 = DB::table("products")->select('product_code','title')->where('product_code', 'LIKE', '%'.$term.'%')->groupBy('title')->take(15)->get();

            foreach ($queries as $query) {
                $results[] = ['value' => $query->title];
            }
            /*
            foreach ($queries2 as $query)
            {
                $results[] = ['value' => $query->title ];
            }
            */
            return Response::json($results);

        } else {

            $queries = DB::table("course")->select('title')->groupBy('title')->take(15)->get();

            foreach ($queries as $query) {
                $results[] = ['value' => $query->title];
            }
            return Response::json($results);
        }


    }

    public function newsearch()
    {
        $title = $_POST['title'];
        $ids = $_POST['ids'];
        $ids = explode(',', $ids);
        $queriesids = DB::table('course')->whereIN('id', $ids)->where('title', 'LIKE', '%' . $title . '%')->select('id')->pluck('id');


        foreach ($ids as $value) {
            if (in_array($value, $queriesids)) {
                unset($ids[$value]);
            }
        }

        return Response::json([$ids, $queriesids]);
    }

    public function showcourse($link)
    {

        $course = Course::where('link', $link)->where('status', 1)->first();
        if ($course) {
            $courseinstructor = CourseInstructor::where('course_id', $course->id)->get();
            $curdate = strtotime(date('Y-m-d'));
            $mydate = strtotime("$course->date");
            $category = Category::where('status', 1)->get();
            $coursecategory = CourseCategory::where('course_id', $course->id)->first();
            if ($curdate < $mydate) {
                $statusDate = 'لم يبدأ';
            } else {
                $start = Carbon::parse($course->date);
                $now = Carbon::now();

                $length = $start->diffInDays($now);

                $statusDate = "بدأ خلال $length يوم";
            }
            $con = Configurationsite::select('imageall')->first();
            $productimages = ProductImages::where('product_id', $course->id)->get();
            return view('website.showcourse', compact('productimages', 'coursecategory', 'category', 'course', 'courseinstructor', 'statusDate', 'con'));
        } else {
            return view('errors.404');
        }
    }

    public function showcategory($link)
    {

        $category = Category::where('link', $link)->where('status', 1)->first();
        if ($category) {
            $coursecategory = DB::table('category_courses')->where('category_id', $category->id)->pluck('course_id');
            $course = Course::whereIn('id', $coursecategory)->where('status', 1)->get();
            $cats = Category::where('id', '!=', $category->id)->where('status', 1)->get();

            $coursecategory = implode(",", $coursecategory);

            $con = Configurationsite::select('imageall')->first();
            return view('website.showcategory', compact('category', 'course', 'cats', 'coursecategory', 'con'));
        } else {
            return view('errors.404');
        }
    }

    public function showinstructor($link)
    {


        $instructor = Instructor::where('link', $link)->first();
        if ($instructor) {
            $courseinstructor = CourseInstructor::where('instructor_id', $instructor->id)->get();
            $con = Configurationsite::select('imageall')->first();
            return view('website.showinstructor', compact('instructor', 'courseinstructor', 'con'));
        } else {
            return view('errors.404');
        }
    }

    public function instructors()
    {
        $instructors = Instructor::all();
        $con = Configurationsite::select('imageall')->first();

        return view('website.showinstructors', compact('instructors', 'con'));
    }

    public function joinus()
    {

        $con = Configurationsite::select('imageall')->first();
        return view('website.joinus', compact('con'));
    }

    public function postjoinus(Request $request)
    {
        $join = new Join();
        $join->name = $request->name;
        $join->phone = $request->number;
        $join->email = $request->email;
        $join->major = $request->major;
        $join->message = $request->message;
        $fileName = '';
        if ($request->hasFile("attach")) {
            $fileName = rand(11111, 99999) . '.' . $request->file('attach')->getClientOriginalExtension(); // renameing image
            $request->file('attach')->move(
                base_path() . '/uploads/join/', $fileName
            );
            $join->attach = $fileName;

        }
        $join->save();
        $path = base_path("/uploads/join/$fileName");
        $message_emails = EmailMessage::first();
        $data = array(
            'join' => $join,
            'message_emails' => $message_emails,
        );
        $e = Configurationsite::select('email_sales')->first();
        $emailsales = $e->email_sales;
        $email = $join->email;
        Mail::send('auth.emails.joinadmin', $data, function ($message) use ($emailsales, $message_emails, $request, $path) {

            $message->to($emailsales)->subject("$message_emails->adminjoin");
            if ($request->hasFile("attach")) {
                $message->attach($path);
            }
        });
        Mail::send('auth.emails.join', $data, function ($message) use ($email, $message_emails) {

            $message->to($email)->subject("$message_emails->clientjoin");
        });

        $msg = 'تم ارسال طلبك بنجاح';
        \Session::set('join-us', $msg);
        return redirect()->back();


        /*
        
        if ($request->hasFile("attach")) {

            $file = $request->file("attach");
            $mime = File::mimeType($file);
            $mimearr = explode('/', $mime);

           // $destinationPath = base_path() . '/uploads/'; // upload path   
            $extension = $mimearr[1]; // getting file extension
            
            $path = base_path('uploads/join/source/' . $fileName);
            $resize200 = base_path('uploads/join/resize200/' . $fileName);
            $resize800 = base_path('uploads/join/resize800/' . $fileName);
              //  $file->move($destinationPath, $fileName);

            Image::make($file->getRealPath())->save($path);

            $arrayimage = list($width, $height) = getimagesize($file->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 150;
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

            $join->attach = $fileName;
        }
        */
    }

    public function gettypevalue()
    {
        $type = $_POST['type'];
        if ($type == 'blog-category') {
            $catblog = Blogcategory::where('status', 1)->pluck('title', 'id');
            return Response::json($catblog);
        }
        if ($type == 'blog-item') {
            $itemblog = Blogitem::where('status', 1)->pluck('title', 'id');
            return Response::json($itemblog);
        }
        if ($type == 'course_category') {
            $category = Category::where('status', 1)->pluck('title', 'id');
            return Response::json($category);
        }
        if ($type == 'course_item') {
            $course = Course::where('status', 1)->pluck('title', 'id');
            return Response::json($course);
        }

    }

    public function getparentmenu()
    {
        $menu = $_POST['menu'];


        $menuitem = DB::table('menu_items')->orderBy('order', 'asc')->where('menu_id', $menu)->where('status', 1)->pluck('name', 'id');
        return Response::json($menuitem);


    }

    public function checkcertificate()
    {
        $con = Configurationsite::select('imageall')->first();

        return view('website.checkcertificate', compact('con'));
    }

    public function postcheckcertificate()
    {
        $number = $_POST['number'];
        $check = Certificate::where('Serial', $number)->first();
        if ($check) {
            $course = $check->Course->title;
        } else {
            $course = '';
        }
        return Response::json([$check, $course]);
    }

    public function contactus()
    {
        $con = Configurationsite::first();

        return view('website.contactus', compact('con'));
    }

    public function postcontactus()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $add = new Contactus();
        $add->name = $name;
        $add->email = $email;
        $add->message = $message;
        $add->save();
        /*
                            $message_emails = EmailMessage::first();
                                $data = array(
                                'add' => $add,
                                'message_emails' => $message_emails,
                            );
                            $e = Configurationsite::select('email_sales')->first();
                            $emailsales = $e->email_sales;
                            $email = $add->email;
                            Mail::send('auth.emails.contactusadmin', $data, function ($message) use ($emailsales,$message_emails) {

                             $message->to($emailsales)->subject("$message_emails->admincontactus");
                            });
                            Mail::send('auth.emails.contactus', $data, function ($message) use ($email,$message_emails){

                             $message->to($email)->subject("$message_emails->clientcontactus");
                             });
                             */

        $msg = 'تم أرسال رسالتك';
        return Response::json($msg);
    }

    public function specificcourse()
    {
        $con = Configurationsite::first();

        return view('website.specificcourse', compact('con'));
    }

    public function postspecificcourse(Request $request)
    {

        $add = new CompanyRegister();
        $add->name = $request->company_full_name;
        $add->company = $request->company_name;
        $add->email = $request->company_email;
        $add->country = $request->company_country;
        $add->phone = $request->company_phone;
        $add->message = $request->company_message;
        $add->save();
        $message_emails = EmailMessage::first();
        $data = array(
            'add' => $add,
            'message_emails' => $message_emails,
        );
        $e = Configurationsite::select('email_sales')->first();
        $emailsales = $e->email_sales;
        $email = $add->email;
        Mail::send('auth.emails.companyadmin', $data, function ($message) use ($emailsales, $message_emails) {

            $message->to($emailsales)->subject("$message_emails->admincompany");
        });
        Mail::send('auth.emails.company', $data, function ($message) use ($email, $message_emails) {

            $message->to($email)->subject("$message_emails->clientcompany");
        });
        $msg = 'تم أرسال طلبك بنجاح';
        \Session::set('company_register', $msg);
        return Redirect()->back();
    }

    public function registration()
    {
        $category = DB::table('category')->where('status', 1)->lists('title', 'id');
        $con = Configurationsite::first();
        return view('website.registration', compact('category', 'con'));
    }

    public function changecategory()
    {
        $cat = $_POST['cat'];
        $coursecategory = DB::table('category_courses')->where('category_id', $cat)->pluck('course_id');
        $courses = DB::table('course')->where('status', 1)->whereIn('id', $coursecategory)->lists('title', 'id');
        return Response::json($courses);
    }

    public function myaddress()
    {
        $address = Address::where('user_id', Auth::user()->id)->get();
        $links = session()->has('links') ? session('links') : [];
        $currentLink = request()->path(); // Getting current URI like 'category/books/'
        array_unshift($links, $currentLink); // Putting it in the beginning of links array
        session(['links' => $links]); // Saving links array to the session
        return view('website.myaddress', compact('address'));

    }

    public function addaddress()
    {
        $countries = Country::all();
        $regions = Region::all();
        $areas = Area::all();
        $links = session()->has('links') ? session('links') : [];
        $currentLink = request()->path(); // Getting current URI like 'category/books/'
        array_unshift($links, $currentLink); // Putting it in the beginning of links array
        session(['links' => $links]); // Saving links array to the session
        return view('website.addaddress', compact('countries', 'regions', 'areas'));
    }

    public function createaddress(Request $request)
    {

        $add = new Address();
        $add->user_id = Auth::user()->id;
        $add->name = $request->add_name;
        $add->address = $request->add_address;
        $add->phone = $request->add_phone;
        $add->phone2 = $request->another_phone;
        $add->lat = $request->add_lat;
        $add->long = $request->add_long;
        $add->country_id = $request->country;
        $add->region_id = $request->region;
        $add->area_id = $request->area;
        $add->save();
        $links = session()->has('links') ? session('links') : [];
        $currentLink = request()->path(); // Getting current URI like 'category/books/'
        array_unshift($links, $currentLink); // Putting it in the beginning of links array
        session(['links' => $links]); // Saving links array to the session
        return redirect(session('links')[2]);
    }

    public function editaddress($id)
    {
        $address = Address::where('id', $id)->first();
        $countries = Country::all();
        $regions = Region::all();
        $areas = Area::all();
        return view('website.editaddress', compact('address', 'countries', 'regions', 'areas'));
    }

    public function posteditaddress(Request $request, $id)
    {

        $add = Address::find($id);
        $add->user_id = Auth::user()->id;
        $add->name = $request->add_name;
        $add->address = $request->add_address;
        $add->phone = $request->add_phone;
        $add->phone2 = $request->another_phone;
        $add->lat = $request->add_lat;
        $add->long = $request->add_long;
        $add->country_id = $request->country;
        $add->region_id = $request->region;
        $add->area_id = $request->area;
        $add->save();
        return Redirect('my-address');
    }

    public function deleteaddress()
    {
        $id = $_POST['id'];
        Address::where('id', $id)->delete();
    }

    public function postregistration(Request $request)
    {
        $course = Course::find($request->course);
        return redirect("$course->link");
    }

    public function postcourseregister(Request $request)
    {

        $add = new CourseRegister();
        $add->course_id = $request->course_id;
        $add->name_ar = $request->name_ar;
        $add->name_en = $request->name_en;
        $add->email = $request->email;
        $add->country = $request->country;
        $add->phone = $request->phone;
        $add->job = $request->job;
        $add->message = $request->message;
        $add->save();

        $message_emails = EmailMessage::first();
        $data = array(
            'add' => $add,
            'message_emails' => $message_emails,
        );
        $e = Configurationsite::select('email_sales')->first();
        $emailsales = $e->email_sales;
        $emailads = $e->email_ads;
        $email = $add->email;
        Mail::send('auth.emails.courseadmin', $data, function ($message) use ($emailsales, $message_emails) {

            $message->to($emailsales)->subject("$message_emails->admincourse");
        });
        Mail::send('auth.emails.courseadmin', $data, function ($message) use ($emailads, $message_emails) {

            $message->to($emailads)->subject("$message_emails->admincourse");
        });
        Mail::send('auth.emails.course', $data, function ($message) use ($email, $message_emails) {

            $message->to($email)->subject("$message_emails->clientcourse");
        });


        $con = Configurationsite::first();
        $payment = Payment::where('status', 1)->get();
        return view('website.registerpage2', compact('add', 'con', 'payment'));
    }

    public function finishregisteranthor()
    {
        $payment_id = $_POST['id'];
        $reg_id = $_POST['regid'];

        $add = CourseRegister::find($reg_id);
        $add->payment_id = $payment_id;
        $add->save();
        $pay = Payment::where('id', $payment_id)->first();
        $con = Configurationsite::first();
        $name = $pay->name;
        $details = $pay->details;
        return Response::json([$name, $details]);


    }

    public function courses()
    {

        $courses = Course::where('status', 1)->get();
        $ids = DB::table('course')->where('status', 1)->pluck('id');
        $ids = implode(',', $ids);
        $cats = Category::where('status', 1)->get();
        return view('website.courses', compact('cats', 'courses', 'ids'));

    }

    public function showblogitem($value)
    {

        $item = BlogItem::where('id', $value)->where('status', 1)->first();
        return redirect("$item->link");

    }

    public function showblogcategory($value)
    {

        $item = BlogCategory::where('id', $value)->where('status', 1)->first();
        return redirect("$item->link");

    }

    public function showcoursecategory($value)
    {

        $category = Category::where('id', $value)->where('status', 1)->first();
        return redirect("$category->link");

    }

    public function gallery()
    {

        $m = MenuItem::where('type', 'gallery')->select('meta_keywords', 'meta_description')->first();
        $gallery = Gallery::where('status', 1)->get();
        return view('website.gallery', compact('gallery', 'm'));

    }

    public function showcourseitem($value)
    {

        $course = Course::where('id', $value)->where('status', 1)->first();
        return redirect("$course->link");

    }

    public function deleteoneimage($id)
    {

        $p = ProductImages::findOrFail($id);
        $img_path = base_path() . '/uploads/product/source/';
        $img_path200 = base_path() . '/uploads/product/resize200/';
        $img_path800 = base_path() . '/uploads/product/resize800/';

        unlink(sprintf($img_path . '%s', $p->image));
        unlink(sprintf($img_path200 . '%s', $p->image));
        unlink(sprintf($img_path800 . '%s', $p->image));

        $p->delete();
    }

    public function getoptionproduct()
    {
        $productid = $_POST['idProduct'];
        $optionid = $_POST['idOption'];
        $product = Product::find($productid);
        if ($product->discount) {
            $price[] = $product->price - $product->discount;
        } else {
            $price[] = $product->price;
        }

        $optionprice = DB::table('product_option')->whereIN('id', $optionid)->where('status', 1)->pluck('price');
        $allprice = (array_sum(array_merge($price, $optionprice))) * \Session::get('currencychange');

        return Response::json($allprice);
    }

    public function postreviewproduct()
    {
        $productid = $_POST['idProduct'];
        $namereview = $_POST['namereview'];
        $textreview = $_POST['textreview'];
//        if (isset($_POST['rating'])) {
//
//            $rating = $_POST['rating'];
//            $rate = new ProductRate();
//            $rate->product_id = $productid;
//            $rate->user_id = Auth::user()->id;
//            $rate->value = $rating;
//            $rate->save();
//        }


        $review = new ProductReview();
        $review->product_id = $productid;
        $review->user_id = Auth::user()->id;
        $review->text = $textreview;
        $review->value = (isset($_POST['rating'])) ? $_POST['rating'] : 0;
        $review->save();


        $msg = 'ok';
        //$optionprice = DB::table('product_option')->whereIN('id',$optionid)->where('status',1)->pluck('price');
        //$allprice = array_sum(array_merge($price,$optionprice));

        return Response::json($msg);
    }

    public function myfavorite()
    {
        if (Auth::check()) {
            $lang = \Session::get('lang');
            $con = Configurationsite::first();
            if ($lang == null) {
                $lan = $con->default_lang;
            } else {
                $lan = $lang;
            }
            $aa = $lan;
            \App::setLocale($aa);
            $favorite = Favorite::where('user_id', Auth::user()->id)->get();
            return view('website.myfavorite', compact('favorite', 'lan'));
        } else {
            $links = session()->has('links') ? session('links') : [];
            $currentLink = request()->path(); // Getting current URI like 'category/books/'
            array_unshift($links, $currentLink); // Putting it in the beginning of links array
            session(['links' => $links]); // Saving links array to the session
            return view('auth.login');
        }

    }

    public function addfavorite()
    {
        $id = $_POST['id'];
        $favorite = Favorite::where('product_id', $id)->where('user_id', Auth::user()->id)->first();
        if (count($favorite) == 0) {
            $favorite = new Favorite();
            $favorite->product_id = $id;
            $favorite->user_id = Auth::user()->id;
            $favorite->save();
        }

    }

    public function removefavorite()
    {
        $id = $_POST['id'];
        Favorite::where('product_id', $id)->delete();
    }

    public function addcart()
    {

        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $aa = $lan;
        \App::setLocale($aa);


        \Session::forget('countpro');

        $id = $_POST['id'];
        $count = $_POST['count'];


        if (Auth::check()) {
            if ($count == 1) {
                $cart = Cart::where(['user_id' => Auth::user()->id, 'product_id' => $id])->first();
                $product = Product::where(['id' => $id])->first();
                if ($cart && $product->stock >= $cart->count + $count) {
                    $cart->count = $cart->count + $count;
                    $cart->save();
                } else if ($product->stock >= 1 && !$cart) {
                    $cart = new Cart();
                    $cart->product_id = $id;
                    $cart->user_id = Auth::user()->id;
                    $cart->count = 1;
                    $cart->save();
                }

                if (isset($_POST['checked'])) {
                    $checked = $_POST['checked'];

                    foreach ($checked as $key => $value) {
                        $optioncart = new OptionCart();
                        $optioncart->cart_id = $cart->id;
                        $optioncart->option_id = $value;
                        $optioncart->save();
                        $op = ProductOption::where('id', $value)->first();
                        $priceoption = Cart::find($cart->id);
                        $priceoption->optionprice += $op->price;
                        $priceoption->save();
                    }

                } else {
                    $checked = [];
                }
                if (isset($_POST['radio'])) {
                    $radio = $_POST['radio'];
                    foreach ($radio as $key => $value) {
                        $optioncart = new OptionCart();
                        $optioncart->cart_id = $cart->id;
                        $optioncart->option_id = $value;
                        $optioncart->save();
                        $op = ProductOption::where('id', $value)->first();
                        $priceoption = Cart::find($cart->id);
                        $priceoption->optionprice += $op->price;
                        $priceoption->save();
                    }
                } else {
                    $radio = [];
                }
            } else {
                $cart = Cart::where(['user_id' => Auth::user()->id, 'product_id' => $id])->first();
                $product = Product::where(['id' => $id])->first();
                if ($cart && $product->stock >= $cart->count + $count) {
                    $cart->count = $cart->count + $count;
                    $cart->save();
                } else if ($product->stock >= 1 && !$cart) {
                    $cart = new Cart();
                    $cart->product_id = $id;
                    $cart->user_id = Auth::user()->id;
                    $cart->count = $count;
                    $cart->save();
                }
                if (isset($_POST['checked'])) {
                    $checked = $_POST['checked'];

                    foreach ($checked as $key => $value) {
                        $optioncart = new OptionCart();
                        $optioncart->cart_id = $cart->id;
                        $optioncart->option_id = $value;
                        $optioncart->save();
                        $op = ProductOption::where('id', $value)->first();
                        $priceoption = Cart::find($cart->id);
                        $priceoption->optionprice += $op->price;
                        $priceoption->save();
                    }

                } else {
                    $checked = [];
                }
                if (isset($_POST['radio'])) {
                    $radio = $_POST['radio'];
                    foreach ($radio as $key => $value) {
                        $optioncart = new OptionCart();
                        $optioncart->cart_id = $cart->id;
                        $optioncart->option_id = $value;
                        $optioncart->save();
                        $op = ProductOption::where('id', $value)->first();
                        $priceoption = Cart::find($cart->id);
                        $priceoption->optionprice += $op->price;
                        $priceoption->save();
                    }
                } else {
                    $radio = [];
                }
            }
            $cart = Cart::where('user_id', Auth::user()->id)->get();
            $html = view('website.reloadcart', ['cart' => $cart, 'lan' => $lan])->render();
        } else {


            $priceoption = [];
            if (isset($_POST['checked'])) {
                $checked = $_POST['checked'];
                $po = ProductOption::whereIN('id', $checked)->get();
                foreach ($po as $key => $value) {
                    array_push($priceoption, $value->price);
                }
                $checked = implode(",", $checked);
            } else {
                array_push($priceoption, 0);
                $checked = '';
            }

            if (isset($_POST['radio'])) {
                $radio = $_POST['radio'];

                $po = ProductOption::whereIN('id', $radio)->get();
                foreach ($po as $key => $value) {
                    array_push($priceoption, $value->price);
                }
                $radio = implode(",", $radio);
            } else {
                array_push($priceoption, 0);
                $radio = '';
            }
            $price = array_sum($priceoption);
            if (session()->has('cartsessionnumber')) {

                $number = session()->get('cartsessionnumber');
            } else {

                $number = session()->set('cartsessionnumber', rand());
                $number = session()->get('cartsessionnumber');

            }

            $cart_product = CartSession::where(['session_number' => $number, 'product_id' => $id])->first();
            $product = Product::where(['id' => $id])->first();
            if ($cart_product && $product->stock >= $cart_product->count + $count) {
//                Product::
                $cart_product->count = intval($cart_product->count) + intval($count);
                $cart_product->save();
            } else if ($product->stock >= $count && !$cart_product) {
                $addsession = new CartSession();
                $addsession->product_id = $id;
                $addsession->session_number = $number;
                $addsession->count = $count;
                $addsession->optionprice = $price;
                $addsession->optionradio = $radio;
                $addsession->optioncheck = $checked;
                $addsession->save();
            }
            $cart = CartSession::where('session_number', $number)->get();
            $html = view('website.reloadcart', ['cart' => $cart, 'lan' => $lan])->render();
        }

        return $html;
        // return Response::json([count($cart),$cart]);
    }

    public function changeaccount()
    {

        $account = User::where('id', Auth::user()->id)->first();
        return view('website.changeaccount', compact('account'));
    }

    public function postchangeaccount(Request $request, $id)
    {

        $add = User::findOrFail($id);
        $add->name = $request->acc_name;
        if ($request->acc_email !== $add->email) {
            $email = User::where(['email' => $request->acc_email])->first();
            if (!$email) {
                $add->email = $request->acc_email;
            }
        }
        if ($request->acc_password != '') {
            $add->password = bcrypt($request->acc_password);
        }
        $add->phone = $request->acc_phone;
        $add->address = $request->acc_address;
        $add->save();
        return redirect()->back();
    }

    public function myorders($status)
    {

        $optionproduct = ProductOption::where('status', 1)->get();
        $order = DB::table('orders')
            ->join('order_status', 'orders.status_id', '=', 'order_status.id')
            ->join('address', 'orders.address_id', '=', 'address.id')
            ->whereIn('order_status.id', ($status > 0) ? [3, 5, 6] : [1, 2, 4])
            ->where('orders.user_id', '=', auth()->user()->id)
            ->orderBy('created_at', 'desc')->groupBy('number')
            ->select('address.address', 'orders.*', 'order_status.name', DB::raw("SUM(total_price) as total,SUM(payment_price) as payment, SUM(shipping_price) as shipping"))
            ->get();
        foreach ($order as $oo) {
            $orders[$oo->number] = Order::where('user_id', '=', Auth::user()->id)->where('number', $oo->number)->orderBy('created_at', 'desc')->get();
        }
        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $aa = $lan;
        \App::setLocale($aa);

        return view('website.orders', compact('order', 'lan', 'orders', 'optionproduct', 'status'));
    }

    public function mydeliveries()
    {
        $delivery = Delivery::where('user_id', Auth::user()->id)->first();
        $optionproduct = ProductOption::where('status', 1)->get();
        $order = DB::table('orders')->join('order_status', 'orders.status_id', '=', 'order_status.id')->join('address', 'orders.address_id', '=', 'address.id')->where('orders.delivery_id', '=', $delivery->id)->orderBy('created_at', 'desc')->groupBy('number')->select('address.address', 'orders.*', 'order_status.name', DB::raw("SUM(total_price) as total"))->get();
        foreach ($order as $oo) {
            $orders[$oo->number] = Order::where('delivery_id', $delivery->id)->where('number', $oo->number)->orderBy('created_at', 'desc')->get();
        }
        $lang = \Session::get('lang');
        $con = Configurationsite::first();
        if ($lang == null) {
            $lan = $con->default_lang;
        } else {
            $lan = $lang;
        }
        $aa = $lan;
        \App::setLocale($aa);
        return view('website.mydeliveries', compact('order', 'lan', 'orders', 'optionproduct'));

    }

    public function changeordertoshipped()
    {
        $id = $_POST['id'];
        $order = Order::where('number', $id)->get();
        foreach ($order as $key => $value) {
            $or = Order::find($value->id);
            $or->status_id = 3;
            $or->save();
        }
    }

    public function changeordertodelivered()
    {
        $id = $_POST['id'];
        $order = Order::where('number', $id)->get();
        foreach ($order as $key => $value) {
            $or = Order::find($value->id);
            $or->status_id = 4;
            $or->save();
        }
    }

    public function cancelorder()
    {
        $number = $_POST['number'];
        $orders = Order::where('number', $number)->get();
        foreach ($orders as $or) {
            $oror = Order::find($or->id);
            $oror->status_id = 3;
            $oror->save();
            $product = Product::find($oror->product_id);
            $product->stock = $product->stock + $oror->quantity;
            $product->save();
            $status = $oror->Status->name;
        }

        return Response::json($status);
    }

    public function pendingorder()
    {
        $number = $_POST['number'];
        $orders = Order::where('number', $number)->get();
        foreach ($orders as $or) {
            $oror = Order::find($or->id);
            $oror->status_id = 1;
            $oror->save();
            $status = $oror->Status->name;
        }

        return Response::json($status);
    }

    public function getvisitproduct()
    {
        $id = $_POST['idProduct'];
        $pro = Product::find($id);
        $pro->visits += 1;
        $pro->save();

    }

    public function deleteorderproduct()
    {
        $id = $_POST['id'];
        Order::where('id', $id)->delete();
    }

    public function removecartsession()
    {
        $id = $_POST['id'];
        CartSession::where('id', $id)->delete();
        $number = session()->get('cartsessionnumber');
        $cart = CartSession::where('session_number', $number)->count();
        return Response::json($cart);
    }

    public function removecart()
    {
        $id = $_POST['id'];
        Cart::where('id', $id)->delete();
        $cart = Cart::where('user_id', Auth::user()->id)->count();
        return Response::json($cart);
    }

    public function mycart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::user()->id)->get();
            $cartcount = Cart::where('user_id', Auth::user()->id)->count();
            $cartids = DB::table('cart')->where('user_id', Auth::user()->id)->pluck('id');
            $optioncart = OptionCart::whereIN('cart_id', $cartids)->get();
            $lang = \Session::get('lang');
            $con = Configurationsite::first();
            if ($lang == null) {
                $lan = $con->default_lang;
            } else {
                $lan = $lang;
            }
            $aa = $lan;
            \App::setLocale($aa);
            return view('website.mycart', compact('cart', 'lan', 'optioncart'));
        } else {
            $number = session()->get('cartsessionnumber');
            $cart = CartSession::where('session_number', $number)->get();
            $options = ProductOption::where('status', 1)->get();

            $lang = \Session::get('lang');
            $con = Configurationsite::first();
            if ($lang == null) {
                $lan = $con->default_lang;
            } else {
                $lan = $lang;
            }
            $aa = $lan;
            \App::setLocale($aa);

            return view('website.mycart', compact('cart', 'lan', 'options'));
        }
    }

    public function updatecart()
    {
        $id = $_POST['id'];
        $quantity = $_POST['quantity'];
        if (Auth::check()) {
            $cart = Cart::find($id);
            $cart->count = $quantity;
            $cart->save();
            $cart1 = Cart::where('id', $id)->get();
            $cartids = DB::table('cart')->where('id', $id)->pluck('id');
            $optioncart = OptionCart::whereIN('cart_id', $cartids)->get();
            $lang = \Session::get('lang');
            $con = Configurationsite::first();
            if ($lang == null) {
                $lan = $con->default_lang;
            } else {
                $lan = $lang;
            }
            $aa = $lan;
            \App::setLocale($aa);
            $html = view('website.updatecart', ['lan' => $lan, 'cart1' => $cart1, 'optioncart' => $optioncart])->render();
        } else {
            $cart = CartSession::find($id);
            $cart->count = $quantity;
            $cart->save();
            $number = $cart->session_number;
            $cart1 = CartSession::where('session_number', $number)->where('id', $id)->get();
            $options = ProductOption::where('status', 1)->get();

            $lang = \Session::get('lang');
            $con = Configurationsite::first();
            if ($lang == null) {
                $lan = $con->default_lang;
            } else {
                $lan = $lang;
            }
            $aa = $lan;
            \App::setLocale($aa);

            $html = view('website.updatecart', ['lan' => $lan, 'cart1' => $cart1, 'options' => $options])->render();
        }

        return $html;
    }

    public function changetypecoupon()
    {
        $type = $_POST['type'];
        if ($type == 'product') {
            $c = DB::table('products')->where('status', 1)->lists('title_ar', 'id');
        } elseif ($type == 'user') {
            $c = DB::table('users')->lists('name', 'id');
        } elseif ($type == 'shipping') {
            $c = DB::table('regions')->lists('name', 'id');
        } elseif ($type == 'category') {
            $c = DB::table('category')->where('status', 1)->lists('title', 'id');
        } elseif ($type == 'general') {

            $c = [
                [
                    'id' => 1,
                    'name' => trans('home.once'),
                ],
                [
                    'id' => 2,
                    'name' => trans('home.more_once'),
                ]
            ];
        } else {
            return;
        }

        $html = view('website.changetype', ['c' => $c, 'type' => $type])->render();
        return $html;

    }

    public function addcoupon(Request $request)
    {
        $coupon = $request->coupon;
        $checkcoupon = Coupon::where('code', $coupon)
            ->whereDate('expire_date', '>=', Carbon::today()->toDateString())
            ->where('mini_order', '<=', $request->total)
            ->first();
        if ($checkcoupon && Auth::check()) {
            $isued = DB::table('coupon_user_used')
                ->where('coupon_id', $checkcoupon->id)
                ->where('user_id', Auth::user()->id)
                ->first();

            $cart = Cart::where('user_id', Auth::user()->id)->get();
            foreach ($cart as $key => $value) {
                if ($value->Product->discount > 0) {
                    session()->put('error', trans('site.coupon_discount_error'));
                }
            }

            if ($checkcoupon->type == 'user' && !$isued) {
                $usercoupon = DB::table('coupon_user')
                    ->where('coupon_id', $checkcoupon->id)
                    ->where('user_id', Auth::user()->id)
                    ->get();

                if ($usercoupon) {
                    $new_coupon_price = 0;
                    if (!\Session::has('coupon_price')) {
                        session()->put('coupon_price', 0);
                    } else {
                        $new_coupon_price = session('coupon_price');
                    }
                    $cartcoupon = Cart::where('user_id', Auth::user()->id)->get();


                    if ($checkcoupon->value > 0) {
                        $new_coupon_price += $checkcoupon->value;
                        /*$updatecart = Cart::find($value->id);
                        $updatecart->coupon_price += $checkcoupon->value;
                        $updatecart->save();*/
                    }
                    if ($checkcoupon->percent > 0) {

                        //$updatecart = Cart::find($value->id);
                        $totalproductprice = 0;
                        foreach ($cartcoupon as $key => $value) {
                            if ($value->Product->discount) {
                                $totalproductprice += (($value->Product->price - $value->Product->discount) + $value->optionprice) * $value->count;
                            } else {
                                $totalproductprice += ($value->Product->price + $value->optionprice) * $value->count;
                            }
                        }
                        $new_coupon_price += $totalproductprice * ($checkcoupon->percent / 100);

                        //$updatecart->coupon_price += ($totalproductprice * $checkcoupon->percent)/100;
                        //$updatecart->save();
                    }
                    session()->put('coupon_price', $new_coupon_price);

                    $newcoupon = Coupon::find($checkcoupon->id);
                    $newcoupon->usedcount = $checkcoupon->usedcount + 1;
                    $newcoupon->save();
                    DB::table('coupon_user_used')->insert(
                        ['coupon_id' => $checkcoupon->id, 'user_id' => Auth::user()->id]
                    );
                    session()->put('success', 'successfully apply this coupon with value ' . session('coupon_price'));
                    return redirect('my-cart');
                } else {
                    session()->put('error', trans('site.coupon_not_valid_error'));
                    return redirect('my-cart');
                }
            } else if ($checkcoupon->type == 'general') {

                $is_used = DB::table('coupon_user_used')
                    ->where('coupon_id', $checkcoupon->id)
                    ->first();
                // just for once
                if ($is_used && $checkcoupon->count == 1) {
                    session()->put('error', trans('site.coupon_not_valid_error'));
                    return redirect('my-cart');
                } else if ($checkcoupon->count == 2 && $isued) {
                    session()->put('error', trans('site.coupon_not_valid_error'));
                    return redirect('my-cart');
                }

                $new_coupon_price = 0;
                if (!\Session::has('coupon_price')) {
                    session()->put('coupon_price', 0);
                } else {
                    $new_coupon_price = session('coupon_price');
                }
                $cartcoupon = Cart::where('user_id', Auth::user()->id)->get();
                if ($checkcoupon->value > 0) {
                    $new_coupon_price += $checkcoupon->value;
                    /*$updatecart = Cart::find($value->id);
                    $updatecart->coupon_price += $checkcoupon->value;
                    $updatecart->save();*/
                }
                if ($checkcoupon->percent > 0) {
                    //$updatecart = Cart::find($value->id);
                    $totalproductprice = 0;
                    foreach ($cartcoupon as $key => $value) {
                        if ($value->Product->discount) {
                            $totalproductprice += (($value->Product->price - $value->Product->discount) + $value->optionprice) * $value->count;
                        } else {
                            $totalproductprice += ($value->Product->price + $value->optionprice) * $value->count;
                        }
                    }
                    $new_coupon_price += $totalproductprice * ($checkcoupon->percent / 100);

                    //$updatecart->coupon_price += ($totalproductprice * $checkcoupon->percent)/100;
                    //$updatecart->save();
                }
                session()->put('coupon_price', $new_coupon_price);
                $newcoupon = Coupon::find($checkcoupon->id);
                $newcoupon->usedcount = $checkcoupon->usedcount + 1;
                $newcoupon->save();
                DB::table('coupon_user_used')->insert(
                    ['coupon_id' => $checkcoupon->id, 'user_id' => Auth::user()->id]
                );
                session()->put('success', 'successfully apply this coupon with value ' . session('coupon_price'));
                return redirect('my-cart');

            } else if ($checkcoupon->type == 'shipping' && !$isued) {

                $shipping_coupon_id = $checkcoupon->id;
                if (!\Session::has('shipping_coupon_id')) {
                    session()->put('shipping_coupon_id', $shipping_coupon_id);

                    $newcoupon = Coupon::find($checkcoupon->id);
                    $newcoupon->usedcount = $checkcoupon->usedcount + 1;
                    $newcoupon->save();

                    DB::table('coupon_user_used')->insert(
                        ['coupon_id' => $checkcoupon->id, 'user_id' => Auth::user()->id]
                    );

                    session()->put('success', trans('site.coupon_added_success_message'));
                    return redirect('my-cart');
                } else {
                    session()->put('error', trans('site.coupon_not_valid_error'));
                    return redirect('my-cart');
                }

            } else if (($checkcoupon->type == 'product' || $checkcoupon->type == 'category') && !$isued) {
                $productcoupon = DB::table('coupon_product')
                    ->where('coupon_id', $checkcoupon->id)->take(1)
                    ->pluck('product_id');

                if (count($productcoupon) > 0) {
                    $cartcoupon = Cart::where('user_id', Auth::user()->id)
                        ->whereIn('product_id', $productcoupon)
                        ->get();
                    $totalproductprice = 0;
                    foreach ($cartcoupon as $key => $value) {

                        if ($checkcoupon->value > 0) {
                            $updatecart = Cart::find($value->id);
                            $updatecart->coupon_price = $checkcoupon->value;
                            $updatecart->save();
                        }
                        if ($checkcoupon->percent > 0) {
                            $updatecart = Cart::find($value->id);

                            if ($value->Product->discount) {
                                $totalproductprice = (($value->Product->price - $value->Product->discount) + $value->optionprice) * $value->count;
                            } else {
                                $totalproductprice += ($value->Product->price + $value->optionprice) * $value->count;
                            }

                            $updatecart->coupon_price += ($totalproductprice * $checkcoupon->percent) / 100;
                            $updatecart->save();
                        }

                    }

                    $newcoupon = Coupon::find($checkcoupon->id);
                    $newcoupon->usedcount = $checkcoupon->usedcount + 1;
                    $newcoupon->save();
                    DB::table('coupon_user_used')->insert(
                        ['coupon_id' => $checkcoupon->id, 'user_id' => Auth::user()->id]
                    );
                    session()->put('success', trans('site.coupon_added_success_message'));
                    return redirect('my-cart');

                } else {
                    session()->put('error', trans('site.coupon_not_valid_error'));
                    return redirect('my-cart');
                }

            } else {

                session()->put('error', trans('site.coupon_not_valid_error'));
                return redirect('my-cart');
            }

        } else {

            session()->put('error', trans('site.coupon_not_valid_error'));
            return redirect('my-cart');
        }
    }

}

