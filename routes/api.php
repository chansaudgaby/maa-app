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

//===============UsersTypeRoutes======================
Route::get('userTypes', 'UserTypeController@index');
Route::get('userType/{id}', 'UserTypeController@show');
Route::post('userType', 'UserTypeController@store');
Route::put('userType', 'UserTypeController@store');
Route::delete('userType/{id}', 'UserTypeController@destroy');

//===============MealsRoutes======================
// List all the meals
Route::get('/meals','MealController@all');
// list one meal
Route::get('/meal/{mealId}','MealController@show');
// create meal
Route::post('/meal/create', 'MealController@store');
// update meal
Route::put('/meal', 'MealController@store');
// delete meal
Route::delete('/meal/{mealId}', 'MealController@destroy');

//===============MealsRoutes======================
// List all the users
Route::get('/users','UserController@all');
// list one user
Route::get('/user/{userId}','UserController@show');
// create user
Route::post('/user/create', 'UserController@store');
// update user
Route::put('/user', 'UserController@store');
// delete user
Route::delete('/user/{userId}', 'UserController@destroy');
