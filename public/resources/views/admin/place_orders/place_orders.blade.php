@extends('layouts.admin')
@section('content')

    <div class="btns-top">
        @permission('add_place_orders')
        <a class="btn btn-primary" href="{{ URL::to('place_orders/create') }}">إضافة فاتورة المبيعات</a>
        <a class="btn btn-warning" href="{{ url('/place_order/return') }}">إضافة مرتجع مبيعات</a>
        @endpermission
        <button type="button" id="btn_finish" class="btn btn-danger">تم التسليم</button>
    </div>

    <table class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
        <tr>
            <th><input type="checkbox" id="checkAll"/></th>
            <th>رقم الطلب</th>
            <th>نوع الطلب</th>
            <th>تاريخ الطلب</th>
            <th>إسم العميل</th>
            <th>رقم العميل</th>
            <th>المنتجات</th>
            <th>السعر</th>
            <th>الكمية</th>
            <th>الخصم</th>
            <th>الإجمالي</th>
            <th>طريقة الدفع</th>
            <th>الحالة</th>
            <th>أدمن</th>
            <th>ملاحظات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order as $x)
            <tr id="{{$x->number}}">
                <td><input type="checkbox" onchange="onchangeFinish(this  ,  '{{$x->number}}')" name="checkbox"
                           value="{{$x->number}}"/></td>
                <td><a target="_blank" class="btn btn-success"
                       href="{{url('/place_order/printOrder/'.$x->number)}}">{{$x->number}}</a></td>
                <td>
                    @if(intval($x->type) === 1)
                        فاتورة
                    @else
                        مرتجع
                    @endif
                </td>
                <td> {{ date('y/m/d' , strtotime($x->created_at)) }}</td>
                <td>
                    @if($x->customer_name !== null)
                        {{$x->customer_name}}
                    @else
                        -------
                    @endif
                </td>
                <td>
                    @if($x->customer_phone !== null)
                        {{$x->customer_phone}}
                    @else
                        -------
                    @endif
                </td>
                <td>
                    <?php
                    $price = '';
                    $quantities = '';
                    foreach ($orderNu as $on) {
                        if ($on->number === $x->number) {
                            if ($on->Product) {
                                $quantities .= '<ul>'
                                    . '<li>'
                                    . $on->quantity
                                    . '</li>'
                                    . '</ul> <hr />';
                                $price .= '<ul>'
                                    . '<li>'
                                    . ((intval($on->Product->discount) !== 0) ? $on->Product->price : $on->Product->price - $on->Product->discount)
                                    . '</li>'
                                    . '</ul> <hr />';
                                echo $on->Product->code . ' - ' . $on->Product->title_ar . '<br /> ';
                            }
                        }
                    }
                    ?>
                </td>
                <td>{!! $price !!}</td>
                <td>
                    {!! $quantities !!}
                </td>
                <td>{{$x->discount}}</td>
                <td>{{ $x->total_price}}</td>
                <td>{{ \App\Paymethod::where(['id' => $x->paymethod])->first()['name']}}</td>
                <td>{{\App\OrderStatus::where(['id' => $x->status])->first()['name']}}</td>
                <td>{{ \App\User::where(['id' => $x->admin_id])->first()['name']}}</td>
                <td>{{$x->notes}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <div class="row">
        <div class="col-md-12">
            <bdi> <span>
                   <span>إجمالى الفواتير</span>: {{$saleOrdersTotal}}
            </span></bdi>
        </div>
        <div class="col-md-12">
            <bdi> <span>
                   <span>إجمالى المرتجعات</span>: {{$returnOrdersTotal}}
            </span></bdi>
        </div>
        <div class="col-md-12">
            <bdi> <span>
                   <span>الفرق </span>: {{$saleOrdersTotal - $returnOrdersTotal}}
            </span></bdi>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let finish = [];


        $(function () {
            $('#btn_finish').on('click', function () {
                console.log(finish);
                if (finish.length > 0) {
                    let form = new FormData();
                    form.append('ids[]', finish);

                    $.ajax({
                        url: '{{url('/place_order/finish')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            for (let index = 0; index < finish.length; index++) {
                                $('#' + finish[index]).remove();
                            }
                            finish = [];
                        }
                    });
                }
            });
        })

        function onchangeFinish(item, number) {
            if ($(item).is(':checked')) {
                finish.push(number);
            }
        }
    </script>

@endsection