@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('permissions/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  <button type="button" name="btn_active" id="btn_active" class="btn btn-primary">{{trans('home.published')}}/{{trans('home.unpublished')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.name')}}</th>
          <th>{{trans('home.display_name')}}</th>
        </thead>
        <tbody>
          @foreach ($p as $x)
              <tr id="{{$x->id}}">
                <td><input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('permissions.edit', $x->id) }}">{!! $x->name !!}</a></td>
                <td>{{$x->display_name}}</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection