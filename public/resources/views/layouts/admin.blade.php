<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Control Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ URL::to('resources/assets/back/images/logo2.png') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/select2.min.css') }}"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/jquery.dataTables.css') }}"/>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/css/bootstrap-select.min.css">

    @if(App::isLocale('ar'))
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/style-ar.css') }}"/>
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/media-queries-ar.css') }}"/>

    @else
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/style.css') }}"/>
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/media-queries.css') }}"/>
    @endif
    @yield('messagepop')
<!-- route('configuration . edit', 1)  -->
    @yield('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div style="overflow-y: visible;" class="menu">
            <div class="brand-name">
                <img src="{{ URL::to('resources/assets/back/images/logo.png') }}" alt="Hyper Design Logo"/>
                <h3><a href="{{ url('/admin') }}">{{trans('home.hyperdesign')}}</a></h3>
            </div>
            <div class="profile-info">
                @if(Auth::user()->image)
                    <img src="{{ URL::to('uploads/user/resize200') }}/{{ Auth::user()->image }}" alt="Person"/>
                @else
                    <img src="{{ URL::to('uploads/male.png') }}" alt="Person"/>
                @endif
                <p>{{trans('home.welcome')}}, <span class="user-name">{{Auth::user()->name}}</span></p>
            </div>
            @permission('website')
            @permission('productsection')
            <div class="links">
                <div class="link" data-toggle="collapse" href="#collapseOne-8" aria-expanded="true"
                     aria-controls="collapseOne">
                    <i class="fa fa-home"></i><a href="#">{{trans('home.product')}}</a><span
                            class="fa fa-chevron-down"></span>
                </div>

                <div class="link-data collapse" id="collapseOne-8">
                    <ul>
                        @permission('complaints')
                        <li><a href="{{ url('/complaints') }}">شكاوى العملاء</a></li>
                        @endpermission
                        @permission('category')
                        <li><a href="{{ url('/category') }}">{{trans('home.category')}}</a></li>
                        @endpermission
                        @permission('brands')
                        <li><a href="{{ url('/brands') }}">{{trans('home.brands')}}</a></li>
                        @endpermission
                        @permission('products')
                        <li><a href="{{ url('/products') }}">{{trans('home.product')}}</a></li>
                        @endpermission

                        @permission('products_print')
                        <li><a href="{{ url('/product/code/print') }}">طباعة الباركود</a></li>
                        @endpermission
                        @permission('product_permission')
                        <li><a href="{{ url('/product/permission') }}">إذن صرف وإضافة منتجات</a></li>
                        @endpermission
                        @permission('product_exchange')
                        <li><a href="{{ url('/product/exchange') }}">تحويل من مخزن لآخر</a></li>
                        @endpermission
                        @permission('product_minus')
                        <li><a href="{{ url('/product/minus') }}">إذن هالك</a></li>
                        @endpermission
                        @permission('product_return')
                        <li><a href="{{ url('/product/return') }}"> إذن مرتجع هالك</a></li>
                        @endpermission
                        @permission('padges')
                        <li><a href="{{ url('/padges') }}">{{trans('home.padge')}}</a></li>
                        @endpermission
                        @permission('pages')
                        <li><a href="{{ url('/pages') }}">{{trans('home.pages')}}</a></li>
                        <li><a href="{{ url('/offers/seo') }}">{{trans('home.offers')}}</a></li>
                        @endpermission
                        @permission('currency')
                        <li><a href="{{ url('/currencies') }}">{{trans('home.currency')}}</a></li>
                        @endpermission
                        @permission('productoption')
                        <li><a href="{{ url('/product-option') }}">{{trans('home.productoption')}}</a></li>
                        @endpermission
                        @permission('coupon')
                        <li><a href="{{ url('/coupon') }}">{{trans('home.coupon')}}</a></li>
                        @endpermission
                        @permission('store_orders')
                        <li><a href="{{ url('/store_orders') }}">{{trans('home.store_orders')}}</a></li>
                        @endpermission
                        @permission('store_orders_create')
                        <li><a href="{{ URL::to('store_orders/create') }}">إضافة طلبيات الصفحة</a></li>
                        @endpermission
                        @permission('place_store_orders')
                        <li><a href="{{ url('/place/store_orders') }}">طلبيات الصفحة من المحل</a></li>
                        @endpermission
                        @permission('finished_place_store_orders')
                        <li><a href="{{ url('/place/store_orders/finished') }}">طلبيات الصفحة من المحل المكتملة</a></li>
                        @endpermission
                        @permission('orders')
                        <li><a href="{{ url('/refundedorders') }}">{{trans('home.refunded_orders')}}</a></li>
                        @endpermission
                        @permission('orders')
                        <li><a href="{{ url('/orders') }}">{{trans('home.completed_orders')}}</a></li>
                        <li><a href="{{ url('/order/store_orders') }}">{{trans('home.completed_store_orders')}}</a></li>
                        @endpermission
                        @permission('place_orders')
                        <li><a href="{{ url('/place_orders') }}">طلبيات المحل</a></li>
                        @endpermission

                        @permission('add_place_orders')
                        <li><a href="{{ url('/place_orders/create') }}">إضافة طلبات المحل</a></li>
                        <li><a href="{{ url('/place_order/return') }}">إضافة مرتجع المحل</a></li>
                        @endpermission

                        @permission('finished_place_orders')
                        <li><a href="{{ url('/place_order/finished') }}">طلبيات المحل المكتملة</a></li>
                        @endpermission

                        @permission('incompletedorder')
                        <li><a href="{{ url('incompleted/orders') }}">{{trans('home.incompleted_orders')}}</a></li>
                        @endpermission
                        @permission('delivery')
                        <li><a href="{{ url('/deliveries') }}">{{trans('home.deliveries')}}</a></li>
                        @endpermission
                        @permission('vendors')
                        <li><a href="{{ url('/vendors') }}">{{trans('home.vendors')}}</a></li>
                        @endpermission

                        @permission('affilates')
                        <li><a href="{{ url('affilates') }}">{{trans('home.affilates')}}</a></li>
                        @endpermission
                    </ul>
                </div>
            </div>
            @endpermission
            @permission('reports')
            <div class="links">
                <div class="link" data-toggle="collapse" href="#collapseOne-15" aria-expanded="true"
                     aria-controls="collapseOne">
                    <i class="fa fa-home"></i><a href="#">{{trans('home.reports')}}</a><span
                            class="fa fa-chevron-down"></span>
                </div>

                <div class="link-data collapse" id="collapseOne-15">
                    <ul>
                        @permission('stock_report')
                        <li><a href="{{ url('/stock_report') }}">تقرير أعداد المخزون</a></li>
                        @endpermission

                        @permission('permission_report')
                        <li><a href="{{ url('/permission/report') }}">تقرير إذن إضافة وصرف منتجات</a></li>
                        @endpermission

                        @permission('exchange_report')
                        <li><a href="{{ url('/exchange/report') }}">تقرير التحويل من مخزن إلى آخر</a></li>
                        @endpermission

                        @permission('minus_report')
                        <li><a href="{{ url('/minus/report') }}">تقرير الهالك والمرتجع</a></li>
                        @endpermission

                        @permission('sub_stock_report')
                        <li><a href="{{ url('/sub_stock_report') }}">تقرير مخزن الفرع</a></li>
                        @endpermission

                        @permission('main_stock_report')
                        <li><a href="{{ url('/main_stock_report') }}">تقرير مخزن الرئيسى</a></li>
                        @endpermission

                        @permission('product_move_report')
                        <li><a href="{{ url('/reports/moves') }}">تقرير حركات الصنف</a></li>
                        @endpermission

                        @permission('store_stock_report')
                        <li><a href="{{ url('/store_stock_report') }}">{{trans('home.store_stock')}}</a></li>
                        @endpermission
                        @permission('order_report')
                        <li><a href="{{ url('/stock/report') }}">{{trans('home.stock_report')}}</a></li>
                        <li><a href="{{ url('/order_report') }}">{{trans('home.orders_report')}}</a></li>
                        @endpermission

                        @permission('products_report')
                        <li><a href="{{ url('/products/report') }}">تقرير المنتجات</a></li>
                        @endpermission

                        @permission('available_products_report')
                        <li><a href="{{ url('/products/available/report') }}">تقرير متاح او غير متاح</a></li>
                        @endpermission

                        @permission('inventory_report')
                        <li><a href="{{ url('/inventory/report') }}">تقرير الجرد بالباركود </a></li>
                        @endpermission


                        @permission('clients_report')
                        <li><a href="{{ url('/show/clients') }}">بيانات العملاء</a></li>
                        @endpermission

                        @permission('spending')
                        <li><a href="{{ url('/spending') }}">المصاريف</a></li>
                        @endpermission

                        @permission('search-words')
                        <li><a href="{{ url('/search-words') }}">{{trans('home.searchword')}}</a></li>
                        @endpermission
                        @permission('affilate_report')
                        <li><a href="{{ url('affilate/report') }}">{{trans('home.affilate_report')}}</a></li>
                        @endpermission
                        @permission('complaints_report')
                        <li><a href="{{ url('/complaints/report') }}">تقرير شكاوى العملاء</a></li>
                        @endpermission
                    </ul>
                </div>
            </div>
            @endpermission
            @permission('forms')
            <div class="links">
                <div class="link" data-toggle="collapse" href="#collapseOne-9" aria-expanded="true"
                     aria-controls="collapseOne">
                    <i class="fa fa-home"></i><a href="#">{{trans('home.forms')}}</a><span
                            class="fa fa-chevron-down"></span>
                </div>

                <div class="link-data collapse" id="collapseOne-9">
                    <ul>
                        @permission('contactus')
                        <li><a href="{{ url('/contactus') }}">{{trans('home.contactus')}}</a></li>
                        @endpermission

                    </ul>
                </div>
            </div>
            @endpermission
            @permission('home')
            <div class="links">
                <div class="link" data-toggle="collapse" href="#collapseOne-10" aria-expanded="true"
                     aria-controls="collapseOne">
                    <i class="fa fa-home"></i><a href="#">{{trans('home.home')}}</a><span
                            class="fa fa-chevron-down"></span>
                </div>
                <!-- <div class="link-data collapse" id="collapseOne">
                   <ul>
                        <div class="sub-link"  data-toggle="collapse" href="#collapseOne-1" aria-expanded="true" aria-controls="collapseOne-1">
                            <a href="#"><i class="fa fa-circle"></i>Medical<span class="fa fa-chevron-down"></span></a>
                        </div>


                    </ul>
                </div> -->
                <div class="link-data collapse" id="collapseOne-10">
                    <ul>
                        @permission('slideshow')
                        <li><a href="{{ url('/slideshow') }}">{{trans('home.slideshow')}}</a></li>
                        @endpermission
                        @permission('blogcategory')
                        <li><a href="{{ url('/blogcategory') }}">{{trans('home.blogcategory')}}</a></li>
                        @endpermission
                        @permission('blogitem')
                        <li><a href="{{ url('/blogitem') }}">{{trans('home.blogitem')}}</a></li>
                        @endpermission
                    </ul>
                </div>
            </div>
            @endpermission
            @permission('configration')
            <div class="links">
                <div class="link" data-toggle="collapse" href="#collapseOne-12" aria-expanded="true"
                     aria-controls="collapseOne">
                    <i class="fa fa-home"></i><a href="#">{{trans('home.configurationsite')}}</a><span
                            class="fa fa-chevron-down"></span>
                </div>
                <!-- <div class="link-data collapse" id="collapseOne">
                   <ul>
                        <div class="sub-link"  data-toggle="collapse" href="#collapseOne-1" aria-expanded="true" aria-controls="collapseOne-1">
                            <a href="#"><i class="fa fa-circle"></i>Medical<span class="fa fa-chevron-down"></span></a>
                        </div>
                    </ul>
                </div> -->
                <div class="link-data collapse" id="collapseOne-12">
                    <ul>
                        @permission('configurationsite')
                        <li><a href="{{ url('/configurationsite/1/edit') }}">{{trans('home.configurationsite')}}</a>
                        </li>
                        @endpermission
                        @permission('configurationsms')
                        <li><a href="{{ url('/config/sms') }}">إعدادات الرسائل</a></li>
                        @endpermission
                        @permission('paymethod')
                        <li><a href="{{ url('/paymethods') }}">{{trans('home.paymethod')}}</a></li>
                        @endpermission
                        @permission('countries')
                        <li><a href="{{ url('/countries') }}">{{trans('home.countries')}}</a></li>
                        @endpermission
                        @permission('regions')
                        <li><a href="{{ url('/regions') }}">{{trans('home.regions')}}</a></li>
                        @endpermission
                        @permission('areas')
                        <li><a href="{{ url('/areas') }}">{{trans('home.areas')}}</a></li>
                        @endpermission
                        @permission('colors')
                        <li><a href="{{ url('/colors') }}">الألوان</a></li>
                        @endpermission
                    </ul>
                </div>
            </div>
            @endpermission
            @permission('users')
            <div class="links">
                <div class="link" data-toggle="collapse" href="#collapseOne-6" aria-expanded="true"
                     aria-controls="collapseOne">
                    <i class="fa fa-home"></i><a href="#">{{trans('home.users')}}</a><span
                            class="fa fa-chevron-down"></span>
                </div>
                <!-- <div class="link-data collapse" id="collapseOne">
                   <ul>
                        <div class="sub-link"  data-toggle="collapse" href="#collapseOne-1" aria-expanded="true" aria-controls="collapseOne-1">
                            <a href="#"><i class="fa fa-circle"></i>Medical<span class="fa fa-chevron-down"></span></a>
                        </div>


                    </ul>
                </div> -->
                <div class="link-data collapse" id="collapseOne-6">
                    <ul>
                        @permission('user')
                        <li><a href="{{ url('/user') }}">{{trans('home.user')}}</a></li>
                        @endpermission
                        @permission('roles')
                        <li><a href="{{ url('/roles') }}">{{trans('home.roles')}}</a></li>
                        @endpermission
                        @permission('paymethod')
                        <li><a href="{{ url('/permissions') }}">{{trans('home.permission')}}</a></li>
                        @endpermission
                    </ul>
                </div>
            </div>
            @endpermission
            @endpermission
        </div>

        <div class="content col-xs-12">
            <div class="nav-bar group">
                <span class="group"><i class="fa fa-bars"></i></span>
                <div class="logout">
                    <a href="{{ url('/logout') }}"><span>{{trans('home.logout')}}</span><i
                                class="fa fa-sign-out"></i></a>
                </div>

                <div class="language group">
                    <ul class="lang">
                        <li><a href="{{ URL::to('lang/en') }}"><img src="{{ URL::to('uploads/us.png') }}"
                                                                    width="25"></a></li>
                        <li><a href="{{ URL::to('lang/ar') }}"><img src="{{ URL::to('uploads/eg.png') }}"
                                                                    width="25"></a></li>
                    </ul>
                </div>

            </div>
            <div class="information">
                @yield('content')
            </div>

        </div>
    </div>
</div>
<footer class="col-xs-12">
    <div class="copyrights">
        <p>Designed & Developed by <a href="#">Hyper Design</a>&copy;</p>
    </div>
</footer>

<script src="{{ URL::to('resources/assets/back/js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ URL::to('resources/assets/back/js/select2.full.min.js') }}"></script>
<script src="{{ URL::to('resources/assets/back/js/bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/js/bootstrap-select.min.js"></script>
<script src="{{ URL::to('resources/assets/back/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::to('resources/assets/back/js/validator.min.js') }}"></script>
<script src="{{ URL::to('resources/assets/back/js/tinymce.min.js') }}"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ URL::to('resources/assets/back/js/script.js') }}"></script>

