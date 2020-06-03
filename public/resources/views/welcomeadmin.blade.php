@extends('layouts.admin')

@section('content')
   {{--<div class="panel panel-primary">--}}
							  {{--<div class="panel-heading">{{trans('home.quicklinks')}}</div>--}}
							  {{--<div class="panel-body">--}}
							  {{--@permission('order')--}}
								{{--<a href="{{ url('/store_orders') }}" type="button" class="btn btn-primary">{{trans('home.store_orders')}}</a>--}}
								{{--<a href="{{ url('/orders') }}" type="button" class="btn btn-primary">{{trans('home.completed_orders')}}</a>--}}
								{{--<a href="{{ url('/incompleted/orders') }}" type="button" class="btn btn-primary">{{trans('home.incompleted_orders')}}</a>--}}
								{{--@endpermission--}}
								{{--@permission('products')--}}
                                {{--<a href="{{ url('/products') }}" type="button" class="btn btn-primary">{{trans('home.product')}}</a>--}}
                                {{--@endpermission--}}
                                {{----}}
                                {{--@permission('reports')--}}
                                {{--<a href="{{ url('/stock_report') }}" type="button" class="btn btn-primary">{{trans('home.stock')}}</a>--}}
                                {{--<a href="{{ url('/store_stock_report') }}" type="button" class="btn btn-primary">{{trans('home.store_stock')}}</a>--}}
                                {{--<a href="{{ url('/order_report') }}" type="button" class="btn btn-primary">{{trans('home.orders_report')}}</a>--}}
                                {{--@endpermission--}}
								{{----}}
							  {{--</div>--}}
							{{--</div>--}}

@endsection
@section('script')
    <script>
     (function() {
        $('.datatable').DataTable();
        })();
    </script>
@endsection
