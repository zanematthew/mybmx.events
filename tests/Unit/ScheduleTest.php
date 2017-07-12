<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;

class ScheduleTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testNew()
    {
        Passport::actingAs(factory(\App\User::class)->create());
        $response = $this->post(route('schedule.store', [
            'name' => 'Awesome Schedule',
        ]));

        // dd($response->decodeResponseJson());
        $response
            ->assertStatus(200)
            ->assertJson([
                'id'         => true,
                'name'       => true,
                'created_at' => true,
            ]);
    }

    public function testEdit()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);

        $schedule = new \App\Schedule;
        $schedule->name = 'Foo';
        $schedule->user()->associate($user);
        $schedule->save();

        $response = $this->json('POST', route('schedule.update', [
            'id' => $schedule->id,
        ]), [
            'id' => $schedule->id,
            'name' => 'My new name',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);
    }

    public function testDestroy()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);

        $schedule = new \App\Schedule;
        $schedule->name = 'Foo';
        $schedule->user()->associate($user);
        $schedule->save();

        $response = $this->json('DELETE', route('schedule.delete', [
            'id' => $schedule->id,
        ]), [
            'id' => $schedule->id,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true,
            ]);
    }

    // Create 1 User
    // Sign the user into the API.
    // Create 1 Schedule
    // Associate Schedule with User
    // Save the schedule to the DB.
    // Create 5 Events
    // Send JSON POST request to the attending route, passing;
    // schedule ID and event id(s)
    public function testMaybeAttend()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);

        $schedule = new \App\Schedule;
        $schedule->name = 'Foo';
        $schedule->user()->associate($user);
        $schedule->save();

        $eventIds = factory(\App\Event::class, 5)->create()->pluck('id')->all();

        $response = $this->json('POST', route('schedule.attend', [
            'id' => $schedule->id,
            'eventIds' => implode(',', $eventIds),
        ]), [
            'id' => $schedule->id,
            'eventIds' => implode(',', $eventIds),
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'toggled' => [
                    'attached' => $eventIds,
                    'detached' => [],
                ]
            ]);
    }

    // Create 1 User
    // Sign the user into the API.
    // Create 1 Schedule
    // Associate Schedule with User
    // Save the schedule to the DB.
    // One event
    // Send JSON POST request to the attending route, passing;
    // schedule ID and event id(s)
    public function testMaybeAttendSingle()
    {
        $user = factory(\App\User::class)->create();

        Passport::actingAs($user);

        $schedule = new \App\Schedule;
        $schedule->name = 'Foo';
        $schedule->user()->associate($user);
        $schedule->save();

        $eventId = factory(\App\Event::class)->create()->pluck('id')->all();

        $response = $this->json('POST', route('schedule.attend', [
            'id' => $schedule->id,
            'eventIds' => implode(',', $eventId),
        ]), [
            'id' => $schedule->id,
            'eventIds' => implode(',', $eventId),
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'toggled' => [
                    'attached' => $eventId,
                    'detached' => [],
                ]
            ]);
    }

    public function testIndex()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);
        $schedule = new \App\Schedule;
        $schedule->name = 'Foo';
        $schedule->user()->associate($user);
        $schedule->save();

        $response = $this->get(route('schedule.index'));

        $response
            ->assertStatus(200)
            ->assertJson(['total' => 1]);
    }

    public function testShow()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);
        $schedule = new \App\Schedule;
        $schedule->name = 'Foo';
        $schedule->user()->associate($user);
        $schedule->save();

        $response = $this->get(route('schedule.show', [
            'id' => $schedule->id,
        ]));

        $response
            ->assertStatus(200)
            ->assertJson(['total' => 1]);
    }

    public function testToggleDefault()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);
        $schedule = new \App\Schedule;
        $schedule->user()->associate($user);
        $schedule->save();

        $response = $this->json('POST', route('schedule.default', [
            'id' => $schedule->id
        ]),
        [
            'id' => $schedule->id
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'default' => true
            ]);
    }
}
