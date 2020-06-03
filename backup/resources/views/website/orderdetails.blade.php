@extends('layouts.app')
@section('content')
<div id="account-content">
      <h1>{{ trans('site.order') }}</h1>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-center">Image</td>
                <td class="text-left">Product Name</td>
                <td class="text-left">Model</td>
                <td class="text-left">Quantity</td>
                <td class="text-right">Unit Price</td>
                <td class="text-right">Option Price</td>
                <td class="text-right">Shipping Price</td>
                <td class="text-right">Total</td>
              </tr>
            </thead>
            <tbody id="cartoncart">
            @foreach($order as $item)
            <tr class="tr_{{ $item->id }}">
            <td class="text-center"> <a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif><img src="{{ url('uploads/product/resize200') }}/{{ $item->Product->image }}" alt="{{ $item->Product->title }}" ></a> </td>
            <td class="text-left"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>@if($lan == 'ar') {{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif</a><br>
                        
                @foreach($optionproduct as $item2)
                    @if(in_array($item2->id, explode(',',$item->option_ids)))
                        <small>{{ $item2->type }}: {{ $item2->option }}</small><br>
                    @endif
                @endforeach
            </td>
            <td class="text-left">{{ $item->Product->brand }}</td>
            <td class="text-left">
               <div>{{ $item->quantity }} </div>
                @if($item->coupon_price)
                <div>{{ trans('site.coupon_price') }} :<span>{{ $item->coupon_price }}</span> </div>
                @endif
             </td>
            <td class="text-right"> {{ $item->one_price }} {{ $item->Product->Currency->symbol }}</td>
            <td class="text-right"> {{ $item->option_price }} {{ $item->Product->Currency->symbol }}</td>
            <td class="text-right"> {{ $item->shipping_price }} {{ $item->Product->Currency->symbol }}</td>
            <td class="text-right"> {{ $item->total_price }} {{ $item->Product->Currency->symbol }}</td>
            </tr>
            @endforeach 
            
            </tbody>  
          </table>
        </div>
        <div>
        <p>{{ trans('site.ordernumber') }} : {{ $order[0]->number }}</p>
        <p>{{ trans('site.date') }} : {{ $order[0]->created_at }}</p>
        <p>{{ trans('site.name') }} : {{ $order[0]->User->name }}</p>
        <p>{{ trans('site.address') }} : {{ $order[0]->User->address }}</p>
        <p>{{ trans('site.phone') }} : {{ $order[0]->User->phone }}</p>
        <p>{{ trans('site.email') }} : {{ $order[0]->User->email }}</p>
        </div>

    
    <div class="buttons clearfix">
    <div class="pull-left"><a class="btn btn-primary print">{{ trans('home.print') }}</a></div>
        <div class="pull-left"><a href="{{ url('my-orders') }}" class="btn btn-primary">All Orders</a></div>
    </div>
</div>

@endsection
@section('script')
<script src="{{ URL::to('resources/assets/back/js/jquery.PrintArea.js') }}"></script>
<script>
$(".print").click(function(){
              var mode = 'iframe'; // popup
              var close = mode == "popup";
              var options = { mode : mode, popClose : close};
              $("#account-content").printArea( options );
          });
          </script>
@endsection 