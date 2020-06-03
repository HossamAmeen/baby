@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('user/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.name')}}</th>
          <th>{{trans('home.admin')}}</th>
          <th>{{trans('home.email')}}</th>
        </thead>
        <tbody>
          @foreach ($u as $x)
              <tr id="{{$x->id}}">
                <td><input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('user.edit', $x->id) }}">{!! $x->name !!}</a></td>
                <td>@if($x->admin == 1) Yes @else No @endif</td>
                <td> {{ $x->email }}</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection