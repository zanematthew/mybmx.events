<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use Laravel\Passport\Passport;

class CreaseBaseScheduleTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testBaseScheduleCreated()
    {
        $user = factory(User::class)->make();
        $this->post('register', [
            '_token'                => csrf_token(),
            'name'                  => $user->name,
            'email'                 => $user->email,
            'password'              => $user->password,
            'password_confirmation' => $user->password,
        ]);
        $this->assertDatabaseHas('schedules', [
            'user_id' => User::first()->id,
            'name'    => 'master'
        ]);
    }

    // Make a user.
    // Register the user, so the event listener on registration fires that
    // creates the "master" schedule.
    // Retrieve the newly registered user.
    // Create 4 more schedules and assign them to the user, 5 schedules total.
    // Visit the /api/user/schedule/ route verify that the "master" schedule does
    // NOT show in the list.
    public function testMasterNotInIndex()
    {
        $user = factory(User::class)->make();
        $this->post('register', [
            '_token'                => csrf_token(),
            'name'                  => $user->name,
            'email'                 => $user->email,
            'password'              => $user->password,
            'password_confirmation' => $user->password,
        ]);

        $user = User::find(User::first()->id);
        factory(\App\Schedule::class, 4)->create([
            'user_id' => $user->id,
        ]);
        Passport::actingAs($user);
        $response = $this->get(route('user.schedule.index'));
        $response->assertJsonMissing([
            'name' => 'master'
        ]);
    }
}