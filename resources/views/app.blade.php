<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') . '?v=' . 'build_' . rand(1, 100) }}" defer></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('font-awesome-4.7.0/css/font-awesome.css')}}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') . '?v=' . 'build_' . rand(1, 100) }}" rel="stylesheet">
</head>
<body>
    <div id="app" v-cloak>
        <router-view></router-view>
    </div>
</body>
</html>
