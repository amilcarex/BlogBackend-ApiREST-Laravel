<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;

class UserProfileUpdateController extends Controller
{
    //

    public function __invoke(Request $request, User $user, User $authUser)
    {

        $this->validate($request, [
            'name' => 'required|string|max:80',
            'email' => 'required|string|email|max:80|unique:users,email,'.$user->id,
            'hierarchy' => 'string|nullable|max:40',
            'description' => 'string|max:400|nullable',
            'skills' =>  'string|max:400|nullable',

        ]);

        $user_role = $authUser->roles()->first();
        if($authUser->id != $user->id && $user_role->id != 1){
            return response()->json(['errors' => 'Only the owner of the profile or the admin can make modifications']);
        }

        if($request->show == true){
            $user->show = 1;
        }else{
            $user->show = 0;
        }
       

        $user->name = $request->name;
        $user->email = $request->email;
        $user->hierarchy = $request->hierarchy;
        $user->description = $request->description;
        $user->skills = $request->skills;
        
        
        $user->update();
        if($user->admin == 1 && $request->role != 1){
            return response()->json(['errors' => 'The main account must be an administrator']);
            
        }else{
            if ($user_role->id != 1) {
                return response()->json(['errors' => 'The role can only be modified by an administrator']);
            }else{
                $user->roles()->sync($request->role);
            }

            
        } 
        return response()->json([
            'success' => 'Profile Updated Succesfully',
    ]);
    
    }
}
