@extends('layouts.app')
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
                                    <li><a href="{{ url('my-orders/0') }}"> <i class="fa fa-angle-left" aria-hidden="true"></i> {{ trans('home.incompleted_orders') }}</a></li>
                                    <li><a href="{{ url('my-orders/1') }}"> <i class="fa fa-angle-left" aria-hidden="true"></i> {{ trans('home.completed_orders') }}</a></li>
                                    @if(Auth::user()->delivery) 
	                                <li><a href="{{ url('my-deliveries') }}"> <i class="fa fa-angle-left" aria-hidden="true"></i> {{trans('site.order_deliveries')}}</a></li>
	                            @endif
                                    <li><a class="active" href="{{ url('my-favorite') }}"> <i class="fa fa-angle-left" aria-hidden="true"></i> {{trans('site.wish_list')}}</a></li>
                                    <li><a href="{{ url('change-account') }}"> <i class="fa fa-angle-left" aria-hidden="true"></i>{{trans('site.account')}}</a></li>
                                    <li><a href="{{ url('my-address') }}"> <i class="fa fa-angle-left" aria-hidden="true"></i> {{ trans('site.myaddress') }}</a></li>
                                </ul>
                            </div>
                       </aside>
                   </div>
                   <div class="col-xs-12 col-md-9">
                    <div class="order">
                        <h3>Wish List</h3>
                        <div class="order-details">
                            
                            @if(count($favorite)>0)
                            @foreach($favorite as $item)
                            <div class="row">
                                <div class="col-xs-3 col-md-3"><div class="img"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif><img class="img-responsive" src="{{ url('uploads/product/resize800') }}/{{ $item->Product->image }}" alt="..."></a></div></div>
                                <div class="col-xs-9 col-md-6 product-top">
                                    <div class="pr-name"><h4><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>@if($lan == 'ar'){{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif</a></h4></div>
                                    <div class="con"><h4>{{ trans('site.brand') }} : {{ $item->Product->brand->name }}</h4></div>
                                    {{--<div class="con"><h4>{{ trans('site.product_stock') }} : @if($item->Product->stock>0) In Stock @endif</h4></div>--}}
                                    <div class="con"><h4>{{ trans('site.unit_price') }} : @if($item->Product->discount) {{ $item->Product->price - $item->Product->discount }}  @else {{ $item->Product->price }} @endif  {{ $item->Product->Currency->symbol }}</h4></div>
                                  
                                </div>
                                  <div class="col-md-3 col-xs-6 text-center">
                                        <button id="mmycartBTN" type="button" data-toggle="modal" data-target="#myModalcart" title="" class="pull-right btn btn-primary mycart" data-count="1" data-id="{{ $item->Product->id }}" data-original-title="Add to Cart"><i class="fa fa-shopping-cart"></i></button>
                                        <a  data-toggle="tooltip" title="" class="pull-right btn btn-danger removefav" data-id="{{ $item->Product->id }}" data-original-title="Remove"><i class="fa fa-times"></i></a>
                                    </div>
                            </div>
                            <hr>
                            @endforeach
                            
                            @else
                            <p>The Wish List is empty !!</p>
                            @endif
                        </div>
                    </div>
                   </div>
                   <div></div>
               </div>
           </div>
        </section>


@endsection
@section('script')
<script>
$('.removefav').on('click', function(){
    var id =  $(this).data('id');
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('removefavorite')}}",
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