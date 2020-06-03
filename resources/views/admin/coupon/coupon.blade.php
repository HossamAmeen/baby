@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('coupon/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
    <button type="button"  id="coupon_print" class="btn btn-success">{{trans('home.print')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.name')}}</th>
          <th>{{trans('home.code')}}</th>
          <th>{{trans('home.expire_date')}}</th>
        <tbody>
          @foreach ($coupon as $x)
              <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('coupon.edit', $x->id) }}">{{ $x->name }}</a></td>
                <td> {{ $x->code }}</td>
                <td> {{ $x->expire_date }}</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection