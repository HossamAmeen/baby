<div class="row">
    <div class="row">
        <div class="col-md-3">
            <bdi>
                <span> الإجمالى :</span>
                <span>{{$order[0]->total_price}}</span>
            </bdi>
        </div>
        <div class="col-md-3">
            <bdi>
                <span> الخصم :</span>
                <span>{{$order[0]->discount}}</span>
            </bdi>
        </div>
        <div class="col-md-3">
            <bdi>
                <span> تاريخ الطلب :</span>
                <span>{{date('y/m/d h:i a' , strtotime($order[0]->created_at))}}</span>
            </bdi>
        </div>
        <div class="col-md-3">
            <bdi>
                <span> رقم الطلب :</span>
                <span>{{$order[0]->number}}</span>
            </bdi>
        </div>
    </div>
    <div class="row">

        <div class="col-md-3">
            <bdi>
                <span> طريقة الدفع :</span>
                <span>{{\App\Paymethod::where(['id' => $order[0]->paymethod])->first()['name']}}</span>
            </bdi>
        </div>
    </div>
    <hr/>

    <div class="row">
        <table>
            <thead>
            <tr>
                <th>كود الصنف</th>
                <th>إسم الصنف</th>
                <th>الكمية</th>
                <th>العمليات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order as $or)
                @php
                    $row = true;
                    if($or->return === 1){
                        $placeOrder = \App\PlaceOrder::where(['order_id' => $or->order_id])->first();
                        $or->quantity -= (($placeOrder) ? $placeOrder->quantity : 0);
                        if($or->quantity === 0){
                        $row = false;
                        }
                    }
                @endphp
                @if($row)
                    <tr>
                        <td> {{$or->Product->code}} </td>
                        <td>{{$or->Product->title_ar}} </td>
                        <td><input name="amount" type="number" id="amount_{{$or->id}}"
                                   onchange="changeQuantity(this , '{{$or->id}}');"
                                   value="{{$or->quantity}}" min="1" max="{{$or->quantity}}" step="1"/></td>
                        <td><input type="checkbox" onchange="onchangeProduct(this , '{{$or->id}}')"></td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>