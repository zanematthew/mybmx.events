<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'My BMX Events') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.0.0/dist/vue-multiselect.min.css">

    </head>
    <body>
        <div id="app">
            <div class="app-container">
                @yield('content')
                <user class="row is-item align-center"></user>
            </div>
        </div>
        <script>
            @if (Auth::check())
                let authuser = {
                    default: {!! Auth::user() !!},
                    social_account: {
                        facebook: {
                            avatar: "{!! Auth::user()->avatar !!}"
                        }
                    }
                };
            @else
                let authuser = '{}';
            @endif
        </script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
