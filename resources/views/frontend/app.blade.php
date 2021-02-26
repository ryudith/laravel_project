<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

        <title>{{ $title }}</title>
    </head>
    <body class="bg-gray-200 flex flex-col min-h-screen">
        <header>
        @include('frontend.menu')
        </header>

        <main class="flex-grow">
        @yield('content')
        </main>

        <footer>
        @include('frontend.footer')
        </footer>
    </body>
</html>