<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public static $refresherView = ['1 Week', '2 Weeks', '3 Weeks', '1 Month'];
    public static $refresherValidate = ['+1 Week', '+2 Weeks', '+3 Weeks', '+1 Month'];
}
