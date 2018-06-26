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
        if($userTypeId == 1):
            $meals = Meal::select('id', 'name','picture','user_id')->paginate(25);
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
    $userTypeId = Auth::user()->userstype_id;
        if($userTypeId == 1):
            $userId = Auth::user()->id;
            $meal = $request->isMethod('put') ? Meal::findOrFail($request->meal_id) : new Meal;

            $meal->id = $request->input('meal_id');
            $meal->name = $request->input('name');
            $meal->picture = $request->input('picture');
            $meal->user_id = $userId;

            if($meal->save()):
                return new MealR($meal);
            endif;
        else: 
            return Response::json(['error'=>'accès non autorisé']);
        endif;
   }

   public function destroy($mealId)
   {
    $userId = Auth::user()->id;
    $userTypeId = Auth::user()->userstype_id;
       $meals = Meal::select('id','user_id','name','picture')->where('id', $mealId)->get()->first();
    //    dd($meals);
    
    if($userTypeId == 1):
        if($meals->user_id = $userId):
            if($meals->delete()):
                return new MealR($meals);
            endif;
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
    endif;

    }

    public function uploadPicture(Request $request)
    {
        
        $userTypeId = Auth::user()->userstype_id;
        // dd($request->file('picture'));
        
        if($userTypeId == 1):
            if($request->input('meal_id')):
                if($request->hasfile('picture')):
                    
                    $file = $request->file('picture');
                    $extension = $file->getClientOriginalExtension(); // getting image extension
                    $filename =substr( md5( Auth::user()->id . '-' . time() ), 0, 15).'.'.$extension;

                    $file->move('images', $filename);

                    $meal = Meal::findOrFail($request->input('meal_id'));
                    $meal->picture = $filename;
                    $meal->save();
                    
                return new MealR($meal);

                endif;
            endif;
        endif;

                


    }
}
