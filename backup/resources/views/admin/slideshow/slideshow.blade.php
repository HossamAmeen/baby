@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('slideshow/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  <button type="button" name="btn_active" id="btn_active" class="btn btn-primary">{{trans('home.published')}}/{{trans('home.unpublished')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.photo')}}</th>
          <th>{{trans('home.status')}}</th>
        <tbody>
          @foreach ($s as $x)
              <tr id="{{$x->id}}">
                <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                <td> {{ $x->id }}</td>
                <td> <a href="{{ route('slideshow.edit', $x->id) }}"><img src="{{url('\uploads\slideshow\resize200')}}\{{$x->image}}" width="150" height="150"></a></td>
                <td> @if($x->status == 1) {{trans('home.published')}} @else {{trans('home.unpublished')}} @endif</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection