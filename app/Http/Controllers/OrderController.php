<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Order as OrderR;

class OrderController extends Controller
{
    public function all()
    {
        $orders = Order::select('id','name','user_id','menu_id')->paginate(25);

        foreach ($orders as $key=>$order) {
        $user = 
            User::where('id','=',$order->user_id)
            ->join('menu', 'menu.id', '=' , 'order.menu_id')
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
        $order = $request->isMethod('put') ? Order::findOrFail($request->order_id) : new Order;
 
        $order->id = $request->input('order_id');
        $order->name = $request->input('name');
        $order->user_id = $request->input('user_id');
        $order->menu_id = $request->input('menu_id');
 
        if($order->save()):
            return new OrderR($order);
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
