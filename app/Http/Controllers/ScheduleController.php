<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Schedule as Schedule;

class ScheduleController extends Controller
{
    protected $masterSchedule;

    public function __construct()
    {
        $this->masterSchedule = 'master';
    }

    /**
     * Retrieve the currently logged in users schedule(s), and events associated with
     * the given schedule, as a paginated collection.
     *
     * @return void An JSON HTTP response containing the current users schedule.
     */
    public function index()
    {
        return Schedule::with('events')->where('user_id', Auth::id())->orderby('created_at', 'desc')->paginate();
    }

    /**
     * Retrieve the currently logged in user schedule by schedule ID.
     *
     * @param  int $id The schedule ID.
     * @return void    HTTP response containing a JSON paginated collection.
     */
    public function show($id = null)
    {
        $schedules = \App\User::find(Auth::id())
            ->schedules()
            ->with('events')
            ->where('id', $id)
            ->firstOrFail()
            ->toArray();

        return response()->json(array_merge($schedules, [
            'count' => count($schedules['events'])
            ])
        );
    }

    /**
     * Create a new schedule and associated with the logged in user.
     *
     * @return void HTTP JSON response containing; created, and created at.
     */
    public function store()
    {
        $schedule = new Schedule;
        $schedule->name = request('name');
        $schedule->user()->associate(Auth::user());
        $schedule->default = 0;
        $schedule->save();

        return response()->json([
            'id'         => $schedule->id,
            'name'       => $schedule->name,
            'created_at' => $schedule->created_at,
            'events'     => 0,
        ]);
    }

    /**
     * Delete a currently logged in users schedule based on schedule ID, and
     * remove associated events.
     *
     * @return void HTTP JSON response containing; deleted boolean.
     */
    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        if ($schedule->user_id !== Auth::id()) {
            return response(json_encode(['value' => false]), 401)->header('Content-Type', 'application/json');
        }

        $schedule->events()->detach();

        return response()->json(['deleted' => $schedule->delete()]);
    }

    /**
     * Update a schedule name based on schedule ID for the currently logged in user.
     *
     * @return void HTTP JSON response containing; updated boolean, and update_at time.
     */
    public function update(Request $request)
    {
        $schedule = Schedule::where([
            'user_id' => Auth::id(),
            'id' => $request->id
        ])->firstOrFail();
        $schedule->name = $request->name;
        $updated        = $schedule->save();

        return response()->json([
            'updated'    => $updated,
            'updated_at' => $schedule->updated_at,
        ]);
    }

    /**
     * For the currently logged-in user toggle an array of events based on IDs for
     * a given schedule.
     *
     * @return void HTTP JSON response containing an array of items that are attached, and detached.
     *                   404 if schedule is not found.
     */
    public function toggleAttendToMaster($eventId = null, $id = null)
    {
        return response()->json([
            'toggled' => Schedule::where('name', '=', $this->masterSchedule)
                ->firstOrFail()
                ->events()
                ->toggle($eventId),
        ]);
    }

    public function toggleDefaultSchedule(Request $request)
    {
        $schedule = Schedule::findOrFail($request->input('id'));
        $schedule->default = $schedule->default ? false : true;
        $schedule->save();
        return response()->json($schedule);
    }

    // Get all event IDs that are attached to a schedule
    // @todo needs test
    public function allEventIds()
    {
        $schedules = Schedule::where([
            'user_id' => Auth::id(),
            'name' => $this->masterSchedule,
            ])
            ->with('events')
            ->whereHas('events')
            ->get()
            ->map(function ($item) {
                return $item->events->pluck('id');
            })->toArray();
        $final = [];
        foreach($schedules as $k => $v) {
            foreach ($v as $vv) {
                $final[] = $vv;
            }
        }
        return response()->json($final);
    }

    public function attendingMaster()
    {
        return response()->json(Schedule::where([
            'user_id' => Auth::id(),
            'name' => $this->masterSchedule
        ])->with('events.venue.city.states')->get());
    }
}
