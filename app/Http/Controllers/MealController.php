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
   
}
