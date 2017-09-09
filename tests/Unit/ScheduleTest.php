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

    // Create 7 schedules total
    // Assign 3 to a random user from the factory
    // Assign 4 to our created user.
    // Verify the HTTP status code (200)
    // Verify the returned JSON structure
    // Verify the count
    public function testIndex()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);

        factory(\App\Schedule::class, 1)->create();
        factory(\App\Schedule::class, 2)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('user.schedule.index'));
        $response
            ->assertStatus(200)
            ->assertJsonStructure([[
                'id',
                'created_at',
                'updated_at',
                'name',
                'user_id',
                'default',
                'slug',
            ]]);
        $this->assertCount(2, $response->getData());
    }

    public function testAllEventIds()
    {
        // Create a user
        $user = factory(\App\User::class)->create();

        // Sign user into our API
        Passport::actingAs($user);

        // Instantiate a new schedule
        // associate the schedule with our API user
        // save our schedule to the database
        $schedule = new \App\Schedule;
        $schedule->user()->associate($user);
        $schedule->save();

        // Create 5 events
        // Retrieve the first 3 events
        // Assign only 3 of the 5 events to our user
        $events = factory(\App\Event::class, 5)->create();
        $firstThreeEvents = array_slice($events->toArray(), 0, 3);
        $schedule->events()->toggle(array_pluck($firstThreeEvents, 'id'));

        // Retrieve the event ids for our user.
        // Verify the status 200
        // verify the Json structure is an array
        // verify the count is 3
        // verify we created 5 events
        $response = $this->get(route('user.schedule.master.event.ids'));
        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);
        $this->assertCount(3, $firstThreeEvents);
        $this->assertCount(5, $events);
    }

    public function testAttendingEventsMaster()
    {
        // Create two master schedules with events for two users.
        // Retrieve only the master schedule for a given user.

        // Create 5 events
        $events = factory(\App\Event::class, 5)->create();
        $firstThreeEvents = array_slice($events->toArray(), 0, 3);

        // Create a schedule named "master", assign it to a user
        $schedule = factory(\App\Schedule::class)->create([
            'name' => 'master',
            'user_id' => factory(\App\User::class)->create()
        ]);

        // Assign the first 3 events to our user
        $schedule->events()->toggle(array_pluck($firstThreeEvents, 'id'));


        // Create another user
        // sign user into API
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);
        // Create schedule named "master", assigned to our user
        $otherSchedule = factory(\App\Schedule::class)->create([
            'name' => 'master',
            'user_id' => $user->id
        ]);
        // Toggle all 5 events to this user
        $otherSchedule->events()->toggle(array_pluck($events->toArray(), 'id'));

        // Verify our status 200
        // Verify the json structure
        // verify that we created 2 schedules
        $response = $this->get(route('user.schedule.attending.events.master'));
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'created_at',
                'updated_at',
                'name',
                'user_id',
                'default',
                'slug',
                'events' => [[]]
            ]);
        $this->assertCount(2, \App\Schedule::all());
    }

    public function testStore()
    {
        Passport::actingAs(factory(\App\User::class)->create());

        $response = $this->post(route('user.schedule.store'), [
            'name' => 'My Awesome Name'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'created_at',
                'updated_at',
        ]);
    }

    public function testUpdate()
    {
        // Create user
        // Sign into API.
        // Create and assign a generated schedule
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);
        $schedule = factory(\App\Schedule::class)->create([
            'user_id' => $user->id,
        ]);
        // Store the current name
        $oldName = $schedule->name;
        // Send the HTTP post request with the schedule ID as the route param, and the
        // name as the request
        $response = $this->post(route('user.schedule.update', [
            'id' => $schedule->id
        ]), [
            'name' => 'New Name'
        ]);

        // Verify the response 200,
        // JSON structure
        // Old name is not equal to new name.
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'created_at',
                'updated_at',
                'name',
                'user_id',
                'default',
                'slug',
            ]);

        $this->assertNotEquals($oldName, \App\Schedule::find($schedule->id)->pluck('name'));
    }

    public function testToggleDefault()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);
        $schedule = factory(\App\Schedule::class)->create([
            'user_id' => $user->id,
        ]);
        $oldDefault = $schedule->default;

        $response = $this->json('POST', route('user.schedule.toggle.default', [
            'id' => $schedule->id,
        ]));
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'created_at',
                'updated_at',
                'name',
                'user_id',
                'default',
                'slug',
            ]);
        $this->assertNotEquals($oldDefault, \App\Schedule::find($schedule->id)->pluck('default'));
    }

    public function testMasterAttend()
    {
        // Create user
        // Sign user into API
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);

        // Create master schedule
        // assign to user
        $schedule = factory(\App\Schedule::class)->create([
            'user_id' => $user->id,
            'name' => 'master',
        ]);

        // Create 3 events, assign all events to the users
        // "master" schedule.
        $events = factory(\App\Event::class, 3)->create();
        $user
            ->schedules()
            ->where('name', 'master')
            ->firstOrFail()
            ->events()
            ->toggle($events->pluck('id'));

        // Get a random ID from the list to "toggle".
        $toggledId = $events->pluck('id')[rand(0,2)];

        // Send the request to remove it from the schedule.
        $response = $this->json('POST', route('user.schedule.master.attend', [
            'id' => $toggledId,
        ]));

        // Verify the status
        // verify the structure
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'attached',
                'detached'
            ]);

        // Verify that the item was removed from the schedule.
        // Retrieve the master schedule, get all events, pluck the ID, and build an array
        // computer the intersection of this array with toggled item, which
        // should not be in the array.
        $this->assertEmpty(array_intersect(
            $user->schedules()->where('name', 'master')->firstOrFail()->events()->pluck('id')->toArray(),
            [$toggledId]
        ));
    }

    public function testDelete()
    {
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);
        $schedule = factory(\App\Schedule::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('DELETE', route('user.schedule.delete', [
            'id' => $schedule->id
        ]));
        $response->assertStatus(200);
    }

    public function testToggleEventTo()
    {
        // Create user
        $user = factory(\App\User::class)->create();
        Passport::actingAs($user);

        // Create schedule
        $schedule = factory(\App\Schedule::class)->create([
            'user_id' => $user->id
        ]);

        $event = factory(\App\Event::class)->create();

        // Add one event to schedule
        $response = $this->json('POST', route('user.schedule.event.toggle', [
            'eventId'    => $event->id,
            'scheduleId' => $schedule->id,
        ]), [
            'eventId'    => $event->id,
            'scheduleId' => $schedule->id,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'attached' => [
                    0 => $event->id
                ],
                'detached' => []
            ]);
    }

    /**
     * Get all Events for a given Schedule.
     *
     * @group schedule
     */
    public function testEvents()
    {

        $user = factory(\App\User::class)->create();

        Passport::actingAs($user);

        $schedule = new \App\Schedule;
        $schedule->user()->associate($user);
        $schedule->save();

        $events = factory(\App\Event::class, 5)->create();
        $firstThreeEvents = array_slice($events->toArray(), 0, 3);
        $schedule->events()->toggle(array_pluck($firstThreeEvents, 'id'));

        $response = $this->get(route('user.schedule.events', [
            'id' => $schedule->id
        ]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([[
                'id',
                'created_at',
                'updated_at',
                'title',
            ]]);
    }
}
