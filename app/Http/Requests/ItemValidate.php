<?php

namespace App\Http\Requests;

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ItemValidate extends FormRequest
{

    public function authorize()
    {
        return true;
    }





    public function rules()
    {

        $refresherValidate = DataController::$refresherValidate;


        return [
            'item_name' => [
                'required',
                'min:2',
                function ($attribute, $value, $fail) {
                    $item = \App\item::where('item_name', request('item_name'))->where('user_id', Auth::user()->id)->get();
                    if (count($item) > 0) {
                        $fail($value . ' already exist in the database');
                    }
                }
            ],
            'item_refresh' => 'required|in:' . implode(',', $refresherValidate),

        ];
    }
}
