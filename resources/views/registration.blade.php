@php
$additional_data= json_decode($demo['additional_data']);
@endphp
Hello {{ $demo['username'] }},
<br><br>
Follow this link to verify your email address.
<br><br>
<a href="{{$demo['url'].'/pages/verify_email?verification_code='.$additional_data->email_verification_code.'&email='.$demo['email']}}">
{{$demo['url'].'/pages/verify_email?verification_code='.$additional_data->email_verification_code.'&email='.$demo['email']}}</a>
<br><br>
If you didn’t ask to verify this address, you can ignore this email.
<br><br>
Thanks,
<br><br>
Your gamabox-energy team

