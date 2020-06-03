@extends('layouts.admin')

@section('content')

    <div class="btns-bottom">
        <button id="printbtn" class="btn btn-default">{{trans('home.print')}}</button>
    </div>

    <div id="displaycontent">
        <table class="table table-striped table-bordered table-hover" id="datatable">
            <thead>
            <tr>
                <th>{{trans('home.id')}}</th>
                <th>{{trans('home.code')}}</th>
                <th>{{trans('home.title')}}</th>
                <th>{{trans('home.image')}}</th>
                <th>{{trans('home.price')}}</th>
                <th>المخزون</th>
                <th>الرئيسي</th>
                <th>الفرعى</th>
                <th>المحل</th>
                <th>{{trans('home.buying_price')}}</th>
                <th>{{trans('home.total_buying_price')}}</th>
                <th>{{trans('home.profit')}}</th>
                <th>{{trans('home.profit_percent')}}</th>
            </tr>
            <tbody>
            <?php
            $buyingPriceTotal = 0;
            $mainStockbuyingPriceTotal = 0;
            $subStockbuyingPriceTotal = 0;
            $storeStockbuyingPriceTotal = 0;
            $priceTotal = 0;
            $profitPercentTotal = 0;
            $mainProfitPercentTotal = 0;
            $subProfitPercentTotal = 0;
            $storeProfitPercentTotal = 0;

            $total = 0;
            $stockTotal = 0;
            $mainTotal = 0;
            $subTotal = 0;
            $storeTotal = 0;
            $mainProfit = 0;
            $subProfit = 0;
            $storeProfit = 0;

            $oldReserved = 0;
            ?>
            @foreach ($products as $product)
                <?php
                $oldReserved = \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1, 2, 4))
                        ->where(['main_stock' => null, 'sub_stock' => null, 'store_stock' => null])
                        ->groupBy('number')
                        ->get()
                        ->sum('quantity')
                    + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1, 2, 4))
                        ->where(['main_stock' => null, 'sub_stock' => null, 'store_stock' => null])
                        ->groupBy('number')
                        ->get()
                        ->sum('quantity');

                $storeProductReservedStock = \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1))
                        ->groupBy('number')
                        ->get()
                        ->sum('store_stock')
                    + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereIn('status_id', array(1))
                                    ->where('from_place', '=', 0);
                            });
                            $query->orWhere(function ($query) {
                                $query->whereIn('status_id', array(1 , 2))
                                    ->where('from_place', '=', 1);
                            });
                        })
                        ->groupBy('number')
                        ->get()
                        ->sum('store_stock')
                    +
                \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                    ->where('product_id', '=', $product->id)
                    ->orderBy('id', 'desc')
                    ->whereIn('status_id', array(2))
                    ->where('from_place', '=', 1)
                    ->groupBy('number')
                    ->get()
                    ->sum('main_stock')
                +
                \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                    ->where('product_id', '=', $product->id)
                    ->orderBy('id', 'desc')
                    ->whereIn('status_id', array(2))
                    ->where('from_place', '=', 1)
                    ->groupBy('number')
                    ->get()
                    ->sum('sub_stock')
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
                    $storeProductReservedStock -= \App\PlaceOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,quantity'))
                        ->where('order_id', '=', $order->id)
                        ->whereIn('status', array(6))
                        ->get()
                        ->sum('quantity');
                }

                $subProductReservedStock = \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1))
                        ->groupBy('number')
                        ->get()
                        ->sum('sub_stock')
                    + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1))
                        ->groupBy('number')
                        ->get()
                        ->sum('sub_stock');

                $mainProductReservedStock = \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(1, 2, 4))
                        ->groupBy('number')
                        ->get()
                        ->sum('main_stock')
                    + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,main_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereIn('status_id', array(1, 2, 4))
                                    ->where('from_place', '=', 0);
                            });
                            $query->orWhere(function ($query) {
                                $query->whereIn('status_id', array(1))
                                    ->where('from_place', '=', 1);
                            });
                        })
                        ->groupBy('number')
                        ->get()
                        ->sum('main_stock')
                    + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(2, 4))
                        ->groupBy('number')
                        ->get()
                        ->sum('sub_stock')
                    + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,sub_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(2, 4))
                        ->where('from_place', '=', 0)
                        ->groupBy('number')
                        ->get()
                        ->sum('sub_stock')

                    + \App\Order::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(2, 4))
                        ->groupBy('number')
                        ->get()
                        ->sum('store_stock')
                    + \App\StoreOrder::select(\Illuminate\Support\Facades\DB::raw('id,number,store_stock'))
                        ->where('product_id', '=', $product->id)
                        ->orderBy('id', 'desc')
                        ->whereIn('status_id', array(2, 4))
                        ->where('from_place', '=', 0)
                        ->groupBy('number')
                        ->get()
                        ->sum('store_stock');

                $productsTotal = $mainProductReservedStock + $subProductReservedStock +
                    $storeProductReservedStock;
                ?>
                <tr>
                    <td {{ ($product->stock <= 5)? 'style=background-color:red;':'' }}> {{ $product->id }}</td>
                    <td> {{ $product->code}}</td>
                    <td> {{ $product->title_ar}}</td>
                    <td><img width="50" src="{{ url('uploads/product/resize200') }}/{{ $product->image }}"></td>
                    <td> @if($product->discount == 0){{ $product->price}} @else <span
                                style="text-decoration: line-through;">{{$product->price}}</span>
                        <br/>{{ $product->price - $product->discount}} @endif</td>
                    <td> {{ $product->stock  + $productsTotal + $oldReserved}}</td>
                    <td> {{ $product->main_stock + $mainProductReservedStock + $oldReserved }}</td>
                    <td> {{ $product->sub_stock + $subProductReservedStock }}</td>
                    <td> {{ $product->store_stock + $storeProductReservedStock }}</td>
                    <?php
                    $profit = ($product->price > 0) ? $product->price - $product->buying_price : 0;
                    $profitPercent = ($product->price > 0) ? ($profit / $product->price) * 100 : 0;

                    $priceTotal += $product->price;
                    $profitPercentTotal += $profitPercent;

                    $mainProfitPercentTotal += ($profit * $product->main_stock);

                    $buyingPrice = $product->buying_price * ($product->stock + $productsTotal);
                    $buyingPriceTotal += $buyingPrice;

                    $total += ($profitPercent * ($product->stock + $productsTotal));
                    $stockTotal += $product->stock + $productsTotal;
                    $mainStockbuyingPriceTotal += ($product->buying_price * ($product->main_stock + $mainProductReservedStock));
                    $mainTotal += $product->main_stock + $mainProductReservedStock;
                    $mainProfit += ($profitPercent * ($product->main_stock + $mainProductReservedStock));
                    $subStockbuyingPriceTotal += ($product->buying_price * ($product->sub_stock + $subProductReservedStock));
                    $subTotal += $product->sub_stock + $subProductReservedStock;
                    $subProfit += ($profitPercent * ($product->sub_stock + $subProductReservedStock));
                    $storeStockbuyingPriceTotal += ($product->buying_price * ($product->store_stock + $storeProductReservedStock));
                    $storeTotal += $product->store_stock + $storeProductReservedStock;
                    $storeProfit += ($profitPercent * ($product->store_stock + $storeProductReservedStock));
                    ?>

                    <td>{{$product->buying_price}}</td>
                    <td>{{$buyingPrice}}</td>
                    <td>{{$profit}}</td>
                    <td>{{number_format($profitPercent,2)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="row">
        <bdi>
            <span>اجمالى سعر الشراء للمخزن الرئيسى :</span>
            <span>{{$mainStockbuyingPriceTotal}}</span>
        </bdi>
        <br/>
        <bdi>
            <span>متوسط نسبة الربح للمخزن الرئيسى :</span>
            <span>{{ number_format( ($mainProfit / $mainTotal)  , 2) }}</span>
        </bdi>

    </div>
    <div class="row">
        <bdi>
            <span>اجمالى سعر الشراء للمخزن الفرعى :</span>
            <span>{{$subStockbuyingPriceTotal}}</span>
        </bdi>
        <br/>
        <bdi>
            <span>متوسط نسبة الربح للمخزن الفرعى :</span>
            <span>@if(intval($subTotal) !== 0 ){{ number_format( ($subProfit / $subTotal)  , 2) }}@else 0 @endif</span>
        </bdi>
    </div>
    <div class="row">
        <bdi>
            <span>اجمالى سعر الشراء للمخزن المحل :</span>
            <span>{{$storeStockbuyingPriceTotal}}</span>
        </bdi>
        <br/>
        <bdi>
            <span>متوسط نسبة الربح للمخزن المحل :</span>
            <span>@if(intval($storeTotal) !==0 ){{ number_format( $storeProfit / $storeTotal , 2) }} @else
                    0 @endif</span>
        </bdi>
    </div>

    <div class="row">
        <p>{{trans('home.total_buying_price')}} : {{ $buyingPriceTotal }}</p>
        <p>{{trans('home.profit_percent_average')}}
            : {{ number_format($total / $stockTotal , 2) }}</p>
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