@extends('layouts.admin')

@section('content')


    {!! Form::open(['data-toggle'=>'validator', 'files'=>'true']) !!}

    <div id="display_customer">

        <div class="row">
            <div class="form-group col-md-6">
                <label>{{trans('home.customer_name')}}</label>
                <input type="text" name="customer_name" class="form-control"
                       placeholder="{{trans('home.customer_name')}}"/>
            </div>

            <div class="form-group col-md-6">
                <label>{{trans('home.customer_phone')}}</label>
                <input type="text" name="customer_phone" class="form-control"
                       placeholder="{{trans('home.customer_phone')}}"/>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group select-group">
                <label>كود المنتج</label>
                {{--<select class="form-control" onchange="quantity()" name="select_product" id="select_product">--}}
                    {{--<option></option>--}}
                    {{--@foreach($products as $product)--}}
                        {{--<option value="{{ $product -> id }}">{{ $product->code .' - '. $product->title_ar }}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
                <input type="text" name="number" class="form-control" id="number"/>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group select-group">
                <label>{{trans('home.paymethod')}}</label>
                <select class="form-control" name="paymethod" id="paymethod" required>
                    <option></option>
                    @foreach($paymethods as $paymethod)
                        <option @if($paymethod -> id === 1) selected
                                @endif value="{{ $paymethod -> id }}">{{ $paymethod ->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group select-group">
                <label for="status">{{trans('home.status')}} :</label>
                <select class="form-control" name="status" id="status" required>
                    <option></option>
                    <option value="1"> مُعَلَّق</option>
                    <option selected value="2">مُؤَكَّد</option>
                    <option value="6">تم التسليم</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>الخصم</label>
            <input type="number" min="0" step="1" onchange="reCalcutaletePrice(this)"
                   class="form-control" id="discount" name="discount"/>
        </div>
        <div class="form-group col-md-6">
            <label>ملاحظات</label>
            <textarea class="form-control" placeholder="ملاحظات" name="notes"></textarea>
        </div>
    </div>
    <hr/>
    <div class="row">
        <table>
            <thead>
            <th>كود الصنف</th>
            <th>إسم الصنف</th>
            <th>الكمية</th>
            <th>سعر البيع</th>
            <th>الكمية</th>
            <th>الإجمالى</th>
            <th>العمليات</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <hr/>

    <div>
        <span>{{trans('home.total_price')}}: <h4 id="totalPrice">0</h4></span>
    </div>

    <hr/>

    <div class="btns-bottom">
        <span>للحفظ إضغط (CTRL + ENTER) </span>
        <button id="submitBtn" type="button" class="btn btn-default">حفظ</button>
    </div>

    {!! Form::close() !!}


    <div id="show_print" style="display: none;">

    </div>

@endsection

@section('script')
    <script src="{{ URL::to('resources/assets/back/js/printThis.js') }}"></script>
    <script src="{{URL::to('resources/assets/back/js/jquery.loader.js')}}"></script>
    <script type="text/javascript">
        let totalPrice = 0;
        let oldDiscount = 0;
        let productsIds = [];
        let quantities = [];
        let save = true;

        $(function () {
            $(document).keydown(function(e) {
                if(e.which === 13 && e.ctrlKey) {
                    if(productsIds.length > 0 && quantities.length > 0 && save){
                        save = false;
                        saveOrder();
                    }
                }
            });

            $('#submitBtn').on('click' , function (e) {
                e.preventDefault();
                if(productsIds.length > 0 && quantities.length > 0 && save){
                    save = false;
                    saveOrder();
                }
            });

            $('#number').keyup(function (e) {
                let number = $('#number').val();
                if (number.length >= 4 && e.keyCode === 13) {
                    let form = new FormData();
                    form.append('number', number);
                    $.ajax({
                        url: '{{url('/get/store/details/number')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            let id = response.data.id;

                            let productIndex = productsIds.indexOf(id);

                            if (productIndex !== -1) {
                                let quantity = parseInt($('#amount_' + id).val()) + 1;
                                let maxQueantity = parseInt($('#amount_' + id).attr('max'));
                                if (quantity <= maxQueantity) {
                                    $('#amount_' + id).val(quantity);
                                    $('#amount_' + id).trigger('change');
                                }
                            }else {
                                productsIds.push(id);
                                quantities.push(1);
                                let tBody = $('table').find('tbody');
                                console.log($('#totalPrice').text());
                                $('#totalPrice').text(parseFloat($('#totalPrice').text()) + (response.data.price * 1));

                                let trHtml = '<tr>'
                                    + '<td>' + response.data.code + '</td>'
                                    + '<td>' + response.data.title_ar + '</td>'
                                    + '<td><input name="amounts[]" type="number" id="amount_' + id + '" ' +
                                    'onchange="changeQuantity(this , ' + id + ' , ' + response.data.price + ');" ' +
                                    'value="1" min="1" max="' + response.data.store_stock + '" step="1" /></td>'
                                    + '<td>' + response.data.price + '</td>'
                                    + '<td id="quantity_' + id + '">1</td>'
                                    + '<td id="price_' + id + '">' + response.data.price + '</td>'
                                    + '<td><span onclick="removeProduct(this , ' + id + ')" class="label label-danger"><li class="fa fa-trash"></li></span></td>'
                                    + '</tr>';
                                tBody.append(trHtml);
                                $('#discount').val(parseInt($('#discount').val()) + 0);
                                $('#discount').trigger('change');
                            }
                            $('#number').val('');
                        },
                        error: function () {
                            $('#number').val('');
                        }
                    });
                }
            });
        });


        function saveOrder() {
            $.loader({
                className: "blue-with-image-2",
                content: ''
            });

            let form = new FormData();
            console.log(productsIds);
            form.append('productsIds[]', productsIds);
            form.append('quantities[]', quantities);
            form.append('customer_name', $('input[name=customer_name]').val());
            form.append('customer_phone', $('input[name=customer_phone]').val());
            form.append('discount', $('input[name=discount]').val());
            form.append('notes', $('textarea[name=notes]').val());
            form.append('paymethod', $('#paymethod').find('option:selected').val());
            form.append('status',$('#status').find('option:selected').val());
            form.append('total',parseInt($('#totalPrice').text()));

            $.ajax({
                url: '{{route('place_orders.store')}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                success: function (response) {
                    newwindow=window.open('{{url('/place_order/printOrder')}}/'+response.data.id);
                    if (window.focus) {
                        newwindow.focus();
                    }
                    freeOrder();
                    save = true;
                    $.loader('close');
                },
                error: function () {
                    save = true;
                    $.loader('close');
                }
            });
        }

        function freeOrder() {
            $('tbody').html('');
            $('#product_row').html('');
            productsIds = [];
            quantities = [];
            $('#totalPrice').text('0');
            $('#status').select2('val' , '2');
            $('#paymethod').select2('val' , '1');
            $('input[name=customer_name]').val('');
            $('input[name=customer_phone]').val('');
            $('input[name=discount]').val(0);
            oldDiscount = 0;
            $('text[name=notes]').val('');
        }

        function quantity() {
            var selection = document.getElementById('select_product');
            var text = selection.options[selection.selectedIndex].text;
            let id = selection.options[selection.selectedIndex].value;

            let productIndex = productsIds.indexOf(id);

            if (productIndex !== -1) {
                let quantity = parseInt($('#amount_' + id).val()) + 1;
                let maxQueantity = parseInt($('#amount_' + id).attr('max'));
                if (quantity <= maxQueantity) {
                    $('#amount_' + id).val(quantity);
                    $('#amount_' + id).trigger('change');
                }
            } else {
                let form = new FormData();
                form.append('id', id);
                $.ajax({
                    url: '{{url('/get/store/details')}}',
                    method: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        productsIds.push(id);
                        quantities.push(1);
                        let tBody = $('table').find('tbody');
                        $('#totalPrice').text(parseFloat($('#totalPrice').text()) + (response.data.price * 1));

                        let trHtml = '<tr>'
                            + '<td>' + response.data.code + '</td>'
                            + '<td>' + response.data.title_ar + '</td>'
                            + '<td><input name="amounts[]" type="number" id="amount_' + id + '" ' +
                            'onchange="changeQuantity(this , ' + id + ' , ' + response.data.price + ');" ' +
                            'value="1" min="1" max="' + response.data.store_stock + '" step="1" /></td>'
                            + '<td>' + response.data.price + '</td>'
                            + '<td id="quantity_' + id + '">1</td>'
                            + '<td id="price_' + id + '">' + response.data.price + '</td>'
                            + '<td><span onclick="removeProduct(this , ' + id + ')" class="label label-danger"><li class="fa fa-trash"></li></span></td>'
                            + '</tr>';
                        tBody.append(trHtml);
                        $('#discount').val(parseInt($('#discount').val()) + 0);
                        $('#discount').trigger('change');
                    }
                });
            }
            selection.value = '';
        }

        function changeQuantity(item, id, price) {
            let quantity = $(item).val();
            let max = $(item).attr('max');

            if(parseInt(quantity) > parseInt(max)) {
                $(item).val(max);
                return;
            }

            $('#totalPrice').text(parseFloat($('#totalPrice').text()) -
                parseFloat($('#price_' + id).text()));
            $('#quantity_' + id).text(quantity);
            $('#price_' + id).text(quantity * price);
            $('#totalPrice').text(parseFloat($('#totalPrice').text()) + (quantity * price));
            let productIndex = productsIds.indexOf(id);
            quantities[productIndex] = quantity;
        }

        function removeProduct(item, id) {
            $('#totalPrice').text(parseFloat($('#totalPrice').text()) -
                parseFloat($('#price_' + id).text()));
            $(item).parent('td').parent('tr').remove();
            let productIndex = productsIds.indexOf(id);
            productsIds.splice(productIndex, 1);
            quantities.splice(productIndex, 1);

        }

        function reCalcutaletePrice(item) {
            let discount = parseInt($(item).val());
            let price = parseInt($('#totalPrice').text());
            if (discount >= 0 && price > 0) {
                $('#totalPrice').text((parseFloat($('#totalPrice').text()) -
                    discount) + oldDiscount);
                oldDiscount = discount;
            }
        }

    </script>

    <script>
        $('#status').select2();
        $('#select_product').select2({
            'placeholder': 'إختر المنتج ',
        });
        $('#paymethod').select2({
            'placeholder': 'إختر طريقة الدفع ',
        });

    </script>
@endsection

@section('style')
    <link href="{{URL::to('resources/assets/back/css/jquery.loader.css')}}" rel="stylesheet"/>
@stop