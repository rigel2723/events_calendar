<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calendar Events</title>
        <!-- REMOVE CSRF TOKEN Error in console -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            window.laravel = { csrfToken: '{{ csrf_token() }}' }
        </script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
        <link rel="stylesheet" href="{{ asset('css/vcalendar.css') }}">
    </head>
    <body class="antialiased">
        <div id="app">
            <calendar-component></calendar-component>
        </div>
        <script src="{{ asset('js/app.js')  }}"></script>
    </body>
</html>
