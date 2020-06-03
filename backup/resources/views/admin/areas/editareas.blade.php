@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'areas/'.$area->id]) !!}


      <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$area->name}}" id="name" >
      </div>

        <div class="form-group">
            <label for="shipping">{{trans('home.shipping')}} :</label>
            <input type="number" step="0.01" class="form-control"
                   placeholder="{{trans('home.shipping')}}" value="{{$area->shipping}}" name="shipping">
        </div>


        <div class="form-group select-group">
            <label for="region_id">{{trans('home.region')}} :</label>
            <select class="form-control region" id="region_id"  name="region_id" required>
                <option></option>
                @foreach ($regions as $region)
                    <option id="{{$region->shipping}}" value="{{ $region->id }}" @if($region->id == $area->region_id) selected @endif>{{ $region->name }}</option>
                @endforeach
            </select>
        </div>
       
      

      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('areas') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
$('#region_id').select2({
  'placeholder' : 'أختار المحافظة',
});
$('#country_id').select2({
  'placeholder' : 'أختار الدولة',
});

$('#region_id').on('change', function(){
    var shipping = $(this).find('option:selected').attr('id');
    $('input[name=shipping]').val(shipping);
});

$('.country').on('change', function(){
    var country =  $(this).val();

            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('changecountry')}}",
              method:'POST',
              data:{country:country},
              success:function(data)
              {
                var s = $('.region');
                var html = '' ;
                html +='<option></option>';
                for (key in data) {
                    if (data.hasOwnProperty(key)) {
                        html += '<option value="'+ key +'">'+ data[key] +'</option>';
                    }
                }
                
                  s.html(html);
              }
         });
        
}); 
</script>    
@endsection