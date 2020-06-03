@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('regions/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.name')}}</th>
          <th>{{trans('home.shipping')}}</th>
          <th>{{trans('home.time')}}</th>
          <th>{{trans('home.country')}}</th>
        <tbody>
          @foreach ($region as $x)
            <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('regions.edit', $x->id) }}">{{ $x->name }}</a></td>
                <td> {{ $x->shipping }}</td>
                <td> {{ $x->shiping_time }}</td>
                <td>@if($x->Country) {{ $x->Country->name }} @endif</td>
            </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection