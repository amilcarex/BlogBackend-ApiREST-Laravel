<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageProfileUpdateController extends Controller
{
    //

    public function __invoke(Request $request)
    {


        $image_name = $request->image;
        $user =  User::find($request->id);
        if ($request->image == NULL) {
            $image_name = $user->image;
        }
        $user->image = $image_name;
        if ($user->update()) {
            return response()->json(['success' => 'Image updated succesfully', 'image' => $image_name]);
        }
    }
}
