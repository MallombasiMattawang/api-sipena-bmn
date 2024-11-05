<?php

namespace Database\Seeders;

use App\Models\StatusAset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusAsetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusAset::insert([
            [
                'nama_status' => 'tersedia',
            ],
            [
                'nama_status' => 'dipinjam',
            ],
            [
                'nama_status' => 'tidak tersedia',
            ],
            [
                'nama_status' => 'diperbaiki',
            ],
            [
                'nama_status' => 'dihapus',
            ],
            [
                'nama_status' => 'direncanakan untuk penghapusan',
            ],
            [
                'nama_status' => 'digunakan',
            ],
            [
                'nama_status' => 'dimusnahkan',
            ],
            [
                'nama_status' => 'dipindahtangankan',
            ],
            
        ]);
    }
}