@yield('script')
@stack('scripts')
<script>
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            timeFormat: "hh:mm:ss"
        });


    });


</script>

<script>

    tinymce.init({
        mode: "specific_textareas",
        mode: "textareas",
        editor_selector: "area1",
        height: 500,
        fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools jbimages'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages',
        toolbar2: 'ltr rtl | print preview media | forecolor backcolor emoticons | fontsizeselect',
        //image_advtab: true,
        templates: [
            {title: 'Test template 1', content: 'Test 1'},
            {title: 'Test template 2', content: 'Test 2'}
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });

    //datatables
    (function () {
        $('#datatable').DataTable();
        $('#datatable2').DataTable();
    })();

    //checkAll

    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $(".checkAll").change(function () {
        $(".web").prop('checked', $(this).prop("checked"));
    });

    $(".checkAllcart").change(function () {
        $(".cart").prop('checked', $(this).prop("checked"));
    });

    $(document).ready(function () {
        $("#c").change(function () {
            $.getJSON("{{ URL::to('cate/') }}/" + $("#c").val(), function (data) {
                console.log(data);
                var $stations = $("#cat");
                $stations.empty();

                if ($("#c").val() == 'articles') {
                    $.each(data, function (index, value) {
                        $stations.append('<option value="' + value.id + '">' + value.title + '</option>');
                    });
                }
                if ($("#c").val() == 'catogries') {
                    $.each(data, function (index, value) {
                        $stations.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
                if ($("#c").val() == 'gallery') {
                    $.each(data, function (index, value) {
                        $stations.append('<option value="' + value.id + '">' + value.title + '</option>');
                    });
                }
                if ($("#c").val() == 'contactus') {
                    $.each(data, function (index, value) {
                        $stations.append('<option value="' + value.id + '">' + value.title + '</option>');
                    });
                }
                $("#cat").trigger("change");
                /* trigger next drop down list not in the example */
            });
        });
    });

    // btn_delete
    $(document).ready(function () {

        $('#coupon_print').click(function () {
            var id = [];
            $(':checkbox:checked').each(function (i) {
                id[i] = $(this).val();
            });
            if (id.length === 0) {
                alert("Please Select at least one checkbox");
            } else if (id.length == 1) {
                window.location = '{{url('coupon/print/')}}' + '/' + id[0];
            } else if (id.length == 2) {
                window.location = '{{url('coupon/print/')}}' + '/' + id[0] + '_' + id[1];
            } else {
                alert("you can't more than 2 coupons");
            }
        });

        $('#btn_delete').click(function () {

            var id = [];
            <?php
            $last_word = Request::segment(1);
            Session::set('route', $last_word);
            ?>
            $(':checkbox:checked').each(function (i) {
                id[i] = $(this).val();
            });
            if (id.length === 0) //tell you if the array is empty
            {
                alert("Please Select atleast one checkbox");
            }
            else {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "<?php echo Session::get('route')?>/" + id,
                    type: 'DELETE',
                    data: {id: id},
                    success: function () {
                        for (var i = 0; i < id.length; i++) {
                            $('tr#' + id[i] + '').css('background-color', '#ccc');
                            $('tr#' + id[i] + '').fadeOut('slow');
                            $('input:checkbox').removeAttr('checked');
                        }
                    }
                });
            }


        });
    });
    // btn_active
    $(document).ready(function () {
        $('#btn_active').click(function () {
            var id = [];
            <?php
            $last_word = Request::segment(1);
            Session::set('route', $last_word);
            ?>
            $(':checkbox:checked').each(function (i) {
                id[i] = $(this).val();
            });
            if (id.length === 0) //tell you if the array is empty
            {
                alert("Please Select atleast one checkbox");
            }

            else {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "<?php echo Session::get('route')?>/up/" + id,
                    method: 'POST',
                    data: {id: id},
                    success: function () {
                        $('input:checkbox').removeAttr('checked');
                        location.reload();
                    }
                });
            }


        });

        $('#btn_back').click(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "backup",
                method: 'GET',
                success: function () {

                }
            });


        });

    });


</script>

</body>
</html>
