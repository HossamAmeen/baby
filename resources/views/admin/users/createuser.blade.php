@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'user.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


      <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required>
      </div>
      <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="{{trans('home.email')}}" id="email" data-error="Please, enter a valid email" required>
        <div class="help-block with-errors"></div>
      </div>
      <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="{{trans('home.password')}}" id="pwd" data-minlength="6" required>
          <div class="help-block">Your Password Must Be at Least 6 Characters</div>
      </div>
      <div class="form-group">
      <label for="about">{{trans('home.about')}} :</label>
        <textarea class="form-control area1" name="about" placeholder="{{trans('home.about')}}" ></textarea>
      </div>
      <div class="form-group">
        <label for="user_photo">{{trans('home.user_photo')}} :</label>
        <input type="file" class="form-control" name="user_photo" id="user_photo">
      </div>
      
      <div class="form-group select-group">
        <select class="form-control selectpicker" data-live-search = "true"  title = "{{trans('home.selectuseradmin')}}"  name="admin">
          <option value="1">{{trans('home.yes')}}</option>
          <option value="0">{{trans('home.no')}}</option>
        </select>
      </div>
      
      <div class="form-group select-group">
        <select class="form-control selectpicker" data-live-search = "true"  title = "{{trans('home.selectuserventor')}}"  name="ventor">
          <option value="1">{{trans('home.yes')}}</option>
          <option value="0">{{trans('home.no')}}</option>
        </select>
      </div>
	
	<div class="form-group select-group">
        <select class="form-control selectpicker" data-live-search = "true"  title = "{{trans('home.selectuseraffilate')}}"  name="affilate">
          <option value="1">{{trans('home.yes')}}</option>
          <option value="0">{{trans('home.no')}}</option>
        </select>
      </div>
      
      <div class="form-group select-group">
        <select class="form-control selectpicker" data-live-search = "true"  title = "{{trans('home.selectuserdelivery')}}"  name="delivery">
          <option value="1">{{trans('home.yes')}}</option>
          <option value="0">{{trans('home.no')}}</option>
        </select>
      </div>
		 <br>
      <div class="form-group select-group">
        <label for="roles_id">{{trans('home.roles')}} :</label>
        {!! Form::select('roles_id[]',$ur,null,['id' => 'role_user','class' => 'form-control','multiple']) !!}
      </div>
	 
      <div class="form-group select-group">
        <input type="checkbox" id="checkbox" >{{trans('home.selectall')}}
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