<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InspeksiAsetResource;
use App\Models\InspeksiAset;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class InspeksiAsetController extends Controller
{
    public function index()
    {
        //get data
        $query = InspeksiAset::selectRaw('tanggal_inspeksi, COUNT(aset_id) as jumlah_aset, COUNT(petugas_id) as jumlah_petugas')
        ->when(request()->search, function ($query) {
            $query->where('tanggal_inspeksi', 'like', '%' . request()->search . '%');
        })
        ->groupBy('tanggal_inspeksi')
        ->latest('tanggal_inspeksi')
        ->paginate(25);

        //append query string to pagination links
        $query->appends(['search' => request()->search]);

        //return with Api Resource
        return new InspeksiAsetResource(true, 'List Data Aset', $query);
    }

    public function view($tanggal_inspeksi)
    {
        //get data
        $query = InspeksiAset::with('aset.kategori', 'kondisi', 'status', 'lokasi', 'petugas')
        ->where('tanggal_inspeksi', $tanggal_inspeksi)
        // mencari berdasarkan kolom 'nama_aset' dalam relasi 'aset'
        ->when(request()->search, function ($query) {
            $query->whereHas('aset', function($q) {
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

    public function generatePdf($tanggal_inspeksi)
    {
        // Ambil data inspeksi berdasarkan tanggal
        $inspeksiAset = InspeksiAset::with('aset.kategori', 'kondisi', 'status', 'lokasi', 'petugas')
            ->where('tanggal_inspeksi', $tanggal_inspeksi)
            ->get();

        // Mengelompokkan petugas berdasarkan inspeksi
        $officers = $inspeksiAset->groupBy('petugas_id');

        // Ambil aset yang diinspeksi
        $assets = $inspeksiAset->map(function ($item) {
            return [
                'kode_aset' => $item->aset->kode_aset, // Misalkan ada relasi ke model Aset
                'nama_aset' => $item->aset->nama_aset,
                'nup' => $item->aset->nup,
                'merk' => $item->aset->merk_type,
                'tahun' => $item->aset->tahun_perolehan,
                'masa_pakai' => $item->aset->masa_pakai,
                'pemegang_aset' => $item->aset->pemegang_aset,
                'kategori' => $item->aset->kategori->nama_kategori,
                'kondisi' => $item->kondisi->nama_kondisi,
                'status' => $item->status->nama_status,
                'lokasi' => $item->lokasi->nama_lokasi,
                'petugas' => $item->petugas->name,
                'hasil_inspeksi' => $item->hasil_inspeksi,
                'rekomendasi' => $item->rekomendasi,
            ];
        });

        // Siapkan data untuk view
        $data = [
            'inspectionDate' => tgl_indo($tanggal_inspeksi),
            'officers' => $officers,
            'assets' => $assets,
        ];

        // Generate the PDF from the view
        $pdf = Pdf::loadView('pdf.report-inspeksi', $data);

        // Set the paper size and orientation
        $pdf->setPaper('A4', 'potrait');

        // Stream the PDF to the browser
        return $pdf->stream('report_inspeksi_' . $tanggal_inspeksi . '.pdf', ['Attachment' => false]);
    }

}
