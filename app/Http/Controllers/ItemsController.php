<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $items = Auth::user()->items()->get();


        $data = DataController::$refresherView;

        return view('home', compact('items', 'data'));
    }



    public function store(ItemValidate $request)
    {

        $item = Auth::User()->items()->create([
            'item_name' => $request->item_name,
            'item_refresh' => $request->item_refresh,
        ]);

        return response()->json(['item' => $item]);
    }

    public function update(ItemValidate $request, $item_id)
    {
        $item = \App\Item::findOrFail($item_id);
        $item->update($request->validated());

        return response()->json(['item' => $item]);
    }

    public function delete($item_id)
    {
        $item = \App\Item::findOrFail($item_id);
        $item->delete();

        return response()->json(['item' => $item]);
    }
}
