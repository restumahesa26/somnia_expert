<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>@yield('title') | SomniaExpert</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ url('dist/assets/images/logo-sm.png') }}">

        @include('includes.style')

        @stack('styles')

        <script src="{{ url('dist/assets/js/head.js') }}"></script>


    </head>

    <body>
        <!-- Begin page -->
        <div class="account-page">
            <div class="container-fluid p-0">
                <div class="row align-items-center g-0 px-3 py-3 vh-100">

                    @yield('content')

                </div>
            </div>
        </div>

        <!-- END wrapper -->

        @include('includes.script')

        @stack('scripts')

        <!-- App js-->
        <script src="{{ url('dist/assets/js/app.js') }}"></script>

    </body>
</html>
