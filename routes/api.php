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
    // List one 'traiteur" meals, only for "traiteur"
    Route::get('/traiteur/myMeals','MealController@myMeals');
    // list the meals of one "traiteur"
    Route::get('/traiteur/{traiteurId}','MealController@mealsByTraiteur');
    // list one meal
    Route::get('/meal/{mealId}','MealController@show');
    // create meal
    Route::post('/meal/uploadPicture','MealController@uploadPicture');
    // create meal
    Route::post('/meal/create', 'MealController@store');
    // update meal
    Route::put('/meal', 'MealController@store');
    // delete meal
    Route::delete('/meal/{mealId}', 'MealController@destroy');

    //===============OrdersRoutes======================
    // List all the orders
    Route::get('/orders','OrderController@all');
    // Liste mes commandes
    Route::get('/myOrders','OrderController@myOrders');
    // Liste mes commandes
    Route::get('/myOrders/traiteur','OrderController@traiteurMyOrders');
    // list one order
    Route::get('/myOrdersFrom/traiteur/{dd}-{mm}-{yyyy}','OrderController@traiteurMyOrdersFrom');
    // list one order
    Route::get('/order/{orderId}','OrderController@show');
    // create order
    Route::post('/order/create', 'OrderController@storage');
    // update order
    Route::put('/order', 'OrderController@storage');
    // delete order
    Route::delete('/order/{orderId}', 'OrderController@destroy');

    //===============MenusRoutes======================
    // List all the menus
    Route::get('/menus','MenuController@all');
    // list one order
    Route::get('/menus/traiteur','MenuController@myMenus');
    // list one order
    Route::get('/menus/traiteur/{idTraiteur}','MenuController@menuByTraiteur');
    // list one order
    Route::get('/menuFrom/{dd}-{mm}-{yyyy}','MenuController@menuOfTheWeek');
    // list one order
    Route::get('/menuOf/{dd}-{mm}-{yyyy}','MenuController@mealPerDay');
    // list one order
    Route::get('/menu/{menuId}','MenuController@show');
    // create menu
    Route::post('/menu/create', 'MenuController@store');
    // update menu
    Route::put('/menu', 'MenuController@store');
    // delete menu
    Route::delete('/menu/{menuId}', 'MenuController@destroy');

    // list one order
    // Route::get('/menuOfToday','MenuController@menuOfToday');
//test
});
