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
        $meals = Meal::select('id', 'name','picture')
                        ->where('meals.user_id', '=', $userId)
                        ->paginate(25);

        // foreach ($meals as $key=>$meal):
        //     $user = User::where('id','=',$meal->user_id)
        //     ->select('fname as TraiteurFName', 'lname as TraiteurLName')
        //     ->get();
        //     $meals[$key]->user = $user;
        // endforeach;

        return Response::json($meals);
   }
   
   public function mealsByTraiteur($traiteurId)
   {
        $userTypeId = Auth::user()->userstype_id;
        if($userTypeId == 3):
            $meals = Meal::select('id', 'name','picture','user_id')
                            ->where('meals.user_id', '=', $traiteurId)
                            ->paginate(25);

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

   public function show($mealId)
   {
    $userId = Auth::user()->id;
    $userTypeId = Auth::user()->userstype_id;
    if(($userTypeId == 1) || ($userTypeId == 3)):
        $meals = Meal::select('id', 'name','picture','user_id')
        ->where('meals.id', '=', $mealId)
        ->get()->first();
        // dd($meals);

            $user = User::where('id','=',$meals->user_id)
            ->select('fname as TraiteurFName', 'lname as TraiteurLName')
            ->get()->first();

        if(($meals->user_id == $userId) || ($userTypeId == 3)):
            return Response::json($meals);
        else:
            return Response::json(['error'=>'ce plat ne vous appartient pas']);
        endif;
    else:
        return Response::json(['error'=>'accès non autorisé']);
    endif;
   }
   
   public function store(Request $request)
   {
        $user = Auth::user();
        $input = $request->all();
        if($user->userstype_id == 1):
            if($request->isMethod('put')):
                $meal = Meal::findOrFail($input['meal_id']);
                $meal->name = $input['name'];

                if($meal->save()):
                    return new MealR($meal);
                endif;
            else:
                $input["user_id"] = $user->id;
                $meal = Meal::create($input);
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
    
    if(($userTypeId == 1) || ($userTypeId == 3)):
        if(($meals->user_id == $userId || ($userTypeId == 3)) ):
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
