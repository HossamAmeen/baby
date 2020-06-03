
@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('affilates/create') }}" >{{trans('home.add')}}</a>
  <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger">{{trans('home.delete')}}</button>
  <button type="button" name="btn_active" id="btn_active" class="btn btn-primary">{{trans('home.published')}}/{{trans('home.unpublished')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.name')}}</th>
          <th>{{trans('home.email')}}</th>
          <th>{{trans('home.phone')}}</th>
          <th>{{trans('home.published')}}</th>
          <th>{{trans('home.user')}}</th>
          
        </thead>
        <tbody>
          @foreach ($affilates as $affilate)
              <tr id="{{$affilate->id}}">
                <td><input type="checkbox" name="checkbox"  value="{{$affilate->id}}" /> </td>
                <td> {{ $affilate->id }}</td>
                <td> <a href="{{ route('affilates.edit', $affilate->id) }}">{!! $affilate->name !!}</a></td>
                <td> {{ $affilate->email }}</td>
                <td> {{ $affilate->phone }}</td>
                <td>@if($affilate->status == 1) Yes @else No @endif</td>
                <td>@if($affilate->user) {{ $affilate->user->name}}@endif</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection