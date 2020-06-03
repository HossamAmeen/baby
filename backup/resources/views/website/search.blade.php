@extends('layouts.admin')

@section('content')

      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th>{{trans('home.name')}}</th>
          <th>{{trans('home.count')}}</th>
          <th>type</th>
          <th>{{trans('home.date')}}</th>
        <tbody>
          @foreach ($words as $x)
              <tr>
                <td>{{ $x->name }}</td>
                <td>{{ $x->count }}</td>
                  <td>{{ $x->type }}</td>
                <td>{{ $x->updated_at }}</td>
              </tr>
          @endforeach
        </tbody>
      </table>
   
    

@endsection