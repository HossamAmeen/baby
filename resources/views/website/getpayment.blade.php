<div class="pay-method">
    <h3>{{trans('site.payment')}}</h3>
    <hr>
    <form class="bs-example" method="POST" action="{{ route('getCheckout') }}">
       {{ csrf_field() }}
            @foreach ($paymethods as $kk => $pay)
            <div class="inner-pay">
            <input type="hidden" name="total" value="{{$totalpro}}" />
                <div class="row">
                <div class="col-xs-9">
                        <label for='payment{{$pay->id}}'><input id="payment{{$pay->id}}" class="nomargin" data-price="{{$pay->price * Session::get('currencychange')}}" value="{{$pay->id}}" @if($kk == 0) checked @endif type="radio" name="optradio"></label>
                        <div class="left">
                        <h4>@if(\Session::get('lang') === 'ar') {{ $pay->name }} @else {{ $pay->name_en }} @endif ({{ $pay->price * Session::get('currencychange') }} {{ Session::get('currencysymbol') }})</h4>
                        <div> {!! $pay->details !!} </div>
                </div>
                </div>
                <div class="col-xs-3">
                    <div class="right"><img src="{{ url('uploads/payment/resize800') }}/{{ $pay->image }}" class="img-responsive pull-right"></div>
                </div>
            </div>
            </div>
            @endforeach
		<div class="form-group">
                    <label>{{ trans('site.charge_notes') }}</label>
                    
                      <textarea name="messagess" id="x" rows="4" class="form-control"  ></textarea>
                                  
                  </div>
                  
                  <div class="form-group">
                     <input  type="checkbox" name="agreecb" id="agreecb" required>
                     <label for="agreecb" class="lb-chk">{{trans('site.agree_on')}} <a href="{{ url('page/1/privacy_policy_'.App::getLocale()) }}" target="_blank">{{ trans('site.privacy_policy') }}</a> {{trans('site.or')}} <a href="{{ url('page/4/refound-policy') }}" target="_blank">{{ trans('site.refound_policy') }}</a></label>
                  </div>     
        
        <button  id="confirmBuyingBTN" class="btn btn-success pull-left"><strong>{{ trans('site.confirm_buying') }}</strong></button>
        
        </form>
        
        <div class="loading">
       		<img style=" width: 10%;" class="loadingimg" src="{{ url('/resources/assets/front/img/circle-loading-gif.gif') }}">
       		<h3>يرجي الانتظار قليلا....</h3>
      </div>
</div>

