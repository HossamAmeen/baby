@foreach($producthome as $kitem => $item)
    <div class="col-md-3 col-sm-4 col-xs-6 productload">
        <div class="card">
            <div class="col-md-12">
                <div class="pro-image">
                    <a @if($lan == 'ar') href="{{ url($item->link) }}" @else href="{{ url($item->link_en) }}" @endif>
                        <img class="card-img-top img-responsive"
                             src="{{ url('uploads/product/resize800') }}/{{ $item->image }}"
                             alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $item->title_ar) }} @else {{ str_replace(' ' , '-' , $item->title) }} @endif"
                             title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif"
                             >
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a @if($lan == 'ar') href="{{ url($item->link) }}" @else href="{{ url($item->link_en) }}" @endif
                                          title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif">
                        @if($lan == 'ar')  {{ mb_substr($item->title_ar,0,40) }} @if(strlen($item->title_ar) >40)
                            ... @endif @else  {{ mb_substr($item->title,0,40) }} @if(strlen($item->title) >40)
                            ... @endif @endif</a></h4>
                @if($item->discount > 0)
                    <div class="new-price"><span
                                class="currency"><strong> {{ number_format(($item->price - $item->discount)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span>
                    </div>
                    <div class="old-price">{{ number_format($item->price * Session::get('currencychange'),2) }}
                        <span class="currency">{{ Session::get('currencysymbol') }}</span></div>
                @else
                    <div class="new-price"><span
                                class="currency"><strong> {{ number_format(($item->price)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span>
                    </div>
                    <div class="old-price"></div>
                @endif
                <a href="#" data-toggle="modal" data-target="#myModalcart"
                   class="mycart btn btn-success btn-block" data-count="1" data-id="{{ $item->id }}"><i
                            class="fa fa-shopping-cart"></i> {{ trans('site.add_to_card') }}</a>
            </div>
        </div>
    </div>

    <input type="hidden" class="idproduct" value="{{ $item->id }}"/>
@endforeach