@extends('layouts.admin')

@section('content')

  <div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('vendors/create') }}" >{{trans('home.add')}}</a>
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
          @foreach ($vendors as $vendor)
              <tr id="{{$vendor->id}}">
                <td><input type="checkbox" name="checkbox"  value="{{$vendor->id}}" /> </td>
                <td> {{ $vendor->id }}</td>
                <td> <a href="{{ route('vendors.edit', $vendor->id) }}">{!! $vendor->name !!}</a></td>
                <td> {{ $vendor->email }}</td>
                <td> {{ $vendor->phone }}</td>
                <td>@if($vendor->status == 1) Yes @else No @endif</td>
                <td> {{ $vendor->user->name}}</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection