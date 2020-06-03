@extends('layouts.admin')

@section('content')


{{--    {!! Form::open(['action' => 'Admin\ComplaintController@saveComplaintAdd'--}}
{{--    , 'method' => 'POST', 'data-toggle'=>'validator']) !!}--}}
    <form action="{{url('/spending/add/save')}}" method="post" data-toggle='validator'>
        {!! csrf_field() !!}

        <div class="form-group select-group">
            <label>التاريخ :</label>
            <input type="text" class="form-control date" value="{{ date('Y-m-d') }}"
                   placeholder="التاريخ" name="date"
                   id="date" required>
        </div>

        <div class="form-group select-group">
            <label>نوع المصاريف :</label>
            <select class="form-control" name="spending_type" id="spending_type">
                <option value="1">أساسية</option>
                <option value="2">فرعية</option>
            </select>
        </div>

        <div class="form-group">
            <input type="text" class="form-control" placeholder="إسم المصاريف" name="spending_name" required>
        </div>

        <div class="form-group">
            <input type="number" class="form-control" placeholder="المبلغ" name="amount"
                   min="1" step="1" required>
        </div>

        <div class="form-group">
            <textarea class="form-control" placeholder="ملاحظات" name="notes"></textarea>
        </div>

        <div class="btns-bottom">
            <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
            <a href="{{ url('/spending') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
        </div>

{{--        {!! Form::close() !!}--}}

    </form>
        @endsection

        @section('script')
            <script>
                $('#spending_type').select2();
                $(".date").datepicker({
                    dateFormat: "yy-mm-dd"
                });
            </script>
@endsection