<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Schedule as Schedule;

class ScheduleController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Auth::user()
            ->schedules()
            ->where('name', '!=', 'Master')
            ->orderBy('updated_at', 'desc')
            ->get()
        );
    }

    public function store(Schedule $schedule): \Illuminate\Http\JsonResponse
    {
        $schedule->name = request('name');
        $schedule->user()->associate(Auth::user());
        $schedule->save();

        return response()->json($schedule->getAttributes());
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $schedule = Auth::user()->schedules()->findOrFail($request->id);
        $schedule->name = $request->name;
        $schedule->save();

        return response()->json($schedule);
    }

    public function toggleDefault(Request $request): \Illuminate\Http\JsonResponse
    {
        $schedule = Auth::user()->schedules()->findOrFail($request->id);
        $schedule->default = $schedule->default ? false : true;
        $schedule->save();
        return response()->json($schedule);
    }

    public function toggleEventTo(Request $request) :\Illuminate\Http\JsonResponse
    {
        return response()->json(Auth::user()
            ->schedules()
            ->findOrFail($request->scheduleId)
            ->events()
            ->toggle($request->eventId)
        );
    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $scheduleWithEvents = Auth::user()
            ->schedules()
            ->findOrFail($request->id);

        $scheduleWithEvents->events()->detach();
        $deleted = $scheduleWithEvents->delete();
        return response()->json($deleted);
    }

    public function events(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(Auth::user()
            ->schedules()
            ->find($request->id)
            ->events()
            ->with('venue.city.states')
            ->get()
        );
    }
}
