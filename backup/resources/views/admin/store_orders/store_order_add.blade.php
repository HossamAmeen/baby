@extends('layouts.admin')

@section('content')


    {!! Form::open(['route' => 'store_orders.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}

    <div id="display_customer">
        <div class="row">
            <div class="form-group col-md-6">
                <label>{{trans('home.customer_name')}}</label>
                <input required type="text" name="customer_name" class="form-control" placeholder="{{trans('home.customer_name')}}"/>
            </div>

            <div class="form-group col-md-6">
                <label>{{trans('home.customer_phone')}}</label>
                <input required type="text" name="customer_phone" class="form-control"
                       placeholder="{{trans('home.customer_phone')}}"/>
            </div>
        </div>
        <div class="form-group select-group">
            <label>{{trans('home.country')}}</label>
            <select class="form-control" name="country" onchange="regions()" id="country" required>
                <option></option>
                @foreach($counteries as $countery)
                    <option value="{{ $countery-> id }}">{{ $countery->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group select-group">
            <label>{{trans('home.region')}}</label>
            <select class="form-control" name="region" onchange="areas()" id="region" required>
                <option></option>
                @foreach($regions as $region)
                    <option value="{{ $region -> id }}">{{ $region ->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group select-group">
            <label>{{trans('home.area')}}</label>
            <select onchange="shipping()" class="form-control" name="area" id="area" required>
                <option></option>
                @foreach($areas as $area)
                    <option value="{{ $area -> id }}">{{ $area ->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="address">{{trans('home.address')}} :</label>
            <input required type="text" class="form-control" placeholder="{{trans('home.address')}}" name="address">
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label>ملاحظات</label>
                <textarea class="form-control" placeholder="ملاحظات" name="notes"></textarea>
            </div>



            <div class="form-group col-md-6">
                <label for="admin_name">أدمن إضافي</label>
                <input type="text" class="form-control" placeholder="أدمن إضافي" name="admin_name">
            </div>
        </div>
    </div><br>

    <div class="form-group select-group">
        <label>{{trans('home.products')}}</label>
        <select class="form-control" onchange="quantity()" name="select_product" id="select_product">
            <option></option>
            @foreach($products as $product)
                <option value="{{ $product -> id }}">{{ $product->code .' - '. $product->title_ar }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label>{{trans('home.paymethod')}}</label>
        <select class="form-control" name="paymethod" id="paymethod" required>
            <option></option>
            @foreach($paymethods as $paymethod)
                <option value="{{ $paymethod -> id }}">{{ $paymethod ->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label for="shipping_price">{{trans('home.shipping_price')}} :</label>
        <input type="number" class="form-control" id="shipping_price" name="shipping_price" min="1"
               step="0.01" required/>
    </div>

    <div class="form-group select-group">
        <label for="delivery_id">{{trans('home.deliveries')}} :</label>
        {!! Form::select('delivery_id',(['' => ''])+$deliveries,null,['id' => 'delivery_id','class' => 'form-control']) !!}
    </div>

    <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
            <option></option>
            <option selected="" value="1"> مُعَلَّق </option>
            <option value="2">مُؤَكَّد </option>
            <option value="3"> مُلْغًى </option>
            <option value="4"> مشحون </option>
            <option value="5">مرتجع</option>
            <option value="6">تم التسليم </option>
        </select>
        </select>
    </div>

    <div id="display_products">

    </div>
    <hr/>

    <div>
        <span>{{trans('home.total_price')}}: <h4 id="totalPrice">0</h4></span>
    </div>

    <hr/>

    <div class="btns-bottom">
        <button id="submitBtn" type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('store_orders') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>


    {!! Form::close() !!}

@endsection

@section('script')

    <script type="text/javascript">
        let totalPrice = 0;

        $('#submitBtn').click(function () {
                $(this).css('opacity', '.65')
                $(this).css('cursor', 'not-allowed')
        });

        $('#status').on('change', function () {
            let status = $(this).find('option:selected').val();
            if (status == 4 || status == 5 || status == 6) {
                $('#delivery_id').prop('required', true);
                if (status == 5 || status == 6)
                    $('input[name=actual_shipping_price]').prop('required', true);
                else
                    $('input[name=actual_shipping_price]').prop('required', false);
            } else {
                $('#delivery_id').prop('required', false);
                $('input[name=actual_shipping_price]').prop('required', false);
            }
        })

        var productsIds = [];
        function quantity() {
            var selection = document.getElementById('select_product');
            var text = selection.options[selection.selectedIndex].text;
            var id = selection.options[selection.selectedIndex].value;

            let form = new FormData();
            form.append('id' , id);
            $.ajax({
                url: '{{url('/get/category/details')}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                success: function (response) {

                    productsIds.push(id);
                    var newdiv = document.createElement("div");
                    $('#totalPrice').text( parseFloat($('#totalPrice').text()) + (((parseInt(response.data.discount) > 0) ? response.data.price - response.data.discount : response.data.price) * 1));
                    var html = '<h3>' + text + '</h3>' +
                        '<h5>{{trans('home.quantity')}}</h5>' +
                        '<div class="input-append">' +
                        '<img style="width:50px;" src="'+response.data.image+'" />'+
                        '<input type="hidden" name="products[]" value="' + id + '">' +
                        '<input type="hidden" id="price_' + id + '" value="' + ((parseInt(response.data.discount) > 0) ? response.data.price - response.data.discount : response.data.price) + '">' +
                        '<input class="span2" value="1" name="amounts[]" id="amount' + id + '"  size="16" type="text" readonly>' +
                        '<button type="button" class="btn" onclick="increase(' + id + ' , '+response.data.stock+');"><b class="icon-minus">+++</b></button>' +
                        '<button type="button" class="btn" onclick="decrease(' + id + ' , '+response.data.stock+');"><b class="icon-plus">---</b></button></div>' +
                        '<button type="button" id="btndelete" onclick="deleteelebt(this,' + id + ')" class="btn btn-danger">{{trans('home.delete')}}</button>';
                    newdiv.innerHTML = html;
                    document.getElementById('display_products').appendChild(newdiv);
                    shipping();
                    $('#select_product').find('option:selected').remove();
                    // $.loader('close');
                }
            });
        }


        function regions() {
            var selection = document.getElementById('country');
            var id = selection.options[selection.selectedIndex].value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("region").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "{{ url('country/regions') }}" + "/" + id, true);
            xhttp.send();

        }

        function areas() {
            var selection = document.getElementById('region');
            var id = selection.options[selection.selectedIndex].value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("area").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "{{ url('region/areas') }}" + "/" + id, true);
            xhttp.send();

            shipping();
        }

        function shipping() {
            var selection = document.getElementById('region');
            var id = selection.options[selection.selectedIndex].value;
            // selection = document.getElementById('select_product');
            // var product_id = selection.options[selection.selectedIndex].value;
            selection = document.getElementById('area');
            var area_id = selection.options[selection.selectedIndex].value;
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("shipping_price").value = this.responseText;
                }
            };
            var  product_id = JSON.stringify(productsIds);
            xhttp.open("GET", "{{ url('region/getShippingPrice') }}"+"?region_id="+id
                +"&product_id="+product_id+"&area_id="+area_id, true);
            xhttp.send();
        }

        function increase(id , max) {
            var x = parseInt(document.getElementById("amount" + id).value);
            if ((x >= 1) && (x < max)) {
                x++;
                document.getElementById("amount" + id).value = x;
                let price = parseFloat(document.getElementById("price_" + id).value);
                $('#totalPrice').text( parseFloat($('#totalPrice').text()) + (price));
            }
        }

        function decrease(id , max) {
            var x = parseInt(document.getElementById("amount" + id).value);
            if ((x > 1) && (x <= max)) {
                x--;
                document.getElementById("amount" + id).value = x;
                let price = parseFloat(document.getElementById("price_" + id).value);
                $('#totalPrice').text( parseFloat($('#totalPrice').text()) - (price));
            }
        }

        function deleteelebt(ob,id) {
            var x = parseInt(document.getElementById("amount" + id).value);
            let price = parseFloat(document.getElementById("price_" + id).value);
            $('#totalPrice').text( parseFloat($('#totalPrice').text()) - (price * x));
            var container = document.getElementById('display_products');
            container.removeChild(ob.parentNode);
            productsIds.slice(productsIds.indexOf(id),1);
            shipping();
        }

    </script>

    <script>
        $('#country').select2();
        $('#region').select2();
        $('#area').select2();
        $('#delivery_id').select2();
        $('#admin_id').select2();
        $('#status').select2();
        $('#select_product').select2({
            'placeholder': 'إختر المنتج ',
        });
        $('#paymethod').select2({
            'placeholder': 'إختر طريقة الدفع ',
        });

    </script>
@endsection