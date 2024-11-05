<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsetResource;
use App\Http\Resources\InspeksiAsetResource;
use App\Http\Resources\KategoriAsetResource;
use App\Http\Resources\KondisiAsetResource;
use App\Http\Resources\LayananResource;
use App\Http\Resources\LayananTiketResource;
use App\Http\Resources\LokasiAsetResource;
use App\Http\Resources\StatusAsetResource;
use App\Models\Aset;
use App\Models\InspeksiAset;
use App\Models\KategoriAset;
use App\Models\KondisiAset;
use App\Models\LokasiAset;
use App\Models\StatusAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ScanBarcodeAsetController extends Controller
{
    public function index($kode_aset)
    {

        $data = Aset::with('kategori', 'kondisi', 'status', 'lokasi')->where('kode_aset', $kode_aset)
            ->first();

        if ($data) {

            //return success with Api Resource
            return new AsetResource(true, ' Aset Ditemukan', $data);
        }

        //return failed with Api Resource
        return new AsetResource(false, 'Aset tidak ditemukan', null);
    }

    public function inspeksiUpdate(Request $request, $id)
    {
        $aset = Aset::where('kode_aset', $id)->first();
    
        if ($aset) {
            // Validasi
            $validator = Validator::make($request->all(), [
                'status_aset_id' => 'required',
                'kondisi_aset_id' => 'required',
                'lokasi_aset_id' => 'required',
                'petugas_id' => 'required',
                'tanggal_inspeksi' => 'required|date',
                'hasil_inspeksi' => 'nullable',
                'rekomendasi' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            // Hapus data inspeksi yang memiliki tanggal dan aset yang sama
            InspeksiAset::where('aset_id', $aset->id)
                ->where('tanggal_inspeksi', $request->tanggal_inspeksi)
                ->forceDelete();
    
            // Upload image
            $name_image = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image->storeAs('public/inspeksi', $image->hashName());
                $name_image = $image->hashName();
            }
    
            // Create inspeksi aset baru
            $data = InspeksiAset::create([
                'status_aset_id' => $request->status_aset_id,
                'kondisi_aset_id' => $request->kondisi_aset_id,
                'lokasi_aset_id' => $request->lokasi_aset_id,
                'petugas_id' => $request->petugas_id,
                'tanggal_inspeksi' => $request->tanggal_inspeksi,
                'hasil_inspeksi' => $request->hasil_inspeksi,
                'rekomendasi' => $request->rekomendasi,
                'image' => $name_image,
                'aset_id' => $aset->id,
            ]);
    
            // Update status, kondisi, dan lokasi di tabel aset
            $aset->update([
                'status_aset_id' => $request->status_aset_id,
                'kondisi_aset_id' => $request->kondisi_aset_id,
                'lokasi_aset_id' => $request->lokasi_aset_id,
            ]);
    
            if ($data) {
                // Return sukses dengan Api Resource
                return new InspeksiAsetResource(true, 'Data Aset Berhasil Diupdate!', $data);
            }
    
            // Return gagal dengan Api Resource
            return new InspeksiAsetResource(false, 'Data Aset Gagal Diupdate!', null);
        } else {
            // Return gagal dengan Api Resource
            return new InspeksiAsetResource(false, 'Data Aset tidak ditemukan!', null);
        }
    }

    public function kondisi()
    {
        //get status
        $query = KondisiAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new KondisiAsetResource(true, 'List Data Kondisi aset', $query);
    }

    public function status()
    {
        //get status
        $query = StatusAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new StatusAsetResource(true, 'List Data status aset', $query);
    }

    public function kategori()
    {
        //get status
        $query = KategoriAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new KategoriAsetResource(true, 'List Data kategori aset', $query);
    }

    public function lokasi()
    {
        //get status
        $query = LokasiAset::withCount('aset')->latest()->get();

        //return with Api Resource
        return new LokasiAsetResource(true, 'List Data lokasi aset', $query);
    }
}
