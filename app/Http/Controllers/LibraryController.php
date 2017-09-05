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
    public function toggle(Request $request): \Illuminate\Http\JsonResponse
    {
        $fqc = $this->typeToFqc($request->item_type);
        $foundItemId = Library::where([
            'item_id'   => $request->item_id,
            'item_type' => $fqc,
        ])->pluck('id')->first();

        $detached = [];
        $attached = [];

        if ($foundItemId) {
            Library::destroy($foundItemId);
            $detached = $foundItemId;
        } else {
            $library            = new Library;
            $library->item_id   = $request->item_id;
            $library->item_type = $fqc;
            $library->user_id   = Auth::id();
            $library->save();
            $attached = $request->item_id;
        }

        return response()->json([
            'attached' => [ $attached ],
            'detached' => [ $detached ],
        ]);
    }

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

        $collection = [];
        foreach ($contents as $item_type => $item_ids) {
            foreach ($item_ids as $item_id) {
                $found = $item_type::find($item_id);
                if ($found) {
                    $collection[ $item_type ][] = $found;
                }
            }
        }

        $cleaned = [];
        foreach ($collection as $key => $value) {
            $item_type = strtolower(str_replace('App\\', '', $key));
            $cleaned[ $item_type ] = $value;
        }

        return response()->json($cleaned);
    }

    public function typeToFqc($type = null)
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
                $modelFqc = null;
                break;
        }
        return $modelFqc;
    }
}
