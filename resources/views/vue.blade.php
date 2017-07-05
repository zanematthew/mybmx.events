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
            <!-- use router-link component for navigation. -->
            <!-- specify the link by passing the `to` prop. -->
            <!-- <router-link> will be rendered as an `<a>` tag by default -->
            <!-- https://router.vuejs.org/en/api/router-link.html -->
            <!-- <router-link to="/event">Go to Event</router-link> -->
            <!-- <router-link to="/events">Go to Events</router-link> -->
            <!-- route outlet -->
            <!-- component matched by the route will render here -->
            <!-- https://router.vuejs.org/en/api/router-view.html -->

            <div class="masthead row">
                <div class="top row">
                    <div class="container"></div>
                </div>
                <div class="bottom row">
                    <div class="container">
                        <div class="nav is-secondary grid is-100">
                            {{-- <router-link :to="{ path: 'relative/path', query: }" append>Test</router-link> --}}
                            <router-link :to="{ name: 'events', params: { when: 'this-month' } }"
                            :class="[$route.fullPath.includes('events') ? 'is-active' : '']"
                            class="nav-item">Events</router-link>
                            <router-link :to="{ name: 'venues' }" class="nav-item">Venues</router-link>
                            <state-select></state-select>
                        </div>
{{--  --}}
{{-- <div class="location nav is-secondary">
    <v-select multiple
        :options="states"
        :on-change="consoleCallback"
        ></v-select>
    <span class="nav-item"><i class="fa fa-map-marker" aria-hidden="true"></i>Maryland, Virginia</span>
    <a href="#" class="nav-item"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
</div> --}}
{{--  --}}
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
