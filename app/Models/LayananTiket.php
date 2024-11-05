<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LayananTiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'layanan_id',
        'user_id',
        'kode_booking',
        'tgl_booking',
        'barcode',
        'tarif_pnbp',
        'tarif_pemda',
        'tarif_asuransi',
        'tarif_total',
        'jumlah',
        'lunas',
        'bukti_tf',
        'is_active',
        'status'

    ];

    public static function generateTicketNumber()
    {
        $date = Carbon::now()->format('Ymd'); // Format tanggal: 20230601
        $lastTicket = self::whereDate('created_at', Carbon::today())->whereNotNull('barcode')->orderBy('id', 'desc')->first();

        if (!$lastTicket) {
            $number = 1;
        } else {
            $lastNumber = (int)substr($lastTicket->barcode, -3);
            $number = $lastNumber + 1;
        }

        return $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public static function generateBookingNumber()
    {
        $kode_book = 'B';
        $date = Carbon::now()->format('ymd'); // Format tanggal: 20230601
        $lastTicket = self::whereDate('created_at', Carbon::today())->whereNull('barcode')->orderBy('id', 'desc')->first();

        if (!$lastTicket) {
            $number = 1;
        } else {
            $lastNumber = (int)substr($lastTicket->kode_booking, -3);
            $number = $lastNumber + 1;
        }

        return $kode_book . '' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    protected function buktiTf(): Attribute
    {
        return Attribute::make(
            get: fn ($bukti_tf) => url('/storage/bukti_bayar/' . $bukti_tf),
        );
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
