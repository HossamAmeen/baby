@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'user/'.$user->id,'files'=>'true']) !!}

      <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" value="{{$user->name}}" name="name" required>
      </div>
      <div class="form-group">
        <label for="email">{{trans('home.email')}} :</label>
        <input type="email" class="form-control" name="email" value="{{ $user->email }}" id="email" data-error="Please, enter a valid email" required>
        <div class="help-block with-errors"></div>
      </div>
      <div class="form-group">
        <label for="pwd">{{trans('home.password')}} :</label>
        <input type="password" class="form-control" name="password" placeholder="{{trans('home.password')}}" id="pwd" data-minlength="6">
        <div class="help-block">Your Password Must Be at Least 6 Characters</div>
      </div>
      <div class="form-group">
      <label for="about">{{trans('home.about')}} :</label>
        <textarea class="form-control area1" name="about" id="about" >{!! $user->about !!}</textarea>
      </div>
      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> 
        @if($user->image)
        <img src="{{url('uploads/user/resize200/')}}/{{$user->image}}" width="150" height="150">
        @else
        <img src="{{url('uploads/user/resize200/noimage200.png')}}" width="150" height="150">
        @endif
      </div>
      <div class="form-group">
        <label for="user_photo">{{trans('home.user_photo')}} :</label>
        <input type="file" class="form-control" name="user_photo" id="user_photo">
      </div>
      <?php
      if (isset($user->admin)) {
            echo '
            <div class="form-group select-group">
            <label for="admin">'.trans('home.admin').':</label>
            <select class="form-control selectpicker" data-live-search = "true"  title = "'.trans('home.selectuseradmin').'" name="admin" >';
            if ($user->admin == '1') {
                echo '
                <option value = "1" selected >'.trans('home.yes').'</option>
                <option  value = "0" >'.trans('home.no').'</option>';
            } else{
                echo '
                <option value = "1"  >'.trans('home.yes').'</option>
                <option  value = "0" selected>'.trans('home.no').'</option>';
            }
            echo '
            </select>
        </div> ';
        }

?>

	<div class="form-group select-group">
	<label>{{trans('home.ventor')}}</label>
        <select class="form-control selectpicker" data-live-search = "true"  title = "{{trans('home.selectuserventor')}}"  name="ventor">
          <option value="1" {{ ($user-> vendor == 1)?'selected':'' }}>{{trans('home.yes')}}</option>
          <option value="0" {{ ($user-> vendor == 0)?'selected':'' }}>{{trans('home.no')}}</option>
        </select>
      </div>
	
	<div class="form-group select-group">
	<label>{{trans('home.affilate')}}</label>
        <select class="form-control selectpicker" data-live-search = "true"  title = "{{trans('home.selectuseraffilate')}}"  name="affilate">
          <option value="1" {{ ($user-> affilate == 1)?'selected':'' }}>{{trans('home.yes')}}</option>
          <option value="0" {{ ($user-> affilate == 0)?'selected':'' }}>{{trans('home.no')}}</option>
        </select>
      </div>
      
      <div class="form-group select-group">
      <label>{{trans('home.delivery')}}</label>
        <select class="form-control selectpicker" data-live-search = "true"  title = "{{trans('home.selectuserdelivery')}}"  name="delivery">
          <option value="1" {{ ($user-> delivery == 1)?'selected':'' }}>{{trans('home.yes')}}</option>
          <option value="0" {{ ($user-> delivery == 0)?'selected':'' }}>{{trans('home.no')}}</option>
        </select>
      </div>
	
        <div class="form-group select-group">
          <label for="roles_id">{{trans('home.roles')}} :</label>
          <select id="role_user" name="roles_id[]" class="form-control" multiple>
          <option><option>
          
          @foreach($ur as $kuu => $uu)
          <option value="{{$kuu}}" @if(in_array($kuu,$urt)) selected @endif >{{ $uu }}</option>
          @endforeach
          </select>
        </div>
        

  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('user') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection

 @section('script')
        <script>

         (function() {
           $('#role_user').select2({
                  placeholder: 'Select Roles'
                });

        $("#checkbox").click(function(){
          if($("#checkbox").is(':checked') ){
            $("#role_user > option").prop("selected","selected");
            $("#role_user").trigger("change");
          }else{
            $("#role_user > option").removeAttr("selected");
            $("#role_user").trigger("change");
          }
        });

      })();


      

    </script>
    @endsection
