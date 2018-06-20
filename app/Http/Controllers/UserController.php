<?php

namespace App\Http\Controllers;

use App\User;
use App\Usertype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User as UserR;
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('Laravel')->accessToken; 
            return response()->json(['success' => $success], $this->successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'lname' => 'required', 
            'fname' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
            'userstype_id' => 'required', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('Laravel')->accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this->successStatus); 
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

    public function all()
    {
        $users = User::select('id', 'lname','fname','email','userstype_id')->paginate(25);

        

        foreach ($users as $key=>$user) {
            $userstype = Usertype::find($user->userstype_id);

            $users[$key]->userstype = $userstype->name;
        }

        return Response::json($users);

    }

    public function show($userId)
    {
        $users = User::
        join('user_types', 'user_types.id', '=', 'users.userstype_id')
        ->select('users.id', 'users.lname as LastName','users.fname as FirstName' , 'user_types.name as type_user')
        ->where('users.id','=',$userId)
        ->get();
 
        return Response::json($users);
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
