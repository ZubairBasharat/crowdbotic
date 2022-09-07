<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            position: relative;
        }
        .login__container {
            background-image: url('{{ asset("assets/images/svg/login-bg.svg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100%;
            padding: 20px
        }
        #login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 500px;
            width: 100%;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        #login-form h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            margin-top: 0;
            text-align: center;
            color: #1f3d83;
        }
        .form-control {
            border: 1px solid #ccc;
            border-radius: 7px;
            padding: 10px 20px;
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
            width: 100%;
            height: 40px;
            outline: none;
        }
        .btn--login {
            background: #1f3d83;
            border: none;
            border-radius: 7px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            padding: 10px 20px;
            text-transform: uppercase;
            width: 100%;
            height: 40px;
            outline: none;
            cursor: pointer;
            margin-top: 20px
        }
        .alert {
            text-align: center;
            font-size: 14px;
            background-color: #ff000036;
            border-radius: 10px;
            padding: 10px;
            color: #444;
            margin-bottom: 20px;
        }
        </style>
</head>

<body class="bg-white bg-login">
    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>
