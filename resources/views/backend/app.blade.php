<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/fontawesome-all.css') }}" />

        <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>

        <link rel="stylesheet" href="{{ asset('css/datatables.css') }}" />
        <script src="{{ asset('js/datatables.js') }}"></script>

        <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}" />
        <script src="{{ asset('js/sweetalert2.js') }}"></script>

        <title>{{ $title }}</title>
    </head>
    <body class="bg-gray-200 flex flex-col min-h-screen">
        <header>
        @include('backend.menu')
        </header>

        <main class="flex-grow mb-6">
        @yield('content')
        </main>

        <footer>
        @include('backend.footer')
        </footer>
    </body>
</html>