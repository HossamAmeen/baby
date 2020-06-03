@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'permissions.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


      <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required>
      </div>
      <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.display_name')}}" name="display_name" >
      </div>
       <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.description')}}" name="description" >
      </div>

      <div class="form-group select-group">
        <label for="roles_id">Roles </label>
        {!! Form::select('roles_id[]',$ur,null,['id' => 'role_user','class' => 'form-control','multiple']) !!}
      </div>
      <div class="form-group select-group">
        <input type="checkbox" id="checkbox" >Select All
      </div>
      
      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">Submit</button>
      <a href="{{ url('permissions') }}" id="back" class="btn btn-default">Back</a>
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