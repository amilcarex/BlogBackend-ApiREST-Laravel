<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SidebarImage;
use App\Traits\GetImage;
class SidebarImageController extends Controller
{
    //

    public function getSidebars()
    {

        $images = SidebarImage::whereNotNull('image')->get();
        $data = [];

        foreach($images as $value){
            $image['id'] = $value->id;
            $image['image'] = $value->image;
            $image['active'] = $value->active === 1 ? true : false;
            
            array_push($data, $image);
        }
        return response()->json($data);
    }

    public function getSidebarsToEdit(){
        $images = SidebarImage::all();
        $data = [];

        foreach ($images as $value) {
            $image['id'] = $value->id;
            $image['image'] = $value->image;
            $image['active'] = $value->active === 1 ? true : false;

            array_push($data, $image);
        }
        return response()->json($data);
    }

    public function saveSidebars(Request $request){


        $sidebars = $request->sidebars;
        foreach($sidebars as $sidebar){
            $sidebar_info = SidebarImage::Select('id', 'active', 'image')->where('id', '=', $sidebar['id'])->first();
            $sidebar_info->image = $sidebar['image'];
            $sidebar_info->active = $sidebar['active'];
            $sidebar_info->update();
        }

        return response()->json(['success' => 'The sidebar images have been updated successfully']);

    }

    public function activeSidebar(){
        $image = SidebarImage::where('active', '=', 1)->first();
        if ($image !== NULL) {
            return response()->json($image);
        } else {
            $image = SidebarImage::first();
            return response()->json($image);
        }
    }

    public function activateImage(Request $request){
        $images = SidebarImage::whereNotNull('image')->get();
        $data = [];
        foreach ($images as $value) {
            if ($request->id == $value->id) {
                $value->update(['active' => 1]);
            } else {
                $value->update(['active' => 0]);
            }
            $image['id'] = $value->id;
            $image['image'] = $value->image;
            $image['active'] = $value->active === 1 ? true : false;
            array_push($data, $image);
        }
        return response()->json($data);
    }

    public function getImageSidebar(Request $request){
        $storage = 'sidebar';
        $image = GetImage::boot($request->image, $storage);
        return $image;
    }
    
}
