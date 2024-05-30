<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PhotoResource;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get photos
        $photos = Photo::when(request()->search, function ($photos) {
            $photos = $photos->where('caption', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $photos->appends(['search' => request()->search]);

        //return with Api Resource
        return new PhotoResource(true, 'List Data Photos', $photos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'    => 'required|mimes:jpeg,jpg,png|max:2000',
            'caption'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/photos', $image->hashName());

        //create Photo
        $Photo = Photo::create([
            'image' => $image->hashName(),
            'caption' => $request->caption,
        ]);

        if ($Photo) {
            //return success with Api Resource
            return new PhotoResource(true, 'Data Photo Berhasil Disimpan!', $Photo);
        }

        //return failed with Api Resource
        return new PhotoResource(false, 'Data Photo Gagal Disimpan!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $Photo)
    {
        //remove image
        Storage::disk('local')->delete('public/photos/' . basename($Photo->image));

        if ($Photo->delete()) {
            //return success with Api Resource
            return new PhotoResource(true, 'Data Photo Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PhotoResource(false, 'Data Photo Gagal Dihapus!', null);
    }
}
