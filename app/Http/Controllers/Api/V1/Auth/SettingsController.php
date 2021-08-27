<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    //
    public function getSettings(){
        $settings = DB::table('general_settings')->first();
        return response()->json($settings);
    }
    public function getSocialSettings()
    {
        $settings = DB::table('social_settings')->select('facebook', 'twitter', 'linkedIn', 'youtube', 'instagram', 'github', 'twitch',)->first();
        return response()->json($settings);
    }
    public function saveSettings(Request $request){

        if($request->social == true){
            DB::table('social_settings')->where('id', 1)->update([
                'facebook' => $request->settings['facebook'],
                'github' => $request->settings['github'],
                'twitter' => $request->settings['twitter'],
                'linkedIn' => $request->settings['linkedIn'],
                'twitch' => $request->settings['twitch'],
                'instagram' => $request->settings['instagram'],
                'youtube' => $request->settings['youtube'],
            ]);
         return response()->json(['success' => 'Settings were successfully updated']);   
        }else{
            $local = $request->settings['localVideo'];
            $homeVideo = $request->settings['homeVideo'];
            if (strstr($request->settings['homeVideo'], "youtube.com")) {
                if (!strstr($request->settings['homeVideo'], "/embed/")) {
                    $get_video = explode("/", $request->settings['homeVideo']);
                    $video = explode("=", end($get_video));
                    $video = "https://www.youtube.com/embed/" . end($video);
                    $homeVideo = $video;
                    $local = false;
                }
            }
            if(strstr($request->settings['homeVideo'], 'twitch.tv')){
                $local = false;
            }
            DB::table('general_settings')->where('id', 1)->update(
                [
                    'webTittle' => $request->settings['webTittle'],
                    'homeVideo' => $homeVideo,
                    'localVideo' => $local == false ? 0 : 1,
                    'adminEmail' => $request->settings['adminEmail'],
                    'allowRegister' => $request->settings['allowRegister'] == false ? 0 : 1,
                    'pinnedOrder' => $request->settings['pinnedOrder'],
                    'defaultRole' => $request->settings['defaultRole'],
                    'bgLogin' => $request->settings['bgLogin'],
                    'bgRegister' => $request->settings['bgRegister'],
                    'maxPostsToDisplay' => intval($request->settings['maxPostsToDisplay'])
                ],
            );
            return response()->json(['success' => 'Settings were successfully updated']);

        }
        
    }

    public function test(Request $request){

      

        return response()->json($request);
    }

  
}
