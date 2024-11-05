<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LokasiAsetResource;
use App\Models\LokasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LokasiAsetController extends Controller
{
    public function index()
    {
        //get data
        $query = LokasiAset::when(request()->search, function ($query) {
            $query = $query->where('nama_lokasi', 'like', '%' . request()->search . '%');
        })->latest()->paginate(15);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new LokasiAsetResource(true, 'List data lokasi aset', $query);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lokasi'  => 'required|unique:lokasi_asets',
            'kode_lokasi'  => 'required|unique:lokasi_asets',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create lokasi aset
        $data = LokasiAset::create([
            'nama_lokasi' => $request->nama_lokasi,
            'kode_lokasi' => $request->kode_lokasi,
            'keterangan' => $request->keterangan,

        ]);

        if ($data) {
            //return success with Api Resource
            return new LokasiAsetResource(true, 'Data lokasi Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new LokasiAsetResource(false, 'Data lokasi Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $data = LokasiAset::whereId($id)->first();

        if ($data) {
            //return success with Api Resource
            return new LokasiAsetResource(true, 'Detail Data lokasi Aset!', $data);
        }

        //return failed with Api Resource
        return new LokasiAsetResource(false, 'Detail Data lokasi Aset Tidak DItemukan!', null);
    }

    public function update(Request $request, $id)
    {
        $data = LokasiAset::findOrFail($id);

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_lokasi'  => [
                'required',
                Rule::unique('lokasi_asets', 'nama_lokasi')->ignore($id),
            ],
            'kode_lokasi'  => [
                'required',
                Rule::unique('lokasi_asets', 'kode_lokasi')->ignore($id),
            ],
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Persiapkan data untuk update
        $dataToUpdate = [
            'nama_lokasi' => $request->nama_lokasi,
            'kode_lokasi' => $request->kode_lokasi,
            'keterangan' => $request->keterangan,

        ];

        // Lakukan update data
        $data->update($dataToUpdate);

        if ($data) {
            // Return sukses dengan Api Resource
            return new LokasiAsetResource(true, 'Data lokasi Aset Berhasil Diupdate!', $data);
        }

        // Return gagal dengan Api Resource
        return new LokasiAsetResource(false, 'Data lokasi Aset Gagal Diupdate!', null);
    }

    public function destroy($id)
    {
        // Temukan data aset berdasarkan ID, jika tidak ada lemparkan error
        $data = LokasiAset::findOrFail($id);

        // Hapus data (soft delete)
        if ($data->delete()) {
            // Return sukses dengan Api Resource
            return new LokasiAsetResource(true, 'Data lokasi Berhasil Dihapus!', null);
        }

        // Return gagal dengan Api Resource jika penghapusan gagal
        return new LokasiAsetResource(false, 'Data lokasi Gagal Dihapus!', null);
    }

    public function all()
    {
        //get lokasi
        $query = LokasiAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new LokasiAsetResource(true, 'List Data Lokasi aset', $query);
    }
}
