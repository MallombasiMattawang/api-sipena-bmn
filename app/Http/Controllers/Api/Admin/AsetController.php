<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsetResource;
use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AsetController extends Controller
{
    public function index()
    {
        //get data
        $query = Aset::with('kategori', 'kondisi', 'status', 'lokasi')
        ->when(request()->search, function ($query) {
            $query = $query->where('nama_aset', 'like', '%' . request()->search . '%');
        })->latest()->paginate(25);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new AsetResource(true, 'List Data Aset', $query);
    }

    public function filter()
    {
        // Mempersiapkan query untuk mengambil data aset
        $query = Aset::with('kategori', 'kondisi', 'status', 'lokasi')
            // Filter berdasarkan 'nama_aset' yang diinputkan di request
            ->when(request()->search, function ($query) {
                $query->where('nama_aset', 'like', '%' . request()->search . '%');
            })
            // Filter berdasarkan kategori
            ->when(request()->kategori, function ($query) {
                $query->where('kategori_aset_id', request()->kategori);
            })
            // Filter berdasarkan kondisi
            ->when(request()->kondisi, function ($query) {
                $query->where('kondisi_aset_id', request()->kondisi);
            })
            // Filter berdasarkan status
            ->when(request()->status, function ($query) {
                $query->where('status_aset_id', request()->status);
            })
            // Filter berdasarkan lokasi
            ->when(request()->lokasi, function ($query) {
                $query->where('lokasi_aset_id', request()->lokasi);
            })
            // Urutkan berdasarkan tanggal terbaru
            ->latest()
            ->paginate(25);
    
        // Menambahkan query string ke tautan pagination secara otomatis
        $query->appends(request()->all());
    
        // Mengembalikan hasil sebagai API Resource
        return new AsetResource(true, 'List Data Aset', $query);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_aset'  => 'required',
            'kode_aset'  => 'required|unique:asets',
            'merk_type'  => 'required',
            'nup'  => 'required',
            'tahun_perolehan'  => 'required',
            'kategori_aset_id' => 'required',
            'status_aset_id' => 'required',
            'kondisi_aset_id' => 'required',
            'lokasi_aset_id' => 'required',
            'deskripsi' => 'required',
            'harga' => 'nullable',
            'tanggal_perolehan' => 'nullable',
            'pemegang_aset' => 'nullable',
            'masa_pakai' => 'required',
            'image'     => 'nullable|image|mimes:jpeg,jpg,png|max:5000',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $name_image = null;
        $image = $request->file('image');
        if ($image) {
            $image->storeAs('public/bmn', $image->hashName());
            $name_image = $image->hashName();
        }

        //create aset
        $data = Aset::create([
            'nama_aset' => $request->nama_aset,
            'kode_aset' => $request->kode_aset,
            'merk_type' => $request->merk_type,
            'nup' => $request->nup,
            'tahun_perolehan' => $request->tahun_perolehan,
            'kategori_aset_id' => $request->kategori_aset_id,
            'status_aset_id' => $request->status_aset_id,
            'kondisi_aset_id' => $request->kondisi_aset_id,
            'lokasi_aset_id' => $request->lokasi_aset_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'tanggal_perolehan' => $request->tanggal_perolehan,
            'pemegang_aset' => $request->pemegang_aset,
            'image' => $name_image,
            'masa_pakai' => $request->masa_pakai
        ]);

        if ($data) {
            //return success with Api Resource
            return new AsetResource(true, 'Data Aset Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new AsetResource(false, 'Data Aset Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $data = Aset::with('kategori', 'kondisi', 'status', 'lokasi')->whereId($id)->first();

        if ($data) {
            //return success with Api Resource
            return new AsetResource(true, 'Detail Data Aset!', $data);
        }

        //return failed with Api Resource
        return new AsetResource(false, 'Detail Data Aset Tidak DItemukan!', null);
    }

    public function update(Request $request, $id)
    {
        $data = Aset::findOrFail($id);

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_aset'  => 'required',
            'kode_aset'  => [
                'required',
                Rule::unique('asets', 'kode_aset')->ignore($id),
            ],
            'merk_type'  => 'required',
            'nup'  => 'required',
            'tahun_perolehan'  => 'required',
            'kategori_aset_id' => 'required',
            'status_aset_id' => 'required',
            'kondisi_aset_id' => 'required',
            'lokasi_aset_id' => 'required',
            'deskripsi' => 'required',
            'harga' => 'nullable',
            'tanggal_perolehan' => 'nullable',
            'pemegang_aset' => 'nullable',
            'masa_pakai' => 'required',
            'image'     => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Persiapkan data untuk update
        $harga = $request->input('harga');
        $tanggal_perolehan = $request->input('tanggal_perolehan');
        // Jika harga atau tanggal kosong/null, set null untuk query
        if (is_null($harga) || $harga === '') {
            $harga = null;
        }

        if (is_null($tanggal_perolehan) || $tanggal_perolehan === '') {
            $tanggal_perolehan = null;
        }
        $dataToUpdate = [
            'nama_aset' => $request->nama_aset,
            'kode_aset' => $request->kode_aset,
            'merk_type' => $request->merk_type,
            'nup' => $request->nup,
            'tahun_perolehan' => $request->tahun_perolehan,
            'kategori_aset_id' => $request->kategori_aset_id,
            'status_aset_id' => $request->status_aset_id,
            'kondisi_aset_id' => $request->kondisi_aset_id,
            'lokasi_aset_id' => $request->lokasi_aset_id,
            'deskripsi' => $request->deskripsi,
            // 'harga' => $harga,
            // 'tanggal_perolehan' => $tanggal_perolehan,
            'pemegang_aset' => $request->pemegang_aset,
            'masa_pakai' => $request->masa_pakai
        ];

        // Jika ada file gambar yang diupload
        if ($request->file('image')) {
            // Hapus gambar lama
            Storage::disk('local')->delete('public/bmn/' . basename($data->image));

            // Upload gambar baru
            $image = $request->file('image');
            $image->storeAs('public/bmn', $image->hashName());

            // Tambahkan nama file gambar yang baru ke dalam dataToUpdate
            $dataToUpdate['image'] = $image->hashName();
        }

        // Lakukan update data
        $data->update($dataToUpdate);

        if ($data) {
            // Return sukses dengan Api Resource
            return new AsetResource(true, 'Data Aset Berhasil Diupdate!', $data);
        }

        // Return gagal dengan Api Resource
        return new AsetResource(false, 'Data Aset Gagal Diupdate!', null);
    }

    public function destroy($id)
    {
        // Temukan data aset berdasarkan ID, jika tidak ada lemparkan error
        $data = Aset::findOrFail($id);

        // Jika gambar ada, coba hapus gambar
        if ($data->image && Storage::disk('local')->exists('public/bmn/' . basename($data->image))) {
            // Hapus gambar
            Storage::disk('local')->delete('public/bmn/' . basename($data->image));
        }

        // Hapus data (soft delete)
        if ($data->forceDelete()) {
            // Return sukses dengan Api Resource
            return new AsetResource(true, 'Data Aset Berhasil Dihapus!', null);
        }

        // Return gagal dengan Api Resource jika penghapusan gagal
        return new AsetResource(false, 'Data Aset Gagal Dihapus!', null);
    }
}
