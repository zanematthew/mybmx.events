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
                    <example-component></example-component>
            <div class="masthead row">
                <div class="top row">
                    <div class="container">
                        <primary-nav :items="primaryNav"></primary-nav>
                    </div>
                </div>
                <div class="bottom row">
                    <div class="container">
                        <div class="nav is-secondary grid is-100">
                            <secondary-nav :items="secondaryNav"></secondary-nav>
                            <state-select :type="$route.name"></state-select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <router-view></router-view>
            </div>
            <div class="grid is-100 footer">
                <div class="container"></div>
            </div>
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
