@extends('layouts.app')
@section('meta')
    <title>@if($lan == 'ar'){{ $product->title_ar }}@else{{ $product->title }}@endif</title>
    <meta name="description" @if($lan == 'ar') content="{{ $product->meta_desc}}"
          @else content="{{ $product->meta_desc_en}}" @endif>
    <meta name="keywords" @if($lan == 'ar') content="{{ $product->meta_key}}"
          @else content="{{ $product->meta_key_en}}" @endif>

@endsection
@section('style')
    <style>
        .lineh {
            line-height: 30px;
            margin-top: 0px
        }

        .zoomContainer {
            position: relative !important;
        }


    </style>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/star-rating.css') }}" media="all"
          type="text/css"/>
@endsection
@section('content')

    <section id="product-details">
        <div class="container">
            <div class="pro-top">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/') }}">{{ trans('site.home') }}</a></li>
                    @foreach($categories as $category)

                        @if($lan == 'ar')
                            <li><a href="{{ url($category->link) }}">{{ $category->title_ar }} </a></li>
                        @else
                            <li><a href="{{ url($category->link) }}">{{ $category->title }} </a></li>
                        @endif

                    @endforeach
                    {{--<li class="active">
                      @if($lan == 'ar')
                      {{ $product->title_ar }}
                      @else
                      {{ $product->title }}
                      @endif
                    </li>--}}
                </ol>
                <div class="row">
                    <div class="col-md-6">
                        <div class="title">@if($lan == 'ar')
                                <h1 @if ($product->stock >= 1) style="font-size: 26px;"
                                    @else style="font-size: 20px;" @endif>{{ $product->title_ar }}</h1>
                            @else
                                <h1 @if ($product->stock >= 1) style="font-size: 26px;"
                                    @else style="font-size: 20px;" @endif>{{ $product->title }}</h1>
                            @endif</div>
                    <!--<div class="rating">
                                <span class="inline"><input id="input-21d" @if(array_key_exists($product->id, $rating)) value="{{$rating[$product->id]}}" @else value="0" @endif type="text" class="rating" data-min=0 data-max=5 data-display-only="true" data-step=1 data-size="sm"></span>
                            </div>-->
                        <div class="image">
                            <a href="{{ url('uploads/product/resize800/') }}/{{ $product->image }}" class="xxx"
                               data-lightbox="image-1" data-title="My caption">
                                <img id="zoom_03" class="main-img"
                                     @if($product->stock <= 0) style="filter: blur(4px);" @endif
                                     alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $product->title_ar) }} @else {{ str_replace(' ' , '-' , $product->title) }} @endif"
                                     src="{{ url('uploads/product/resize800/') }}/{{ $product->image }}"/></a>


                            <div id="gallery_01" style="margin: 26px 0px;">
                                <div id="owl-zoom" class="owl-carousel owl-theme">
                                    <div class="item">
                                        <a href="#" data-lightbox="image-2" data-title="My caption"
                                           data-image="{{ url('uploads/product/resize800/') }}/{{ $product->image }}"
                                           data-zoom-image="{{ url('uploads/product/source/') }}/{{ $product->image }}"
                                           class="mashe">
                                            <img id="zoom_03" @if($product->stock <= 0) style="filter: blur(4px);" @endif
                                            alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $product->title_ar) }} @else {{ str_replace(' ' , '-' , $product->title) }} @endif"
                                                 src="{{ url('uploads/product/resize200/') }}/{{ $product->image }}"/>
                                        </a>
                                    </div>
                                    @foreach($productimage as $imgpro)
                                        <div class="item">
                                            <a href="#" data-lightbox="image-{{ $imgpro->id }}" class="mashe"
                                               data-title="My caption"
                                               data-image="{{ url('uploads/product/resize800/') }}/{{ $imgpro->image }}"
                                               data-zoom-image="{{ url('uploads/product/source/') }}/{{ $imgpro->image }}">
                                                <img id="zoom_03" @if($product->stock <= 0) style="filter: blur(4px);"
                                                     @endif
                                                     alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $product->title_ar) }} @else {{ str_replace(' ' , '-' , $product->title) }} @endif"
                                                     src="{{ url('uploads/product/resize200/') }}/{{ $imgpro->image }}"/>
                                            </a>
                                        </div>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-4">
                        <div class="por-info ">
                            <div class="price">
                                @if($product->discount > 0)
                                    <span class="new-price"><h3><strong
                                                    class="price-change">{{ number_format(($product->price - $product->discount)* Session::get('currencychange'),2) }}</strong> <span
                                                    class="currency">{{ Session::get('currencysymbol') }}</span></h3></span>
                                    <span class="old-price">{{ number_format($product->price* Session::get('currencychange'),2) }}
                                        <span class="currency">{{ Session::get('currencysymbol') }}</span></span>
                                @else
                                    <span class="new-price"><h3><strong
                                                    class="price-change">{{ number_format($product->price* Session::get('currencychange'),2)}} </strong> <span
                                                    class="currency">{{ Session::get('currencysymbol') }}</span> </h3></span>
                                    <div class="old-price"></div>
                                @endif
                            </div>
                            <hr>
                            <div class="item-det">
                                <input type="hidden" value="{{ $product->id }}" class="productid"/>
                                <ul class="list-unstyled">
                                    @if($product->brand)
                                        <li><strong>{{ trans('site.brand') }} :</strong> {{ $product->brand->name }}
                                        </li>@endif
                                    @if($product->code)
                                        <li><strong>{{ trans('site.product_code') }} :</strong> {{ $product->code }}
                                        </li>@endif
                                    {{--                                    <li><strong>{{ trans('site.in_stock') }} :</strong>{{ $product->stock }}</li>--}}
                                    @if($product->weight)
                                        <li><strong>{{ trans('site.weight') }} :</strong> {{ $product->weight }}
                                        </li>@endif
                                    <hr class="producthr">
                                </ul>
                            </div>
                            @if(count($optionradio)>0 || count($optioncheck)>0)
                                <div class="pro-desc">
                                    <h4><strong>{{ trans('site.options') }} :</strong></h4>
                                    <hr>
                                    @if(count($optionradio)>0)
                                        <h6><strong>{{ trans('site.radio') }} :</strong></h6>
                                        @foreach($optionradio as $item)
                                            <div class="radio">
                                                <label><input type="radio" class="option op_radio" name="selectone"
                                                              value="{{ $item->id }}">{{ $item->option }}
                                                    ({{ number_format($item->price* Session::get('currencychange'),2) }} {{ Session::get('currencysymbol') }}
                                                    )</label>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(count($optioncheck)>0)
                                        <h6><strong>{{ trans('site.check') }} :</strong></h6>
                                        @foreach($optioncheck as $item)
                                            <div class="checkbox">
                                                <label><input type="checkbox" class="option op_check"
                                                              value="{{ $item->id }}">{{ $item->option }}
                                                    ({{ number_format($item->price* Session::get('currencychange'),2) }} {{ Session::get('currencysymbol') }}
                                                    )</label>
                                            </div>
                                        @endforeach
                                    @endif
                                    <hr>
                                </div>
                            @endif
                        </div>

                        @if($product->stock >= 1)
                            <div class="pro-info">
                                <label style="margin: 6px;"
                                       class="control-label text-decorop">{{ trans('site.quantity') }}</label>
                                <div class="input-append">
                                    <select name="quantity" id="quantity" style="width:25%;">
                                        @for($i=1;$i<=$product->stock;$i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>

                                </div>
                                {{--  <ul class="list-unstyled">
                                    <li>condtion : <span> New</span></li>
                                    <li>sold by : <a href="#">mohamed</a></li>
                                </ul>  --}}
                            </div>
                            <hr>
                            <div class="add"><strong>
                                    <button type="button" id="button-cart" data-toggle="modal"
                                            data-target="#myModalcart"
                                            data-count="1" data-id="{{ $product->id }}" data-loading-text="Loading..."
                                            class="btn btn-success btn-block mycartproduct">{{ trans('site.add_to_card') }}</button>
                                </strong></div>

                        @else
                            <div class="por-info ">
                                <div class="price">
                                    <span class="new-price">
                                            <h3>
                                                <strong>
                                                لقد نفذت الكمية
                                                </strong>
                                            </h3>
                                    </span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </section>


    @if ($product->stock >= 1)
        <section id="review">
            <div class="container">
                <div class="content">
                    <ul class="nav nav-tabs tab-description ">
                        <li class="active unstyledd"><a href="#tab-description" data-toggle="tab"
                                                        aria-expanded="true">{{trans('site.description')}}</a></li>

                        <li class="unstyledd"><a href="#tab-review" data-toggle="tab"
                                                 aria-expanded="false">{{ trans('site.reviews') }}
                                ({{ count($productreview) }})</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-description">
                            @if($lan == 'ar')
                                {!! $product->description !!}
                            @else
                                {!! $product->desc_en !!}
                            @endif
                        </div>
                        <div class="tab-pane" id="tab-review">
                            <form class="form-horizontal" id="form-review">
                                <div id="review">

                                    @if(count($productreview)>0)
                                        <div class="view-comment">
                                            <div class="" style="margin-bottom: 10px;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        @foreach($productreview as $item)
                                                            <div class="user_icon"><i class="fa fa-user fa-2x"></i>
                                                            </div>
                                                            <div class="name"><strong>{{ $item->User->name }}</strong>
                                                            </div>
                                                            <div class="date"
                                                                 style="color:rgba(136, 21, 163, 0.87)"> {{ date('l dS F Y',strtotime($item->created_at)) }} </div>
                                                            <div class="desc">
                                                                <p> {{ $item->text }}</p>
                                                            </div>
                                                            <hr>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>{{trans('site.no_reviews')}}</p>
                                    @endif
                                </div>
                                <h2 class="heading">{{trans('site.write_review')}}</h2>
                                @if(Auth::check())
                                    <div class="form-group required">
                                        <div class="col-xs-12">
                                            <label class="control-label">{{trans('site.your_name')}}</label>
                                            <input type="text" id="input-name" class="form-control namereview">
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <div class="col-xs-12">
                                            <label class="control-label">{{trans('site.your_review')}}</label>
                                            <textarea rows="5" id="input-review"
                                                      class="form-control textreview"></textarea>
                                        </div>
                                    </div>
                                <!--   <div class="form-group required">
                                <div class="col-xs-12">
{{--                                    <label class="control-label rat">{{trans('site.rating')}}</label>--}}
                                        &nbsp;&nbsp;&nbsp; Bad&nbsp;
                                    <input type="radio" value="1" class="ratingproduct" name="rating" >
                                    &nbsp;
                                    <input type="radio" value="2" class="ratingproduct" name="rating" >
                                    &nbsp;
                                    <input type="radio" value="3" class="ratingproduct" name="rating" >
                                    &nbsp;
                                    <input type="radio" value="4" class="ratingproduct" name="rating" >
                                    &nbsp;
                                    <input type="radio" value="5" class="ratingproduct" name="rating" >
                                    &nbsp;Good
                                </div>
                            </div> -->
                                    <div class="form-group msgreview">
                                    </div>


                                    <div class="row">
                                        <div class="col-md-1">
                                            <label class="control-label rat">{{trans('site.rating')}}</label>
                                        </div>
                                        <div class="col-md-11">
                                            <fieldset class="rate">
                                                <input id="rate1-star5" type="radio" name="rating" value="5"/>
                                                <label class="rat-star" for="rate1-star5" title="Excellent">5</label>

                                                <input id="rate1-star4" type="radio" name="rating" value="4"/>
                                                <label class="rat-star" for="rate1-star4" title="Good">4</label>

                                                <input id="rate1-star3" type="radio" name="rating" value="3"/>
                                                <label class="rat-star" for="rate1-star3" title="Satisfactory">3</label>

                                                <input id="rate1-star2" type="radio" name="rating" value="2"/>
                                                <label class="rat-star" for="rate1-star2" title="Bad">2</label>

                                                <input id="rate1-star1" type="radio" name="rating" value="1"/>
                                                <label class="rat-star" for="rate1-star1" title="Very bad">1</label>
                                            </fieldset>
                                        </div>
                                    </div>



                                    <div class="buttons clearfix">
                                        <div class="pull-right">
                                            <button type="button" id="button-review"
                                                    class="btn subreview btn-success">{{trans('site.continue')}}</button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    @if(count($views) > 0)

        <section id="product">

            <div class="container">
                <h3>{{ trans('site.usv') }}</h3>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-md-8 col-lg-12">
                        <div class="inner-pro">
                            <div class="row selected-product">

                                @foreach($views as $key => $item)
                                    @if($key === 0 || $key === 4)
                                        <div class="row">
                                            @endif
                                            <div class="col-md-3 col-sm-4 col-xs-6">
                                                <div class="card">
                                                    <div class="col-md-12">
                                                        <div class="pro-image">
                                                            <a @if($lan == 'ar') href="{{ url($item->link) }}"
                                                               @else href="{{ url($item->link_en) }}" @endif>
                                                                <img class="card-img-top img-responsive"
                                                                     src="{{ url('uploads/product/resize800') }}/{{ $item->image }}"
                                                                     alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $item->title_ar) }} @else {{ str_replace(' ' , '-' , $item->title) }} @endif"
                                                                     title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif"
                                                                     >
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <h4 class="card-title">
                                                            <a @if($lan == 'ar') href="{{ url($item->link) }}"
                                                               @else href="{{ url($item->link_en) }}" @endif>
                                                                @if($lan == 'ar')  {{ mb_substr($item->title_ar,0,40) }} @if(strlen($item->title_ar) >40)
                                                                    ... @endif @else  {{ mb_substr($item->title,0,40) }} @if(strlen($item->title) >40)
                                                                    ... @endif @endif
                                                            </a>
                                                        </h4>
                                                        @if($item->discount > 0)
                                                            <div class="new-price"><span
                                                                        class="currency"><strong> {{ number_format(($item->price - $item->discount)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span>
                                                            </div>
                                                            <div class="old-price">{{ number_format($item->price * Session::get('currencychange'),2) }}
                                                                <span class="currency">{{ Session::get('currencysymbol') }}</span>
                                                            </div>
                                                        @else
                                                            <div class="new-price"><span
                                                                        class="currency"><strong> {{ number_format(($item->price)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span>
                                                            </div>
                                                            <div class="old-price2"></div>
                                                        @endif
                                                        <a href="#" data-toggle="modal" data-target="#myModalcart"
                                                           class="mycart btn btn-success btn-block" data-count="1"
                                                           data-id="{{ $item->id }}"><i
                                                                    class="fa fa-shopping-cart"></i> {{ trans('site.add_to_card') }}
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                            @if($key === 3 || $key === 7)
                                        </div>
                                    @endif
                                @endforeach


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
@section('script')
    <script src="{{ URL::to('resources/assets/front/js/elevateZoom.min.js') }}"></script>
    <script src="{{ URL::to('resources/assets/front/js/star-rating.js') }}" type="text/javascript"></script>
    <script src="{{ URL::to('resources/assets/front/js/fancybox.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        function increase(id) {
            var x = parseInt(document.getElementById("quantity" + id).value);
            if ((x >= 1) && (x < parseInt('{{ $product->max_quantity }}'))) {
                x++;
                document.getElementById("quantity" + id).value = x;
            }

        }

        function decrease(id) {
            var x = parseInt(document.getElementById("quantity" + id).value);
            if ((x > 1) && (x <= parseInt('{{ $product->max_quantity }}'))) {
                x--;
                document.getElementById("quantity" + id).value = x;
            }
        }

    </script>
    <script>

        var idProduct = $('.productid').val();


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $.ajax({
            url: " {{url('getvisitproduct')}}",
            method: 'POST',
            data: {idProduct: idProduct},
            success: function (data) {

            }
        });


        $('.mashe').on('click', function () {

            var image = $(this).data('image');
            $('.xxx').removeAttr("href");
            $('.xxx').attr("href", image);


        });

        $('.registercourse').click(function () {
            $('.form-register').css('display', 'block');
        });
        $('.option').on('click', function () {

            var idOption = [];
            var idProduct = $('.productid').val();
            $('.option:checked').each(function (i) {
                idOption[i] = $(this).val();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url: " {{url('getoptionproduct')}}",
                method: 'POST',
                data: {idOption: idOption, idProduct: idProduct},
                success: function (data) {
                    var s = $('.price-change');
                    var ht = data;
                    s.html(ht);
                }
            });


        });
        $('.subreview').on('click', function () {


            var namereview = $('.namereview').val();
            var textreview = $('.textreview').val();
            var idProduct = $('.productid').val();
            var rating = $('input[name=rating]:checked').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            if (namereview != '' && textreview != '') {
                $.ajax({
                    url: " {{url('postreviewproduct')}}",
                    method: 'POST',
                    data: {namereview: namereview, textreview: textreview, idProduct: idProduct, rating: rating},
                    success: function (data) {
                        var s = $('.msgreview');
                        var ht = '';
                        ht += '<p>' + data + '</p>';
                        s.html(ht);
                        $('.namereview').val('');
                        $('.textreview').val('');
                        $('input[name=rating]:checked').attr('checked', false);
                    }
                });
            } else {
                alert('write something !!')
            }
        });

        $('.mycartproduct').on('click', function () {
            var id = $(this).data('id');
            var count = $("#quantity option:selected").val();
            var checked = [];
            $('.op_check:checked').each(function (i) {
                checked[i] = $(this).val();
            });
            var radio = [];
            $('.op_radio:checked').each(function (i) {
                radio[i] = $(this).val();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });


            if (count != 0 && count <= parseInt('{{ $product->stock }}')) {
                $.ajax({
                    url: " {{url('addcart')}}",
                    method: 'POST',
                    data: {id: id, count: count, checked: checked, radio: radio},
                    success: function (data) {
                        $('.reloadcart').html(data);
                        setTimeout(function () {
                            $('#myModalcart').modal('hide');
                        }, 5000);
                    }
                });
            }

        });


        var ambit = $(document);

        // Disable Cut + Copy + Paste (input)
        ambit.on('copy paste cut', function (e) {
            e.preventDefault(); //disable cut,copy,paste
            return false;
        });
       // document.addEventListener('contextmenu', event => event.preventDefault());
    </script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5a0c1f65a8957fed"></script>

    <script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "{{$product->title_ar}}",
  "image": "{{ url('uploads/product/resize800/') }}/{{ $product->image }}",
  "description": "@if($lan == 'ar'){{$product->short_description}}@else{{$product->short_desc_en}}@endif",
  "sku": "{{$product->code}}",
  "mpn": "925872",
  "brand": {
    "@type": "Thing",
    "name": "{{ $product->brand->name }}"
  },
  "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "@if(count($productreview) > 0){{ $productreview->sum('value')/ count($productreview) }}@else{{5}}@endif",
        "reviewCount": "@if(count($productreview) > 0){{count($productreview)}}@else{{1}}@endif"
  }
}


  {{--"offers": {--}}
    {{--"@type": "discount",--}}
    {{--"priceCurrency": "{{Session::get('currencysymbol')}}",--}}
    {{--"price": "{{$product->price}}",--}}
    {{--"priceValidUntil": "2020-11-05",--}}
    {{--"itemCondition": "http://schema.org/UsedCondition",--}}
    {{--"availability": "http://schema.org/InStock",--}}
    {{--"seller": {--}}
      {{--"@type": "Organization",--}}
      {{--"name": "Baby Mumz"--}}
    {{--}--}}
  {{--}--}}
    </script>

@endsection
