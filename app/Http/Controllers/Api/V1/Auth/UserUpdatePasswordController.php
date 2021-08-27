<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserUpdatePasswordController extends Controller
{
    //

    public function __invoke(Request $request, User $user, User $authUser)
    {
        
        $this->validate($request, [
            'password' => 'required|string|min:8|confirmed|regex:regex:/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/'
        ]);

        if ($authUser->id != $user->id && $authUser->admin != 1) {
            return response()->json(['errors' => 'Only the owner of the profile or the admin can make modifications']);
        }
        $user->password = $request->password;
        $user->update();
        return response()->json([
            'success' => 'Password Updated Succesfully',
        ]);
    }
}
