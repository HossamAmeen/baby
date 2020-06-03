@if($type == 'user')
    {!! Form::select('user_id[]',$c,null,['id' => 'user_id','class' => 'form-control','multiple','required'=>'']) !!}
@elseif($type == 'shipping')
    {!! Form::select('region_id[]',$c,null,['id' => 'region_id','class' => 'form-control','multiple','required'=>'']) !!}
@elseif($type == 'category')
    {!! Form::select('category_id[]',$c,null,['id' => 'category_id','class' => 'form-control','multiple','required'=>'']) !!}
@elseif($type == 'general')
    <select id='count' name="count" class='form-control' required>
        @foreach($c as $b)
            <option value="{{$b['id']}}">{{$b['name']}}</option>
        @endforeach
    </select>
@else
    {!! Form::select('product_id[]',$c,null,['id' => 'product_id','class' => 'form-control','multiple','required'=>'']) !!}
@endif   
