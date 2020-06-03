@extends('layouts.admin')

@section('content')

    <div class="btns-bottom">
        <button id="printbtn" class="btn btn-default">{{trans('home.print')}}</button>
    </div>

    <div id="displaycontent">
        <table class="table table-striped table-bordered table-hover" id="datatable">
            <thead>
            <th>{{trans('home.number')}}</th>
            <th>{{trans('home.code')}}</th>
            <th>{{trans('home.title')}}</th>
            <th>{{trans('home.image')}}</th>
            <th>{{trans('home.price')}}</th>
            <th>المخزون</th>
            {{--<th>الرئيسي</th>--}}
            {{--<th>الفرعى</th>--}}
            <th>المحل</th>
            <th>{{trans('home.ordered')}}</th>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td {{ ($product->stock <= 5)? 'style=background-color:red;':'' }}> {{ $product->number }}</td>
                    <td> {{ $product->code}}</td>
                    <td> {{ $product->title_ar}}</td>
                    <td><img width="50" src="{{ url('uploads/product/resize200') }}/{{ $product->image }}"></td>
                    <td> @if($product->discount == 0){{ $product->price}} @else <span
                                style="text-decoration: line-through;">{{$product->price}}</span>
                        <br/>{{ $product->price - $product->discount}} @endif</td>
                    <td> {{ $product->stock}}</td>
                    {{--<td> {{ $product->main_stock}}</td>--}}
                    {{--<td> {{ $product->sub_stock}}</td>--}}
                    <td> {{ $product->store_stock}}</td>
                    <td>

                        @php
                            $total = \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id' , '=' , $product->id)
                            ->orderBy('id','desc')
                            ->whereIn('status_id',array(1,2,4))
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity')
                            +
                            \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                            ->where('product_id' , '=' , $product->id)
                            ->orderBy('id','desc')
                            ->whereIn('status_id',array(1,2,4))
                            ->groupBy('number')
                            ->get()
                            ->sum('quantity')
                            + \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->whereIn('status', array(1, 2))
                        ->where(['type' => 1])
                        ->orderBy('id', 'desc')
                        ->groupBy('number')
                        ->get()
                        ->sum('quantity');
                            $orders = \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                            ->whereIn('status', array(1, 2))
                            ->where(['type' => 1])
                            ->orderBy('id', 'desc')
                            ->get();
                        foreach ($orders as $order) {
                            $total -= \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                                ->where('order_id', '=', $order->id)
                                ->whereIn('status', array(6))
                                ->get()
                                ->sum('quantity');
                        }
                        @endphp
                        {{ $total  }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@endsection


@section('script')
    <script>

        $('#printbtn').click(function () {

            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write('<h1>' + document.title + '</h1>');
            mywindow.document.write(document.getElementById('displaycontent').innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        });

    </script>

@endsection