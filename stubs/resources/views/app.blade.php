<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        @routes

        @env (['local'])
            <script type="module" src="http://localhost:3000/@vite/client"></script>
            <script type="module" src="http://localhost:3000/resources/js/app.js"></script>
        @else
            @php
                $manifest = json_decode(File::get(public_path('dist/manifest.json')), true);
            @endphp

            <link rel="stylesheet" href="{{ asset('dist/' . $manifest['resources/js/app.js']['css'][0]) }}">

            <script type="module" src="{{ asset('dist/' . $manifest['resources/js/app.js']['file']) }}"></script>
        @endenv
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
