<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatusAsetResource;
use App\Models\StatusAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StatusAsetController extends Controller
{
    public function index()
    {
        //get data
        $query = StatusAset::when(request()->search, function ($query) {
            $query = $query->where('nama_status', 'like', '%' . request()->search . '%');
        })->latest()->paginate(15);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new StatusAsetResource(true, 'List data status aset', $query);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_status'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create status aset
        $data = StatusAset::create([
            'nama_status' => $request->nama_status,
            
        ]);

        if ($data) {
            //return success with Api Resource
            return new StatusAsetResource(true, 'Data status Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new StatusAsetResource(false, 'Data status Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $data = StatusAset::whereId($id)->first();

        if ($data) {
            //return success with Api Resource
            return new StatusAsetResource(true, 'Detail Data status Aset!', $data);
        }

        //return failed with Api Resource
        return new StatusAsetResource(false, 'Detail Data status Aset Tidak DItemukan!', null);
    }

    public function update(Request $request, $id)
    {
        $data = StatusAset::findOrFail($id);

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_status'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Persiapkan data untuk update
        $dataToUpdate = [
            'nama_status' => $request->nama_status
        ];

        // Lakukan update data
        $data->update($dataToUpdate);

        if ($data) {
            // Return sukses dengan Api Resource
            return new StatusAsetResource(true, 'Data status Aset Berhasil Diupdate!', $data);
        }

        // Return gagal dengan Api Resource
        return new StatusAsetResource(false, 'Data status Aset Gagal Diupdate!', null);
    }

    public function destroy($id)
    {
        // Temukan data aset berdasarkan ID, jika tidak ada lemparkan error
        $data = StatusAset::findOrFail($id);

        // Hapus data (soft delete)
        if ($data->delete()) {
            // Return sukses dengan Api Resource
            return new StatusAsetResource(true, 'Data status Berhasil Dihapus!', null);
        }

        // Return gagal dengan Api Resource jika penghapusan gagal
        return new StatusAsetResource(false, 'Data status Gagal Dihapus!', null);
    }

    public function all()
    {
        //get status
        $query = StatusAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new StatusAsetResource(true, 'List Data status aset', $query);
    }
}
