<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param RegisterRequest $request
     * @return mixed
     */
    public function __invoke(RegisterRequest $request)
    {
        $defaultRole = DB::table('general_settings')->Select('defaultRole')->first();
        $image_name = env('APP_URL') . '/storage/placeholder/default-avatar.png';
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'image' => $image_name,
        ]);
        $user->roles()->attach($defaultRole);
        $credentials = $request->only(['email', 'password']);

        $loginRequest = new LoginRequest($credentials);

        return (new LoginController)($loginRequest);
    }
}
