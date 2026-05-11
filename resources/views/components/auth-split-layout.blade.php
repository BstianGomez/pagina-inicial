<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="height:100%;overflow:hidden;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            *, *::before, *::after { box-sizing: border-box; }
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
                overflow: hidden;
                font-family: 'Instrument Sans', sans-serif;
            }
        </style>
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
