@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('blogcategory/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  <button type="button" name="btn_active" id="btn_active" class="btn btn-primary">{{trans('home.published')}}/{{trans('home.unpublished')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.title')}}</th>
          <th>{{trans('home.status')}}</th>
        <tbody>
          @foreach ($blogcategory as $x)
              <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('blogcategory.edit', $x->id) }}">{{ $x->title }}</a></td>
                <td> @if($x->status == 1) {{trans('home.published')}} @else {{trans('home.unpublished')}} @endif</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection