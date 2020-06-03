@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ route('colors.create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.name')}}</th>
        <tbody>
          @foreach ($colors as $x)
            <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> <a href="{{ route('colors.edit', $x->id) }}">{{ $x->name }}</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection