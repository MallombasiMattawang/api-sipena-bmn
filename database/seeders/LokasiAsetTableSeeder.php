<?php

namespace Database\Seeders;

use App\Models\LokasiAset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiAsetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LokasiAset::insert([
            [   
                'kode_lokasi' => '008',
                'nama_lokasi' => 'ruang occ',
                'keterangan' => '-'
            ],
            
            
            
        ]);
    }
}
