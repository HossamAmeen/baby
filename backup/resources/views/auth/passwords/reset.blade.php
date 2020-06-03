@extends('layouts.app')
@section('content')

<section class="register-page">
            
        <section class="contact-form" id="contact">
        <div class="container">
            
                    <h2 class="register-h2">Change Password<i class="fa fa-user-plus"></i></h2>
            <div class="row">
                    <form  role="form" method="POST" name="contactform" id="contactform" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}
                    <div class="col-md-6">
                        <fieldset class="wow slideInLeft">
                            
                             <input autocomplete="off"type="email" id="email" name="email" value="{{ $email or old('email') }}" class="form-control" placeholder="{{ trans('site.enter_your_email_address') }}" required >
                            <br>
                            @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            <br>
                            
                             <input autocomplete="off" type="password" id="password" name="password" class="form-control" placeholder="{{ trans('site.please_enter_your_password') }}" required >
                            <br>
                            @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            
                             <input autocomplete="off" type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="{{ trans('site.please_enter_your_confirmation_password') }}" required >
                            <br>
                            @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            <br>
                            <fieldset>
                            <button type="submit" class="btn btn-lg">Create</button>
                            </fieldset>
                            
                        </fieldset>
                    </div>
                    
                   
                </form>
            </div>
        </div>
    </section>

@endsection

