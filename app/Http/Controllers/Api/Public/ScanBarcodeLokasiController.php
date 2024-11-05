<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsetResource;
use App\Http\Resources\LayananResource;
use App\Http\Resources\LayananTiketResource;
use App\Http\Resources\LokasiAsetResource;
use App\Models\Aset;
use App\Models\LayananTiket;
use App\Models\LokasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScanBarcodeLokasiController extends Controller
{
    public function index($kode_lokasi)
    {
        
        $data = LokasiAset::where('kode_lokasi', $kode_lokasi)
            ->first();

        if ($data) {
            // $aset = Aset::where('lokasi_aset_id', $data->id)->get();
            //return success with Api Resource
            return new LokasiAsetResource(true, ' Lokasi Ditemukan', $data);
        }

        //return failed with Api Resource
        return new LokasiAsetResource(false, 'Lokasi tidak ditemukan', null);
    }

    public function aset($kode_lokasi)
    {

        $lokasi = LokasiAset::where('kode_lokasi', $kode_lokasi)
            ->first();

        $data = Aset::with('kategori', 'kondisi', 'status', 'lokasi')->where('lokasi_aset_id', $lokasi->id)
            ->get();

        if ($data) {

            //return success with Api Resource
            return new AsetResource(true, ' Aset Ditemukan', $data);
        }

        //return failed with Api Resource
        return new AsetResource(false, 'Aset tidak ditemukan', null);
    }
}
