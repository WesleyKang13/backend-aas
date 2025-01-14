<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Notification from {{$notification->user->firstname. ' '.$notification->user->lastname}}</title>
</head>
<body>
    <p>
        {{$notification->detail}}
    </p>
</body>
</html>
