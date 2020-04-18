<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'ItemsController@index')->name('home');


Route::post('/item', 'ItemsController@store')->name('item.store');
Route::patch('/item/{item}', 'ItemsController@update')->name('item.update');
Route::delete('/item/{item}', 'ItemsController@delete')->name('item.delete');



Route::get('/list', 'ListsController@index')->name('list.index');
Route::post('/list', 'ListsController@store')->name('list.store');
Route::get('/showOccupied', 'ListsController@showOccupied')->name('list.showOccupied');


Route::get('/shopping', 'ShoppingController@index')->name('shopping.index');
Route::patch('/shopping', 'ShoppingController@update')->name('shopping.update');
