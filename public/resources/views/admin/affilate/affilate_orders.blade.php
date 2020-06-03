@extends('layouts.admin')

@section('content')

<ul class="nav nav-tabs">
    <li><a href="{{ route('affilates.edit', $affilate ->id) }}">{{trans('home.affilatedata')}}</a></li>
    <li class="active"><a href="{{ route('affilate_orders',['id' => $affilate ->id]) }}">{{trans('home.orders')}}</a></li>
    <li><a href="{{ route('affilate_drags',['id' => $affilate ->id]) }}">{{trans('home.withdrows')}}</a></li>
    <li><a href="{{ route('affilate_products',['id' => $affilate ->id]) }}">{{trans('home.products')}}</a></li>
    <li><a href="{{ route('affilate_categories',['id' => $affilate ->id]) }}">{{trans('home.categories')}}</a></li>
  </ul><br><br>

  
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          
          <th>{{trans('home.number')}}</th>
          <th>{{trans('home.status')}}</th>
          <th>{{trans('site.commission')}}</th>
          <th>{{trans('home.date')}}</th>
          
        </thead>
        <tbody>
        
          @php   
          
	     foreach($orders as $order){
	  @endphp
              <tr>
                
                <td>{{ $order -> order -> number }}</td>
                <td>{{ App\OrderStatus::find($order -> order ->status_id) -> name}}</td>
                <td>{{ $order -> commission}}</td>
                <td>{{ $order -> date }}</td>
              </tr>
         @php
         
	 }
	 @endphp
        </tbody>
      </table>
   
    

@endsection