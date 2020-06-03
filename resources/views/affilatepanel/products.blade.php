@extends('layouts.app')

@section('title')
    <title>{{ $affilate -> name }}</title>
@endsection

@section('content')

    <section class="price">

        <div class="container">
            <br>
            <div>
                <a href="{{ route('connect_product',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left"
                   style="margin-right:5px">{{trans('site.cwnp')}}</a>
                <a href="{{ route('connect_category',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left"
                   style="margin-right:5px">{{trans('site.cwnc')}}</a>
                <a href="{{ route('affilatepanelorders',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left"
                   style="margin-right:5px">{{trans('site.orders')}}</a>
                <a href="{{ route('affilatebalance',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left"
                   style="margin-right:5px">{{trans('site.balance')}}</a>
            </div>
            <br><br>
            <h2>{{trans('site.products')}}</h2>
            <br>
            <table class="tablerep newdatatable">

                <thead class="theadd">
                <tr class="trow">
                    <th class="thead" scope="col">{{trans('home.code')}}</th>
                    <th class="thead" scope="col">{{trans('home.name')}}</th>
                    <th class="thead" scope="col">{{trans('home.image')}}</th>
                    <th class="thead" scope="col">{{trans('home.link')}}</th>
{{--                    <th class="thead" scope="col">{{trans('site.expire_date')}}</th>--}}
                    <th class="thead" scope="col">{{trans('site.commission')}}</th>
                    <th class="thead" scope="col">{{trans('site.visits')}}</th>
                    <th class="thead" scope="col">{{trans('site.orderscount')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($products))
                    @foreach($products as $product)

                        <tr class="trow">

                            <td class="tdata" data-label="{{trans('home.code')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{$product -> product -> code }}" readonly></div>
                            </td>

                            <td class="tdata" data-label="{{trans('home.name')}}">

                                @if(App::getLocale() == 'ar')  {{ mb_substr($product -> product->title_ar,0,40) }} @if(strlen($product -> product->title_ar) >40)
                                    ... @endif @else  {{ mb_substr($product -> product->title,0,40) }} @if(strlen($product -> product->title) >40)
                                    ... @endif @endif
                            </td>
                            <td class="tdata" data-label="{{trans('home.image')}}">
                                <div><img class="img-responsive" style="width: 75px;height: 75px"
                                          src="{{ url('uploads/product/resize800') }}/{{ $product->product->image }}"/>
                                </div>
                            </td>
                            <td class="tdata" data-label="{{trans('home.link')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $product -> link }}" readonly></div>
                            </td>

                            {{--<td class="tdata" data-label="{{trans('site.expire_date')}}">--}}
                                {{--<div class="form-group"><input type="text" class="form-control"--}}
                                                               {{--value="{{ $product -> expire_date }}" readonly></div>--}}
                            {{--</td>--}}

                            <td class="tdata" data-label="{{trans('site.commission')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $product -> commission }}" readonly></div>
                            </td>


                            <td class="tdata" data-label="{{trans('site.visits')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $product -> visits }}" readonly></div>
                            </td>

                            <td class="tdata" data-label="{{trans('site.orderscount')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $product -> orders}}" readonly></div>
                            </td>


                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <br><br>
            <h2>{{trans('site.categories')}}</h2><br>

            <table class="tablerep newdatatable">

                <thead class="theadd">
                <tr class="trow">
                    <th class="thead" scope="col">{{trans('home.title')}}</th>
                    <th class="thead" scope="col">{{trans('home.link')}}</th>
                    <th class="thead" scope="col">{{trans('site.expire_date')}}</th>
                    <th class="thead" scope="col">{{trans('site.visits')}}</th>
                    <th class="thead" scope="col">{{trans('site.orderscount')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($categories))
                    @foreach($categories as $category)
                        <tr class="trow">

                            <td class="tdata" data-label="{{trans('home.title')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{$category -> category -> title }}" readonly>
                                </div>
                            </td>

                            <td class="tdata" data-label="{{trans('home.link')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $category -> link }}" readonly></div>
                            </td>

                            <td class="tdata" data-label="{{trans('site.expire_date')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $category -> expire_date }}" readonly></div>
                            </td>

                            <td class="tdata" data-label="{{trans('site.visits')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $category -> visits }}" readonly></div>
                            </td>

                            <td class="tdata" data-label="{{trans('site.orderscount')}}">
                                <div class="form-group"><input type="text" class="form-control"
                                                               value="{{ $category -> orders}}" readonly></div>
                            </td>

                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <br><br>


        </div>

    </section>

@endsection

