<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;


trait GetImage
{
    public static function boot($filename, $storage)
    {
        $file = Storage::disk($storage)->get($filename);
        return new Response($file, 200);
    }
}
