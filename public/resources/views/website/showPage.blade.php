@extends('layouts.app')
@section('title')
<title>{{ $page->title }}</title>
@endsection
@section('meta')
<meta name="keywords" content="">
<meta name="description" content="">
@endsection
@section('content')
<section>
        <div class="container">
		{!! $page->text !!}
	</div>
</section>

@endsection