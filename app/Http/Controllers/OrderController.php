<?php

namespace App\Http\Controllers;

use App\Order;
use App\Menu;
use App\Meal;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Order as OrderR;
use Carbon;

class OrderController extends Controller
{
    public function all()
    {
        $userTypeId = Auth::user()->userstype_id;
        if($userTypeId == 3):
            $orders = Order::select('id','name','user_id','menu_id', 'quantity')->paginate(25);

            foreach ($orders as $key=>$order) {
            $user = 
                User::where('id','=',$order->user_id)
                ->select('fname as FirstName', 'lname as LastName')->get();
            // dd($user);

            $orders[$key]->user = $user;
        }

        foreach ($orders as $key=>$order):
            $menu = Menu::select('meals.name','meal_id')
            ->join('meals', 'meals.id', 'menus.meal_id')
            ->where('menus.id','=',$order->menu_id)
            ->get();
            $orders[$key]->menu = $menu;
        endforeach;

        

            return Response::json($orders);
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
       
    }

    public function myOrders()
    {
        $userId = Auth::User()->id;

        $orders = Order::select('id','name','user_id','menu_id', 'quantity')
                        ->where('orders.user_id' , '=' , $userId)
                        ->paginate(25);

        foreach ($orders as $key=>$order):
            $user = User::where('id','=',$order->user_id)
                        ->select('fname as FirstName', 'lname as LastName')->get();
            $orders[$key]->user = $user;
        endforeach;
        
        foreach ($orders as $key=>$order):
            $menu = Menu::select('meals.name','meal_id')
            ->join('meals', 'meals.id', 'menus.meal_id')
            ->where('menus.id','=',$order->menu_id)
            ->get();
            
            $orders[$key]->menu = $menu;
        endforeach;
        
        return Response::json($orders);
    }

    public function traiteurMyOrders()
    {
        $user = Auth::User();
        
        if($user->userstype_id == 1):
            $meals = Meal::select('meals.id','meals.name','meals.picture','menus.orders')
                ->join('menus','menus.meal_id','meals.id')
                ->where([
                    ['menus.user_id','=',$user->id],
                    ['menus.orders','!=',0]
                ])
                ->get();

            return Response::json($meals);
        else:
            return Response::json(['error'=>'accès non autorisée']);
        endif;
    }

    public function traiteurMyOrdersFrom($dd, $mm, $yyyy)
    {
        $user = Auth::User();
        // date
        $fromDate = Carbon::createFromDate($yyyy, $mm, $dd);
        // dd($fromDate);
        $stringFromDate = Carbon::Parse($fromDate)->format('Y-m-d');
        // dd($stringFromDate);
        $fromDateAddDay = $fromDate->addDay(4);
        $stringFromDateAdd = Carbon::Parse($fromDateAddDay)->format('Y-m-d');
        // dd($stringFromDateAdd);

        $checkMonday = date('N',strtotime($stringFromDate));

        if($user->userstype_id == 1):
            $meals = Meal::select('meals.id','meals.name','meals.picture','menus.orders')
                ->join('menus','menus.meal_id','meals.id')
                ->where([
                    ['menus.user_id','=',$user->id],
                    ['menus.orders','!=',0]
                ])
                ->whereBetween('menus.date', array($stringFromDate, $stringFromDateAdd))
                ->get();

            return Response::json($meals);
        else:
            return Response::json(['error'=>'accès non autorisée']);
        endif;
    }

    public function show($orderId)
    {
        $orders = Order::select('id', 'name', 'user_id', 'menu_id', 'quantity')
            ->where('id', $orderId)
            ->get();

            foreach ($orders as $key=>$order) {
                $user = User::where('id','=',$order->user_id)
                            ->select('fname as FirstName', 'lname as LastName')->get();
                // dd($user);
        
                $orders[$key]->user = $user;
            }
        return Response::json($order);
        
    }

    public function storage(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $userId = Auth::user()->id;
        $userTypeId = Auth::user()->userstype_id;
        // dd($userTypeId);
            if($userTypeId == 2):
                if($request->isMethod('put')):
                    $order = Order::where([
                        [ 'id', '=', $input['order_id']],
                        [ 'menu_id', '=', $input['menu_id']],
                        [ 'user_id', '=', $userId]
                        ])->get()->first();
                        // dd($order);
            
                    if(!empty($order)):
                        $diff = $request->input('quantity')-$order->quantity;
                        $order->menu_id = $input['menu_id'];
                        $order->quantity = $input['quantity'];
                        // dd($diff);
                        if($order->save()):
                            $menu = DB::table('menus')
                                ->where('id', $input['menu_id']);

                            if($diff >= 0):
                                $menu->increment('orders', $diff);
                            else:
                                $menu->decrement('orders', abs($diff));
                            endif;

                            return new OrderR($order);
                        endif;
                    else:
                        return Response::json(['error'=>'error ici']);
                    endif;
                else:
                    $input['user_id'] = $userId;
                        $menu = Order::create($input);
                            DB::table('menus')
                            ->where('id', $input['menu_id'])
                            ->increment('orders', $input['quantity']);
                        return new OrderR($menu);
                endif;
            else:                
                return Response::json(['error'=>'Vous ne pouvez pas creer de menu']);
            endif;
    }

    public function destroy($orderId)
    {
        $order = Order::findOrFail($orderId);
        if($order->delete()):
            return new OrderR($order);
        endif;
        }


}
