@extends('layouts.admin')

@section('content')

    <div class="form-group select-group">
        <label>{{trans('home.code')}} :</label>
        <input type="text" class="form-control" placeholder="{{ trans('home.code') }}" name="code"
               id="code" required>
    </div>
    <div class="btns-bottom">
        <button id="displaybtn" class="btn btn-default">{{trans('home.display')}}</button>
    </div>

    <div id="displaycontent">

    </div>

@endsection



@section('script')
    <script>

        $('#datatable').DataTable({});

        $('#displaybtn').click(function () {
            var code = $('#code').val();

            if (code) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: " {{url('/reports/moves/search')}}"+'?code='+code,
                    method: 'GET',
                    success: function (data) {
                        $('#displaycontent').html(data);
                        $('#datatable').DataTable();
                    }
                });

            }
        });

    </script>


@endsection