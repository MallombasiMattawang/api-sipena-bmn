<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\InspeksiAsetResource;
use App\Models\InspeksiAset;
use Illuminate\Http\Request;

class InspeksiPetugasController extends Controller
{
    public function index($petugas)
    {
        //get data
        $query = InspeksiAset::selectRaw('tanggal_inspeksi, COUNT(aset_id) as jumlah_aset')
            ->when(request()->search, function ($query) {
                $query->where('tanggal_inspeksi', 'like', '%' . request()->search . '%');
            })
            // ->where('petugas_id', $petugas)
            ->groupBy('tanggal_inspeksi')
            ->latest('tanggal_inspeksi')
            ->paginate(25);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new InspeksiAsetResource(true, 'List Data Inspeksi', $query);
    }

    public function view($tanggal_inspeksi)
    {
        //get data
        $query = InspeksiAset::with('aset.kategori', 'kondisi', 'status', 'lokasi', 'petugas')
            ->where('tanggal_inspeksi', $tanggal_inspeksi)
            // mencari berdasarkan kolom 'nama_aset' dalam relasi 'aset'
            ->when(request()->search, function ($query) {
                $query->whereHas('aset', function ($q) {
                    $q->where('nama_aset', 'like', '%' . request()->search . '%');
                });
            })

            // urutkan berdasarkan 'tanggal_inspeksi'
            ->latest()
            ->paginate(25);

        // tambahkan query string ke pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new InspeksiAsetResource(true, 'List Data Aset', $query);
    }
}
