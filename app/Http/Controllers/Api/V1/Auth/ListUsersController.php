<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;

class ListUsersController extends Controller
{
    //
    public function __invoke(Request $request)
    {
        //
        
        $order = $request->order ? : 'asc';
        $field = $request->field ? : 'created_at';
        $search = $request->search ? : '';
        $per_page = intval($request->perPage) ?  : 5 ;
        if($search != ''){
            $users = User::Select('id', 'name', 'email', 'created_at', 'admin')->where('name', 'like', '%'. $search.'%')->orWhere('email', 'like', '%'. $search.'%')->orderBy($field, $order)->paginate($per_page);
        }else{
            $users = User::Select('id', 'name', 'email', 'created_at','admin')->orderBy($field, $order)->paginate($per_page);
        }
        foreach($users as $user){
            $role = $user->roles()->first();
            $user->role = $role;
        }
        return response()->json([
            'pagination' => [
                'total' => $users->total(),
                'currentPage' => $users->currentPage(),
                'perPage' => $users->perPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'users' => $users]);
    }
}
