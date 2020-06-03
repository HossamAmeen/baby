@extends('layouts.app')
@section('title')
<title>{{ $con->name }}</title>
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
	                                <li><a href="{{ url('my-deliveries') }}">{{trans('site.order_deliveries')}}</a></li>
	                            @endif
                                    <li><a href="{{ url('my-favorite') }}">{{trans('site.wish_list')}}</a></li>
                                    <li><a href="{{ url('change-account') }}">{{trans('site.account')}}</a></li>
                                </ul>
                            </div>
                       </aside>
                   </div>
                   <div class="col-xs-12 col-md-9">
		      <div class="pay-method2">
		        <div class="inner-pay">
		            <div class="row">
		                <div class="col-xs-12">
		                        <div class=" text-center">
		                        <h2 style="color:#000">{{ trans('site.ofm') }}</h2>
		                            <p>{{ trans('site.order_number') }} :<span>{{ $order[0]->number }}</span></p>
		                        </div>
		                </div>
		
		            </div>
		        </div>
		     </div>
		    </div>
		 </div>
           </div>
        </section>
@endsection