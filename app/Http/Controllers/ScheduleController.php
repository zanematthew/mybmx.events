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
            ->get()
        );
    }

    public function masterEventIds(): \Illuminate\Http\JsonResponse
    {
        $schedules = Auth::user()
            ->schedules()
            ->where('name', 'master')
            ->whereHas('events')
            ->with('events')
            ->get()
            ->map(function ($item) {
                return $item->events->pluck('id');
            });
        $eventIds = [];
        foreach($schedules as $k => $v) {
            foreach ($v as $vv) {
                $eventIds[] = $vv;
            }
        }
        return response()->json($eventIds);
    }

    public function attendingEventsMaster(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Auth::user()
            ->schedules()
            ->where('name', 'master')
            ->with('events.venue.city.states')
            ->first()
        );
    }

    public function store(Schedule $schedule): \Illuminate\Http\JsonResponse
    {
        $schedule->name = request('name');
        $schedule->user()->associate(Auth::user());
        $schedule->save();

        return response()->json([
            'id'         => $schedule->id,
            'name'       => $schedule->name,
            'created_at' => $schedule->created_at,
        ]);
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $schedule = Auth::user()->schedules()->findOrFail($request->id);
        $schedule->name = $request->name;
        $updated        = $schedule->save();

        return response()->json($schedule);
    }

    public function toggleDefault(Request $request): \Illuminate\Http\JsonResponse
    {
        $schedule = Auth::user()->schedules()->findOrFail($request->id);
        $schedule->default = $schedule->default ? false : true;
        $schedule->save();
        return response()->json($schedule);
    }

    public function masterAttend(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(Auth::user()
            ->schedules()
            ->where('name', 'master')
            ->firstOrFail()
            ->events()
            ->toggle($request->id)
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
}
