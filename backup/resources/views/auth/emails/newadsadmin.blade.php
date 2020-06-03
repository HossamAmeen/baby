<!DOCTYPE html>
<html lang="ar">
   <header>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo|Lalezar">
        
    </header>
	
    <body>
          <p style="text-align: center;">{{ $news->Ad->ads_text2 }}</p>
          
           <table class="table table-striped table-bordered table-hover"  DIR="RTL" style="border: 1px solid orange;padding: 10px;">
       <thead>
      
       	<th style="border: 1px solid orange;padding: 10px;">الاسم</th>
       	<th style="border: 1px solid orange;padding: 10px;">البريد الالكتروني</th>
        <th style="border: 1px solid orange;padding: 10px;">رقم الهاتف</th>
       </thead>
       <tbody>
       
        <tr>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;"> {{ $news->name }}</td>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;"> {{$news->email}}</td>
          <td style="border: 1px solid orange;padding: 10px;text-align: center;">{{ $news->phone }}</td>
        </tr>
        
       </tbody>
       </table>
    </body>
</html>