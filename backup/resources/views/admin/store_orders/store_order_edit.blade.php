@extends('layouts.admin')

@section('content')

    <h1>{{ trans('home.order_operations') }}</h1>
    <table>
        <thead>
        <th>{{ trans('home.message') }}</th>
        <th>{{ trans('home.date') }}</th>
        <th>{{ trans('home.user') }}</th>
        </thead>
        @foreach($orderoperation as $or)
            <tr>
                <td>  {{$or->message}}</td>
                <td>  {{$or->date_time}}</td>
                <td>  {{$or->User->name }}</td>
            </tr>
        @endforeach
    </table>


    {!! Form::open([ 'method'=>'PATCH','url' => 'store_orders/'.$order[0]->number ]) !!}

    <div id="display_customer">
        <input type="hidden" name="id" value="{{$order[0]->number}}">
        <div class="row">
            <div class="form-group col-md-6">
                <label>{{trans('home.customer_name')}}</label>
                <input type="text" name="customer_name" class="form-control"
                       value="{{$order[0]->name}}" required
                       placeholder="{{trans('home.customer_name')}}"/>
            </div>

            <div class="form-group col-md-6">
                <label>{{trans('home.customer_phone')}}</label>
                <input type="text" name="customer_phone" class="form-control"
                       value="{{$order[0]->phone}}" required
                       placeholder="{{trans('home.customer_phone')}}"/>
            </div>
        </div>

        <?php
        $address = \App\Address::where(['id' => $order[0]->address_id])->first();
        $region = null;
        $area = null;
        if ($address) {
            $region = \App\Region::where(['id' => $address->region_id])->first();
            $area = \App\Area::where(['id' => $address->area_id])->first();
        }
        ?>

        <div class="form-group select-group">
            <label>{{trans('home.country')}}</label>
            <select class="form-control" name="country" onchange="regions()" id="country" required>
                <option></option>
                @foreach($counteries as $countery)
                    <option @if($address && $address->country_id == $countery->id) selected
                            @endif value="{{ $countery-> id }}">{{ $countery->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group select-group">
            <label>{{trans('home.region')}}</label>
            <select class="form-control" name="region" onchange="areas()" id="region" required>
                <option></option>
                @foreach($regions as $regionObj)
                    <option @if($address && $region && $address->region_id == $regionObj->id) selected
                            @endif  value="{{ $regionObj -> id }}">{{ $regionObj ->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group select-group">
            <label>{{trans('home.area')}}</label>
            <select class="form-control" name="area" id="area" required>
                <option></option>
                @foreach($areas as $areaObj)
                    <option @if($address && $area && $address->area_id == $areaObj->id) selected
                            @endif  value="{{ $areaObj -> id }}">{{ $areaObj ->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="address">{{trans('home.address')}} :</label>
            <input type="text" class="form-control" required
                   value="@if($address){{$address->address}}@endif"
                   placeholder="{{trans('home.address')}}" name="address">
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label>ملاحظات</label>
                <textarea class="form-control" placeholder="ملاحظات" name="notes">{{$order[0]->notes}}</textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="admin_name">أدمن إضافي</label>
                <input type="text" class="form-control"
                       value="{{$order[0]->admin_name}}"
                       placeholder="أدمن إضافي" name="admin_name">
            </div>
        </div>
    </div><br>

    @if($order[0]->status_id === 1 || $order[0]->status_id === 2)
        <div class="form-group select-group">
            <label>{{trans('home.products')}}</label>
            <select class="form-control" onchange="quantity()" name="select_product" id="select_product">
                <option></option>
                @foreach($products as $product)
                    <option value="{{ $product -> id }}">{{ $product->code .' - '. $product->title_ar }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="form-group select-group">
        <label>{{trans('home.paymethod')}}</label>
        <select class="form-control" name="paymethod" id="paymethod" required>
            <option></option>
            @foreach($paymethods as $paymethod)
                <option @if($order[0]->payment_id == $paymethod->id) selected
                        @endif value="{{ $paymethod -> id }}">{{ $paymethod ->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label for="shipping_price">{{trans('home.shipping_price')}} :</label>
        <input type="number" class="form-control" value="{{$order[0]->shipping_price}}" name="shipping_price" min="1"
               step="0.01" required/>
    </div>

    <div class="form-group select-group">
        <label for="actual_shipping_price">{{trans('home.actual_shipping_price')}} :</label>
        <input type="number" class="form-control" id="actual_shipping_price" name="actual_shipping_price" min="0"
               step="0.01"/>
    </div>

    <div class="form-group select-group">
        <label for="delivery_id">{{trans('home.deliveries')}} :</label>
        <select class="form-control" name="delivery_id" id="delivery_id">
            <option></option>
            @foreach($deliveries as $delivery)
                <option @if($order[0]->delivery_id == $delivery->id) selected
                        @endif value="{{ $delivery->id }}">{{ $delivery->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
            <option></option>
            @foreach($status as $s)

                <option @if($order[0]->status_id === $s->id) selected
                        @endif value="{{ $s->id }}">{{trans("home.$s->name")}}</option>
                @if($order[0]->status_id == 5) @break @endif
            @endforeach
        </select>
    </div>

    @if($order[0]->status_id === 1 || $order[0]->status_id === 2)
        <div id="display_products">
            @foreach($order as $or)
                <div>
                    <h3>@if($or->Product) {{ $or->Product->title_ar }} @else Product Deleted @endif</h3>
                    <h5>{{trans('home.quantity')}}</h5>
                    <div class="input-append">
                        {{--<input type="hidden" name="products[]" value="@if($or->Product) {{ $or->Product->id }}@endif">--}}
                        <input class="span2" value="{{$or->quantity}}" name="amounts[]"
                               @if($or->Product) id="{{'amount'.$or->Product->id }}" @endif size="16" type="text"
                               readonly>
                        <button type="button" class="btn"
                                onclick="increase('@if($or->Product){{ $or->Product->id }}@endif' , '@if($or->Product){{$or->Product->stock + $or->quantity }}@endif');">
                            <b class="icon-minus">+++</b>
                        </button>
                        <button type="button" class="btn"
                                onclick="decrease('@if($or->Product){{$or->Product->id}}@endif' , '@if($or->Product){{ $or->Product->stock  + $or->quantity }}@endif');">
                            <b class="icon-plus">---</b>
                        </button>
                        @if(count($order) > 1)
                            {{--<button type="button" class="btn"--}}
                                    {{--onclick="deleteProductOrder(this , '{{$or->id}}');"><b--}}
                                        {{--class="btn btn-danger">{{trans('home.delete')}}</b>--}}
                            {{--</button>--}}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('store_orders') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>


    {!! Form::close() !!}


    <div id="print2" class="row invoiceprint" style="padding:0px 20px;page-break-after: auto;">
        {{--@if(App::isLocale('ar'))--}}
        {{--<!--<img class="invoiceprintimg" src="{{ url('uploads/configurationsite/resize200') }}/{{ $con->logo }}"> -->--}}
        {{--<img class=" invoiceprintimg img-responsive" src="{{ URL::to('resources/assets/front/img/logo-ar.png') }}">--}}
        {{--<!--<img class="invoiceprintimg2" src="{{ url('uploads/configurationsite/resize200') }}/{{ $con->logo }}"> -->--}}
        {{--<img class=" invoiceprintimg2 img-responsive"--}}
        {{--src="{{ URL::to('resources/assets/front/img/logo-ar.png') }}">--}}
        {{--@else--}}
        <img class=" invoiceprintimg img-responsive" src="{{ URL::to('resources/assets/front/img/logo-eng.png') }}">
        <img class=" invoiceprintimg2 img-responsive"
             src="{{ URL::to('resources/assets/front/img/logo-eng.png') }}">
        {{--@endif--}}
        <div class="col-xs-3">

            <img class=" img-responsive" src="{{ URL::to('resources/assets/front/img/logo-eng.png') }}">
        </div>
        <div class="col-xs-6"></div>
        <div class="col-xs-3">
            <p class="pull-left">website:www.babymumz.com</p>
            <div class="clearfix"></div>
            <p class="pull-left">facebook:bm.babymumz</p>
            <div class="clearfix"></div>
            <p class="pull-left">instegram:baby_mumz</p>
        </div>
        <div class="col-xs-3">
            <p><strong>Order Number: {{$order[0]->number }}</strong></p>
        </div>
        <div class="col-xs-9">
            <p class="pull-right"><strong>Date: {{$order[0]->created_at }}</strong></p>
        </div>
        <table class="table table-bordered" style="margin-bottom: 0px; margin-top:1px;">


            <tr>
                <td>{{ trans('home.customer_name') }}</td>
                <td> {{ $order[0]->name }}  </td>
            </tr>
            <tr>
                <td>{{ trans('home.customer_phone') }}</td>
                <td>{{ $order[0]->phone }}</td>
            </tr>
            <tr>
                <td>{{trans('home.region') . ' / '.trans('home.area')}}</td>
                <td>@if($region && $area) {{$region->name . ' , ' . $area->name}} @endif</td>
            </tr>
            <tr>
                <td>{{ trans('home.address') }}</td>
                <td>@if($address) {{ $address->address }} @endif</td>
            </tr>
        </table>
        <table class="table table-bordered" style="margin-bottom: 0px; margin-top:1px;">

            <tr>
                <th>{{ trans('home.title') }} {{ trans('home.product') }}</th>
                <th>{{ trans('home.code') }}</th>
                <th>{{ trans('home.price') }}</th>
                <th>{{ trans('home.quantity') }}</th>
                <th>{{ trans('home.option_price') }}</th>
                <th>{{ trans('home.coupon_price') }}</th>
                <th>{{ trans('home.total_price') }}</th>
            </tr>

            <tbody>
            @php
                $total = 0;
                $subtotal = 0
            @endphp
            @foreach($order as $or)

                <tr>
                    <td class="font-weight-bold">@if($or->Product) {{ $or->Product->title_ar }} @else Product
                        Deleted @endif</td>
                    <td class="font-weight-bold">@if($or->Product) {{ $or->Product->code}} @else Product
                        Deleted @endif</td>
                    <td class="font-weight-bold">{{ $or->one_price }}</td>
                    <td class="font-weight-bold">{{ $or->quantity }}</td>
                    <td class="font-weight-bold">{{ $or->option_price }} +</td>
                    <td class="font-weight-bold">{{ $or->coupon_price }} -</td>
                    <td class="font-weight-bold">{{ ($or->one_price * $or->quantity) + $or->option_price - $or->coupon_price }}</td>
                </tr>
                @php
                    $total += $or->total_price;
                    $subtotal += ($or->one_price * $or->quantity) + $or->option_price - $or->coupon_price;
                @endphp
            @endforeach
            </tbody>
        </table>
        <table class="table table-bordered" style="margin-bottom: 0px; margin-top:1px;">
            <tr>
                <td style="width: 25%;">{{ trans('home.subtotal') }}</td>
                <td>  {{ $subtotal }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">{{ trans('home.shipping_price') }}</td>
                <td>  {{ $order[0]-> shipping_price }}</td>
            </tr>
            <tr>
                <td>{{ trans('home.paymethod') }}</td>
                <?php $payMethod = \App\Paymethod::where(['id' => $order[0]->payment_id])->first(); ?>
                <td> @if($payMethod) @if(\Session::get('lang') == 'ar'){{$payMethod->name}} @else {{$payMethod->name_en}} @endif @endif</td>
            </tr>
            <tr>
                <td>{{ trans('home.payment_price') }}</td>
                <td> {{$order[0]->payment_price}}</td>
            </tr>
            <tr>
                <td>{{ trans('home.total') }}</td>
                <td>{{$total}}</td>
            </tr>
        </table>
        <div class="note text-center"><p><strong>Check the return policy from the website</strong></p></div>

        <div class="cuticon"><img src="{{ URL::to('resources/assets/back/images/cuticon.png') }}"></div>


        <div class="col-xs-3">
            <p><strong>Order Number: {{$order[0]->number }}</strong></p>
        </div>
        <div class="col-xs-9">
            <p class="pull-right"><strong>Date: {{$order[0]->created_at }}</strong></p>
        </div>
        <table class="table table-bordered" style="margin-bottom: 0px; margin-top:1px;">


            <tr>
                <td>{{ trans('home.customer_name') }}</td>
                <td> {{ $order[0]->name }} </td>
            </tr>
            <tr>
                <td>{{ trans('home.customer_phone') }}</td>
                <td>{{ $order[0]->phone }} </td>
            </tr>
            <tr>
                <td>{{trans('home.region') . ' / '.trans('home.area')}}</td>

                <td>@if($region && $area) {{$region->name . ' , ' . $area->name}} @endif</td>
            </tr>
            <tr>
                <td>{{ trans('home.address') }}</td>
                <td> @if($address) {{$address->address }} @endif</td>
            </tr>
        </table>
        <table class="table table-bordered" style="margin-bottom: 0px; margin-top:1px;">

            <tr>
                <th>{{ trans('home.title') }} {{ trans('home.product') }}</th>
                <th>{{ trans('home.code') }}</th>
                <th>{{ trans('home.price') }}</th>
                <th>{{ trans('home.quantity') }}</th>
                <th>{{ trans('home.option_price') }}</th>
                <th>{{ trans('home.coupon_price') }}</th>
                <th>{{ trans('home.total_price') }}</th>
            </tr>

            <tbody>

            @foreach($order as $or)

                <tr>
                    <td>@if($or->Product) {{ $or->Product->title_ar }} @else Product Deleted @endif</td>
                    <td>@if($or->Product) {{ $or->Product->code}} @else Product Deleted @endif</td>
                    <td>{{ $or->one_price }}</td>
                    <td>{{ $or->quantity }}</td>
                    <td>{{ $or->option_price }} +</td>
                    <td>{{ $or->coupon_price }} -</td>
                    <td>{{ ($or->one_price * $or->quantity) + $or->option_price - $or->coupon_price }}</td>
                </tr>

            @endforeach


            </tbody>
        </table>
        <table class="table table-bordered" style="margin-bottom: 0px; margin-top:1px;">
            <tr>
                <td style="width: 25%;">{{ trans('home.subtotal') }}</td>
                <td>  {{ $subtotal }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">{{ trans('home.shipping_price') }}</td>
                <td>  {{ $order[0]-> shipping_price }}</td>
            </tr>
            <tr>
                <td>{{ trans('home.paymethod') }}</td>
                <?php $payMethod = \App\Paymethod::where(['id' => $order[0]->payment_id])->first(); ?>
                <td>@if($payMethod) @if(\Session::get('lang') == 'ar'){{$payMethod->name}} @else {{$payMethod->name_en}} @endif @endif</td>
            </tr>
            <tr>
                <td>{{ trans('home.payment_price') }}</td>
                <td> {{$order[0]->payment_price}}</td>
            </tr>
            <tr>
                <td>{{ trans('home.total') }}</td>
                <td>{{$total}}</td>
            </tr>
        </table>
        <div class="row pay-block">
            <div class="col-md-6 col-xs-6 ">

                <div class="col-md-12">
                    <p> {{trans('home.recipient_signature')}} </p>
                </div>
                <div class="col-md-12">
                    <p> .....................</p>
                </div>
            </div>

            <div class="col-md-6 col-xs-6 ">

                <div class="col-md-12">
                    <p> {{trans('home.delivery_signature')}} </p>
                </div>
                <div class="col-md-12">
                    <p> ............................</p>
                </div>
            </div>
        </div>
    </div>

    <div class="progress-btn">
        <button type="button" id="print" class="ui basic button">إطبع الفاتورة</button>
    </div>




@endsection

@section('script')
    {{--    <script src="{{ URL::to('resources/assets/back/js/jquery.PrintArea.js') }}"></script>--}}
    <script src="{{ URL::to('resources/assets/back/js/printThis.js') }}"></script>
    <script type="text/javascript">

        var productsIds = [];

        function quantity() {
            var selection = document.getElementById('select_product');
            var text = selection.options[selection.selectedIndex].text;
            var id = selection.options[selection.selectedIndex].value;

            let form = new FormData();
            form.append('id', id);
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
                    $('#totalPrice').text(parseFloat($('#totalPrice').text()) + (response.data.price * 1));
                    var html = '<h3>' + text + '</h3>' +
                        '<h5>{{trans('home.quantity')}}</h5>' +
                        '<div class="input-append">' +
                        '<img style="width:50px;" src="' + response.data.image + '" />' +
                        '<input type="hidden" name="products[]" value="' + id + '">' +
                        '<input type="hidden" id="price_' + id + '" value="' + ((response.data.discount > 0) ? response.data.price - response.data.discount : response.data.price) + '">' +
                        '<input class="span2" value="1" name="addamounts[]" id="amount' + id + '"  size="16" type="text" readonly>' +
                        '<button type="button" class="btn" onclick="increase(' + id + ' , ' + response.data.stock + ');"><b class="icon-minus">+++</b></button>' +
                        '<button type="button" class="btn" onclick="decrease(' + id + ' , ' + response.data.stock + ');"><b class="icon-plus">---</b></button></div>' +
                        '<button type="button" id="btndelete" onclick="deleteelebt(this,' + id + ')" class="btn btn-danger">{{trans('home.delete')}}</button>';
                    newdiv.innerHTML = html;
                    document.getElementById('display_products').appendChild(newdiv);
                    shipping();
                    $('#select_product').find('option:selected').remove();
                    // $.loader('close');
                }
            });
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
            var product_id = JSON.stringify(productsIds);
            xhttp.open("GET", "{{ url('region/getShippingPrice') }}" + "?region_id=" + id
                + "&product_id=" + product_id + "&area_id=" + area_id, true);
            xhttp.send();
        }

        function deleteelebt(ob, id) {
            var x = parseInt(document.getElementById("amount" + id).value);
            let price = parseFloat(document.getElementById("price_" + id).value);
            $('#totalPrice').text(parseFloat($('#totalPrice').text()) - (price * x));
            var container = document.getElementById('display_products');
            container.removeChild(ob.parentNode);
            productsIds.slice(productsIds.indexOf(id), 1);
            shipping();
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

        }

        function deleteProductOrder(item , orderId) {
            let form = new FormData();
            form.append('id', orderId);
            $.ajax({
                url: '{{url('/store_order/product/delete')}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                success: function (response) {
                    $(item).parent('div').parent('div').remove();
                }
            });
        }


        function increase(id, max) {
            var x = parseInt(document.getElementById("amount" + id).value);
            if ((x >= 1) && (x < max)) {
                x++;
                document.getElementById("amount" + id).value = x;
            }
        }

        function decrease(id, max) {
            var x = parseInt(document.getElementById("amount" + id).value);
            if ((x > 1) && (x <= max)) {
                x--;
                document.getElementById("amount" + id).value = x;
            }
        }

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

    </script>

    <script>


        //        $('#customer').select2({
        //            'placeholder': 'إختر العميل ',
        //        });
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

    <script>
        $(function () {
            //Print order
            $("#print").click(function () {
                // var mode = 'iframe'; // popup
                // var close = mode == "popup";
                // var options = {
                //     mode: mode,
                //     popClose: close
                // };
                // $("#print2").printArea(options);
                $("#print2").printThis({
                    importCSS: true,            // import page CSS
                    importStyle: true,         // import style tags
                    printContainer: true,
                    printDelay: 333,
                    header: null,
                    formValues: true
                });
            });
        });
    </script>
@endsection