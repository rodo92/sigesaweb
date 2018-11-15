<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>WebSigesa | Login</title>

    <link rel="stylesheet" type="text/css" href="{{ ('css/app.css') }}">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">

<div id="app">
    <login></login>
</div>

<script src="{{ ('js/app.js') }}" type="text/javascript" charset="utf-8" async defer></script>
</body>
</html>
