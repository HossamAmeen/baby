@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.name')}}</th>
          <th>{{trans('home.phone')}}</th>
        <tbody>
          @foreach ($contact as $x)
              <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('contactus.edit', $x->id) }}">{{ $x->name }}</a></td>
                <td>{{ $x->phone }}</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection