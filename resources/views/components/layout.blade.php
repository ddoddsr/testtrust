<!doctype html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Laravel Layout </title>

        <!-- Stylesheet -->
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        <!-- Page Content -->
        {{ $slot }}
    </body>
    @stack('modals')
    @stack('scripts')
    @livewireScripts
</html>