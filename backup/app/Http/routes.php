<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/products/patch/update' , 'ProductController@patchUpdateProducts');

Route::get('/products/patch/update_link' , 'ProductController@patchUpdateProductsLinks');

////Clear route cache:
Route::get('/route-cache', 'WebsiteController@clearCache');
Route::get('/edit/product/images', 'WebsiteController@editProductImages');
Route::get('/edit/slider/images', 'WebsiteController@editSliderImages');
Route::get('/edit/brand/images', 'WebsiteController@editBrandImages');

Route::get('resources/assets/{location}/{type}/{file}', [function ($location,$type, $file) {
    $path = base_path("resources\assets\\$location\\$type\\$file");
    if (file_exists($path)) {
//        return response()->download($path, "$file");
        $file = \Illuminate\Support\Facades\File::get($path);
        $mime = '';
        if($type === 'css'){
            $mime = 'text/css';
        }else if($type === 'js'){
            $mime = 'text/javascript';
        }

        $response = \Illuminate\Support\Facades\Response::make($file, 200);
        $response->header("Content-Type", $mime);

        return $response;
    }
    return response()->json([], 404);
}]);
Route::get('uploads/{location}/{size}/{file}', [function ($location , $size, $file) {
    $path = base_path("uploads\\$location\\$size\\$file");
    if (file_exists($path)) {
//        return response()->download($path, "$file");
        $file = \Illuminate\Support\Facades\File::get($path);
        $mime = 'image\jpeg';

        $response = \Illuminate\Support\Facades\Response::make($file, 200);
        $response->header("Content-Type", $mime);

        return $response;
    }
}]);
////
Route::auth();

//Route::get('backup', function () {
//
//    //\Artisan::call('db:backup');
//    Artisan::call('db:backup');
//    //
//});
Route::get('auth/facebook', 'Auth\AuthController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleFacebookCallback');
Route::get('auth/google', 'Auth\AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\AuthController@handleGoogleCallback');
Route::post('load_more/products', 'ZizoController@load_more');
Route::post('user/register', 'Auth\AuthController@store');
Route::get('page/{id}/{link}', 'ZizoController@page_show');
Route::get('currency/change', 'CalculateOrderController@currencycahange');

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::get('payPremium', ['as' => 'payPremium', 'uses' => 'PayPalControler@payPremium']);
    Route::post('getCheckout', ['as' => 'getCheckout', 'uses' => 'PayPalControler@getCheckout']);
    Route::get('getDone', ['as' => 'getDone', 'uses' => 'PayPalControler@getDone']);
    Route::get('getCancel', ['as' => 'getCancel', 'uses' => 'PayPalControler@getCancel']);
    Route::get('finish_order', 'PayPalControler@finishorder');

    Route::post('getpayment', 'CalculateOrderController@getpayment');
    Route::post('order-details', 'CalculateOrderController@orderdetails');
    Route::get('continue-order', 'CalculateOrderController@continueorder');
    Route::post('add/favorite', 'ZizoController@add_favorite');
    Route::get('incompleted/orders', 'OrderController@index2');

    Route::get('vendor/products/{id}', 'VendorPanelController@vendor_products')->name('vendorproducts');
    Route::get('vendor/orders/{id}', 'VendorPanelController@vendor_orders')->name('vendororders');
    Route::get('vendor/balance/{id}', 'VendorPanelController@vendor_balance')->name('vendorbalance');
    Route::get('vendor/categories/{id}', 'VendorPanelController@categories')->name('vendor_categories');
    Route::post('vendor/edit/product', 'VendorPanelController@edit_product');
    Route::post('vendor/delete/product', 'VendorPanelController@delete_product');
    Route::post('vendor/category/sub', 'VendorPanelController@getsubcategory');
    Route::post('vendor/product/add', 'VendorPanelController@addproduct');
    Route::get('vendor/product/show/{id}', 'VendorPanelController@showproduct')->name('showproduct');
    Route::post('vendor/product/store', 'VendorPanelController@saveproduct')->name('saveproduct');
    Route::post('vendor/product/update/{id}', 'VendorPanelController@updateproduct')->name('updateproduct');
    Route::post('vendor/balance/drag', 'VendorPanelController@vendordrag')->name('vendordrag');
    Route::post('vendor/orders/report', 'VendorPanelController@display_orders');

    Route::post('refunded_order', 'ZizoController@refunded_order');
    Route::get('affilate/panel/products/{id}', 'AffilatePanelController@affilate_products')->name('affilatepanelproducts');
    Route::get('affilate/connect/{id}', 'AffilatePanelController@connect_product')->name('connect_product');
    Route::get('affilate/balance/{id}', 'AffilatePanelController@affilate_balance')->name('affilatebalance');
    Route::get('affilate/panel/orders/{id}', 'AffilatePanelController@affilate_orders')->name('affilatepanelorders');
    Route::post('affilate/category/sub', 'AffilatePanelController@getsubcategory');
    Route::post('affilate/category/products', 'AffilatePanelController@category_products');
    Route::post('affilate/product/connect', 'AffilatePanelController@saveconnect')->name('saveconnect');
    Route::post('affilate/balance/drag', 'AffilatePanelController@affilatedrag')->name('affilatedrag');
    Route::post('affialte/orders/report', 'AffilatePanelController@display_orders');
    Route::get('affilate/connect_category/{id}', 'AffilatePanelController@connect_category')->name('connect_category');
    Route::post('affilate/category/connect', 'AffilatePanelController@savecatconnect');


});

