<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//===============MealsRoutes======================
// List all the meals
Route::get('/meals','MealController@all');
// list one meal
Route::get('/meal/{mealId}','MealController@show');
// create meal
Route::post('/meal/create', 'MealController@store');
// update meal
Route::put('/meal', 'MealController@store');