<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LayananTiketResource;
use App\Models\ConfigDay;
use App\Models\LayananTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Carbon\Carbon;

class LayananTiketController extends Controller
{

    public function index()
    {
        // Mengambil tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Get layananTikets
        $layananTikets = LayananTiket::with('layanan')
            ->when(request()->search, function ($query) {
                $query->where('created_at', 'like', '%' . request()->search . '%');
            })
            ->when(empty(request()->search), function ($query) use ($today) {
                // Jika search kosong, cari berdasarkan tanggal hari ini
                $query->whereDate('created_at', $today);
            })
            ->latest()
            ->paginate(25);

        // Append query string to pagination links
        $layananTikets->appends(['search' => request()->search]);

        //return with Api Resource
        return new LayananTiketResource(true, 'List Data Tiket', $layananTikets);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'layanan_id'      => 'required',
            'tarif_pnbp_hr_kerja'    => 'nullable|integer',
            'tarif_pemda_hr_kerja'   => 'nullable|integer',
            'tarif_pnbp_hr_libur'    => 'nullable|integer',
            'tarif_pemda_hr_libur'   => 'nullable|integer',
            'tarif_asuransi'     => 'nullable|integer',
            'jumlah' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $status_hari = ConfigDay::where('id', 1)->first();

        if ($status_hari->status == 'BIASA') {
            $tarif_pnbp = $request->tarif_pnbp_hr_kerja * $request->jumlah;
            $tarif_pemda = $request->tarif_pemda_hr_kerja * $request->jumlah;
            $tarif_asuransi = $request->tarif_asuransi * $request->jumlah;
            $tarif_total = $tarif_pemda + $tarif_pnbp + $tarif_asuransi;
        } else {
            $tarif_pnbp = $request->tarif_pnbp_hr_libur * $request->jumlah;
            $tarif_pemda = $request->tarif_pemda_hr_libur * $request->jumlah;
            $tarif_asuransi = $request->tarif_asuransi * $request->jumlah;
            $tarif_total = $tarif_pemda + $tarif_pnbp + $tarif_asuransi;
        }

        $ticketNumber = LayananTiket::generateTicketNumber();
        //create layanan
        $layanan = LayananTiket::create([
            'barcode' => $ticketNumber,
            'layanan_id' => $request->layanan_id,
            'tarif_pnbp' => $tarif_pnbp,
            'tarif_pemda' => $tarif_pemda,
            'tarif_asuransi' => $tarif_asuransi,
            'tarif_total' => $tarif_total,
            'jumlah' => $request->jumlah,
            'lunas' => 'YES',
            'is_active' => 1,
        ]);


        // Generate barcode
        // $generator = new BarcodeGeneratorHTML();
        // $barcode = $generator->getBarcode($layanan->id, $generator::TYPE_CODE_128);

        // Update barcode field in the product
        // $layanan->update(['barcode' => $barcode]);


        if ($layanan) {
            //return success with Api Resource
            return new LayananTiketResource(true, 'Tiket Berhasil Dibuat!', $layanan);
        }

        //return failed with Api Resource
        return new LayananTiketResource(false, 'Tiket Gagal Dibuat!', null);
    }

    public function show($id)
    {
        $layanan = LayananTiket::with('layanan')->whereId($id)->first();

        if ($layanan) {
            //return success with Api Resource
            return new LayananTiketResource(true, 'Detail Data Tiket!', $layanan);
        }

        //return failed with Api Resource
        return new LayananTiketResource(false, 'Detail Data Tiket Tidak DItemukan!', null);
    }

}
