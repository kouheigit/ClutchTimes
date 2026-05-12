<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('build/assets/app-BmNPS28k.css') }}">
        <script type="module" src="{{ asset('build/assets/app-DLzHMEKX.js') }}"></script>
        
        <style>
            .splash-background {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
                position: relative;
            }
            .splash-background::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.3);
                z-index: 0;
            }
            .splash-content {
                position: relative;
                z-index: 1;
            }
        </style>
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased splash-background">
            <div class="splash-content">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
