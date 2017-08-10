<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Schedule;

class CreateBaseScheduleListener
{
    public function __construct()
    {

    }

    public function handle(Registered $event)
    {
        Schedule::create([
            'name'    => 'master',
            'user_id' => $event->user->id
        ]);
    }
}