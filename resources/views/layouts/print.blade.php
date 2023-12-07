<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>


        <style type="text/css">
            body::-webkit-scrollbar {
                display: none;
            }
            @page {
                size: portrait;
            }
        </style>



    </head>
    <body class="font-sans antialiased ">
        <v-app id="app">
            <div class="min-h-screen " style="height:calc(100% - 50px">
                <!-- Page Heading -->
                <header class="bg-white ">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                <!-- Page Content -->
                <main  class="h-100">
                    {{ $slot }}
                </main>
            </div>
        </v-app>
    </body>
</html>
<!-- <style lang="scss" scoped>
    body::-webkit-scrollbar {
        display: none;
    }
    @page {
        size: A4 portrait;
    }
</style> -->
