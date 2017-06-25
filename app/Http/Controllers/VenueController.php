<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VenueController extends Controller
{
    protected $paginate = 10;
    protected $requestParams = [
        'page',
    ];

    public function index()
    {
        return \App\Venue::with('city.states', 'events')
            ->orderby('name', 'asc')
            ->paginate($this->paginate);

        // return \App\Venue::with('city.states', 'events')->whereHas('events', function ($query) {
        //     $query->whereBetween('start_date', [
        //         date('Y-m-d', strtotime('today')),
        //         date('Y-m-d', strtotime('last day of this month')),
        //     ]);
        // })->orderby('name', 'asc')->paginate($this->paginate);
    }

    public function state($state = null)
    {
        return \App\Venue::with('city.states', 'events')->whereHas('city.states', function ($query) use ($state) {
            $query->where('abbr', strtoupper($state));
        })->paginate($this->paginate);
    }

    public function single($venueId = null)
    {
        return \App\Venue::with('events')->where('id', $venueId)->first();
    }

    public function events($eventId = null)
    {
        return \App\Events::where('venue_id', $eventId)->whereBetween('start_date', [
            date('Y-m-d', strotime('today')),
            date('Y-m-d', strtotime('last day of this month')),
        ])->paginate($this->paginate);
    }
}
