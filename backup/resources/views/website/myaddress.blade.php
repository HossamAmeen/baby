@extends('layouts.app')
@section('content')

    <section id="account">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-3">
                    <aside>
                        <div class="acc-info">
                            <div class="icon"></div>
                            <div class="name"><h4>{{ Auth::user()->name }}</h4></div>
                            <div class="e-mail"><p>{{ Auth::user()->email }}</p></div>
                        </div>
                        <div class="info-bar">
                            <ul class="list-unstyled">
                                <li><a href="{{ url('my-orders/0') }}">{{ trans('home.incompleted_orders') }}</a></li>
                                <li><a href="{{ url('my-orders/1') }}">{{ trans('home.completed_orders') }}</a></li>
                                @if(Auth::user()->delivery)
                                    <li><a class="active" href="{{ url('my-deliveries') }}">{{trans('site.order_deliveries')}}</a></li>
                                @endif
                                <li><a href="{{ url('my-favorite') }}">{{trans('site.wish_list')}}</a></li>
                                <li><a href="{{ url('change-account') }}">{{trans('site.account')}}</a></li>
                                <li><a href="{{ url('my-address') }}">{{ trans('site.myaddress') }}</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
                <div class="col-xs-12 col-md-9">
                    <h1>{{ trans('site.myaddress') }}</h1>
                    <div class="buttons clearfix">
                        <div class="pull-right">
                            <a type="button" href="{{ url('addaddress') }}" class="btn btn-info"
                               class="btn btn-primary">{{ trans('site.add') }}</a>
                        </div>
                    </div>
                    <fieldset>
                        <legend>{{ trans('site.your_address')  }}</legend>
                        <div class="col-sm-12  col-md-8">
                            @if(count($address) > 0)
                                @foreach ($address as $ad)
                                    <div class="Addr-info" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    display: flow-root;padding: 14px;margin:20px;">
                                        <div class="name"><p>{{ trans('site.name') }} :<span> {{ $ad->name }}</span></p>
                                        </div>
                                        <div class="Address"><p>{{ trans('site.address') }}
                                                :<span> {{ $ad->address }}</span></p></div>
                                        <div class="phone"><p>{{ trans('site.phone') }} : <span> {{ $ad->phone }}</span></p>
                                        </div>
                                        <div class="pull-right">
                                            <a class="btn btn-info" href="{{ url('editaddress') }}/{{ $ad->id }}"
                                               data-id="{{ $ad->id }}">{{ trans('site.edit') }}</a>
                                            <button class="btn btn-danger deleteaddress"
                                                    data-id="{{ $ad->id }}">{{ trans('site.delete') }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="Addr-info" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
display: flow-root;padding: 14px;margin:20px;">
                                    {{trans('site.no_address')}}
                                </div>
                            @endif
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        </div>
    </section>

@endsection
@section('script')
    <script>
        $('.deleteaddress').on('click', function () {
            var id = $(this).data('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: " {{url('deleteaddress')}}",
                method: 'POST',
                data: {id: id},
                success: function (data) {
                    location.reload();
                }
            });
        });

    </script>
@endsection
            