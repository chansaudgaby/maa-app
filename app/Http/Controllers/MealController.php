<?php

namespace App\Http\Controllers;

use App\Meal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Meal as MealR;

class MealController extends Controller
{
   public function all() 
   {
       $meals = Meal::select('id', 'name','quantity','picture','user_id')->paginate(25);
       return Response::json($meals);
   }

   public function show($mealId)
   {
       $meal = Meal::findOrFail($mealId);
       return Response::json($meal);
   }
   
   public function store(Request $request)
   {
       $meal = $request->isMethod('put') ? Meal::findOrFail($request->meal_id) : new Meal;

       $meal->id = $request->input('meal_id');
       $meal->name = $request->input('name');
       $meal->quantity = $request->input('quantity');
       $meal->picture = $request->input('picture');
       $meal->user_id = $request->input('user_id');

       if($meal->save()):
           return new MealR($meal);
       endif;
   }

   public function destroy($mealId)
   {
       $meals = Meal::findOrFail($mealId);
       if($meals->delete()):
           return new MealR($meals);
       endif;
       }
}
