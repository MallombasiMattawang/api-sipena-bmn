<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Aparatur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AparaturResource;
use Illuminate\Support\Facades\Validator;

class AparaturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get aparaturs
        $aparaturs = Aparatur::when(request()->search, function ($aparaturs) {
            $aparaturs = $aparaturs->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $aparaturs->appends(['search' => request()->search]);

        //return with Api Resource
        return new AparaturResource(true, 'List Data Aparaturs', $aparaturs);
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
            'name'     => 'required',
            'role'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/aparaturs', $image->hashName());

        //create aparatur
        $aparatur = Aparatur::create([
            'image'     => $image->hashName(),
            'name'      => $request->name,
            'role'      => $request->role,
        ]);

        if ($aparatur) {
            //return success with Api Resource
            return new AparaturResource(true, 'Data Aparatur Berhasil Disimpan!', $aparatur);
        }

        //return failed with Api Resource
        return new AparaturResource(false, 'Data Aparatur Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aparatur = Aparatur::whereId($id)->first();

        if ($aparatur) {
            //return success with Api Resource
            return new AparaturResource(true, 'Detail Data Aparatur!', $aparatur);
        }

        //return failed with Api Resource
        return new AparaturResource(false, 'Detail Data Aparatur Tidak Ditemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aparatur $aparatur)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'role'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/aparaturs/' . basename($aparatur->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/aparaturs', $image->hashName());

            //update aparatur with new image
            $aparatur->update([
                'image' => $image->hashName(),
                'name'  => $request->name,
                'role'  => $request->role,
            ]);
        }

        //update aparatur without image
        $aparatur->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        if ($aparatur) {
            //return success with Api Resource
            return new AparaturResource(true, 'Data Aparatur Berhasil Diupdate!', $aparatur);
        }

        //return failed with Api Resource
        return new AparaturResource(false, 'Data Aparatur Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aparatur $aparatur)
    {
        //remove image
        Storage::disk('local')->delete('public/aparaturs/' . basename($aparatur->image));

        if ($aparatur->delete()) {
            //return success with Api Resource
            return new AparaturResource(true, 'Data Aparatur Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new AparaturResource(false, 'Data Aparatur Gagal Dihapus!', null);
    }
}
