<?php

namespace Database\Seeders;

use App\Models\KategoriAset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriAsetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriAset::insert([
            [
                'nama_kategori' => 'elektronik',
            ],
            [
                'nama_kategori' => 'furniture',
            ],
            [
                'nama_kategori' => 'kendaraan',
            ],
            [
                'nama_kategori' => 'peralatan kantor',
            ],
        ]);
    }
}