Route::group(['middleware' => ['web']], function () {

    Route::get('country/regions/{id}', 'ZizoController@country_regions');
    Route::get('region/areas/{id}', 'ZizoController@region_areas');
    Route::get('region/getShippingPrice', 'ZizoController@getShippingPrice');
    Route::get('offers/seo', 'ZizoController@showOffersSeo');
    Route::post('offers/seo/save', 'ZizoController@saveOffersSeo')->name('saveseo');
    Route::get('country/regions/{id}/{addid}', 'ZizoController@country_regions2');
    Route::get('region/areas/{id}/{addid}', 'ZizoController@region_areas2');
    Route::get('{link}/affilate/{id}', 'ZizoController@affilate_product');

    Route::get('/offers', 'ZizoController@getOffers');
    Route::get('brand/products/{id}/{name}', 'ZizoController@brandproducts')->name('brandproducts');
    Route::get('/lang/{lang}', 'LanguageController@index');
    Route::get('/', 'WebsiteController@index');
    Route::post('newnewsletter', 'WebsiteController@newnewsletter');
    Route::post('newadsregister', 'WebsiteController@newadsregister');
    Route::get('search-home', 'WebsiteController@search');
    Route::get('search-home/autocomplete2', 'WebsiteController@homeautocomplete2');
//    Route::post('search-home/autocomplete', 'WebsiteController@homeautocomplete');
    Route::post('newsearch', 'WebsiteController@newsearch');
    Route::get('show-course/{link}', 'WebsiteController@showcourse');
    Route::get('show-category/{link}', 'WebsiteController@showcategory');
    Route::get('show-instructor/{link}', 'WebsiteController@showinstructor');
    Route::get('instructors', 'WebsiteController@instructors');
    Route::get('join-us', 'WebsiteController@joinus');
    Route::post('postjoinus', 'WebsiteController@postjoinus');
    Route::get('check-certificate', 'WebsiteController@checkcertificate');
    Route::post('check-certificate', 'WebsiteController@postcheckcertificate');
    Route::get('contact-us', 'WebsiteController@contactus');
    Route::post('newcontactus', 'WebsiteController@postcontactus');
    Route::get('registration', 'WebsiteController@registration');
    Route::post('changecategory', 'WebsiteController@changecategory');
    Route::post('postregistration', 'WebsiteController@postregistration');
    Route::get('blog-item/{value}', 'WebsiteController@showblogitem');
    Route::get('blog-category/{value}', 'WebsiteController@showblogcategory');
    Route::get('specific_course', 'WebsiteController@specificcourse');
    Route::post('postspecificcourse', 'WebsiteController@postspecificcourse');
    Route::get('gallery', 'WebsiteController@gallery');
    Route::post('getoptionproduct', 'WebsiteController@getoptionproduct');
    Route::post('postreviewproduct', 'WebsiteController@postreviewproduct');
    Route::get('my-favorite', 'WebsiteController@myfavorite');
    Route::post('addfavorite', 'WebsiteController@addfavorite');
    Route::post('removefavorite', 'WebsiteController@removefavorite');
    Route::post('addcart', 'WebsiteController@addcart');
    Route::post('removecart', 'WebsiteController@removecart');
    Route::post('removecartsession', 'WebsiteController@removecartsession');
    Route::get('my-cart', 'WebsiteController@mycart');

    Route::post('updatecart', 'WebsiteController@updatecart');
    Route::post('addcoupon', 'WebsiteController@addcoupon');

    Route::get('change-account', 'WebsiteController@changeaccount');
    Route::post('account/{id}', 'WebsiteController@postchangeaccount');
    Route::get('my-orders/{status}', 'WebsiteController@myorders');
    Route::get('my-address', 'WebsiteController@myaddress');
    Route::get('addaddress', 'WebsiteController@addaddress');
    Route::post('createaddress', 'WebsiteController@createaddress');
    Route::get('editaddress/{id}', 'WebsiteController@editaddress');
    Route::post('editaddress/{id}', 'WebsiteController@posteditaddress');
    Route::post('deleteaddress', 'WebsiteController@deleteaddress');

    Route::post('getmore', 'WebsiteController@getmore');
    Route::post('cancelorder', 'WebsiteController@cancelorder');
    Route::post('pendingorder', 'WebsiteController@pendingorder');
    Route::post('deleteorderproduct', 'WebsiteController@deleteorderproduct');
    Route::post('getvisitproduct', 'WebsiteController@getvisitproduct');
    Route::post('changecurrency', 'WebsiteController@changecurrency');
    Route::get('my-deliveries', 'WebsiteController@mydeliveries');
    Route::post('changeordertoshipped', 'WebsiteController@changeordertoshipped');
    Route::post('changeordertodelivered', 'WebsiteController@changeordertodelivered');
});

