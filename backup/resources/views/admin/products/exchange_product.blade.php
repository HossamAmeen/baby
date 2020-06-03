@extends('layouts.admin')

@section('content')


    {!! Form::open(['id' => 'formSubmit','data-toggle'=>'validator', 'files'=>'true']) !!}

    <div id="display_customer">

        <div class="row">

            <div class="form-group col-md-6">
                <label for="to">إسم المخزن إلى :</label>
                <select class="form-control" name="to" id="to" required>
                    <option value="0" > المخزن الرئيسي</option>
                    <option value="1" selected>المخزن الفرعى</option>
                    <option value="2">المحل</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="from">إسم المخزن من :</label>
                <select class="form-control" name="from" id="from" required>
                    <option value="0" selected> المخزن الرئيسي</option>
                    <option value="1" >المخزن الفرعى</option>
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
                <th>كود الصنف</th>
                <th>إسم الصنف</th>
                <th>الكمية</th>
                <th>سعر</th>
                <th>العمليات</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <hr/>
<div class="row">
    <bdi>
        <span> المجموع :</span>
        <span id="total">0</span>
    </bdi>
</div>
<hr />
    <div class="btns-bottom">
        <button id="submitBtn" type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    </div>
    <div class="row" id="message" style="display: none; background: #ffbcbc;border: 1px solid;">
        <span style="color: #1c7430">
            تمت الإضافة بنجاح
        </span>
    </div>

    <div class="row" id="error-message" style="display: none; background: #ffbcbc;border: 1px solid;">
        <span style="color: #761c19">
            العملية مرفوضة
        </span>
    </div>

    {!! Form::close() !!}


@endsection

@section('script')
    <script src="{{URL::to('resources/assets/back/js/jquery.loader.js')}}"></script>
    <script type="text/javascript">
        let products = [];
        let tempProduct = null;
        let save = false;

        $(function () {

            $('#from').select2();
            $('#to').select2();

            // $('#from').on('change' , function () {
            //     freeOrder();
            // });

            $('#number').keyup(function (e) {
                if(e.keyCode === 13) return;
                let number = $('#number').val();
                if (number.length >= 4) {
                    $('#from').attr('disabled' , true);
                    $('#to').attr('disabled' , true);
                    let form = new FormData();
                    form.append('code', number);
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

                            console.log(products);
                            let productIndex = products.findIndex( (product) => {
                                return product.id === response.data.id;
                            });

                            if (productIndex !== -1){
                                return;
                            }

                            let fromStock = parseInt($('#from').find('option:selected').val());
                            let quantity = 0;
                            if(fromStock === 0){
                                quantity = response.data.main_stock;
                            }else if(fromStock === 1){
                                quantity = response.data.sub_stock;
                            }else if(fromStock === 2){
                                quantity = response.data.store_stock;
                            }

                            if (parseInt(quantity) === 0 )
                                return;
                            tempProduct = {
                                id: response.data.id,
                                quantity: 1,
                                title: response.data.title_ar,
                                code: response.data.code,
                                price: response.data.price,
                            };
                            let html =
                                '            <div class="form-group col-md-3">' +
                                '                <label>الكمية</label>' +
                                '                <input type="number" step="1" min="0" max="'+quantity+'" value="1" ' +
                                'class="form-control" onchange="onChangeQuantity(this)"' +
                                '                       placeholder="الكمية"/>' +
                                '            </div>' +
                                '<div class="form-group col-md-3">' +
                                '                <label>الكمية المتاحة</label>' +
                                '                <input type="text" readonly value="' + quantity + '" class="form-control"' +
                                '                       placeholder="الكمية المتاحة"/>' +
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
                if(save === false) {
                    save = true;
                    saveOrder();
                }
            });

        });


        function onChangeQuantity(item) {
            let quantity = $(item).val();
            tempProduct.quantity = quantity;
        }

        function onChangeBuyingPrice(item) {
            let buyingPrice = $(item).val();
            tempProduct.buying_price = buyingPrice;
        }

        function addProduct() {
            if(tempProduct !== null && tempProduct.quantity > 0) {
                products.push(tempProduct);
                let rawHtml = '<tr>' +
                    '<td>' + tempProduct.code + '</td>' +
                    '<td>' + tempProduct.title + '</td>' +
                    '<td>' + tempProduct.quantity + '</td>' +
                    '<td>' + (tempProduct.quantity * tempProduct.price) + '</td>' +
                    '<td><span onclick="removeProduct(this , '+tempProduct.id+')" ' +
                    'class="label label-danger"><li class="fa fa-trash"></li></span></td>'+
                    '</tr>';
                $('tbody').append(rawHtml);
                tempProduct = null;
                $('#product_row').html('');
                $('#number').val('');
                calculateTotal();
                console.log(products);
            }
        }

        function saveOrder() {
            let form = new FormData();
            let fromStock = parseInt($('#from').find('option:selected').val());
            let toStock = parseInt($('#to').find('option:selected').val());
            if (products.length > 0 && fromStock !== toStock) {
                $.loader({
                    className: "blue-with-image-2",
                    content: ''
                });
                form.append('products', JSON.stringify(products));
                form.append('type', 2);
                form.append('to', toStock);
                form.append('from', fromStock);
                form.append('notes', $('textarea[name=notes]').val());
                $.ajax({
                    url: '{{url('/product/permission/save')}}',
                    method: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        $('#message').fadeIn('slow', function(){
                            $('#message').delay(1000).fadeOut();
                        });
                        freeOrder();
                        $('#from').attr('disabled' , false);
                        $('#to').attr('disabled' , false);
                        $.loader('close');
                        save = false;
                    },
                    error: function () {
                        $('#error-message').fadeIn('slow', function(){
                            $('#error-message').delay(1000).fadeOut();
                        });
                        $.loader('close');
                        save = false;
                    }
                });
            }else{
                save = false;
            }
        }

        function freeOrder() {
            products = [];
            $('#number').val('');
            $('product_row').html('');
            $('tbody').html('');
            $('#total').text('0');
        }
        function removeProduct(item, productId) {
            let index = products.findIndex( (product) => {
                return product.id === productId;
            });
            if(index !== -1) {
                $(item).parent('td').parent('tr').remove();
                products.splice(index, 1);
                calculateTotal();
                console.log(products);
            }
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
        
        function calculateTotal() {
            let total = 0;
            for (let index = 0 ; index < products.length; index ++){
                total += (products[index].quantity * products[index].price);
            }
            $('#total').text(total);
        }

    </script>

@endsection

@section('style')
    <link href="{{URL::to('resources/assets/back/css/jquery.loader.css')}}" rel="stylesheet"/>
@stop