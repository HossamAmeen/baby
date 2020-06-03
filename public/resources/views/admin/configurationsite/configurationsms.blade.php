@extends('layouts.admin')
@section('content')

    <form action="{{url('/save/config/sms')}}" method="post" data-toggle='validator'>

        {!! csrf_field() !!}

        <div class="form-group select-group">
            <label>رسالة الطلب المؤكد</label>
            <select class="form-control" name="confirmed_sms" id="confirmed_sms" required>
                <option value="1" @if(intval($con->confirmed_sms) === 1) selected @endif>تعمل</option>
                <option value="0" @if(intval($con->confirmed_sms) === 0) selected @endif>لا تعمل</option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="confirmed_sms_text"
                      id="confirmed_sms_text">{{$con->confirmed_sms_text}}</textarea>
        </div>

        <div class="form-group select-group">
            <label>رسالة الطلب تم التسليم</label>
            <select class="form-control" name="delivered_sms" id="delivered_sms" required>
                <option value="1" @if(intval($con->delivered_sms) === 1) selected @endif>تعمل</option>
                <option value="0" @if(intval($con->delivered_sms) === 0) selected @endif>لا تعمل</option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="delivered_sms_text"
                      id="delivered_sms_text">{{$con->delivered_sms_text}}</textarea>
        </div>

        <div class="form-group select-group">
            <label>رسالة الطلب صفحة من المحل</label>
            <select class="form-control" name="page_delivered_sms" id="page_delivered_sms" required>
                <option value="1" @if(intval($con->page_delivered_sms) === 1) selected @endif>تعمل</option>
                <option value="0" @if(intval($con->page_delivered_sms) === 0) selected @endif>لا تعمل</option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="page_delivered_sms_text"
                      id="page_delivered_sms_text">{{$con->page_delivered_sms_text}}</textarea>
        </div>

        <div class="form-group select-group">
            <label>رسالة الطلب المحل</label>
            <select class="form-control" name="place_delivered_sms" id="place_delivered_sms" required>
                <option value="1" @if(intval($con->place_delivered_sms) === 1) selected @endif>تعمل</option>
                <option value="0" @if(intval($con->place_delivered_sms) === 0) selected @endif>لا تعمل</option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="place_delivered_sms_text"
                      id="place_delivered_sms_text">{{$con->place_delivered_sms_text}}</textarea>
        </div>

        <div class="btns-bottom">
            <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        </div>
    </form>

@endsection
@section('script')
    <script>
        $('#confirmed_sms').select2();
        $('#delivered_sms').select2();
    </script>
@endsection

