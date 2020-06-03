@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  {{--<a class="btn btn-primary" href="{{ URL::to('regions/create') }}" >{{trans('home.add')}}</a>--}}
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.user')}}</th>
          <th>{{trans('home.order_number')}}</th>
          <th>{{trans('home.reason')}}</th>
          <th>{{trans('home.comment')}}</th>
          <th>{{trans('home.image')}}</th>
        <tbody>
          @foreach ($orders as $x)
            <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('user.edit', $x->user_id) }}">{{ $x->user->name }}</a></td>
                <td> {{ $x->order_id}}</td>
                <td> {{ $x->reason }}</td>
                <td> {{ $x->comment }}</td>
                <td><img src="{{url('uploads/refundedorders/source')}}/{{$x->img}}" width="50"/></td>
            </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection