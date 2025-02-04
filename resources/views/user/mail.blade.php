<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Created</title>
</head>
<body>
    <p>
        Hello {{$user->firstname. ' '.$user->lastname}},</br>

        Please follow the credentials provided to <a href="{{url('/')}}">login</a> to your account:</br>
        Email: {{$user->email}}</br>
        Password: {{$password}}</br>

        If you have trouble login into your account please do not hesitate to contact <a href="mailto:admin@example.com">admin@example.com</a>
    </p>
</body>
</html>
