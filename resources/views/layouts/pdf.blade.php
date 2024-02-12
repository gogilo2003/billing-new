<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <style>
        @import url('{{ asset('/css/pdf.css') }}');

    </style>
    @stack('styles')
</head>

<body>
    <div class="title">
        <h4>
            @yield('title')
        </h4>
    </div>
    <div class="wrapper">
        <div class="container">
            @yield('content')
        </div>
    </div>
</body>

</html>
