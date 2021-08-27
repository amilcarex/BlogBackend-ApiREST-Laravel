<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
class UserAboutController extends Controller
{
    //

    public function index(Request $request){

        $users = User::select('id','name', 'description', 'image', 'hierarchy', 'skills')->where('show', '=', 1)->paginate(1);
        foreach($users as $user){
            $experience = DB::table('user_experience')->where('user_id', '=', $user->id)->get();
            
            $user->skills = explode(',', $user->skills);
            foreach($user->skills as $skill){
                $skill = trim($skill);
            }
            $user->experience = $experience;
        }

        $month = date('m');
        $year = date('Y');
        $views_total = DB::table('public_views')->where('page', 'about')->whereYear('updated_at', $year)->whereMonth('updated_at', $month)->first();
        if ($views_total) {
            DB::table('public_views')->where('id', '=', $views_total->id)->update([
                'views' => $views_total->views + 1,
                'updated_at' => new \DateTime(),
            ]);
        } else {
            DB::table('public_views')->insert([
                'id' => Uuid::uuid4(),
                'page' => 'about',
                'views' => 1,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),

            ]);
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
            'users' => $users
        ]);
    }
}
