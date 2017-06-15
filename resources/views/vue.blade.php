<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'My BMX Events') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div id="app">
            <!-- use router-link component for navigation. -->
            <!-- specify the link by passing the `to` prop. -->
            <!-- <router-link> will be rendered as an `<a>` tag by default -->
            <!-- https://router.vuejs.org/en/api/router-link.html -->
            <!-- <router-link to="/event">Go to Event</router-link> -->
            <!-- <router-link to="/events">Go to Events</router-link> -->
            <!-- route outlet -->
            <!-- component matched by the route will render here -->
            <!-- https://router.vuejs.org/en/api/router-view.html -->
            <div class="container">
                <router-view></router-view>
            </div>
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
