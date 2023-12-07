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
        <script src="{{ asset('/js/app.js') }}" defer></script>

        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    

        <!-- jspdf -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" ></script>

        <!-- PDF-LIB -->
        <script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.4.0/dist/pdf-lib.min.js" ></script>

        <script src="https://cdn.jsdelivr.net/npm/splitpanes@3.1.5/dist/splitpanes.umd.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/splitpanes@3.1.5/dist/splitpanes.min.css">

        <!-- xlsx-populate -->
        <!-- <script src="https://cdn.tutorialjinni.com/xlsx-populate/1.21.0/xlsx-populate-no-encryption.js"></script> -->
        <!-- <script src="https://cdn.tutorialjinni.com/xlsx-populate/1.21.0/xlsx-populate-no-encryption.min.js"></script> -->
        <!-- <script src="https://cdn.tutorialjinni.com/xlsx-populate/1.21.0/xlsx-populate.js"></script> -->
        <!-- <script src="https://cdn.tutorialjinni.com/xlsx-populate/1.21.0/xlsx-populate.min.js"></script> -->

        <!-- FileSaver.js -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js"></script> -->

        <!-- vue-print-nb -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/vue-print-nb@1.7.5/lib/print.umd.min.js"></script> -->

    </head>
    <body class="font-sans antialiased">
        <iframe src="/sound/silence.mp3" allow="autoplay" id="audio" style="display:none"></iframe>
        <v-app id="app">
            <div class="min-h-screen " style="height:calc(100% - 50px)">
                <!-- Page Heading -->
                <header class="bg-white">
                    <!-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 m-0 p-0"> -->
                    <div class="m-0 p-0">
                        {{ $header }}
                    </div>
                </header>
                <!-- Page Content -->
                <main class="h-100">
                    {{ $slot }}
                </main>
            </div>
        </v-app>
    </body>
</html>
<style lang="scss" scoped>
    body::-webkit-scrollbar {
        display: none;
    }
    @page {
        size: portrait;
    }
</style>
