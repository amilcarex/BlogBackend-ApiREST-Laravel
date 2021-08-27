<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class InfoUserController extends Controller
{
 
    public function __invoke(Request $request)
    {
        //

        $data = User::find($request->id);
        if($data == null){
            return response()->json(['redirect' => true, 'message' => 'This user does not exist']);
        }else{
            $data->role = $data->roles()->first();
        }
        
        return response()->json($data);
    }

}

