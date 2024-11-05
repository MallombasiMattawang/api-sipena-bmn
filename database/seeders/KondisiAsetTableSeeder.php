<?php

namespace Database\Seeders;

use App\Models\KondisiAset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KondisiAsetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KondisiAset::insert([
            [
                'nama_kondisi' => 'baik',
            ],
            [
                'nama_kondisi' => 'rusak ringan',
            ],
            [
                'nama_kondisi' => 'rusak sedang',
            ],
            [
                'nama_kondisi' => 'rusak berat',
            ],
            [
                'nama_kondisi' => 'Hilang',
            ],
            [
                'nama_kondisi' => 'butuh penggantian',
            ],
            [
                'nama_kondisi' => 'dalam perbaikan',
            ],
        ]);
    }
}
