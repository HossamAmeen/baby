@extends('layouts.admin')

@section('content')


{{--    {!! Form::open(['action' => 'Admin\ComplaintController@saveComplaintAdd'--}}
{{--    , 'method' => 'POST', 'data-toggle'=>'validator']) !!}--}}
    <form action="{{url('/complaints/edit/'.$complaint->id.'/edit')}}" method="post" data-toggle='validator'>
        {!! csrf_field() !!}

        <div class="form-group">
            <input type="text" value="{{$complaint->name}}" class="form-control" placeholder="إسم العميل" name="name" required>
        </div>

        <div class="form-group">
            <input type="text" value="{{$complaint->phone}}" class="form-control" placeholder="رقم العميل" name="phone" required>
        </div>

        <div class="form-group">
            <textarea class="form-control" placeholder="الشكوى" rows="6" name="message" required>{{$complaint->message}}</textarea>
        </div>

        <div class="form-group">
            <textarea class="form-control" placeholder="ملاحظات" name="notes">{{$complaint->notes}}</textarea>
        </div>

        <div class="form-group">
            <input type="text" class="form-control" value="{{$complaint->order_id}}" placeholder="رقم الطلب" name="order_id" required>
        </div>

        <div class="form-group select-group">
            <label for="status">إختر الحالة :</label>
            <select class="form-control" name="status" id="status">
                <option @if($complaint->status === 'new') selected @endif value="new">لم يتم الحل</option>
                <option @if($complaint->status === 'process') selected @endif value="process">يتم الحل</option>
                <option @if($complaint->status === 'solved') selected @endif value="solved">تم الحل</option>
            </select>
        </div>

        <div class="btns-bottom">
            <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
            <a href="{{ url('/complaints') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
        </div>

{{--        {!! Form::close() !!}--}}

    </form>
        @endsection

        @section('script')
            <script>
                $('#status').select2({
                    'placeholder': 'إختر حالة',
                });
            </script>
@endsection