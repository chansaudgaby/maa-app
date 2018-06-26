<?php

namespace App\Http\Controllers;

use App\Order;
use App\Menu;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Order as OrderR;

class OrderController extends Controller
{
    public function all()
    {
        $userTypeId = Auth::user()->userstype_id;
        if(($userTypeId == 1) || ($userTypeId == 3)):
            $orders = Order::select('id','name','user_id','menu_id', 'quantity')->paginate(25);

            foreach ($orders as $key=>$order) {
            $user = 
                User::where('id','=',$order->user_id)
                ->select('fname as FirstName', 'lname as LastName')->get();
            // dd($user);

            $orders[$key]->user = $user;
        }

            return Response::json($orders);
        else:
            return Response::json(['error'=>'accès non autorisé']);
        endif;
       
    }

    public function myOrders()
    {
        $userId = Auth::User()->id;
        // dd($userId);

        $orders = Order::select('id','name','user_id','menu_id', 'quantity')
                        ->where('orders.user_id' , '=' , $userId)
                        ->paginate(25);

        foreach ($orders as $key=>$order) {
        $user = User::where('id','=',$order->user_id)
                    ->select('fname as FirstName', 'lname as LastName')->get();
                    
        $orders[$key]->user = $user;
    }

        return Response::json($orders);
       
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
        dd($input);
        $userId = Auth::user()->id;
        $userTypeId = Auth::user()->userstype_id;
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
