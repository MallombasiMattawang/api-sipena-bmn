<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasaAsetResource;
use App\Models\MasaAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MasaAsetController extends Controller
{
    public function index()
    {
        //get data
        $query = MasaAset::when(request()->search, function ($query) {
            $query = $query->where('nama_masa', 'like', '%' . request()->search . '%');
        })->latest()->paginate(15);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new MasaAsetResource(true, 'List data masa aset', $query);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_masa'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create masa aset
        $data = MasaAset::create([
            'nama_masa' => $request->nama_masa,
            
        ]);

        if ($data) {
            //return success with Api Resource
            return new MasaAsetResource(true, 'Data masa Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new MasaAsetResource(false, 'Data masa Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $data = MasaAset::whereId($id)->first();

        if ($data) {
            //return success with Api Resource
            return new MasaAsetResource(true, 'Detail Data masa Aset!', $data);
        }

        //return failed with Api Resource
        return new MasaAsetResource(false, 'Detail Data masa Aset Tidak DItemukan!', null);
    }

    public function update(Request $request, $id)
    {
        $data = MasaAset::findOrFail($id);

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_masa'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Persiapkan data untuk update
        $dataToUpdate = [
            'nama_masa' => $request->nama_masa
        ];

        // Lakukan update data
        $data->update($dataToUpdate);

        if ($data) {
            // Return sukses dengan Api Resource
            return new MasaAsetResource(true, 'Data masa Aset Berhasil Diupdate!', $data);
        }

        // Return gagal dengan Api Resource
        return new MasaAsetResource(false, 'Data masa Aset Gagal Diupdate!', null);
    }

    public function destroy($id)
    {
        // Temukan data aset berdasarkan ID, jika tidak ada lemparkan error
        $data = MasaAset::findOrFail($id);

        // Hapus data (soft delete)
        if ($data->delete()) {
            // Return sukses dengan Api Resource
            return new MasaAsetResource(true, 'Data masa Berhasil Dihapus!', null);
        }

        // Return gagal dengan Api Resource jika penghapusan gagal
        return new MasaAsetResource(false, 'Data masa Gagal Dihapus!', null);
    }

    public function all()
    {
        //get masa
        $query = MasaAset::latest()->get();

        //return with Api Resource
        return new MasaAsetResource(true, 'List Data masa aset', $query);
    }
}
