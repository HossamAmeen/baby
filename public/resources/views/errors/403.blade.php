@extends('layouts.admin')
@section('style')
	 <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/403.css') }}"/>
@endsection

@section('content')
	<div class="wrapper">
		<div class="error-spacer"></div>
		<div role="main" class="main">
			<?php $messages = array('We need a map.', 'I think we\'re lost.', 'We took a wrong turn.'); ?>

			<h1>Access Denied</h1>

			<h2>Server Error: 403 (Forbidden)</h2>

			<hr>

			<h3>What does this mean?</h3>

			<p>
				You don't have permission to access on this page.
			</p>

			<p>
				Perhaps you would like to go to our <a href="{{{ URL::to('/admin') }}}">Admin page</a>?
			</p>
		</div>
	</div>
	@endsection
