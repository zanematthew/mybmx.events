<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User as User;
use App\Library as Library;

/**
 * @todo  Leverage Polymorphism, morphTo for `$item_type`, `$this->typeToFqc()`.
 */
class LibraryController extends Controller
{

    /**
     * For the current user; adds an item to the Library,
     * if its already exists, it is removed.
     *
     * @param  Request $request The HTTP request
     * @param  Library $library The Library model
     * @return Object           JsonResponse
     */
    public function toggle(Request $request, Library $library): \Illuminate\Http\JsonResponse
    {
        $fqc = $this->typeToFqc($request->item_type);
        $foundItemId = Library::where([
            'item_id'   => $request->item_id,
            'item_type' => $fqc,
        ])->pluck('id')->first();

        $detached = [];
        $attached = [];

        if ($foundItemId) {
            $library->destroy($foundItemId);
            $detached[] = $request->item_id;
        } else {
            $library->item_id   = $request->item_id;
            $library->item_type = $fqc;
            $library->user_id   = Auth::id();
            $library->save();
            $attached[] = $request->item_id;
        }

        return response()->json([
            'attached' => $attached,
            'detached' => $detached,
        ]);
    }

    /**
     * Retrieve all IDs for items in current users Library.
     *
     * @return Object   JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $items = Auth::user()
            ->library()
            ->orderBy('item_type', 'asc')
            ->get()
            ->toArray();

        $contents = [];
        foreach ($items as $item) {
            $contents[ $item['item_type'] ][] = $item['item_id'];
        }

        return response()->json([
            'event'    => $contents['App\Event'] ?? [],
            'schedule' => $contents['App\Schedule'] ?? [],
            'venue'    => $contents['App\Venue'] ?? [],
        ]);
    }

    /**
     * Converts the "item type", i.e, event, venue, schedule to the
     * fully qualified class name, i.e., App\Event.
     *
     * @todo  This can/should be handled via polymorphism, but this works
     * for now.
     * @param  string $type The item type.
     * @return string
     */
    public function typeToFqc($type = null): string
    {
        switch ($type) {
            case 'event':
                $modelFqc = 'App\Event';
                break;
            case 'venue';
                $modelFqc = 'App\Venue';
                break;
            case 'schedule';
                $modelFqc = 'App\Schedule';
                break;
            default:
                $modelFqc = '';
                break;
        }
        return $modelFqc;
    }

    // @todo needs test
    public function getItemsByType(Request $request)
    {
        $model = $this->typeToFqc($request->item_type);

        // @todo Remove this and all switches
        switch ($request->item_type) {
            case 'event':
                return $model::with('venue.city.states')->whereIn('id', $request->item_ids)->paginate();
                break;
            case 'venue':
                return $model::whereIn('id', $request->item_ids)->paginate();
                break;
            case 'schedule':
                dd('special');
                break;
            default:
                break;
        }


    }
}
