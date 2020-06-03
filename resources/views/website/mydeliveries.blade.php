@extends('layouts.app')
@section('title')
<title>{{ trans('site.your_deliveries') }} </title>
    <style>
        .order-details{
            padding: 20px;
        }
        button.btn.btn-info.pull-right.changeordertoshipped {
            margin-right: 5px;
            margin-left: 5px;
        }
    </style>
@endsection
@section('content')

<section id="account">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3">
                <aside>
                    <div class="acc-info">
                        <div class="icon"></div>
                        <div class="name"><h4>{{ Auth::user()->name }}</h4></div>
                        <div class="e-mail"><p>{{ Auth::user()->email }}</p></div>
                    </div>
                    <div class="info-bar">
                        <ul class="list-unstyled">
                            <li><a href="{{ url('my-orders/0') }}">{{ trans('home.incompleted_orders') }}</a></li>
                            <li><a href="{{ url('my-orders/1') }}">{{ trans('home.completed_orders') }}</a></li>
                            @if(Auth::user()->delivery) 
                            <li><a class="active" href="{{ url('my-deliveries') }}">{{trans('site.order_deliveries')}}</a></li>
                            @endif
                            <li><a href="{{ url('my-favorite') }}">{{trans('site.wish_list')}}</a></li>
                            <li><a href="{{ url('change-account') }}">{{trans('site.account')}}</a></li>
                            <li><a href="{{ url('my-address') }}">{{ trans('site.myaddress') }}</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
            <div class="col-xs-12 col-md-9">
            <div class="order">
                <h3>{{ trans('site.your_deliveries') }}</h3>
                @foreach($order as $key => $oo)
                <fieldset style="margin-bottom: 7px;">
                    <div class="order-details">
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="or-date">{{trans('site.order_date')}} : {{ $oo->created_at }}</div>
                                <div class="or-id">{{trans('site.order_number')}} : {{ $oo->number }}</div>
                                <div class="or-id status_{{ $oo->number }}">{{trans('site.order_status')}} : {{ $oo->name }}</div>
                                <div class="or-id">{{trans('site.order_address')}} : {{ $oo->address }}</div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="total"><strong>{{trans('site.total')}} : {{ $oo->total }}</strong></div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <button class="btn btn-info pull-right changeordertoshipped" data-id="{{ $oo->number }}">{{ trans('site.shipped') }}</button>
			        <button class="btn btn-info pull-right changeordertodelivered" data-id="{{ $oo->number }}">{{ trans('site.delivered') }}</button>
                            </div>
                        </div>
                        @foreach($orders as $k =>$o)
                            @if($k == $oo->number)
                                @foreach($o as $item)
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-3"><div class="img"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif><img class="img-responsive" src="{{ url('uploads/product/resize800') }}/{{ $item->Product->image }}" alt="..."></a></div></div>
                                        <div class="col-xs-12 col-md-9">
                                            <div class="pr-name"><h4><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>@if($lan == 'ar') {{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif</a></h4></div>
                                            <div class="seller"><h4>{{trans('site.brand')}} : {{ $item->Product->brand->name }}</h4></div>
                                            <div class="seller"><h4>{{trans('site.quantity')}} : {{ $item->quantity }}</h4></div>
                                            <div class="con">
                                                @foreach($optionproduct as $item2)
                                                    @if(in_array($item2->id, explode(',',$item->option_ids)))
                                                        <h4>{{ $item2->type }}: {{ $item2->option }}</h4>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="seller"><h4> {{trans('site.unit_price')}} : {{ $item->one_price }}  {{ $item->Product->Currency->symbol }}</h4></div>
                                            <div class="seller"><h4> {{trans('site.option_price')}} : {{ $item->option_price }} {{ $item->Product->Currency->symbol }}</h4></div>
                                            <div class="seller"><h4> {{trans('site.payment_price')}} : {{ $item->payment_price }} {{ $item->Product->Currency->symbol }}</h4></div>
                                            <div class="seller"><h4> {{trans('site.shipping_price')}} : {{ $item->shipping_price }} {{ $item->Product->Currency->symbol }}</h4></div>
                                            <div class="seller"><h4> {{trans('site.total')}} : {{ $item->total_price }} {{ $item->Product->Currency->symbol }}</h4></div>
                                            <div class="status"><h3></h3></div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </fieldset>
                @endforeach
            </div>
            </div>
            <div></div>
        </div>
    </div>
</section>


@endsection
@section('script')
<script>
$('.changeordertoshipped').on('click', function(){
    var id =  $(this).data('id');
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('changeordertoshipped')}}",
              method:'POST',
              data:{id:id},
              success:function(data)
              {
                  location.reload();
              }
         });
});
$('.changeordertodelivered').on('click', function(){
    var id =  $(this).data('id');
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('changeordertodelivered')}}",
              method:'POST',
              data:{id:id},
              success:function(data)
              {
                  location.reload();
              }
         });
});

</script> 
@endsection 