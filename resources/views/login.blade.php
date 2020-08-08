<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf_token" content="{{csrf_token()}}">
    <meta name="base_url" content="{{url('/')}}">
    <title>Login - UsanceBook 📰</title>
    <!-- <link rel="shortcut icon" href="{{asset('img/logo.png')}}"/> -->
    <meta name="theme-color" content="#000">

    <link rel="stylesheet" href="{{asset('fontawsome/releases/v0.0.0/css/pro.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
    <div id="app">
        <App {{App::isLocale('fa')?'dir=auto':'dir=auto'}}></App>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>