<!DOCTYPE html>
<html>

<body>
<p>Name: <span>{{ $name }}</span></p><br>
<p>E-Mail: <span>{{ $email }}</span></p><br>
<p>Phone: <span>{{ $phone }}</span></p><br>
<p>Message: <span>{{ $me }}</span></p><br>
@if(isset($re))
<p>Replay: <span>{!! $re !!}</span></p><br>
@endif
</body>
</html>