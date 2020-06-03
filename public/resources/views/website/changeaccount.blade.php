@extends('layouts.app')
@section('content')


 <section id="shipping-info">
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
                                    <li><a class="active" href="{{ url('change-account') }}">{{trans('site.account')}}</a></li>
                                    <li><a href="{{ url('my-address') }}">{{ trans('site.myaddress') }}</a></li>
                                </ul>
                            </div>
                       </aside>
                   </div>
                   <div class="col-xs-12 col-md-8">
                    <div class="edit-info">
                        <h3>{{trans('site.edit_account')}}</h3>
                        <div class="shipping-address">
                            <div class="E-info">
                               {!! Form::open(['method'=>'POST','url'=>'account/'.$account->id]) !!}
                                 <div class="form-group">
                                    <label for="name">  {{ trans('site.name') }}  </label>
                                    <input type="text" placeholder="{{ trans('site.name') }}" name="acc_name" value="{{ Auth::user()->name }}" class="form-control" required id="name">
                                  </div>
                                  <div class="form-group">
                                    <label for="email"> {{ trans('site.email') }}  </label>
                                    <input type="email" class="form-control" name="acc_email" value="{{ Auth::user()->email }}" placeholder="{{ trans('site.email') }}" required >
                                  </div>
                                  <div class="form-group">
                                    <label for="phone">  {{ trans('site.phone') }}  </label>
                                    <input type="text" name="acc_phone" value="{{ Auth::user()->phone }}" pattern="[0-9]{9,15}"  placeholder="{{ trans('site.phone') }}" required class="form-control" >
                                  </div>

                                   <div class="form-group">
                                    <label for="address">  {{ trans('site.address') }}  </label>
                                    <input type="text" name="acc_address" value="{{ Auth::user()->address }}"  placeholder="{{ trans('site.address') }}" required class="form-control" >
                                  </div>

                                  <div class="form-group">
                                    <label for="name">  {{ trans('site.password') }}  </label>
                                    <input type="password" name="acc_password"   placeholder="{{ trans('site.password') }}" class="form-control" id="password">
                                  </div>

                                  <button type="submit" class="btn btn-info "><strong>{{ trans('site.submit') }}</strong></button>
                            </form>
                            </div>
                        </div>
                    </div>
                   </div>
                   <div></div>
               </div>
           </div>
        </section>
@endsection
            