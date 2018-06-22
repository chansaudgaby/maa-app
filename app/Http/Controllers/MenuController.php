<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Menu;
use App\User;
use App\Meal;
use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Menu as MenuR;
use Carbon;

class MenuController extends Controller
{
    public function myMenus() 
    {
        $userTypeId = Auth::user()->userstype_id;
        $userId = Auth::user()->id;
        if(($userTypeId == 1) || ($userTypeId == 3)):
            $menus = Menu::select('id','user_id','meal_id','date')
            ->where('user_id', $userId)
            ->paginate(25);
            foreach ($menus as $key=>$menu):
                $user = User::where('id','=',$menu->user_id)->select('fname as FirstName', 'lname as LastName')->get();
                $menus[$key]->user = $user; 
            endforeach;
            foreach ($menus as $key=>$menu):
                $meal = Meal::where('id','=',$menu->meal_id)->select('name','user_id','picture')->get();
                    $menus[$key]->meal = $meal;
            endforeach;
            return Response::json($menus);
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
    }

    public function all()
    {
        $userTypeId = Auth::user()->userstype_id;
        //====Si l'utilisateur est un traiteur alors
        if(($userTypeId == 2) || ($userTypeId == 3)):
            $menus = Menu::select('id','user_id','meal_id','date')->paginate(25);
            foreach ($menus as $key=>$menu):
                $user = User::where('id','=',$menu->user_id)->select('fname as FirstName', 'lname as LastName')->get();
                $menus[$key]->user = $user; 
            endforeach;
            foreach ($menus as $key=>$menu):
                $meal = Meal::where('id','=',$menu->meal_id)->select('name','user_id','picture')->get();
                    $menus[$key]->meal = $meal;
            endforeach;
        return Response::json($menus);
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
    }

    public function menuByTraiteur($idTraiteur)
    {
        $userTypeId = Auth::user()->userstype_id;
        //====Si l'utilisateur est un traiteur alors
        if(($userTypeId == 2) || ($userTypeId == 3)):
            $menus = Menu::select('id','user_id','meal_id','date')
            ->where('user_id', $idTraiteur)
            ->paginate(25);

            foreach ($menus as $key=>$menu):
                $user = User::where('id','=',$menu->user_id)->select('fname as FirstName', 'lname as LastName')->get();
                $menus[$key]->user = $user; 
            endforeach;
            foreach ($menus as $key=>$menu):
                $meal = Meal::where('id','=',$menu->meal_id)->select('name','user_id','picture')->get();
                    $menus[$key]->meal = $meal;
            endforeach;
            return Response::json($menus);
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;

    }

    public function menuOfToday()
    {
        $currentDate = Carbon\Carbon::now();
        $currentDateFormat = $currentDate->format("Y-m-d");
        // dd($mytimeFormatt);
        $userTypeId = Auth::user()->userstype_id;
        //====Si l'utilisateur est un traiteur alors
        if(($userTypeId == 2) || ($userTypeId == 3)):
            $menus = Menu::select('id','user_id','meal_id','date')
            ->where('date', '=', $currentDateFormat)
            ->paginate(25);
            foreach ($menus as $key=>$menu):
                $user = User::where('id','=',$menu->user_id)->select('fname as FirstName', 'lname as LastName')->get();
                $menus[$key]->user = $user; 
            endforeach;
            foreach ($menus as $key=>$menu):
                $meal = Meal::where('id','=',$menu->meal_id)->select('name','user_id','picture')->get();
                    $menus[$key]->meal = $meal;
            endforeach;
        return Response::json($menus);
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
    }
    
    public function menuOfTheWeek($dd, $mm, $yyyy) 
    {
        // date
        $fromDate = Carbon::createFromDate($yyyy, $mm, $dd);
        // dd($fromDate);
        $stringFromDate = Carbon::Parse($fromDate)->format('Y-m-d');
        // dd($stringFromDate);
        $fromDateAddDay = $fromDate->addDay(4);
        $stringFromDateAdd = Carbon::Parse($fromDateAddDay)->format('Y-m-d');
        // dd($stringFromDateAdd);

        $checkMonday = date('N',strtotime($stringFromDate));
        // dd($checkMonday);
        $userTypeId = Auth::user()->userstype_id;
        //====Si l'utilisateur est un traiteur alors
        if($checkMonday == 1):
            if(($userTypeId == 2) || ($userTypeId == 3)):
                $menus = Menu::select('id','user_id','meal_id','date')
                // ->where('date', '<=', $firstMondayFormat)
                // ->where('date', '>=', $firstMondayFormat, '+', 5)
            ->whereBetween('date', array($stringFromDate, $stringFromDateAdd))
                
                ->paginate(25);
                // dd($menus);
                foreach ($menus as $key=>$menu):
                    $user = User::where('id','=',$menu->user_id)->select('fname as FirstName', 'lname as LastName')->get();
                    $menus[$key]->user = $user; 
                endforeach;
                foreach ($menus as $key=>$menu):
                    $meal = Meal::where('id','=',$menu->meal_id)->select('name','user_id','picture')->get();
                        $menus[$key]->meal = $meal;
                endforeach;
            return Response::json($menus);
            else:
                return Response::json(['error'=>'accès non autorisé']);
            endif;
            else:
                return Response::json(['error'=>'La date du '.$stringFromDate. ' ne correspond pas à un lundi']);
        endif;
        
    }

    public function show($menuId) 
    {
        $userTypeId = Auth::user()->userstype_id;
        //====Si l'utilisateur est un traiteur alors
        $userId = Auth::user()->id;
        if(($userTypeId == 2) || ($userTypeId == 3)):
            $menus = Menu::select('id','user_id','meal_id','date')
            ->where('menus.id', '=', $menuId)
            ->paginate(25);
            foreach ($menus as $key=>$menu):
                $user = User::where('id','=',$menu->user_id)->select('fname as FirstName', 'lname as LastName')->get();
                $menus[$key]->user = $user; 
            endforeach;
            foreach ($menus as $key=>$menu):
                $meal = Meal::where('id','=',$menu->meal_id)->select('name','user_id','picture')->get();
                    $menus[$key]->meal = $meal;
            endforeach;
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
            return Response::json($menus);
    }
    
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $userTypeId = Auth::user()->userstype_id;
            if($userTypeId == 1):
                $menu = $request->isMethod('put') ? Menu::findOrFail($request->menu_id) : new Menu;
        
                if($userId == $menu->user_id):
                    $menu->id = $request->input('menu_id');
                    $menu->user_id = $userId;
                    $menu->meal_id = $request->input('meal_id');
                    $menu->date = $request->input('date');
        
                    if($menu->save()):
                        return new MenuR($menu);
                    endif;
                else:
                    return Response::json(['error'=>'Vous ne pouvez pas modifier de menu']);
                endif;
            else:                
                return Response::json(['error'=>'Vous ne pouvez pas creer de menu']);
            endif;
    }
 
    public function destroy($menuId)
    {
        $userId = Auth::user()->id;
        $menu = Menu::findOrFail($menuId);
        if($userId == $menu->user_id):
            if($menu->delete()):
                return new MenuR($menu);
            endif;
        else:
            return Response::json(['error'=>'Ce menu ne vous appartient pas']);
        endif;
        }
}
