@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'permissions/'.$permission->id,'files'=>'true']) !!}

      <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" value="{{$permission->name}}" name="name" required>
      </div>
      <div class="form-group">
      <label for="display_name">{{trans('home.display_name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.display_name')}}" value="{{$permission->display_name}}" name="display_name" >
      </div>
      <div class="form-group">
      <label for="description">{{trans('home.description')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.description')}}" value="{{$permission->description}}" name="description" >
      </div>
      <div class="form-group select-group">
          <label for="roles_id">Roles :</label>
          <select id="role_user" name="roles_id[]" class="form-control" multiple>
          <option><option>
          
          @foreach($ur as $kuu => $uu)
          <option value="{{$kuu}}" @if(in_array($kuu,$urt)) selected @endif >{{ $uu }}</option>
          @endforeach
          </select>
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