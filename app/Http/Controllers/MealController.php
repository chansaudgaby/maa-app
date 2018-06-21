<?php

namespace App\Http\Controllers;

use App\Meal;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Meal as MealR;

class MealController extends Controller
{
   public function all() 
   {
        $userTypeId = Auth::user()->userstype_id;
        //====Si l'utilisateur est un traiteur alors
        if($userTypeId == 2):
            $meals = Meal::select('id', 'name','quantity','picture','user_id')->paginate(25);
            foreach ($meals as $key=>$meal):
                $user = User::where('id','=',$meal->user_id)
                ->select('fname as TraiteurFName', 'lname as TraiteurLName')
                ->get();
                $meals[$key]->user = $user;
            endforeach;
        return Response::json($meals);
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
   }

   public function myMeals()
   {
        $userId = Auth::user()->id;
        $meals = Meal::select('id', 'name','quantity','picture','user_id')
                        ->where('meals.user_id', '=', $userId)
                        ->paginate(25);

        foreach ($meals as $key=>$meal):
            $user = User::where('id','=',$meal->user_id)
            ->select('fname as TraiteurFName', 'lname as TraiteurLName')
            ->get();
            $meals[$key]->user = $user;
        endforeach;

        return Response::json($meals);
   }
   
   public function mealsByTraiteur($traiteurId)
   {
        $meals = Meal::select('id', 'name','quantity','picture','user_id')
                        ->where('meals.user_id', '=', $traiteurId)
                        ->paginate(25);

        foreach ($meals as $key=>$meal):
            $user = User::where('id','=',$meal->user_id)
            ->select('fname as TraiteurFName', 'lname as TraiteurLName')
            ->get();
            $meals[$key]->user = $user;
        endforeach;

        return Response::json($meals);
   }

   public function show($mealId)
   {
       $meals = Meal::
       join('users', 'users.id', '=', 'meals.user_id')
       ->select('meals.*', 'users.fname as FirstName','users.lname as LastName')
       ->where('meals.id','=',$mealId)
       ->get();

       return Response::json($meals);
   }
   
   public function store(Request $request)
   {
        $userId = Auth::user()->id;
        $meal = $request->isMethod('put') ? Meal::findOrFail($request->meal_id) : new Meal;

       $meal->id = $request->input('meal_id');
       $meal->name = $request->input('name');
       $meal->quantity = $request->input('quantity');
       $meal->picture = $request->input('picture');
       $meal->user_id = $userId;

       if($meal->save()):
           return new MealR($meal);
       endif;
   }

   public function destroy($mealId)
    {
        $meals = Meal::findOrFail($mealId);
    
        $userId = Auth::User()->id;
        $userTypeId = Auth::User()->userstype_id;
        if(($userTypeId == 3) || ($userTypeId == 1)):
            if($meals->delete()):
                return new MealR($meals);
            endif;
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
    }

}
