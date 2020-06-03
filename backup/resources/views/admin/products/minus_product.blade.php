@extends('layouts.admin')

@section('content')


    {!! Form::open(['id' => 'formSubmit','data-toggle'=>'validator', 'files'=>'true']) !!}

    <div id="display_customer">

        <div class="row">
            <div class="form-group col-md-6">
            </div>

            <div class="form-group col-md-6">
                <label for="to">إسم المخزن :</label>
                <select class="form-control" name="to" id="to" required>
                    <option value="0" selected> المخزن الرئيسي</option>
                    <option value="1">المخزن الفرعى</option>
                    <option value="2">المحل</option>
                </select>
            </div>
        </div>


        <div class="row">

            <div class="form-group col-md-6">
                <label>ملاحظات</label>
                <textarea class="form-control" name="notes"></textarea>
            </div>
            <div class="form-group col-md-6">
                <label>كود المنتج</label>
                <input type="text" id="number" name="number" class="form-control"
                       placeholder="كود المنتج"/>
            </div>
        </div>

        <div class="row" id="product_row">

        </div>

        <div class="row">
            <div class="col-md-6">
                <button type="button" onclick="addProduct()" class="btn btn-primary">إضافة</button>
            </div>
        </div>
    </div>
    <br>

    <hr/>
    <div id="tableDiv">
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

                </tbody>
            </table>
        </div>
    </div>
    <hr/>


    <div class="btns-bottom">
        <button id="submitBtn" type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    </div>
    <div class="row" id="message" style="display: none; background: #ffbcbc;border: 1px solid;">
        <span style="color: #1c7430">
            تمت الإضافة بنجاح
        </span>
    </div>

    {!! Form::close() !!}


@endsection

@section('script')
    <script src="{{URL::to('resources/assets/back/js/jquery.loader.js')}}"></script>
    <script type="text/javascript">
        let products = [];
        let productsLength = 0;
        let tempProduct = null;

        let type = 3;

        $(function () {

            $('#to').select2();


            $('#number').keyup(function (e) {
                if(e.keyCode === 13) return;
                let number = $('#number').val();
                if (number.length >= 4) {
                    $('#to').attr('disabled' , true);
                    let form = new FormData();
                    form.append('code', number);
                    form.append('type', type);
                    $.ajax({
                        url: '{{url('/get/product/details')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            let fromStock = parseInt($('#to').find('option:selected').val());
                            let quantity = 0;
                            if (fromStock === 0) {
                                quantity = response.data.main_stock;
                            } else if (fromStock === 1) {
                                quantity = response.data.sub_stock;
                            } else if (fromStock === 2) {
                                quantity = response.data.store_stock;
                            }

                            if(products.find(function (product) {
                                return parseInt(product.id) === parseInt(response.data.id)
                            }) !== undefined){
                                return;
                            }


                            if (parseInt(quantity) === 0)
                                return;

                            tempProduct = {
                                id: response.data.id,
                                quantity: 1,
                                title: response.data.title_ar,
                                code: response.data.code,
                                price: response.data.price,
                                buying_price: 0
                            };

                            let html = ((parseInt(type) === 0) ? '<div class="form-group col-md-3">' +
                                '                <label>سعر الشراء</label>' +
                                '                <input type="number" step="1" min="0" class="form-control"' +
                                '                onchange="onChangeBuyingPrice(this)" placeholder="سعر الشراء"/>' +
                                '            </div>' : '') +
                                '            <div class="form-group col-md-3">' +
                                '                <label>الكمية</label>' +
                                '                <input type="number" step="1" min="1" value="1" ' +
                                'class="form-control" onchange="onChangeQuantity(this)"' +
                                ' max="' + quantity + '"' +
                                '                       placeholder="الكمية"/>' +
                                '            </div>' +
                                '            <div class="form-group col-md-3">' +
                                '                <label>السعر</label>' +
                                '                <input type="text" readonly value="' + response.data.price + '" class="form-control"' +
                                '                       placeholder="السعر"/>' +
                                '            </div>' +
                                '            <div class="form-group col-md-3">' +
                                '                <label>إسم المنتج</label>' +
                                '                <input type="text" readonly value="' + response.data.title_ar + '" class="form-control"' +
                                '                       placeholder="إسم المنتج"/>' +
                                '            </div>';
                            $('#product_row').html(html);
                        }
                    });
                }
            });

            $('#formSubmit').on('submit', function (e) {
                e.preventDefault();
                saveOrder();
            });

        });


        function onChangeQuantity(item) {
            let type = $('#type').find('option:selected').val();
            let quantity = $(item).val();
            if(parseInt(type) === 1){
                let max = $(item).attr('max');
                if(parseInt(max) < quantity){
                    $(item).val(parseInt(max));
                    tempProduct.quantity = parseInt(max);
                    return;
                }
            }

            tempProduct.quantity = quantity;
        }

        function onChangeBuyingPrice(item) {
            let buyingPrice = $(item).val();
            tempProduct.buying_price = buyingPrice;
        }

        function addProduct() {
            if (tempProduct !== null) {
                products.push(tempProduct);
                let id = tempProduct.id;
                let rawHtml = '<tr>' +
                    '<td>' + tempProduct.code + '</td>' +
                    '<td>' + tempProduct.title + '</td>' +
                    '<td>' + tempProduct.quantity + '</td>' +
                    '<td><span onclick="removeProduct(this , '+productsLength+')" ' +
                    'class="label label-danger"><li class="fa fa-trash"></li></span></td>' +
                    '</tr>';
                productsLength++;
                $('tbody').append(rawHtml);
                tempProduct = null;
                $('#product_row').html('');
                $('#number').val('');
            }
        }

        function saveOrder() {
            let form = new FormData();
            if (products.length > 0) {
                $.loader({
                    className: "blue-with-image-2",
                    content: ''
                });
                form.append('products', JSON.stringify(products));
                form.append('type', type);
                form.append('to', $('#to').find('option:selected').val());
                form.append('notes', $('textarea[name=notes]').val());


                $.ajax({
                    url: '{{url('/product/minus/save')}}',
                    method: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        $('#message').fadeIn('slow', function () {
                            $('#message').delay(1000).fadeOut();
                        });
                        freeOrder();
                        $('#to').attr('disabled' , false);
                        $.loader('close');
                    },
                    error: function () {
                        $.loader('close');
                    }
                });
            }
        }

        function freeOrder() {
            $('tbody').html('');
            products = [];
            $('#number').val('');
            $('#product_row').html('');
            $('tbody').html('');
        }

        function removeProduct(item, index) {
            $(item).parent('td').parent('tr').remove();
            products.splice(index, 1);
            productsLength--;
        }

        function changeQuantity(item, id) {
            let productIndex = productsIds.indexOf(id);
            if (productIndex !== -1)
                quantities[productIndex] = quantity;
        }

        function onchangeProduct(item, id) {
            if ($(item).is(':checked')) {
                productsIds.push(id);
                quantities.push($('#amount_' + id).val());
                $(item).parent('td').parent('tr').css('background', '#dedede');
            } else {
                let productIndex = productsIds.indexOf(id);
                if (productIndex !== -1) {
                    productsIds.splice(productIndex, 1);
                    quantities.splice(productIndex, 1);
                    $(item).parent('td').parent('tr').css('background', 'white');
                }
            }
        }

    </script>

@endsection


@section('style')
    <link href="{{URL::to('resources/assets/back/css/jquery.loader.css')}}" rel="stylesheet"/>
@stop