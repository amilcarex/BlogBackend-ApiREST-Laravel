<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GetImage;

class GetPlaceholderController extends Controller
{
    //
    
  //

    public function __invoke(Request $request)
    {
   
        $storage = 'placeholder';
        $image = GetImage::boot($request->image, $storage);
        return $image;
    
    }
}
