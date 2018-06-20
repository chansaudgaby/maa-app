<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User as UserR;

class UserController extends Controller
{
    public function all()
    {
        $users = User::select('id', 'lname','fname','email','userstype_id')->paginate(25);
        return Response::json($users);
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);
        return Response::json($user);
    }
    
    public function store(Request $request)
    {
        $user = $request->isMethod('put') ? User::findOrFail($request->user_id) : new User;
 
        $user->id = $request->input('user_id');
        $user->lname = $request->input('lname');
        $user->fname = $request->input('fname');
        $user->email = $request->input('email');
        // $user->remember_token = $request->input('remember_token');
        $user->password = $request->input('password');
        $user->userstype_id = $request->input('userstype_id');
 
        if($user->save()):
            return new UserR($user);
        endif;
    }
 
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        if($user->delete()):
            return new UserR($user);
        endif;
        }
}
