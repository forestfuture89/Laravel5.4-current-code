<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the image from storage folder.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($filename)
    {
        $path = storage_path() . '/app/public/profile_pics/' . $filename;

        if (!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
