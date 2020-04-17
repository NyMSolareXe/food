<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListsController extends Controller
{

    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        $items = Auth::user()->items()->get();
        $list = Auth::user()->list()->get();
        $listItems = implode(',', Auth::user()->list()->orderBy('item_id', 'ASC')->get()->pluck('item_id')->toArray());


        return view('list.index', compact('list', 'items', 'listItems'));
    }

    public function store(Request $request)
    {

        // Existing Data
        $currentList = Auth::user()->list->pluck('item_id')->toArray();
        // New Data
        $array = explode(',', $request->item_array_data);

        foreach ($currentList as $user_item_id) {
            if (in_array($user_item_id, $array) === true) {
                // $keepItem[] = $user_item_id;
            } elseif (in_array($user_item_id, $array) === false) {
                $deleteItem[] = $user_item_id;
            }
        }

        if ($array[0] !== '') {
            foreach ($array as $user_item_id) {
                if (in_array($user_item_id, $currentList) === true) {
                    // $keepItem[] = $user_item_id;
                } elseif (in_array($user_item_id, $currentList) === false) {
                    // $newItem[] = $user_item_id;
                    $dollar2 = Auth::user()->list()->create([
                        'item_id' => $user_item_id
                    ]);
                }
            }
        }



        if (isset($deleteItem)) {
            \App\ListModel::whereIn('item_id', $deleteItem)->where('user_id', Auth::user()->id)->delete();
        }




        $list = Auth::user()->list()->get()->pluck('item_id');
        $items = \App\Item::whereIn('id', $list)->get();


        return response()->json(['items' => $items]);
    }

    public function showOccupied()
    {
        $list = Auth::user()->list()->get()->pluck('item_id');
        $items = \App\Item::whereIn('id', $list)->get();


        return response()->json(['items' => $items]);
    }
}
