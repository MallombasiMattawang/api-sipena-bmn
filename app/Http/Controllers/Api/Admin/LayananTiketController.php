<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LayananTiketResource;
use App\Models\ConfigDay;
use App\Models\LayananTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LayananTiketController extends Controller
{

    public function index()
    {
        //cek user administrator
        $admin_id = auth()->guard('api')->user()->id;

        // Mengambil tanggal hari ini
        $today = Carbon::today()->toDateString();

        if ($admin_id == 1) {
            // Get layanans
            $layanans = LayananTiket::with('layanan')
                ->when(request()->search, function ($query) {
                    $query->where('created_at', 'like', '%' . request()->search . '%');
                })
                ->when(empty(request()->search), function ($query) use ($today) {
                    // Jika search kosong, cari berdasarkan tanggal hari ini
                    $query->whereDate('created_at', $today);
                })->whereNull('kode_booking')
                ->latest()
                ->paginate(25);
        } else {
            // Get layanans
            $layanans = LayananTiket::with('layanan')
                ->when(request()->search, function ($query) {
                    $query->where('created_at', 'like', '%' . request()->search . '%');
                })
                ->when(empty(request()->search), function ($query) use ($today) {
                    // Jika search kosong, cari berdasarkan tanggal hari ini
                    $query->whereDate('created_at', $today);
                })->where('user_id', $admin_id)
                ->latest()
                ->paginate(25);
        }


        // Append query string to pagination links
        $layanans->appends(['search' => request()->search]);

        //return with Api Resource
        return new LayananTiketResource(true, 'List Data Tiket', $layanans);
    }


    public function store(Request $request, $date = null)
    {
        $validator = Validator::make($request->all(), [
            'layanan_id'      => 'required',
            'tarif_pnbp_hr_kerja'    => 'nullable|integer',
            'tarif_pemda_hr_kerja'   => 'nullable|integer',
            'tarif_pnbp_hr_libur'    => 'nullable|integer',
            'tarif_pemda_hr_libur'   => 'nullable|integer',
            'tarif_asuransi'     => 'nullable|integer',
            'jumlah' => 'required|integer',
            'status' => 'required',
            'role' => 'required',
            'tgl_booking' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // $status_hari = ConfigDay::where('id', 1)->first();

        if ($request->status == 'BIASA') {
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

        if ($request->role == 'public') {
            $bookingNumber = LayananTiket::generateBookingNumber();
            $message = 'Booking';
            //create booking
            $layanan = LayananTiket::create([
                'user_id'     => auth()->guard('api')->user()->id,
                'kode_booking' => $bookingNumber,
                'layanan_id' => $request->layanan_id,
                'tarif_pnbp' => $tarif_pnbp,
                'tarif_pemda' => $tarif_pemda,
                'tarif_asuransi' => $tarif_asuransi,
                'tarif_total' => $tarif_total,
                'jumlah' => $request->jumlah,
                'lunas' => 'NO',
                'is_active' => 0,
                'tgl_booking' => $request->tgl_booking,
                'status' => $request->status,
            ]);
        } else {
            $ticketNumber = LayananTiket::generateTicketNumber();
            $message = 'Tiket';
            //create tiket
            $layanan = LayananTiket::create([
                'user_id'     => auth()->guard('api')->user()->id,
                'barcode' => $ticketNumber,
                'layanan_id' => $request->layanan_id,
                'tarif_pnbp' => $tarif_pnbp,
                'tarif_pemda' => $tarif_pemda,
                'tarif_asuransi' => $tarif_asuransi,
                'tarif_total' => $tarif_total,
                'jumlah' => $request->jumlah,
                'lunas' => 'YES',
                'is_active' => 1,
                'tgl_booking' => $request->tgl_booking,
                'status' => $request->status,
            ]);
        }

        if ($layanan) {
            //return success with Api Resource
            return new LayananTiketResource(true, $message . ' Tiket Berhasil Dibuat!', $layanan);
        }

        //return failed with Api Resource
        return new LayananTiketResource(false, $message . ' Tiket Gagal Dibuat!', null);
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

    public function update(Request $request, LayananTiket $layanan)
    {
        $validator = Validator::make($request->all(), [
            'bukti_tf'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('bukti_tf')) {

            //remove old image
            Storage::disk('local')->delete('public/bukti_bayar/' . basename($layanan->bukti_tf));

            //upload new image
            $image = $request->file('bukti_tf');
            $image->storeAs('public/bukti_bayar', $image->hashName());

            // Cari ticket berdasarkan id
            $ticket = LayananTiket::find($request->id);

            $ticket->update([
                'bukti_tf' => $image->hashName(),
                'is_active' => 2,
            ]);

             //return success with Api Resource
             return new LayananTiketResource(true, 'Bukti Transer berhasil, mohon tunggu aktivasi tiket kamu', $ticket);
        }

        if ($request->is_active == 1) {
            // Cari ticket berdasarkan id
            $ticket = LayananTiket::find($request->id);
            $ticketNumber = LayananTiket::generateTicketNumber();

            $ticket->update([
                'is_active' => 1,
                'lunas' => 'YES',
                'barcode' => $ticketNumber
            ]);

             //return success with Api Resource
             return new LayananTiketResource(true, 'aktivasi tiket berhasil', $ticket);
        }


        //return failed with Api Resource
        return new LayananTiketResource(false, 'Proses Gagal!', null);
    }

    public function booking()
    {
        // Mengambil tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Get layanans
        $layanans = LayananTiket::with('layanan')
            ->when(request()->search, function ($query) {
                $query->where('created_at', 'like', '%' . request()->search . '%');
            })->whereNotNull('kode_booking')
            ->latest()
            ->paginate(25);



        // Append query string to pagination links
        $layanans->appends(['search' => request()->search]);

        //return with Api Resource
        return new LayananTiketResource(true, 'List Data Tiket', $layanans);
    }
}
