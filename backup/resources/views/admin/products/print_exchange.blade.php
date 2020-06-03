<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Control Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ URL::to('resources/assets/back/images/logo2.png') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/select2.min.css') }}"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/jquery.dataTables.css') }}"/>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.0/css/bootstrap-select.min.css">

    @if(App::isLocale('ar'))
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/style-ar.css') }}"/>
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/media-queries-ar.css') }}"/>

    @else
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/style.css') }}"/>
        <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/media-queries.css') }}"/>
    @endif
    @yield('messagepop')
<!-- route('configuration . edit', 1)  -->
    @yield('style')

    <style>
        @media print {
            .information{
                border: none;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="content col-xs-12">
            <div class="information">

                <div id="print2" class="row invoiceprint" style="padding:0px 20px;page-break-after: auto;">

                    {{--<img class=" invoiceprintimg img-responsive" src="{{ URL::to('resources/assets/front/img/logo-eng.png') }}">--}}
                    {{--<img class=" invoiceprintimg2 img-responsive"--}}
                    {{--src="{{ URL::to('resources/assets/front/img/logo-eng.png') }}">--}}
                    {{--@endif--}}
                    <div class="row"></div>

                    <div class="col-xs-4">
                        <p><strong>رقم الإذن :
                                {{$order[0]->number }}</strong></p>
                    </div>
                    <div class="col-xs-8">
                        <p class="pull-right"><strong>التاريخ :
                                {{$order[0]->created_at }}</strong></p>
                    </div>

                    <table class="table table-bordered" style="margin-bottom: 0px; margin-top:1px;">

                        <tr>
                            <th>{{ trans('home.title') }} {{ trans('home.product') }}</th>
                            <th>{{ trans('home.code') }}</th>
                            <th>{{ trans('home.price') }}</th>
                            <th>{{ trans('home.quantity') }}</th>
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
                                <td class="font-weight-bold">
                                    @if($or->Product)
                                        @php
                                            $product = $or->Product;
                                            if ($product->discount == 0) {
                                                $total += $product->price;
                                                echo  $product->price;
                                            } else {
                                                $total += ($product->price - $product->discount);
                                                echo '<span style="text-decoration: line-through;">' . $product->price . '</span>' .
                                                '<br/>' . ($product->price - $product->discount);
                                            }
                                        @endphp
                                    @else Product
                                    Deleted @endif</td>
                                <td class="font-weight-bold">{{ $or->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <hr/>
                    <div class="col-xs-3">
                        <p><strong>الإجمالى :
                                {{$total}}</strong></p>
                    </div>
                </div>

                {{--<div class="progress-btn">--}}
                    {{--<button type="button" id="print" class="ui basic button">إطبع الفاتورة</button>--}}
                {{--</div>--}}

            </div>
        </div>
    </div>
</div>

<script src="{{ URL::to('resources/assets/back/js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ URL::to('resources/assets/back/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('resources/assets/back/js/printThis.js') }}"></script>
<script>
    $(function () {
        window.print();
        // window.close();
        //Print order
        // $("#print").click(function () {
        //
        //     // $("#print2").printThis({
        //     //     importCSS: true,            // import page CSS
        //     //     importStyle: true,         // import style tags
        //     //     printContainer: true,
        //     //     printDelay: 333,
        //     //     header: null,
        //     //     formValues: true
        //     // });
        // });
    });
</script>

</body>
</html>