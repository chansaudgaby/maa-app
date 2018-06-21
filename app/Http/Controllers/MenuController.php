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

class MenuController extends Controller
{
    public function all()
    {
        $userTypeId = Auth::user()->userstype_id;
        //====Si l'utilisateur est un traiteur alors
        if($userTypeId == 1):
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
    
    public function show($menuId) 
    {
        $userId = Auth::user()->id;
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
        return Response::json($menu);
    }
    
    public function store(Request $request)
    {
        $menu = $request->isMethod('put') ? Menu::findOrFail($request->menu_id) : new Menu;
 
        $menu->id = $request->input('menu_id');
        $menu->user_id = $request->input('user_id');
        $menu->meal_id = $request->input('meal_id');
 
        if($menu->save()):
            return new MenuR($menu);
        endif;
    }
 
    public function destroy($menuId)
    {
        $menu = Menu::findOrFail($menuId);
        if($menu->delete()):
            return new MealR($menu);
        endif;
        }
}
