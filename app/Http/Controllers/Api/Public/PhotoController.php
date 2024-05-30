<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Photo;
use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoResource;

class PhotoController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $photos = Photo::latest()->paginate(9);

        //return with Api Resource
        return new PhotoResource(true, 'List Data Photos', $photos);
    }
}
