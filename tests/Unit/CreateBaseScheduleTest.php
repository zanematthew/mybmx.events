<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreaseBaseScheduleTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testBaseScheduleCreated()
    {
        $user = factory(\App\User::class)->make();
        $this->post('register', [
            '_token'                => csrf_token(),
            'name'                  => $user->name,
            'email'                 => $user->email,
            'password'              => $user->password,
            'password_confirmation' => $user->password,
        ]);
        $this->assertDatabaseHas('schedules', [
            'user_id' => \App\User::first()->id,
            'name'    => 'master'
        ]);
    }
}