<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class UserExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getExperiences(Request $request, User $user){
    
        $experiences = DB::table('user_experience')->where('user_id', $user->id)->get();
        
        return response()->json($experiences);
    }
    
    public function modifyExperiences(Request $request){
        $experience = $request->experience;
        
        if($experience['occupation'] == null || $experience['occupation'] == ''){
            return response()->json(['errors' => 'You must indicate the occupation or job title' ]);
        } 
        if($experience['from'] == null ) {
            return response()->json(['errors' => 'The date from cannot be empty']);
        } 

        if($experience['company'] == null){
            $experience['company'] = 'Independent';
        }
        if($experience['logo'] == null){
            $experience['logo'] = env('APP_URL') . '/storage/placeholder/no-logo.png';
        }
        if($experience['id'] == null){
            DB::table('user_experience')->insert([
                'company' => $experience['company'],
                'occupation' => $experience['occupation'],
                'from' => $experience['from'],
                'to' => $experience['to'],
                'logo' => $experience['logo'],
                'user_id' => $experience['user_id'] 
                ]);
            return response()->json(['success' => 'Successfully added']);
        }

        if($experience['id'] != null){
            DB::table('user_experience')->where('id', $experience['id'])->update(['company' => $experience['company'],
                'occupation' => $experience['occupation'],
                'from' => $experience['from'],
                'to' => $experience['to'],
                'logo' => $experience['logo'],
            ]);
            return response()->json(['success' => 'Succesfully edited']);
        }
    }
    
    public function deleteExperience($id){

        $experience = DB::table('user_experience')->where('id', $id);
        if ($experience->delete()) {

            return response()->json(['success' => 'Successfully Delete.']);
        } else {
            return response()->json(['errors' => 'Failed to delete.']);
        }
    }

}
