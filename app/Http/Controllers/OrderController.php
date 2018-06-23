<?php

namespace App\Http\Controllers;

use App\Order;
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
        // dd($user);

        $orders[$key]->user = $user;
    }

        return Response::json($orders);
       
    }

    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);
        return Response::json($order);
    }
   
    public function store(Request $request)
    {
        $userId = Auth::User()->id;

        $order = $request->isMethod('put') ? Order::findOrFail($request->order_id) : new Order;
 
        $order->id = $request->input('order_id');
        $order->name = $request->input('name');
        $order->user_id = $userId;
        $order->menu_id = $request->input('menu_id');
        $order->quantity = $request->input('quantity');
 
        if($order->save()):
            DB::table('menus')
            ->where('id', $order->menu_id)
            ->increment('orders', $order->quantity);
            return new OrderR($order);
        else:
            return Response::json(['error'=>'il n/y a plus de plats de libre']);
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
