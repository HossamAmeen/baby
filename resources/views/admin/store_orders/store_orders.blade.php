@extends('layouts.admin')
@section('content')

    @if(isset($place) && $place)
        <div class="btns-top">
            <a class="btn btn-primary"
               @if(isset($place) && $place)
               href="{{ url('/place/store_order/create') }}"
               @else
               href="{{ URL::to('store_orders/create') }}"
                    @endif
            >{{trans('home.add')}}</a>
            {{--<button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>--}}
        </div>
    @endif

    <table class="table table-striped table-bordered table-hover" id="datatable10">
        <thead>
        <tr>
            <th>{{trans('home.date')}}</th>
            <th>#</th>
            <th><input type="checkbox" id="checkAll"/></th>
            <th>{{trans('home.status_date')}}</th>
            <th>{{trans('home.name')}}</th>
            <th>{{trans('home.address')}}</th>
            <th>{{trans('home.phone')}}</th>
            <th>{{trans('home.products')}}</th>
            <th>الكمية</th>
            <th>السعر</th>
            <th>الشحن</th>
            <th>{{trans('home.total')}}</th>
            <th>ملاحظات</th>
            <th>مندوب</th>
            <th>أدمن</th>
            <th>أدمن إضافي</th>
            <th>الحالة</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order as $x)
            <tr id="{{$x->number}}">
                <td> {{ date('Y/m/j' , strtotime($x->created_at)) }}</td>
                <td> {{ $x->number }}</td>

                <td>
                    @if(intval($x->from_place) === 1)
                        <a class="btn btn-warning" target="_blank"
                           href="/place/store_order/print/{{$x->number}}">{{$x->number}}</a>
                    @else
                        <input type="checkbox" name="checkbox" value="{{$x->number}}"/>
                    @endif
                </td>

                <td>@if($x->status_date != null){{date('Y/m/j' , strtotime($x->status_date))}}@endif</td>
                <td>
                    <a @if(intval($x->from_place) === 1)
                       href="{{ url('/place/store_order/edit/'.$x->number) }}"
                       @else
                       href="{{ route('store_orders.edit', $x->number) }}"
                            @endif
                    >{{ $x->name }}</a>
                </td>
                <?php
                $address = \App\Address::where(['id' => $x->address_id])->first();
                $country = \App\Country::where(['id' => $address['country_id']])->first();
                $region = \App\Region::where(['id' => $address['region_id']])->first();
                $area = \App\Area::where(['id' => $address['area_id']])->first();
                ?>
                <td>{{$country['name'] .' '.$region['name'] .' '.$area['name'].' '.$address['address']}}</td>
                <td>{{ $x->phone }}</td>
                <td>
                    <a @if(intval($x->from_place) === 1)
                       href="{{ url('/place/store_order/edit/'.$x->number) }}"
                       @else
                       href="{{ route('store_orders.edit', $x->number) }}"
                            @endif
                    >
                        <?php
                        $price = '';
                        $quantities = '';
                        foreach ($orderNu as $on) {
                            if ($on->number == $x->number) {
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
                        $products = \App\Product::whereIn('id', \App\StoreOrder::where(['number' => $x->number])->get(['product_id']))->get();
                        foreach ($products as $product) {
                            $price .= (($product->discount > 0) ? ($product->price - $product->discount) : $product->price) . '<br /> ';
                        }
                        ?>
                    </a>
                </td>
                <td>
                    {!! $quantities !!}
                </td>
                <td>{!! substr($price , 0 , strlen($price)-1) !!}</td>
                <td>{{$x->shipping_price}}</td>
                <td>{{ $x->total}}</td>
                <td>{{$x->notes}}</td>
                <td>{{ \App\Delivery::where(['id' => $x->delivery_id])->first()['name']}}</td>
                <td>{{ \App\User::where(['id' => $x->admin_id])->first()['name']}}</td>
                <td>{{ $x->admin_name}}</td>
                <td>{{ trans("home.".\App\OrderStatus::where('id','=',$x->status_id)->first()['name'])}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('script')
    <script>
        $('#datatable10').DataTable({
            order: [[0, 'desc']]
        });
    </script>
@endsection