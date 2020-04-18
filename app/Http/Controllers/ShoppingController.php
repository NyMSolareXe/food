<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingController extends Controller
{
    public function index()
    {
        // $items = Auth::user()->items()->get();
        $list = Auth::user()->list()->get();
        // $listItems = implode(',', Auth::user()->list()->orderBy('item_id', 'ASC')->get()->pluck('item_id')->toArray());

        $items_picked = implode(',', Auth::user()->list()->where('item_picked', 'N')->get()->pluck('id')->toArray());

        // dd($items_picked);


        return view('shopping.index', compact('list', 'items_picked'));
    }



    public function update(Request $request)
    {


        $list = \App\ListModel::where('user_id', Auth::user()->id)->whereIn('id', $request->list_array_data)->update(['item_picked' => 'N']);
        $list = \App\ListModel::where('user_id', Auth::user()->id)->whereNotIn('id', $request->list_array_data)->update(['item_picked' => 'Y']);

        return response()->json(['Status' => 'Good']);
    }
}
