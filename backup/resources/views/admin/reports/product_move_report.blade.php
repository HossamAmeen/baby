<div id="displaycontent">
    <table class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
        <tr>
            <th>{{trans('home.code')}}</th>
            <th>الكمية</th>
            <th>المخزن</th>
            <th>الرئيسى</th>
            <th>فرعى</th>
            <th>المحل</th>
            <th>مكان الطلب</th>
            <th>الأدمن</th>
            <th>التاريخ</th>
        </tr>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td> {{$product->code}} </td>
                <td> {{$product->quantity}} </td>
                <td> {{$product->stock}} </td>
                <td> {{$product->main_stock}} </td>
                <td> {{$product->sub_stock}} </td>
                <td> {{$product->store_stock}} </td>
                <td> {{$product->action}} </td>
                <td> {{$product->admin_name}} </td>
                <td> {{$product->created_at}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

