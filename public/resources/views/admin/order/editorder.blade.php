@extends('layouts.admin')

@section('style')
    <style>
        @media print {

            #print2 {
                page-break-after: avoid;
            }
        }

        @media print {
            #page1, #page2 {
                display: block;
                visibility: visible;
                position: relative;
            }
        }

    </style>
@endsection
@section('content')
    {!! Form::open(['method'=>'PATCH','url' => 'orders/'.$order[0]->number]) !!}


    <div class="form-group">
        <label>{{ trans('home.number') }} : {{ $order[0]->number }}</label>
    </div>
    <div class="form-group">
        <label>{{ trans('home.buyer_data') }}:</label> {{ trans('home.name') }}:
        @if($order[0]->user_name != null) {{ $order[0]->user_name }} @else {{ $order[0]->User->name }} @endif<br>
        {{ trans('home.phone') }}
        : @if($order[0]->user_phone != null) {{ $order[0]->user_phone }} @else {{ $order[0]->User->phone }} @endif<br>
        {{ trans('home.address') }}
        : @if($order[0]->user_address != null) {{ $order[0]->user_address }} @else @if($order[0]->Address) {{ $order[0]->Address->address }} @endif @endif
        <br>

    </div>

    <div class="form-group select-group">
        <label>{{trans('home.paymethod')}}</label>
        <select class="form-control" name="paymethod" id="paymethod" required>
            <option></option>
            @foreach($paymethods as $paymethod)
                <option value="{{ $paymethod -> id }}" {{ ($paymethod -> id == $order[0]->payment_id)?'selected':'' }}>
                    {{ $paymethod ->name }} ({{ $paymethod -> price }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label for="shipping_price">{{trans('home.shipping_price')}} :</label>
        <input type="number" class="form-control" name="shipping_price" value="{{ $order[0]-> shipping_price }}" min="1"
               step="0.01" required/>
    </div>

    <div class="form-group select-group">
        <label for="actual_shipping_price">{{trans('home.actual_shipping_price')}} :</label>
        <input type="number" class="form-control" name="actual_shipping_price" min="0"
               step="0.01" />
    </div>

    <div class="form-group select-group">
        <label for="delivery_id">{{trans('home.deliveries')}} :</label>
        @if($order[0]->delivery_id)
            {!! Form::select('delivery_id',(['' => ''])+$deliveries,$order[0]->delivery_id,['id' => 'delivery_id','class' => 'form-control']) !!}
        @else
            {!! Form::select('delivery_id',(['' => ''])+$deliveries,null,['id' => 'delivery_id','class' => 'form-control']) !!}
        @endif
    </div>

    <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
            <option></option>
            @foreach($status as $s)

                <option @if($order[0]->status_id == $s->id) selected
                        @endif value="{{ $s->id }}">{{trans("home.$s->name")}}</option>
                @if($order[0]->status_id == 5) @break @endif
            @endforeach
        </select>
    </div>
    <div class="row">
        @php
            $total = 0;
        @endphp
        @foreach($order as $or)

            <div style="border-style: solid; margin: 1px;padding:3px 5px;">
                <div class="form-group">
                    <p>{{ trans('home.title') }} : @if($or->Product) {{ $or->Product->title_ar }} @else Product
                        Deleted @endif </p>
                </div>
                @if($or->option_ids)
                    <?php
                    $options = explode(",", $or->option_ids);
                    ?>
                    <div class="form-group">
                        <p>{{ trans('home.option_price') }}: {{ $or->option_price }} </p>
                    </div>
                @endif

                <div class="form-group select-group">
                    <label>{{ trans('home.quantity') }}: </label>
                    <select class="form-control quantity" name="quantities[]" id="quantity_{{ $or->id }}"
                            data-id="{{ $or->id }}">
                        @php
                            for($i=1;$i<=($or->Product->stock + $or->quantity);$i++){
                        @endphp
                        <option value="{{ $i }}" {{($or->quantity == $i)?'selected':''}}>{{ $i }}</option>
                        @php
                            }
                        @endphp
                    </select>
                </div>
                <div class="form-group">
                    <p>{{ trans('home.price') }}: {{ $or->one_price }} </p>
                </div>
                @if($or->shipping_price > 0)
                    <div class="form-group">
                        <p>{{ trans('home.shipping_price') }}: {{ $or->shipping_price }} </p>
                    </div>
                @endif
                @if($or->payment_price > 0)
                    <div class="form-group">
                        <p>{{ trans('home.payment_price') }}: {{ $or->payment_price }} </p>
                    </div>
                @endif
                @if($or->coupon_price > 0)
                    <div class="form-group">
                        <p>{{ trans('home.coupon_price') }}: {{ $or->coupon_price }}</p>
                    </div>
                @endif
                <div class="form-group">
                    <label>{{ trans('home.total_price') }} : <span
                                id="display_total">{{ $or->total_price  }}</span></label>

                </div>
            </div>
            @php
                $total += $or->total_price;
            @endphp
        @endforeach
    </div>

    <div class="form-group">
        <label>{{ trans('home.comment') }}</label>
        <textarea name="message" class="form-control" rows="5">{{ $order[0]->comment}}</textarea>
    </div>
    <br><br>
    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{ trans('home.submit') }}</button>
        <a href="{{ url('orders') }}" id="back" class="btn btn-default">{{ trans('home.back') }}</a>
    </div>
    {!! Form::close() !!}

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
                <td> @if($order[0]->user_name != null) {{ $order[0]->user_name }} @else {{ $order[0]->User->name }} @endif </td>
            </tr>
            <tr>
                <td>{{ trans('home.customer_phone') }}</td>
                <td>@if($order[0]->user_phone != null) {{ $order[0]->user_phone }} @else {{ $order[0]->User->phone }} @endif</td>
            </tr>
            <tr>
                <td>{{trans('home.region') . ' / '.trans('home.area')}}</td>
                <?php
                $region = null;
                $area = null;
                    if($order[0]->Address){
                $region = \App\Region::where(['id' => $order[0]->Address->region_id])->first();
                $area = \App\Area::where(['id' => $order[0]->Address->area_id])->first();
                }
                ?>
                <td>@if($region && $area) {{$region->name . ' , ' . $area->name}} @endif</td>
            </tr>
            <tr>
                <td>{{ trans('home.address') }}</td>
                <td> @if($order[0]->user_address != null) {{ $order[0]->user_address }} @else @if($order[0]->Address) {{ $order[0]->Address->address }} @endif @endif</td>
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
                <td> @if(\Session::get('lang') == 'ar'){{$order[0]->paymethod->name}} @else {{$order[0]->paymethod->name_en}} @endif</td>
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
                <td> @if($order[0]->user_name != null) {{ $order[0]->user_name }} @else {{ $order[0]->User->name }} @endif </td>
            </tr>
            <tr>
                <td>{{ trans('home.customer_phone') }}</td>
                <td>@if($order[0]->user_phone != null) {{ $order[0]->user_phone }} @else {{ $order[0]->User->phone }} @endif</td>
            </tr>
            <tr>
                <td>{{trans('home.region') . ' / '.trans('home.area')}}</td>
                <?php
                    $region = null;
                    $area = null;
                    if($order[0]->Address){
                $region = \App\Region::where(['id' => $order[0]->Address->region_id])->first();
                $area = \App\Area::where(['id' => $order[0]->Address->area_id])->first();
                }
                ?>
                <td>@if($region && $area) {{$region->name . ' , ' . $area->name}} @endif</td>
            </tr>
            <tr>
                <td>{{ trans('home.address') }}</td>
                <td>  @if($order[0]->user_address != null) {{ $order[0]->user_address }} @else @if($order[0]->Address) {{ $order[0]->Address->address }} @endif @endif</td>
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
                <td> @if(\Session::get('lang') == 'ar'){{$order[0]->paymethod->name}} @else {{$order[0]->paymethod->name_en}} @endif</td>
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
                    <p>{{trans('home.delivery_signature')}} </p>
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

    {{--<script src="{{ URL::to('resources/assets/back/js/jquery.PrintArea.js') }}"></script>--}}
    <script src="{{ URL::to('resources/assets/back/js/printThis.js') }}"></script>
    <script>
        $('.quantity').on('change', function () {
            var order_id = $(this).data('id');
            var quantity = $('#quantity_' + order_id + ' option:selected').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: " {{url('orders/update_quantity')}}",
                method: 'POST',
                data: {order_id: order_id, quantity: quantity},
                success: function (data) {
                    location.reload();
                }
            });
        });
        $(function () {

            $('#status').on('change' , function () {
                let status = $(this).find('option:selected').val();
                if(status == 4 || status == 5 || status == 6) {
                    $('#delivery_id').prop('required', true);
                    if (status == 5 || status == 6)
                        $('input[name=actual_shipping_price]').prop('required', true);
                    else
                        $('input[name=actual_shipping_price]').prop('required', false);
                }else {
                    $('#delivery_id').prop('required', false);
                    $('input[name=actual_shipping_price]').prop('required', false);
                }
            })

            //Print order
            $("#print").click(function () {
                // var mode = 'iframe'; // popup
                // var close = mode == "popup";
                // var options = {
                //     mode: mode,
                //     popClose: close
                // };
                // $("#print2").printArea();
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
        $('#status').select2({
            placeholder: 'أختار الحالة'
        });
        $('#delivery_id').select2({
            placeholder: '{{ trans('home.select_delivery') }}'
        });

        $('#paymethod').select2({
            placeholder: '{{ trans('home.paymethod') }}'
        });
    </script>
@endsection