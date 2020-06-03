@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('pages/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{ trans('home.title') }}</th>
          <th>{{ trans('home.lang') }}</th>
        <tbody>
          @foreach ($pages as $x)
              <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox" value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('pages.edit', $x->id) }}">{{ $x->title }}</a></td>    
                <td> {{ trans('home.'.$x->lang) }}</td>
             </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection