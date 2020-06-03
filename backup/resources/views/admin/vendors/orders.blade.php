@extends('layouts.admin')

@section('content')

<ul class="nav nav-tabs">
    <li><a href="{{ route('vendors.edit', $vendor->id) }}">{{trans('home.vendordata')}}</a></li>
    <li  class="active"><a href="{{ route('vendor_orders',['id' => $vendor->id]) }}">{{trans('home.orders')}}</a></li>
    <li><a href="{{ route('vendor_drags',['id' => $vendor->id]) }}">{{trans('home.withdrows')}}</a></li>
  </ul><br><br>

  
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          
          <th>{{trans('home.number')}}</th>
          <th>{{trans('home.status')}}</th>
          <th>{{trans('home.total')}}</th>
          <th>{{trans('home.date')}}</th>
          
        </thead>
        <tbody>
          @php   
	     for($i = 0; $i < count($order_date); $i++){
	  @endphp
              <tr>
                
                <td>{{ $orders[$i] -> number }}</td>
                <td>{{ App\OrderStatus::find($orders[$i]->status_id) -> name}}</td>
                <td>{{ $orders[$i] -> total }}</td>
                <td>{{ $order_date[$i] }}</td>
              </tr>
         @php
	 }
	 @endphp
        </tbody>
      </table>
   
    

@endsection