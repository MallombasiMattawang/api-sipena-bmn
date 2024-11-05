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
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CetakBarcodeController extends Controller
{
    public function barcodeAset($kode_aset)
    {
        $data = Aset::where('kode_aset', $kode_aset)->first();

        if ($data) {

            // Membuat QR Code
            $qrCodeImage = 'https://api.qrserver.com/v1/create-qr-code/?data='.$data->kode_aset.'&size=150x150';
            return view('pdf.barcode-aset', ['data' => $data, 'qrCodeImage' => $qrCodeImage]);
        }

        // Return gagal dengan Api Resource
        return new AsetResource(false, 'Aset tidak ditemukan', null);
    }

    public function barcodeLokasiAset($kode_lokasi)
    {
        $data = LokasiAset::where('kode_lokasi', $kode_lokasi)->first();

        if ($data) {
            $aset =  Aset::where('lokasi_aset_id', $data->id)->get();

            // Membuat QR Code
            $qrCodeImage = 'https://api.qrserver.com/v1/create-qr-code/?data='.$data->kode_lokasi.'&size=150x150';
            return view('pdf.barcode-lokasi-aset', [
                'data' => $data, 
                'qrCodeImage' => $qrCodeImage,
                'aset' => $aset
            ]);
        }

        // Return gagal dengan Api Resource
        return new LokasiAsetResource(false, 'Lokasi Aset tidak ditemukan', null);
    }
}
