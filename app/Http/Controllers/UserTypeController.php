<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\UserType;
use App\Http\Resources\UserType as UserTypeResource;

use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userTypes = UserType::paginate(15);
        return UserTypeResource::collection($userTypes);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userType = $request->isMethod('put') ? UserType::findOrFail
        ($request->userType_id) : new UserType;

        $userType->id = $request->input('userType_id');
        $userType->name = $request->input('name');

        if($userType->save()){
            return new UserTypeResource($userType);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userType = UserType::findOrFail($id);
        return new UserTypeResource($userType);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userType = UserType::findOrFail($id);

        if($userType->delete()) {
            return new UserTypeResource($userType);
        }
        
    }
}