Route::group(['middleware' => ['web', 'auth', 'admin']], function () {


    Route::get('/admin', 'WebsiteController@admin');
    Route::resource('user', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::post('gettypevalue', 'WebsiteController@gettypevalue');
    Route::post('getparentmenu', 'WebsiteController@getparentmenu');
    Route::post('deleteoneimage/{id}', 'WebsiteController@deleteoneimage');
    Route::post('changetypecoupon', 'WebsiteController@changetypecoupon');

    Route::group(['middleware' => ['permission:website']], function () {
        Route::get('user/address/{id}', 'ZizoController@useraddresses');

        Route::get('vendor/order/{id}', 'VendorController@vendor_orders')->name('vendor_orders');
        Route::get('vendor/drags/{id}', 'VendorController@vendor_drags')->name('vendor_drags');
        Route::post('vendor/drag/status/{id}', 'VendorController@drag_status')->name('drag_status');

        Route::get('affilate/report', 'ZizoController@affilate_orders');
        Route::post('affilate/report/display', 'ZizoController@display_orders');

        Route::get('affilate/order/{id}', 'AffilateController@affilate_orders')->name('affilate_orders');
        Route::get('affilate/drags/{id}', 'AffilateController@affilate_drags')->name('affilate_drags');
        Route::get('affilate/admin/products/{id}', 'AffilateController@affilate_products')->name('affilate_products');
        Route::get('affilate/admin/categories/{id}', 'AffilateController@affilate_categories')->name('affilate_categories');
        Route::post('affilate/drag/status/{id}', 'AffilateController@drag_status')->name('affilate_drag_status');

        Route::get('user/data/{id}', 'ZizoController@userdata');

        Route::post('order/user/store', 'OrderController@store2')->name('orders.store2');
        Route::post('{name}/up/{ids}', 'WebsiteController@updatestatus');
        Route::resource('brands', 'BrandController');
        Route::resource('refundedorders', 'RefundedOrderController');
        Route::resource('pages', 'PageController');
        Route::resource('deliveries', 'DeliveryController');
        Route::resource('paymethods', 'PayMethodController');
        Route::resource('blogcategory', 'BlogCategoryController');
        Route::resource('blogitem', 'BlogItemController');
        Route::resource('vendors', 'VendorController');
        Route::resource('affilates', 'AffilateController');
        Route::resource('configurationsite', 'ConfigurationsiteController');

        Route::GET('/config/sms', 'ConfigurationsiteController@showConfigSms');
        Route::post('/save/config/sms', 'ConfigurationsiteController@saveConfigSms');

        Route::GET('/clients/opinion', 'Admin\ComplaintController@showClientsOpinion');
        Route::POST('/get/order/details', 'Admin\ComplaintController@getOrderDetails');
        Route::POST('/client/opinion/save', 'Admin\ComplaintController@saveClientOpinion');


        Route::resource('artist', 'ArtistController');
        Route::resource('coupon', 'CouponController');
        Route::get('coupon/print/{id}', 'CouponController@print_coupon');

        Route::resource('slideshow', 'SlideshowController');
        Route::resource('image-home', 'ImageHomeController');
        Route::resource('sponsors', 'SponsorsController');
        Route::resource('countries', 'CountryController');
        Route::resource('regions', 'RegionController');
        Route::resource('areas', 'AreaController');

        Route::resource('colors', 'Admin\ColorController');

        Route::get('/complaints', 'Admin\ComplaintController@showComplaints');
        Route::get('/complaints/data', 'Admin\ComplaintController@getComplaintsData');
        Route::get('/complaints/add', 'Admin\ComplaintController@showComplaintAdd');
        Route::POST('/complaints/add/save', 'Admin\ComplaintController@saveComplaintAdd');
        Route::POST('/complaints/process', 'Admin\ComplaintController@processComplaint');
        Route::POST('/complaints/solve', 'Admin\ComplaintController@solveComplaint');



        Route::get('/complaints/edit/{id}', 'Admin\ComplaintController@showComplaintEdit');
        Route::POST('/complaints/edit/{id}/edit', 'Admin\ComplaintController@saveComplaintEdit');

        Route::get('/complaints/report', 'Admin\ComplaintController@showComplaintsReport');
        Route::get('/complaints/report/data', 'Admin\ComplaintController@getComplaintsReportData');



        Route::get('stock_report', 'ZizoController@stockreport');
        Route::get('sub_stock_report', 'ProductController@substockreport');
        Route::get('/sub_stock/report/data', 'ProductController@getSubstockreportData');

        Route::get('/stock/report', 'ZizoController@showAdminStockreport');

        Route::get('store_stock_report', 'ProductController@store_stock_report');
        Route::get('store/stock/report/data', 'ProductController@getStoreStockreportData');

        Route::get('main_stock_report', 'ProductController@main_stock_report');
        Route::get('main/stock/report/data', 'ProductController@getMainStockreportData');

        Route::get('/reports/moves', 'ProductController@showProductMoveReport');
        Route::get('/reports/moves/search', 'ProductController@showProductMoveReportData');
        Route::get('/reports/moves/data', 'ProductController@getProductMoveReport');


        Route::get('order_report', 'ZizoController@orderreport');
        Route::post('displayorders', 'ZizoController@displayorders');


        Route::get('/show/clients', 'ZizoController@showClients');
        Route::get('/get/clients/data', 'ZizoController@getClientsData');

        Route::get('/spending', 'ZizoController@showSpending');
        Route::get('/spending/data', 'ZizoController@getSpendingData');
        Route::get('/spending/add', 'ZizoController@showSpendingAdd');
        Route::post('/spending/add/save', 'ZizoController@saveSpendingAdd');

        Route::resource('orders', 'OrderController');
        Route::get('/order/store_orders', 'OrderController@storeOrders');
        Route::get('/store/orders/finished/data', 'OrderController@getStoreOrdersData');

        Route::resource('store_orders', 'StoreOrderController');
        Route::post('/get/category/details', 'StoreOrderController@getProductDetails');
        Route::post('/get/store/details', 'Admin\PlaceOrderController@getProductDetails');
        Route::post('/get/store/details/number', 'Admin\PlaceOrderController@getProductDetailsByNumber');

        Route::get('place/store_orders', 'StoreOrderController@showPlaceStoreOrders');
        Route::get('place/store_order/create', 'StoreOrderController@showAddPlaceStoreOrder');
        Route::post('place/store_order/create/save', 'StoreOrderController@saveAddPlaceStoreOrder');

        Route::get('place/store_orders/finished', 'StoreOrderController@showFinishedPlaceStoreOrders');

        Route::get('/place/store_order/edit/{id}', 'StoreOrderController@showEditPlaceStoreOrder');
        Route::post('/place/store_order/edit/{id}/save', 'StoreOrderController@saveEditPlaceStoreOrder');

        Route::get('/place/store_order/print/{id}', 'StoreOrderController@showPrintPlaceStoreOrder');


        Route::resource('place_orders', 'Admin\PlaceOrderController');
        Route::get('place_order/printOrder/{id}', 'Admin\PlaceOrderController@printOrder');
        Route::post('/place_order/finish', 'Admin\PlaceOrderController@FinsishOrder');
        Route::get('/place_order/return', 'Admin\PlaceOrderController@returnOrder');
        Route::post('/get/place_order', 'Admin\PlaceOrderController@getPlaceOrder');
        Route::post('/place_order/return/save', 'Admin\PlaceOrderController@saveReturnOrder');
        Route::get('/place_order/finished', 'Admin\PlaceOrderController@getFinishedOrders');
        Route::get('/place/order/finished/data', 'Admin\PlaceOrderController@getFinishedOrdersData');

        Route::get('/inventory/report', 'Admin\ComplaintController@showInventoryReport');
        Route::post('/inventory/report/inventory', 'Admin\ComplaintController@inventoryReportInventory');
        Route::post('/inventory/report/apply', 'Admin\ComplaintController@applyReportInventory');

        Route::get('/product/permission', 'ProductController@showPermissionProductAdd');
        Route::post('/get/product/details', 'ProductController@getProductDetails');
        Route::post('/product/permission/save', 'ProductController@savePermissionProduct');
        Route::get('/product/exchange', 'ProductController@showProductExchange');

        Route::get('/product/minus', 'ProductController@showMinusProduct');
        Route::post('/product/minus/save', 'ProductController@saveMinusProduct');

        Route::get('/product/return', 'ProductController@showReturnProduct');
        Route::post('/product/return/save', 'ProductController@saveReturnProduct');

        Route::get('/minus/report', 'ProductController@showMinusProductReport');
        Route::get('/minus/report/data', 'ProductController@getMinusProductReportData');

        Route::get('/products/report', 'ProductController@showProductsReport');
        Route::post('/products/report/data', 'ProductController@getProductsReportData');

        Route::get('/permission/report', 'ProductController@showPermissionProductAddReport');
        Route::get('/permission/report/data', 'ProductController@getPermissionProductAddReportData');

        Route::get('/exchange/report', 'ProductController@showExchangeProductReport');
        Route::get('/exchange/report/data', 'ProductController@getExchangeProductReportData');

        Route::get('/exchange/print/{id}', 'ProductController@printExchangeProductOrder');

        Route::get('/products/available/report', 'ProductController@showProductsAvailableReport');
        Route::get('/products/available/report/data', 'ProductController@getProductsAvailableReport');


        Route::post('/store_order/product/delete', 'StoreOrderController@deleteOrderProduct');

        Route::post('orders/update_quantity', 'OrderController@update_quantity');

        Route::get('order/user/create', 'OrderController@createuser');

        Route::resource('order-status', 'OrderStatusController');
        Route::resource('clients', 'ClientController');

        Route::resource('searchitem', 'SearchItemController');
        Route::get('search-words', 'WebsiteController@searchword');
        Route::get('newsletter', 'WebsiteController@newsletter');
        Route::post('delete-newsletter', 'WebsiteController@deletenewsletter');
        Route::resource('category', 'CategoryController');
        Route::resource('products', 'ProductController');
        Route::get('product/code/print', 'ProductController@printProductBarcode');
        Route::resource('product-option', 'ProductOptionController');
        Route::resource('padges', 'PadgeController');
        Route::resource('currencies', 'CurrencyController');
        Route::controller('datatables', 'ProductController', [
            'anyData' => 'datatables.data',
        ]);
        Route::resource('contactus', 'ContactusController');

    });
    /*
    Route::controller('datatables', 'MedicineController', [
        'anyData'  => 'datatables.data',
    ]);
    */


});

Route::get('{value}', 'ZizoController@allroute');

//Route::get('/', 'ZizoController@showMaintainancePage');