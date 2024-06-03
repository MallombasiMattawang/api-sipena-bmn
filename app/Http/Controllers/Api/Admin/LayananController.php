<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LayananResource;
use App\Models\Layanan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        //get layanans
        $layanans = Layanan::when(request()->search, function ($layanans) {
            $layanans = $layanans->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(15);

        //append query string to pagination links
        $layanans->appends(['search' => request()->search]);

        //return with Api Resource
        return new LayananResource(true, 'List Data Layanan', $layanans);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            // 'image'     => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'active'    => 'required',
            'tarif_pnbp_hr_kerja'    => 'required|integer',
            'tarif_pemda_hr_kerja'   => 'required|integer',
            'tarif_pnbp_hr_libur'    => 'required|integer',
            'tarif_pemda_hr_libur'   => 'required|integer',
            'tarif_asuransi'     => 'required|integer',
            'description'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/layanan', $image->hashName());

        //create layanan
        $layanan = Layanan::create([
            'name' => $request->name,
            // 'image'       => $image->hashName(),
            'active' => $request->active,
            'tarif_pnbp_hr_kerja' => $request->tarif_pnbp_hr_kerja,
            'tarif_pemda_hr_kerja' => $request->tarif_pemda_hr_kerja,
            'tarif_pnbp_hr_libur' => $request->tarif_pnbp_hr_libur,
            'tarif_pemda_hr_libur' => $request->tarif_pemda_hr_libur,
            'tarif_asuransi' => $request->tarif_asuransi,
            'description' => $request->description,
        ]);

        if ($layanan) {
            //return success with Api Resource
            return new LayananResource(true, 'Data Layanan Berhasil Disimpan!', $layanan);
        }

        //return failed with Api Resource
        return new LayananResource(false, 'Data Layanan Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $layanan = Layanan::whereId($id)->first();

        if ($layanan) {
            //return success with Api Resource
            return new LayananResource(true, 'Detail Data Layanan!', $layanan);
        }

        //return failed with Api Resource
        return new LayananResource(false, 'Detail Data Layanan Tidak DItemukan!', null);
    }

    public function update(Request $request, Layanan $layanan)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'active'     => 'required',
            'tarif_pnbp_hr_kerja'    => 'required|integer',
            'tarif_pemda_hr_kerja'   => 'required|integer',
            'tarif_pnbp_hr_libur'    => 'required|integer',
            'tarif_pemda_hr_libur'   => 'required|integer',
            'tarif_asuransi'     => 'required|integer',
            'description'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/layanan/' . basename($layanan->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/layanan', $image->hashName());

            $layanan->update([
                'name'  => $request->name,
                'image' => $image->hashName(),
                'active' => $request->active,
                'tarif_pnbp_hr_kerja' => $request->tarif_pnbp_hr_kerja,
                'tarif_pemda_hr_kerja' => $request->tarif_pemda_hr_kerja,
                'tarif_pnbp_hr_libur' => $request->tarif_pnbp_hr_libur,
                'tarif_pemda_hr_libur' => $request->tarif_pemda_hr_libur,
                'tarif_asuransi' => $request->tarif_asuransi,
                'description' => $request->description,
            ]);
        }

        $layanan->update([
            'name'  => $request->name,
            'active' => $request->active,
            'tarif_pnbp_hr_kerja' => $request->tarif_pnbp_hr_kerja,
            'tarif_pemda_hr_kerja' => $request->tarif_pemda_hr_kerja,
            'tarif_pnbp_hr_libur' => $request->tarif_pnbp_hr_libur,
            'tarif_pemda_hr_libur' => $request->tarif_pemda_hr_libur,
            'tarif_asuransi' => $request->tarif_asuransi,
            'description' => $request->description,
        ]);

        if ($layanan) {
            //return success with Api Resource
            return new LayananResource(true, 'Data Layanan Berhasil Diupdate!', $layanan);
        }

        //return failed with Api Resource
        return new LayananResource(false, 'Data Layanan Gagal Disupdate!', null);
    }

    public function destroy(Layanan $layanan)
    {
        //remove image
        Storage::disk('local')->delete('public/layanan/' . basename($layanan->image));

        if ($layanan->delete()) {
            //return success with Api Resource
            return new LayananResource(true, 'Data Layanan Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new LayananResource(false, 'Data Layanan Gagal Dihapus!', null);
    }

    public function all()
    {
        //get layanans
        $layanans = Layanan::where('active', 'YES')->get();

        //return with Api Resource
        return new LayananResource(true, 'List Data Layanan', $layanans);
    }
}
