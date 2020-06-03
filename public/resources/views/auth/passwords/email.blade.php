@extends('layouts.app')
@section('title')
<title>{{ trans('site.reset_password') }}</title>
@endsection
<!-- Main Content -->
@section('content')
<div class="container">
<div class="row">
<div id="account-content">
   
<div id="content" >
  
         <div class="col-md-3"></div>
  <div class="col-xs-12 col-md-6">
          <form method="post" class="form-horizontal" action="{{ url('/password/email') }}" >
                  
                   <div class="title ">
                    <h2 class="purpel background"><span> {{ trans('site.reset_password') }}  </span></h2>
                </div>
                   
                    {{ csrf_field() }}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->has('email'))
                    <div class="alert alert-danger">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                @endif
            <fieldset>
              <legend>{{ trans('site.reset_password') }}</legend>
              <div class="form-group required">
                <label class="col-sm-2 control-label" >{{trans('site.email')}}</label>
                <div class="col-sm-10">
                  <input type="email" id="input-email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('site.enter_your_email_address') }}" ><br />
                </div>
              </div>
            </fieldset>
            <div class="buttons clearfix ">
              {{--<div class="pull-left login-btn"><a href="" class="btn btn-default">Back</a></div>--}}
              <div class="pull-right login-btn">
                <input type="submit" class="btn btn-primary" value="{{ trans('site.reset_password') }}" id="button">
              </div>
            </div>
          </form>
    </div>
             <div class="col-md-3"></div>

          </div>
        
          
          </div>
    </div>
          </div>
          
@endsection
