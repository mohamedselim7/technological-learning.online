<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home</title>

    @include('layouts.header')
    @include('layouts.navbar')
    @yield('page_css')
</head>

<body style="background-image: url('{{ asset('assets/images/login_background.jpg') }}'); background-size: cover; background-position: center; ">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.5); z-index: -1;"></div>

    @yield('content')

    {{-- الشات بوت --}}
    @include('components.chatbot')

    @include('layouts.scripts')
    @yield('page_js')
</body>

</html>
