<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8" />

        <title>Joutes - Schedule</title>

        <link href="{{ asset('css/vendor/app.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/schedule.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('css/vendor/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,900" rel="stylesheet">
    </head>
    <body>


            <div id="page">

                <div id="content">

                    @yield('content')

                </div><!-- content -->

            </div><!-- page -->


        <script src="{{ asset('js/vendor/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>
