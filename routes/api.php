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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//===============UsersRoutes======================
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

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

// Intégrer les routes à l'intérieur du groupe

Route::group(['middleware' => 'auth:api'], function(){

    //===============UsersTypeRoutes======================
    // List all the userTypes
    Route::get('userTypes', 'UserTypeController@index');
    // List one userType
    Route::get('userType/{id}', 'UserTypeController@show');
    // create userType
    Route::post('userType', 'UserTypeController@store');
    // update userType
    Route::put('userType', 'UserTypeController@store');
    // delete userType
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


    //===============OrdersRoutes======================
    // List all the orders
    Route::get('/orders','OrderController@all');
    // list one order
    Route::get('/order/{orderId}','OrderController@show');
    // create order
    Route::post('/order/create', 'OrderController@store');
    // update order
    Route::put('/order', 'OrderController@store');
    // delete order
    Route::delete('/order/{orderId}', 'OrderController@destroy');


    //===============MenusRoutes======================
    // List all the menus
        
    Route::get('/menus','MenuController@all');
    // list one order
    Route::get('/menu/{menuId}','MenuController@show');
    // create menu
    Route::post('/menu/create', 'MenuController@store');
    // update menu
    Route::put('/menu', 'MenuController@store');
    // delete menu
    Route::delete('/menu/{menuId}', 'MenuController@destroy');
});
