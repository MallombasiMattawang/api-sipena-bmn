<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KondisiAsetResource;
use App\Models\KondisiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KondisiAsetController extends Controller
{
    public function index()
    {
        //get data
        $query = KondisiAset::when(request()->search, function ($query) {
            $query = $query->where('nama_kondisi', 'like', '%' . request()->search . '%');
        })->latest()->paginate(15);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new KondisiAsetResource(true, 'List data kondisi aset', $query);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kondisi'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create kondisi aset
        $data = KondisiAset::create([
            'nama_kondisi' => $request->nama_kondisi,
            
        ]);

        if ($data) {
            //return success with Api Resource
            return new KondisiAsetResource(true, 'Data kondisi Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new KondisiAsetResource(false, 'Data kondisi Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $data = KondisiAset::whereId($id)->first();

        if ($data) {
            //return success with Api Resource
            return new KondisiAsetResource(true, 'Detail Data kondisi Aset!', $data);
        }

        //return failed with Api Resource
        return new KondisiAsetResource(false, 'Detail Data kondisi Aset Tidak DItemukan!', null);
    }

    public function update(Request $request, $id)
    {
        $data = KondisiAset::findOrFail($id);

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_kondisi'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Persiapkan data untuk update
        $dataToUpdate = [
            'nama_kondisi' => $request->nama_kondisi
        ];

        // Lakukan update data
        $data->update($dataToUpdate);

        if ($data) {
            // Return sukses dengan Api Resource
            return new KondisiAsetResource(true, 'Data kondisi Aset Berhasil Diupdate!', $data);
        }

        // Return gagal dengan Api Resource
        return new KondisiAsetResource(false, 'Data kondisi Aset Gagal Diupdate!', null);
    }

    public function destroy($id)
    {
        // Temukan data aset berdasarkan ID, jika tidak ada lemparkan error
        $data = KondisiAset::findOrFail($id);

        // Hapus data (soft delete)
        if ($data->delete()) {
            // Return sukses dengan Api Resource
            return new KondisiAsetResource(true, 'Data kondisi Berhasil Dihapus!', null);
        }

        // Return gagal dengan Api Resource jika penghapusan gagal
        return new KondisiAsetResource(false, 'Data kondisi Gagal Dihapus!', null);
    }

    public function all()
    {
        //get kondisi
        $query = KondisiAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new KondisiAsetResource(true, 'List Data Kondisi aset', $query);
    }
}
