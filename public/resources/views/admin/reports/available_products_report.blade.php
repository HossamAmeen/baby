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
                <th>متاح</th>
            </tr>
            <tbody>
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

        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{url('/products/available/report/data')}}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'code', name: 'code'},
                {data: 'title_ar', name: 'title_ar'},
                {data: 'image', name: 'image', orderable: false, searchable: false},
                {data: 'available', name: 'available'},
            ],
        });

    </script>

@endsection