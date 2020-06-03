<!DOCTYPE html>
<html lang="ar">
   <header>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo|Lalezar">
        
    </header>
	
    <body>
         
          <p style="text-align: center;">أنضم الينا</p>
           <table class="table table-striped table-bordered table-hover"  DIR="RTL" style="border: 1px solid orange;padding: 10px;">
       <thead>
      
       	<th style="border: 1px solid orange;padding: 10px;">الاسم</th>
       	<th style="border: 1px solid orange;padding: 10px;">البريد الالكتروني</th>
        <th style="border: 1px solid orange;padding: 10px;">رقم الهاتف</th>
        <th style="border: 1px solid orange;padding: 10px;">التخصص</th>
        <th style="border: 1px solid orange;padding: 10px;">الرسالة</th>
       </thead>
       <tbody>
       
        <tr>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;"> {{ $join->name }}</td>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;"> {{$join->email}}</td>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;">{{ $join->phone }}</td>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;">{{ $join->major }}</td>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;">{!! $join->message !!}</td>
        </tr>
        
       </tbody>
       </table>
    </body>
</html>