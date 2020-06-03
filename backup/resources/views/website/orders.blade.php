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
                            <li><a @if($status == 0) class="active" @endif href="{{ url('my-orders/0') }}">{{ trans('home.incompleted_orders') }}</a></li>
                            <li><a @if($status == 1) class="active" @endif href="{{ url('my-orders/1') }}">{{ trans('home.completed_orders') }}</a></li>
                            @if(Auth::user()->delivery) 
                            <li><a href="{{ url('my-deliveries') }}">{{ trans('site.order_deliveries') }}</a></li>
                            @endif
                            <li><a href="{{ url('my-favorite') }}">{{ trans('site.wish_list') }}</a></li>
                            <li><a href="{{ url('change-account') }}">{{ trans('site.account') }}</a></li>
                            <li><a href="{{ url('my-address') }}">{{ trans('site.myaddress') }}</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
            <div class="col-xs-12 col-md-9">
            <div class="order">
                <h3>{{trans('site.your_order')}}</h3>
                
                @foreach($order as $key => $oo)
                <fieldset style="margin-bottom: 7px; ">
                    <div class="order-details">
                        <div class="row order-info nomargin">
                            <div class="col-xs-12 col-md-4">
                                <div class="or-date"> <strong> {{trans('site.order_date')}} </strong> :<span class="result"> {{ $oo->created_at }} </span></div>
                                <div class="or-id"> <strong> {{trans('site.order_number')}} </strong> :<span class="result"> {{ $oo->number }} </span></div>
                                <div class="total"> <strong> {{trans('site.total')}} </strong> :<span class="result"> {{ $oo->total }} </span></div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                            	<div class="or-id"> <strong> {{trans('site.payment_price')}} </strong> :<span class="result"> {{ $oo->payment}} </span></div>
                           	<div class="or-id"> <strong> {{trans('site.shipping_price')}} </strong> :<span class="result"> {{ $oo->shipping}} </span></div>
                            </div>
                             <div class="col-xs-12 col-md-4">
                               <div class="or-id status_{{ $oo->number }} "> <strong> {{trans('site.order_status')}} </strong> :<span class="result stat-res"> {{ $oo->name }} </span></div>
                                <div class="or-id"> <strong> {{trans('site.order_address')}} </strong> :<span class="result"> {{ $oo->address }} </span></div>
                             </div>
                            <div class="col-xs-12 ">
                                @if($oo->status_id == 6)
                                <button class="btn btn-info pull-right pendingorder" data-number="{{ $oo->number }}">{{trans('site.refunded_order')}}</button>
                                @endif
                                @if($oo->status_id == 1)
                                <button class="btn btn-danger pull-right cancelorder" data-number="{{ $oo->number }}">{{trans('site.cancel_order')}}</button>
                                @endif
                            </div>
                        </div>
                        @foreach($orders as $k =>$o)
                            @if($k == $oo->number)
                                @foreach($o as $item)
                                @if($item->Product)
                                    
                                    <div class="row single-order nomargin">
                                        <div class="col-xs-offset-4 col-xs-4 col-md-offset-0 col-md-2"><div class="img"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif><img class="img-responsive" src="{{ url('uploads/product/resize800') }}/{{ $item->Product->image }}" alt="..."></a></div></div>
                                        <div class="col-xs-12 col-md-9">
                                            <div class="pr-name"><h4><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>@if($lan == 'ar') {{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif</a></h4></div>
                                            <div class="row  nomargin">
                                            	<div class="col-xs-12 col-md-4 nopadding">
                                            		<div class="seller"><h5> <strong> {{trans('site.brand')}} </strong> : {{ $item->Product->brand->name }}</h5></div>
                                            		<div class="seller"><h5> <strong> {{trans('site.quantity')}} </strong> : {{ $item->quantity }}</h5></div>
                                            		<div class="seller"><h5> <strong> {{trans('site.unit_price')}} </strong> : {{ $item->one_price }}  {{ $item->Product->Currency->symbol }}</h5></div>
                                            	</div>
                                            	<div class="col-xs-12 col-md-4">
                                            		 <div class="seller"><h5> <strong> {{trans('site.option_price')}} </strong> : {{ $item->option_price }} {{ $item->Product->Currency->symbol }}</h5></div>
                                            		 <div class="seller"><h5> <strong> {{trans('site.total')}}  </strong> : {{ $item->total_price }} {{ $item->Product->Currency->symbol }}</h5></div>
                                            	</div>
                                            	<div class="col-xs-12 col-md-4">
                                            <div class="con">
                                            
                                                @foreach($optionproduct as $item2)
                                                    @if(in_array($item2->id, explode(',',$item->option_ids)))
                                                        <h5> <strong> {{ $item2->type }} </strong> : {{ $item2->option }}</h5>
                                                    @endif
                                                @endforeach
                                            </div>
                                            	</div>
                                            </div>
                                            
                                           

                                            <!-- <div>
                                                <button class="btn btn-danger pull-right deleteproduct" data-id="{{ $item->id }}">{{trans('site.delete_product')}}</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    @endif
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

<div class="modal fade" id="refunded_order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('site.refunded_order') }}</h4>
      </div>
      {!! Form::open(['url' => 'refunded_order', 'data-toggle'=>'validator', 'files'=>'true']) !!}
      <div class="modal-body">
      	<div class="form-group">
      		<label for="reason">{{trans('site.reason')}} :</label>
    		<input type="text" class="form-control" placeholder="{{trans('site.reason')}}" name="reason" required >
        </div>
        <div class="form-group">
    		<label for="photo">{{trans('home.photo')}} :</label>
    		<input type="file" class="form-control" name="photo" id="photo" accept="image/*" >
        </div>
        <div class="form-group">
      		<label for="comment">{{trans('site.comment')}} :</label>
      		<textarea class="form-control " name="comment" id="comment" ></textarea>
       </div>
	<input type="hidden" name="order_num" id="order_num" value="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('site.cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ trans('home.submit') }}</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

@endsection
@section('script')
<script>

$('.cancelorder').on('click', function(){ 
	if(confirm('{{ trans('site.cancelordermsg') }}')){  
            var number = $(this).data('number');
            var btnorder = $(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:" {{url('cancelorder')}}",
                method:'POST',
                data:{number:number},
                success:function(data)
                {
                    btnorder.parent().parent().parent().parent().remove();
                    //btnorder.removeClass('btn-danger cancelorder');
                    //btnorder.addClass('btn-info pendingorder');
                    //btnorder.text("{{trans('site.pending_order')}}");
                    //$('.status_'+number).html("{{trans('site.order_status')}} : "+ data);
                }
            });
       }else{
       
       }
});

    

$('.pendingorder').on('click', function(){
	
        var number = $(this).data('number');
        var btnorder = $(this);
        $("#order_num").val(number);
        $("#refunded_order").modal();
            /*$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:" {{url('pendingorder')}}",
                method:'POST',
                data:{number:number},
                success:function(data)
                {
                    //location.reload();btn btn-danger
                    btnorder.removeClass('btn-info pendingorder');
                    btnorder.addClass('btn-danger cancelorder');
                    btnorder.text("{{trans('site.cancel_order')}}");
                    $('.status_'+number).html("{{trans('site.order_status')}} : "+ data);
                }
            });*/

});
$('.deleteproduct').on('click', function(){
        var id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:" {{url('deleteorderproduct')}}",
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