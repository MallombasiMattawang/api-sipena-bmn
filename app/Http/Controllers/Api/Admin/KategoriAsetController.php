<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriAsetResource;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KategoriAsetController extends Controller
{
    public function index()
    {
        //get data
        $query = KategoriAset::when(request()->search, function ($query) {
            $query = $query->where('nama_kategori', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new KategoriAsetResource(true, 'List data kategori aset', $query);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create kategori aset
        $data = KategoriAset::create([
            'nama_kategori' => $request->nama_kategori,
            
        ]);

        if ($data) {
            //return success with Api Resource
            return new KategoriAsetResource(true, 'Data Kategori Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new KategoriAsetResource(false, 'Data kategori Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $data = KategoriAset::whereId($id)->first();

        if ($data) {
            //return success with Api Resource
            return new KategoriAsetResource(true, 'Detail Data Kategori Aset!', $data);
        }

        //return failed with Api Resource
        return new KategoriAsetResource(false, 'Detail Data kategori Aset Tidak DItemukan!', null);
    }

    public function update(Request $request, $id)
    {
        $data = KategoriAset::findOrFail($id);

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_kategori'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Persiapkan data untuk update
        $dataToUpdate = [
            'nama_kategori' => $request->nama_kategori
        ];

        // Lakukan update data
        $data->update($dataToUpdate);

        if ($data) {
            // Return sukses dengan Api Resource
            return new KategoriAsetResource(true, 'Data kategori Aset Berhasil Diupdate!', $data);
        }

        // Return gagal dengan Api Resource
        return new KategoriAsetResource(false, 'Data Kategori Aset Gagal Diupdate!', null);
    }

    public function destroy($id)
    {
        // Temukan data aset berdasarkan ID, jika tidak ada lemparkan error
        $data = KategoriAset::findOrFail($id);

        // Hapus data (soft delete)
        if ($data->delete()) {
            // Return sukses dengan Api Resource
            return new KategoriAsetResource(true, 'Data Kategori Berhasil Dihapus!', null);
        }

        // Return gagal dengan Api Resource jika penghapusan gagal
        return new KategoriAsetResource(false, 'Data Kategori Gagal Dihapus!', null);
    }

    public function all()
    {
        //get categories
        $query = KategoriAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new KategoriAsetResource(true, 'List Data kategori aset', $query);
    }
}
