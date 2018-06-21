<?php

namespace App\Http\Controllers;

use App\Menu;
use App\User;
<<<<<<< HEAD
=======
use App\Meal;
>>>>>>> origin/entity_menu
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Menu as MenuR;

class MenuController extends Controller
{
    public function all()
    {
<<<<<<< HEAD
        
        $menus = Menu::select('id','user_id','meal_id')->paginate(25);

        foreach ($menus as $key=>$menu) {
            $user = Menu::join('users', 'users.id' , '=' , 'menus.user_id')
                        ->select('users.fname as FirstName', 'users.lname as LastName')
                        ->where('menus.id', '=' , $menu->id)
                        ->get();
            $menus[$key]->user = $user;
=======
        $menus = Menu::select('id','user_id','meal_id','date')->paginate(25);
        
        foreach ($menus as $key=>$menu) {
            $user = User::where('id','=',$menu->user_id)->select('fname as FirstName', 'lname as LastName')->get();
            //dd($user);
            $menus[$key]->user = $user;   
        }
        foreach ($menus as $key=>$menu) {
            $meal = Meal::where('id','=',$menu->meal_id)->select('name','user_id','picture')->get();
            $menus[$key]->meal = $meal;
>>>>>>> origin/entity_menu
        }
        return Response::json($menus);
    }

    public function show($menuId) 
    {
        $menu = Menu::findOrFail($menuId);
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
