Hello {{$email_data['name']}}
<br><br>
Welcome to InvSys!
<br>
Your current password is:<b> {{$email_data['password']}} </b>
<br>
You can also change it in account settings after you've verified your account. 
<br><br>
Please click the link below first to verify your E-Mail Address and activate your account!
<br><br>
<a href="http://127.0.0.1:8000/verify?code={{$email_data['verification_code']}}">Click Here!</a>

<br><br>
Thank you!
<br>
Denzell Loria