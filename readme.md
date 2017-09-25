# Setup

1. Run migrations
2. Seed Database
3. Run Unit Test
4. Develop

# Database

## Migrations

## Seeding

# Models

All Models are defined using the [`php artian make:model <Model>`](https://laravel.com/docs/5.4/eloquent#defining-models) command.

This application has the following models:

    * City
        * One City belongs to many States
    * State
        * One State has many Cities
    * Event
        * Many Events belongs to many Schedules
        * One Event belongs to one Venue
    * Venue
        * One Venue has many Events
        * One Venue belongs to one City
    * Schedule
        * Many Schedules belongs to one user
    * User
        * One User has many Schedules

## Model Factories

Test data generated with [Faker](https://github.com/fzaninotto/Faker) will result in strange scenarios, i.e., Event start and end dates will date back into the 1900's.

**Fake Event**

An Event cannot exists without a Venue. When faking an Event a Venue, City, and State are created. Each item is related accordingly.

`factory(App\Event::class)->create(); // One Event`

`factory(App\Event::class, 5)->create(); // 5 Events, this will also create 5 Venues, Cities, and States`

**Fake Schedule, with Fake Events**

A fake Schedule will create a fake Event, along with needed relations.

`factory(App\EventSchedule::class)->create();`

# Routes

## API Routes

These are handled via Laravel. These are the routes for interacting with the database.

http://mybmx.events/api/event/{id}/{slug?}
http://mybmx.events/api/events/{state?}
http://mybmx.events/api/events/{year}/{state?}
http://mybmx.events/api/events/{year}/{month}/{state?}
http://mybmx.events/api/events/{year}/{type}/{state?}
http://mybmx.events/api/events/{year}/{month}/{type}/{state?}

http://mybmx.events/api/venue/{id}/{slug?}
http://mybmx.events/api/venues/{state?}

http://mybmx.events/{vue?}
Catch all for VueRouter.

## Front-end Routes

These are handled by VueJS.

http://mybmx.events/event/{id}/{slug?}
http://mybmx.events/events/{state?}
http://mybmx.events/events/{year}/{state?}
http://mybmx.events/events/{year}/{month}/{state?}
http://mybmx.events/events/{year}/{type}/{state?}
http://mybmx.events/events/{year}/{month}/{type}/{state?}

**Links**

* [Laravel Routes Documentation](https://laravel.com/docs/5.4/routing).
* List routes `php artisan route:list`.

## Events

**Plural**
`events/`, Return _all_ Events for the _current_ year (Y).
`events/<year>/`, Return _all_ Events for a given year (Y).
`events/<year>/<month>`, Return _all_ Events for a given year (Y), and month (n).
`events/<year>/<month>/<type>`

Plus state abbr

**Singular**
`event/<name>/<id>`, Return a single Event.

## Venues

`venues/`, Return _all_ Venues for _current_ state.
`venues/<state abbr>`, Return _all_ Venues by state abbreviation.
`venue/<name>/<id>`, Return a _single_ Venue prefixed with the name and Venue id.
`venue/<name>/events/<id>`, Return a _single_ Venue with _all_ Events for the _current_ Venue, and _current_ year (Y), prefixed with the Venue id.
`venue/<name>/events/<year>/<id>`, Return a _single_ Venue with _all_ Events for the _current_ Venue, year (Y) provided, prefixed with the Venue id.
`venue/<name>/events/<year>/<month>/<id>`, Return a _single_ Venue with _all_ Events for the _current_ venue, given a year (Y), and month (n) prefixed with the Venue id.

## Resource Routes

### Schedule

`schedule/`
`schedule/<id>/edit`
`schedule/<id>/add/<event id>/`

### Login, Register, Socialite

# Testing

`.env`, DB_DATABASE_TEST mysql_testing
`config/database.php`