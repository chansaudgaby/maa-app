<?php

namespace App\Http\Controllers;

use App\Menu;
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
        $menus = Menu::select('id','user_id','meal_id')->paginate(25);
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
