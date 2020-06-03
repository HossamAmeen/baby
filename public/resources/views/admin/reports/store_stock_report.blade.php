@extends('layouts.admin')

@section('content')

    <div class="btns-bottom">
        <button id="printbtn" class="btn btn-default">{{trans('home.print')}}</button>
    </div>

    <div id="displaycontent">
        <table class="table table-striped table-bordered table-hover" id="datatable">
            <thead>
            <tr>
                <th>{{trans('home.number')}}</th>
                <th>{{trans('home.code')}}</th>
                <th>{{trans('home.title')}}</th>
                <th>{{trans('home.image')}}</th>
                <th>{{trans('home.price')}}</th>
                <th>المحل</th>
                <th>{{trans('home.ordered')}}</th>
            </tr>
            <tbody>
            @foreach($productsData as $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->code}}</td>
                    <td>{{$product->title_ar}}</td>
                    <td><img width="50" src="{{url('uploads/product/resize200').'/'.$product->image}}"></td>
                    <td>
                        @if ($product->discount == 0)
                            {{$product->price}}
                        @else
                            <span style="text-decoration: line-through;">{{$product->price}}</span>
                            <br/>{{($product->price - $product->discount)}}
                        @endif
                    </td>
                    <td>{{$product->store_stock}}</td>
                    <td>{{$product->ordered}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@endsection


@section('script')
    <script>

        $('#printbtn').click(function () {

            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write('<h1>' + document.title + '</h1>');
            mywindow.document.write(document.getElementById('displaycontent').innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        });

        $('#datatable').DataTable();

        {{--$('#datatable').DataTable({--}}
            {{--processing: true,--}}
            {{--serverSide: true,--}}
            {{--ajax: '{{url('/store/stock/report/data')}}',--}}
            {{--columns: [--}}
                {{--{data: 'id', name: 'id', orderable: false},--}}
                {{--{data: 'code', name: 'code', orderable: false},--}}
                {{--{data: 'title_ar', name: 'title_ar', orderable: false},--}}
                {{--{data: 'image', name: 'image', orderable: false, searchable: false},--}}
                {{--{data: 'price', name: 'price'},--}}
                {{--{data: 'store_stock', name: 'store_stock'},--}}
                {{--{--}}
                    {{--data: 'ordered',--}}
                    {{--name: 'ordered',--}}
                    {{--orderable: true,--}}
                {{--},--}}
            {{--]--}}
        {{--});--}}

    </script>

@endsection