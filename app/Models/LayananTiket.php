<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LayananTiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'layanan_id',
        'barcode',
        'tarif_pnbp',
        'tarif_pemda',
        'tarif_asuransi',
        'tarif_total',
        'jumlah',
        'lunas',
        'bukti_tf',
        'is_active',
    ];

    public static function generateTicketNumber()
    {
        $date = Carbon::now()->format('Ymd'); // Format tanggal: 20230601
        $lastTicket = self::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();

        if (!$lastTicket) {
            $number = 1;
        } else {
            $lastNumber = (int)substr($lastTicket->barcode, -3);
            $number = $lastNumber + 1;
        }

        return $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
