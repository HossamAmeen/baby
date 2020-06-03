@extends('layouts.admin')
@section('content')

    <div class="btns-top">
        <a class="btn btn-primary" href="{{ URL::to('orders/create') }}">{{trans('home.add')}}</a>
        {{--<button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>--}}
        <a href="{{ url('order/user/create') }}" class="btn btn-danger">{{trans('home.create_order_new_user')}}</a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-bordered table-hover" id="datatable2">
                <thead>
                <th><input type="checkbox" id="checkAll"/></th>
                <th>#</th>
                <th>{{trans('home.date')}}</th>
                <th>{{trans('home.status_date')}}</th>
                <th>{{trans('home.user')}}</th>
                <th>{{trans('home.products')}}</th>
                <th>الكمية</th>
                <th>{{ trans('home.address') }}</th>
                <th>{{trans('home.phone')}}</th>
                <th>السعر</th>
                <th>الشحن</th>
                <th>{{trans('home.total')}}</th>
                <th>المندوب</th>
                <th>{{trans('home.status')}}</th>
                </thead>
                <tbody>
                @foreach ($order as $x)
                    <tr id="{{$x->number}}">
                        <td><input type="checkbox" name="checkbox" value="{{$x->number}}"/></td>
                        <td>{{$x->number}}</td>
                        <td>
                            <a href="{{ route('orders.edit', $x->number) }}">{{ date('Y/m/j' , strtotime($x->created_at)) }}</a>
                        </td>
                        <td>@if($x->status_date != null){{date('Y/m/j' , strtotime($x->status_date))}}@endif</td>
                        <td>
                            <a href="{{ route('orders.edit', $x->number) }}">@if($x->user_name != null) {{ $x->user_name }} @else {{ $x->User->name }} @endif</a>
                        </td>

                        <td><a href="{{ route('orders.edit', $x->number) }}">
                                <?php

                                $price = '';
                                $quantities = '';
                                foreach ($orderNu as $on) {
                                    if ($on->number == $x->number) {
                                        $orderShipping = \App\Order::where(['number' => $on->number])->get();
                                        $shippingPrice = $orderShipping[0]->shipping_price;
                                        if ($on->Product) {
                                            $quantities .= '<ul>'
                                                . '<li>'
                                                . trans('home.quantity') . ': ' . $on->quantity
                                                . ' - ' . trans('home.main_stock') . ': ' . $on->main_stock
                                                . ' - ' . trans('home.sub_stock') . ': ' . $on->sub_stock
                                                . ' - ' . trans('home.store_stock_quan') . ': ' . $on->store_stock
                                                . '</li>'
                                                . '</ul> <hr />';
                                            echo $on->Product->code . ' - ' . $on->Product->title_ar . '<br /> ';
                                        }
                                    }
                                }
                                $products = \App\Product::whereIn('id' , \App\Order::where(['number' => $x->number])->get(['product_id']))->get();
                                foreach ($products as $product){
                                    $price .= (($product->discount > 0) ? ($product->price - $product->discount) : $product->price) . '<br /> ';
                                }
                                ?>
                            </a>
                        </td>
                        <?php
                        if ($x->user_address == null) {
                            $address = \App\Address::where(['id' => $x->address_id])->first();
                            $country = \App\Country::where(['id' => $address['country_id']])->first();
                            $region = \App\Region::where(['id' => $address['region_id']])->first();
                            $area = \App\Area::where(['id' => $address['area_id']])->first();
                        }
                        ?>
                        <td>{!! $quantities !!}</td>
                        <td>@if($x->user_address != null) {{ $x->user_address }} @else {{ $country['name'] .' - '.$region['name'] .' - '.$area['name']}}
                            <br/> {{$address['address']}} @endif</td>
                        <td>
                            <a href="{{ route('orders.edit', $x->number) }}">@if($address['phone'] != null) {{$address['phone']}} @elseif($x->user_phone != null) {{ $x->user_phone }} @else {{ $x->User->phone }} @endif</a>
                        </td>
                        <td>{!!  substr($price , 0 , strlen($price)-1)!!}</td>
                        <td>{{$shippingPrice}}</td>
                        <td>{{ $x->total}}</td>
                        <td><?php
                            $orderData = \App\Order::where(['number' => $x->number])->get();
                            ?>
                            {{ \App\Delivery::where(['id' => $orderData[0]->delivery_id])->first()['name']}}
                        </td>
                        <td><a href="{{ route('orders.edit', $x->number) }}">{{ $x->Status->name }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection