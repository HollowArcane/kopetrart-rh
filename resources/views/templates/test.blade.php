<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> GRH Client </title>

        <link rel="stylesheet" type="text/css" href="/app/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/app/css/login.css" />
        <link rel="stylesheet" type="text/css" href="/lib/fa/css/all.min.css" />

        <link rel="stylesheet" type="text/css" href="/lib/mdb5/css/mdb.min.css" />

        <script type="text/javascript" src="/lib/mdb5/js/mdb.umd.min.js" defer></script>
        <script type="text/javascript" src="/lib/toolkit/js/dom.toolkit.js"></script>
        <script type="text/javascript" src="/app/js/script.js" defer></script>


        @yield('style')
    </head>
    <body>
        <div id="container">
            <aside>
                <x-navbar.main></x-navbar.main>
            </aside>
            <main style="height: 100dvh">
                <div class="rh__stack">
                    <div class="container mt-5">
                            @yield('content')
                    </div>
                </div>
            </main>
            <footer>
                @yield('footer')
            </footer>
        </div>
    </body>
</html>
