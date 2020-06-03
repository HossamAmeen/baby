<table id="inventory_table">
    <thead>
    <tr>
        <th>كود</th>
        <th>إسم المنتج</th>
        <th>الكمية</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{$product->code}}</td>
            <td>{{$product->title_ar}}</td>
            <td>{{$product->quantity}}</td>
        </tr>
    @endforeach
    </tbody>
</table>