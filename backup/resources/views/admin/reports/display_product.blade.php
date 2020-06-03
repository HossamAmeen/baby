<table class="table table-striped table-bordered table-hover" id="productsReportdatatable">
    <thead>
    <tr>
        <th>رقم المنتج</th>
        <th>كود المنتج</th>
        <th>إسم المنتج</th>
        <th>صورة المنتج</th>
        <th>سعر البيع</th>
        <th>الكمية المباعة</th>
        <th>الكمية المتاحة</th>
        <th>سعر الشراء</th>
        <th>إجمالى سعر البيع</th>
        <th>إجمالى سعر الشراء</th>
        <th>الربح</th>
        <th>نسبة الربح</th>
    </tr>
    </thead>

    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>{{$product->code}}</td>
            <td>{{$product->title_ar}}</td>
            <td>
                @if ($product->image)
                    <img src="{{url('/uploads/product/resize200/' . $product->image )}}" width="100" height="100"></a>
                @else
                    <img src="{{url('/uploads/product/resize200/200noimage.png')}}" width="100" height="100"></a>
                @endif
            </td>
            <td>
                @if ($product->discount == 0)
                    {{$product->price}}
                @else
                    <span style="text-decoration: line-through;">{{$product->price}}</span>
                    <br/>{{$product->price - $product->discount}}
                @endif
            </td>
            <td>{{$product->totalOrderd}}</td>
            <td>{{$product->totalStock}}</td>
            <td>{{$product->buying_price}}</td>
            <td>{{$product->totalPrice}}</td>
            <td>{{$product->totalBuyingPrice}}</td>
            <td>{{$product->profit}}</td>
            <td>{{$product->profitPercent}}</td>
        </tr>
    @endforeach
    </tbody>

</table>

<p><span>إجمالى سعر البيع : </span> {{ $totalPrice }}</p>
<p><span>إجمالي سعر الشراء : </span>{{$totalBuyingPrice}}</p>
<p><span>الربح : </span>{{$totalProfit}}</p>
<p><span>نسبة الربح : </span>{{$totalProfitPercent}}</p>