<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DeleteUserController extends Controller
{
    //
    public function __invoke(Request $request){

        $user = User::find($request->id);
        if($user->delete()){

            return response()->json(['success' => 'User Successfully Delete']);
        }else{

            return response()->json(['error' => 'Failed to delete user']);
        }
    }
}
