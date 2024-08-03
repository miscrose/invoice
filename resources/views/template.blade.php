<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        .toggle-button {
            position: relative;
            width: 60px;
            height: 20px;
            background-color: red;
            border-radius: 20px;
            border: 2px solid black;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .toggle-button .toggle-circle {
            position: absolute;
            width: 17px;
            height: 17px;
            background-color: black;
            border-radius: 50%;
           
        }
        .toggle-button.on {
            background-color: green;
        }
        .toggle-button.on .toggle-circle {
            transform: translateX(40px);
        }
      </style>
</head>
<body style="background-color: #e7e7e7;">

@auth
       @include('nav')


@if (Auth::user()->uservalid==='v')
<div class="container-fluid " >

    @yield('contenu')

</div>

@else
    <h1>attente de validation du compte</h1>
@endif

@endauth




<div class="container-fluid " >

    @yield('login_sign')

</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>