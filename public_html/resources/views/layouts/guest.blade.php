<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="position: relative; overflow: hidden;">
            <!-- ✅ الخلفية بدون blur -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('{{ asset('assets/images/login_background.jpg') }}'); background-size: cover; background-position: center;"></div>

            <!-- طبقة شفافة خفيفة -->
         <!--<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.5); z-index: 1;"></div>-->

            <!-- صندوق تسجيل الدخول -->
            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <div>
                    <a href="/">
                        <img src="{{ asset('assets/images/login_background.jpg') }}" alt="Logo" class="w-20 h-20 object-contain" />
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
