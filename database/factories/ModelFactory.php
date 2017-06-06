<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

// City Factory
$factory->define(App\City::class, function (Faker\Generator $faker) {
    return ['name' => $faker->city];
});

// State Factory
$factory->define(App\State::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->state,
        'abbr' => $faker->unique()->stateAbbr,
    ];
});

// CityState Factory
$factory->define(App\CityState::class, function (Faker\Generator $faker) {
    return [
        'city_id'  => function () {
            return factory(App\City::class)->create()->id;
        },
        'state_id' => function () {
            return factory(App\State::class)->create()->id;
        }
    ];
});

// Venue Factory
$factory->define(App\Venue::class, function (Faker\Generator $faker) {
    return [
        'created_at'      => date('Y-m-d H:i:s', time()),
        'updated_at'      => date('Y-m-d H:i:s', time()),
        'name'            => $faker->company,
        'district'        => random_int(1, 99),
        'usabmx_id'       => random_int(1, 999),
        'website'         => $faker->url,
        'image_uri'       => $faker->imageUrl(),
        'description'     => $faker->text,
        'email'           => $faker->safeEmail,
        'primaryContact' => $faker->name,
        'phoneNumber'    => $faker->phoneNumber,
        'streetAddress'   => $faker->streetAddress,
        'zipCode'         => $faker->postcode,
        'lat'             => $faker->latitude,
        'long'            => $faker->longitude,
        'city_id'         => function () {
            return factory(App\CityState::class)->create()->city_id;
        },
    ];
});

// Event Factory
$factory->define(App\Event::class, function (Faker\Generator $faker) {
    return [
        'title'      => $faker->name,
        'start_date' => $faker->date(),
        'end_date'   => $faker->date(),
        'venue_id'   => function () {
            return factory(App\Venue::class)->create()->id;
        },
        'type'       => function () {
            $types = [
                'Double Points',
                'Single Points',
                'Triple Points',
                'State Qualifier',
                'State Championship Final',
                'Redline Cup Qualifier',
                'Redline Cup Final',
                'National',
                'Grand National',
            ];
            return $types[array_rand($types)];
        }
    ];
});

// Schedule Factory
$factory->define(App\Schedule::class, function (Faker\Generator $faker) {
    return [
        'name'    => $faker->word,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});

// Assign Events to a Schedule
$factory->define(App\EventSchedule::class, function (Faker\Generator $faker) {
    return [
        'event_id'   => function () {
            return factory(App\Event::class)->create()->id;
        },
        'schedule_id' => function () {
            return factory(App\Schedule::class)->create()->id;
        }
    ];
});
