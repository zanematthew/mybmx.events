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
            </div>
        </div>
        @auth
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();"
                class="row is-item align-center">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @endauth

        <script>
            @auth
                let authuser = {
                    default: {!! Auth::user() !!},
                    social_account: {
                        facebook: {
                            avatar: "{!! Auth::user()->avatar !!}"
                        }
                    }
                };
            @endauth

            @guest
                let authuser = '{}';
            @endguest
        </script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
