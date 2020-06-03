<table class="table table-striped table-bordered table-hover" id="datatable">
    <thead>

    <th>{{trans('home.number')}}</th>
    <th>إسم المستخدم</th>
    <th>التاريخ</th>
    <th>العنوان</th>
    <th>{{trans('home.products')}}</th>
    <th>{{trans('home.delivery')}}</th>
    <th>الأدمن</th>
    <th>{{trans('home.status')}}</th>
    <th>{{trans('home.totalshipping')}}</th>
    <th>{{trans('home.actual_shipping_price')}}</th>
    <th>{{trans('home.total')}}</th>
    <th>{{trans('home.total_buying_price')}}</th>
    <th>{{trans('home.profit')}}</th>
    <th>{{trans('home.profit_percent')}}</th>
    </thead>
    <tbody>

    <?php
    $totalshipping = 0;
    $total = 0;
    $totalActualShipping = 0;
    $totalCoupon = 0;
    $totalBuyingPrice = 0;
    $totalProfit = 0;
    $totalProfitPercent = 0;
    ?>

    @foreach($orders as $order)
        <?php
        $totalshipping += $order->totalshipping;
        $total += $order->total;
        $totalCoupon += $order->coupon_price;
        ?>
        <tr>
            <td> {{ $order->number}}</td>
            <td> {{ App\User::find($order -> user_id)->name}}</td>
            <td> {{ date('Y/m/d' , strtotime($order->created_at))}}</td>
            <?php
            if ($order->user_address == null) {
                $address = \App\Address::where(['id' => $order->address_id])->first();
                $country = \App\Country::where(['id' => $address['country_id']])->first();
                $region = \App\Region::where(['id' => $address['region_id']])->first();
                $area = \App\Area::where(['id' => $address['area_id']])->first();
            }
            ?>
            <td>@if($order->user_address != null) {{ $x->user_address }} @else {{ $country['name'] .' - '.$region['name'] .' - '.$area['name']}}
                <br/> {{$address['address']}} @endif</td>
            <td>
                <?php
                $profit = 0;
                $profitPercent = 0;
                $totalProductPrice = 0;
                $totalProductBuyingPrice = 0;
                $products = explode(",", $order->products);
                ?>
                @foreach ($products as $product)
                    <?php $pro = App\Product::find((int)$product); ?>
                    @if($pro)
                        <?php
                        $totalProductBuyingPrice += $pro->buying_price;
                        $productPrice = ($pro->discount > 0) ? $pro->price - $pro->discount : $pro->price;
                        $profit += $productPrice - $pro->buying_price;
                        $totalProductPrice += $productPrice;
                        ?>
                    @endif
                    @if($pro) {{ $pro->code.' - '.$pro -> title_ar }} @else Product Deleted @endif<br/>
                @endforeach
                @php
                    $totalBuyingPrice += $totalProductBuyingPrice;
                    $profitPercent = ($totalProductPrice > 0) ? ($profit / $totalProductPrice) * 100 : 0;
                    $totalProfitPercent += $profitPercent;$totalProfit += $profit;
                    $totalActualShipping += $order->actual_shipping_price;
                @endphp
            </td>

            @if($order->delivery_id != 0)
                <td>{{App\Delivery::find($order->delivery_id)->name }}</td>
            @else
                <td>{{ trans('home.nodelivery') }}</td>
            @endif
            <td> {{ App\OrderStatus::find($order->status_id) -> name}}</td>
            <td> {{ $order->totalshipping}}</td>
            <td>{{$order->actual_shipping_price}}</td>
            <td> {{ $order->total}}</td>
            <td>{{$totalProductBuyingPrice}}</td>
            <td>{{$profit}}</td>
            <td>{{number_format($profitPercent,2)}}</td>
        </tr>
    @endforeach

    @foreach ($storeOrders as $storeOrder)
        @php
            $totalshipping+=$storeOrder->totalshipping;
            $total += $storeOrder->total;
        @endphp
        <tr>
            <td> {{ $storeOrder->number}}</td>
            <?php $admin = App\User::find($storeOrder->admin_id); ?>
            <td> {{$storeOrder->name}}</td>
            <td> {{date('Y/m/d' , strtotime($storeOrder->created_at))}}</td>
            <?php
            if ($storeOrder->address_id !== null) {
                $address = \App\Address::where(['id' => $storeOrder->address_id])->first();
                $country = \App\Country::where(['id' => $address['country_id']])->first();
                $region = \App\Region::where(['id' => $address['region_id']])->first();
                $area = \App\Area::where(['id' => $address['area_id']])->first();
            }
            ?>
            <td>@if($storeOrder->address_id !== null) {{ $country['name'] .' - '.$region['name'] .' - '.$area['name']}}
                <br/> {{$address['address']}} @endif</td>
            <td>

                @php
                    $profit = 0;
                    $profitPercent = 0;
                    $totalProductBuyingPrice = 0;
                    $totalProductPrice = 0;
                     $products = explode(",",$storeOrder->products);
                @endphp
                @foreach ($products as $product)
                    <?php $pro = App\Product::find((int)$product); ?>
                    @if($pro)
                        <?php
                        $totalProductBuyingPrice += $pro->buying_price;
                        $productPrice = ($pro->discount > 0) ? $pro->price - $pro->discount : $pro->price;
                        $profit += $productPrice - $pro->buying_price;
                        $totalProductPrice += $productPrice;
                        ?>
                    @endif
                    @if($pro) {{ $pro->code.' - '.$pro -> title_ar }} @else Product Deleted @endif<br/>
                @endforeach
                @php
                    $totalBuyingPrice += $totalProductBuyingPrice;
                                        $profitPercent = ($totalProductPrice > 0) ? ($profit / $totalProductPrice) * 100 : 0;
                                        $totalProfitPercent += $profitPercent;$totalProfit += $profit;
                                    $totalActualShipping += $storeOrder->actual_shipping_price;
                @endphp

            </td>

            @if($storeOrder->delivery_id != 0)
                <td>{{App\Delivery::find($storeOrder->delivery_id) -> name }}</td>
            @else
                <td>{{ trans('home.nodelivery') }}</td>
            @endif

            <td> {{ App\OrderStatus::find($storeOrder->status_id) -> name}}</td>
            <td> {{ $storeOrder->totalshipping}}</td>
            <td>{{$storeOrder->actual_shipping_price}}</td>
            <td> {{ $storeOrder->total}}</td>
            <td>{{$totalProductBuyingPrice}}</td>
            <td>{{$profit}}</td>
            <td>{{number_format($profitPercent,2)}}</td>
        </tr>
    @endforeach

    @foreach ($placeStoreOrders as $placeStoreOrder)
        @php
            $totalshipping += 0;
            $total += $placeStoreOrder->total;
        @endphp
        <tr>
            <td> {{ $placeStoreOrder->number}}</td>
            <?php $admin = App\User::find($placeStoreOrder->admin_id); ?>
            <td> {{$placeStoreOrder->name}}</td>
            <td> {{date('Y/m/d' , strtotime($placeStoreOrder->created_at))}}</td>
            <?php
            if ($placeStoreOrder->address_id == null) {
                $address = \App\Address::where(['id' => $placeStoreOrder->address_id])->first();
                $country = \App\Country::where(['id' => $address['country_id']])->first();
                $region = \App\Region::where(['id' => $address['region_id']])->first();
                $area = \App\Area::where(['id' => $address['area_id']])->first();
            }
            ?>
            <td>@if($placeStoreOrder->address_id == null) {{ $country['name'] .' - '.$region['name'] .' - '.$area['name']}}
                <br/> {{$address['address']}} @endif</td>
            <td>

                @php
                    $profit = 0;
                    $profitPercent = 0;
                    $totalProductBuyingPrice = 0;
                    $totalProductPrice = 0;
                     $products = explode(",",$placeStoreOrder->products);
                @endphp
                @foreach ($products as $product)
                    <?php $pro = App\Product::find((int)$product); ?>
                    @if($pro)
                        <?php
                        $totalProductBuyingPrice += $pro->buying_price;
                        $productPrice = ($pro->discount > 0) ? $pro->price - $pro->discount : $pro->price;
                        $profit += $productPrice - $pro->buying_price;
                        $totalProductPrice += $productPrice;
                        ?>
                    @endif
                    @if($pro) {{ $pro->code.' - '.$pro -> title_ar }} @else Product Deleted @endif<br/>
                @endforeach
                @php
                    $totalBuyingPrice += $totalProductBuyingPrice;
                                        $profitPercent = ($totalProductPrice > 0) ? ($profit / $totalProductPrice) * 100 : 0;
                                        $totalProfitPercent += $profitPercent;$totalProfit += $profit;
                                    $totalActualShipping += 0;
                @endphp
            </td>

            @if($placeStoreOrder->delivery_id != 0)
                <td>{{App\Delivery::find($placeStoreOrder->delivery_id) -> name }}</td>
            @else
                <td>{{ trans('home.nodelivery') }}</td>
            @endif
            <td> {{ App\OrderStatus::find($placeStoreOrder->status_id) -> name}}</td>
            <td> 0</td>
            <td>0</td>
            <td> {{ $placeStoreOrder->total}}</td>
            <td>{{$totalProductBuyingPrice}}</td>
            <td>{{$profit}}</td>
            <td>{{number_format($profitPercent,2)}}</td>
        </tr>
    @endforeach

    @foreach ($placeOrders as $placeStoreOrder)
        @php
            $totalshipping += 0;
            $total += $placeStoreOrder->total;
        @endphp
        <tr>
            <td> {{ $placeStoreOrder->number}}</td>
            <?php $admin = App\User::find($placeStoreOrder->admin_id); ?>
            <td> {{$placeStoreOrder->customer_name}}</td>
            <td> {{date('Y/m/d' , strtotime($placeStoreOrder->created_at))}}</td>
            <td></td>
            <td>

                @php
                    $profit = 0;
                    $profitPercent = 0;
                    $totalProductBuyingPrice = 0;
                    $totalProductPrice = 0;
                     $products = explode(",",$placeStoreOrder->products);
                @endphp
                @foreach ($products as $product)
                    <?php $pro = App\Product::find((int)$product); ?>
                    @if($pro)
                        <?php
                        $totalProductBuyingPrice += $pro->buying_price;
                        $productPrice = ($pro->discount > 0) ? $pro->price - $pro->discount : $pro->price;
                        $profit += $productPrice - $pro->buying_price;
                        $totalProductPrice += $productPrice;
                        ?>
                    @endif
                    @if($pro) {{ $pro->code.' - '.$pro -> title_ar }} @else Product Deleted @endif<br/>
                @endforeach
                @php
                    $totalBuyingPrice += $totalProductBuyingPrice;
                                        $profitPercent = ($totalProductPrice > 0) ? ($profit / $totalProductPrice) * 100 : 0;
                                        $totalProfitPercent += $profitPercent;$totalProfit += $profit;
                                    $totalActualShipping += 0;
                @endphp
            </td>


            <td>{{ trans('home.nodelivery') }}</td>
            <td> {{ App\OrderStatus::find($placeStoreOrder->status) -> name}}</td>
            <td> 0</td>
            <td>0</td>
            <td> {{ $placeStoreOrder->total}}</td>
            <td>{{$totalProductBuyingPrice}}</td>
            <td>{{$profit}}</td>
            <td>{{number_format($profitPercent,2)}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<p>{{trans('home.totalshipping')}} : {{ $totalshipping }}</p>
<p>{{trans('home.totalactualshipping')}} : {{ $totalActualShipping }}</p>
<p><span>إجمالي الكوبونات : </span>{{$totalCoupon}}</p>
<p>{{trans('home.total')}} : {{ $total }}</p>
<p>{{trans('home.total_selling')}} : {{ $total - $totalshipping }}</p>


<p>{{trans('home.total_buying_price')}} : {{ $totalBuyingPrice }}</p>
<p>{{trans('home.profit')}} : {{ $total - $totalshipping - $totalBuyingPrice }}</p>
<p>{{trans('home.profit_percent_average')}}
    : @if((collect($orders)->count() + collect($storeOrders)->count() + collect($placeStoreOrders)->count() + collect($placeOrders)->count()) > 0){{ number_format($totalProfitPercent / (collect($orders)->count() + collect($storeOrders)->count() + collect($placeStoreOrders)->count() + collect($placeOrders)->count()) , 2) }}@else
        0 @endif</p>
<p><span>عدد الطلبات :</span> {{collect($orders)->count() + collect($storeOrders)->count()
+ collect($placeStoreOrders)->count() + collect($placeOrders)->count()}}</p>