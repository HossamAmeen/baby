@extends('layouts.app')
@section('title')
    <title>{{ $con->name }}</title>
@endsection
@section('meta')
    <meta name="keywords" content="">
    <meta name="description" content="">
@endsection
@section('content')

    <section id="main-slider">
        <div class="container">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    @foreach($slider as $k => $s)
                        <li data-target="#carousel-example-generic" data-slide-to="{{ $k }}" @if($k == 0) class="active" @endif></li>
                    @endforeach
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    @foreach($slider as $k => $s)
                        <div class="item @if($k == 0) active @endif">
                            @if($s->link)
                                <a href="{{ url($s->link) }}" ><img src="{{ url('uploads/slideshow/resize1200') }}/{{ $s->image }}" alt=""></a>
                            @else
                                <img src="{{ url('uploads/slideshow/resize1200') }}/{{ $s->image }}" alt="">
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">{{ trans('site.previous') }}</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next{{ trans('site.next') }}</span>
                </a>
            </div>
        </div>
    </section>
    @if(count($deals)>0)
        <section class="deals">
            <div class="container">
                <div class="title">

                    <h3 class="purpel">{{ trans('site.deals') }}</h3>
                    <hr>
                    {{--  <h4><a href="#">View all </a> <i class="fa fa-angle-right" aria-hidden="true"></i></h4>  --}}
                </div>
                <div id="carousal">
                    <div id="owl-sp" class="owl-carousel owl-theme">
                        @foreach($deals as $fea)
                            <div class="item product  text-center">
                                <a href="{{ url($fea->link) }}" ><div class="image"><img class="" src="{{ url('uploads/product/resize800') }}/{{ $fea->image }}" alt="{{ $fea->title }}"></div></a>
                                <div class="desc">
                                    <div class="proudct-name"><h5><a href="{{ url($fea->link) }}">@if($lan == 'ar')  {{ mb_substr($fea->title_ar,0,40) }} @if(strlen($fea->title_ar) >40) ... @endif @else  {{ mb_substr($fea->title,0,40) }} @if(strlen($fea->title) >40) ... @endif @endif</a></h5></div>
                                    @if($fea->discount > 0)
                                        <div class="new-price"><span class="currency"><strong> {{ ($fea->price - $fea->discount)* Session::get('currencychange') }}</strong>{{ Session::get('currencysymbol') }}</span></div>
                                        <div class="old-price">{{ $fea->price * Session::get('currencychange') }} <span class="currency">{{ Session::get('currencysymbol') }}</span></div>
                                    @else
                                        <div class="new-price"><span class="currency"><strong> {{ ($fea->price)* Session::get('currencychange') }}</strong>{{ Session::get('currencysymbol') }}</span></div>
                                        <div class="old-price"></div>
                                    @endif

                                </div>
                                <div class="add">
                                    <button type="button" data-toggle="modal" data-target="#myModalcart" class="mycart btn btn-success btn-block" data-count="1" data-id="{{ $fea->id }}" ><i class="fa fa-shopping-cart"></i> {{ trans('site.add_to_card') }}</button>
                                </div>

                                @if(Auth::user() && Auth::user()->isFavorite($fea->id))
                                    <div class="wish">
                                        <button data-id="{{ $fea->id }}" class="wishbtn" ><i class="fa fa-heart fa-2x" aria-hidden="true"></i></button>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>
    @endif
    <section class="selected-product ">
        <input type="hidden" name="step" id="step" value="1" />
        <div class="container">
            <div class="row nomargin " id="dispaly_products">

                <div class="title purpel"><h3>{{ trans('site.products') }} </h3></div>
                <hr>
                @foreach($producthome as $kitem => $item)
                    <div  class="col-xs-6 col-sm-4 col-md-4 col-lg-3 productload" >
                        <div class="product text-center">
                            <a href="{{ url($item->link) }}" ><div class="image"><img src="{{ url('uploads/product/resize800') }}/{{ $item->image }}" alt="{{ $item->title }}" title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif"></div></a>
                            <div class="proudct-name"><p><a href="{{ url($item->link) }}" title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif">
                                        @if($lan == 'ar')  {{ mb_substr($item->title_ar,0,40) }} @if(strlen($item->title_ar) >40) ... @endif @else  {{ mb_substr($item->title,0,40) }} @if(strlen($item->title) >40) ... @endif @endif</a></p></div>
                            @if($item->discount > 0)
                                <div class="new-price"><span class="currency"><strong> {{ ($item->price - $item->discount)* Session::get('currencychange') }}</strong>{{ Session::get('currencysymbol') }}</span></div>
                                <div class="old-price">{{ $item->price * Session::get('currencychange') }} <span class="currency">{{ Session::get('currencysymbol') }}</span></div>
                            @else
                                <div class="new-price"><span class="currency"><strong> {{ ($item->price)* Session::get('currencychange') }}</strong>{{ Session::get('currencysymbol') }}</span></div>
                                <div class="old-price"></div>
                            @endif
                            <div class="add">
                                <button type="button" data-toggle="modal" data-target="#myModalcart" class="mycart btn btn-success btn-block" data-count="1" data-id="{{ $item->id }}" ><i class="fa fa-shopping-cart"></i> {{ trans('site.add_to_card') }}</button>
                            </div>

                            @if(Auth::user() && Auth::user()->isFavorite($item->id))
                                <div class="wish">
                                    <button data-toggle="modal" data-target="#myModalfav" data-id="{{ $item->id }}" class="wishbtn" ><i class="fa fa-heart fa-2x" aria-hidden="true"></i></button>
                                </div>@endif
                        </div>
                    </div>
                    <input type="hidden" class="idproduct" value="{{ $item->id }}" />
                @endforeach
            </div>

            <div class="col-xs-12 col-md-12 text-center" id="load_more_img">
                <img src="{{ url('resources/assets/front/img/circle-loading-gif.gif') }}" />
            </div>

            <div class="col-xs-12 col-md-12 text-center removemore" id="load_more_div">
                <button class="btn btn-deafult select  more">{{ trans('site.load_more') }}</button>
            </div>
            <hr class="removemore">
        </div>

    </section>

    <section class="sponsor ">
        <div class="container">
            <hr>
            <div class="row">
                <div class="title text-center purpel"><h4>{{ trans('site.widest_brands') }}</h4></div>
            </div>
            <div class="col-xs-12 col-md-12 text-center">
                <div id="carousal">
                    <div id="owl-sp2" class="owl-carousel owl-theme">
                        @foreach ($brands as $brand)
                            @if($brand -> image != '')
                                <div class="item text-center">
                                    <div class="image">
                                        <a href="{{ route('brandproducts',['id' => $brand->id,'name' => $brand->name]) }}">
                                            <img class="" src="{{ url('uploads/brands/source') }}/{{ $brand->image }}" alt="{{ $brand->name }}" />
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@section('script')
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    {{--  <script src="{{ URL::to('resources/assets/front/js/demos.js')}}"></script>  --}}
    <script>
        var stop = 1;
        $('#load_more_div').hide();
        $(window).scroll(function() {
            var step = parseInt($('#step').val());
            if(($(window).scrollTop() + $(window).height()) > ($(document).height() - 400) && step <= 3 && stop) {
                stop = 0;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:" {{url('load_more/products')}}",
                    method:'POST',
                    data:{step:step},
                    success:function(data)
                    {

                        if(data == ""){
                            //step +=1;
                            $('#step').val(4);
                            $('#dispaly_products').append('<p>No More Posts</>');
                        }
                        else{
                            step += 1;
                            if(step > 3){
                                $('#load_more_img').html('');
                                $('#load_more_div').show();

                            }
                            $('#step').val(step);
                            $('#dispaly_products').append(data);
                            stop = 1;
                        }

                    }
                });
            }
        });


        $('.more').on('click', function(){
            var id =[];
            $('.idproduct').each(function(i){
                id[i] = $(this).val();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:" {{url('getmore')}}",
                method:'POST',
                data:{id:id},
                success:function(data)
                {
                    var html = '';
                    for(var j=0;j<data[0].length;j++){

                        html += '<div class="col-xs-6 col-md-4 col-lg-3 productload">';


                        html +='<div class="product text-center">';
                        html += '<a href="{{ url('') }}/'+ data[0][j].link +'" ><div class="image"><img src="{{ url('uploads/product/resize800') }}/'+data[0][j].image+'" alt="'+data[0][j].title +'"></div></a>';
                        html += '<div class="proudct-name"><p><a href="{{ url('') }}/'+ data[0][j].link +'">';
                        if(data[1] == 'ar'){
                            html +=    data[0][j].title_ar.substr(0, 40); if(data[0][j].title_ar.length > 40 )  { html += '...' };
                        }else{
                            html +=  data[0][j].title.substr(0, 40); if(data[0][j].title.length > 40 )  { html += '...' };
                        }
                        html += '</a></p></div>';
                        if(data[0][j].discount != 0.00){
                            html += '<div class="new-price">'+ (( data[0][j].price - data[0][j].discount ))*{{ Session::get('currencychange') }}+" {{ Session::get('currencysymbol') }}</div>";
                            html += '<div class="old-price">'+ (data[0][j].price)*{{ Session::get('currencychange') }} +'</div>';
                        }else{
                            html += '<div class="new-price">'+ ( data[0][j].price )*{{ Session::get('currencychange') }}+" {{ Session::get('currencysymbol') }}</div><div class='old-price'></div>";
                        }
                        html += '<div class="add"><button type="button" data-toggle="modal" data-target="#myModalcart" class="mycart btn btn-success btn-block" data-count="1" data-id="'+ data[0][j].id +'" ><i class="fa fa-shopping-cart"></i>{{ trans('site.add_to_card') }}</button></div>';
                        @if(Auth::user() && Auth::user()->isFavorite($item->id))
                            html +='<div class="wish"><button data-toggle="modal" data-target="#myModalfav" data-id="'+data[0][j].id+'" class="wishbtn" ><i class="fa fa-heart fa-2x" aria-hidden="true"></i></button></div>'
                        @endif
                            html += '</div></div><input type="hidden" class="idproduct" value="'+ data[0][j].id +'" />';
                        /*  $('')
    .insertAfter($('.productload').last());    */

                    }
                    $('.productload:last').after(html);
                    if(data[2] == 0){
                        $('.removemore').remove()
                    }
                }
            });


        });
        //Auto();


        /* function Auto(){
                var options = {

                        url: function(phrase) {
                             return "{{url('/')}}/search-home/autocomplete";
                 },

                getValue: function(element) {
                    return element.value;
                    },

                    ajaxSettings: {
                    dataType: "json",
                    method: "GET",
                    data: {
                         dataType: "json"
                        }
                  },

                preparePostData: function(data) {
                    data.phrase = $(".q").val();
                    return data;
                    },

                requestDelay: 400

                };

                $(".q").easyAutocomplete(options);
}  */



    </script>
@endsection