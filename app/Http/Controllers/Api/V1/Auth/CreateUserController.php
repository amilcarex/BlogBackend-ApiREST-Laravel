<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
class CreateUserController extends Controller
{
    //

    public function __invoke(RegisterRequest $request )
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:80|unique:users',
            'description' => 'string|max:400|nullable',
            'password' =>'required|string|min:8|confirmed',
            'skills' =>  'string|max:400|nullable',
        ]);

        
            $image_name = $request->image;
            if($request->image == "null"){
                $image_name = env('APP_URL').'/storage/placeholder/default-avatar.png';
            }
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'description' => $request->description,
            'skills' => $request->skills,
            'hierarchy' => $request->hierarchy,
            'image' => $image_name,
            'password' => $request->password,
            
        ]);

        $user->roles()->attach($request->role);
        
        $credentials = $request->only(['email', 'password']);

        $loginRequest = new LoginRequest($credentials);

        $loginToken = (new LoginController)($loginRequest);

        return response()->json(['success' => 'User Successfully Created']);
    }
}
