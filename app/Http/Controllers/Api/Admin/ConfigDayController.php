<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConfigDayResource;
use App\Models\ConfigDay;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigDayController extends Controller
{
    public function index()
    {
        //get configday
        $today = ConfigDay::where('id', 1)->first();

        //return with Api Resource
        return new ConfigDayResource(true, 'Config Day', $today);
    }

    public function show($id)
    {
        $today = date('Y-m-d');
        $dayOfWeek = date('l', strtotime($today)); // Mendapatkan hari dalam bentuk string, misal: 'Saturday'

        // Cek apakah hari ini adalah hari libur berdasarkan tabel Holiday
        $cek_holiday = Holiday::where('tgl_libur', $today)->first();

        // Cek apakah hari ini adalah Sabtu atau Minggu
        $isWeekend = ($dayOfWeek == 'Saturday' || $dayOfWeek == 'Sunday');

        // Jika hari ini adalah hari libur atau hari Sabtu/Minggu
        if ($cek_holiday || $isWeekend) {
            ConfigDay::where('id', 1)->update([
                'status' => 'LIBUR',
            ]);
        } else {
            ConfigDay::where('id', 1)->update([
                'status' => 'BIASA',
            ]);
        }

        $today = ConfigDay::whereId($id)->first();
        $day = date('D');

        if ($today) {
            //return success with Api Resource
            return new ConfigDayResource(true, 'Detail Data Status hari!', $today, $day);
        }

        //return failed with Api Resource
        return new ConfigDayResource(false, 'Detail Data Status Hari Tidak DItemukan!', null);
    }

    public function update(Request $request, ConfigDay $configDay)
    {
        $validator = Validator::make($request->all(), [
            'status'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //update configDay
        $configDay->update([
            'status' => $request->status,
        ]);

        if ($configDay) {
            //return success with Api Resource
            return new ConfigDayResource(true, 'Data config Day Berhasil Diupdate!', $configDay);
        }

        //return failed with Api Resource
        return new ConfigDayResource(false, 'Data config Day Gagal Diupdate!', null);
    }

    public function cekDay($id)
    {

        $dayOfWeek = date('l', strtotime($id)); // Mendapatkan hari dalam bentuk string, misal: 'Saturday'

        // Cek apakah hari ini adalah hari libur berdasarkan tabel Holiday
         $cek_holiday = Holiday::where('tgl_libur', $id)->first();

        // Cek apakah hari ini adalah Sabtu atau Minggu
        $isWeekend = ($dayOfWeek == 'Saturday' || $dayOfWeek == 'Sunday');



         if ($cek_holiday || $isWeekend) {
            $data = [
                'status' => 'LIBUR',
            ];
            //return success with Api Resource
            return new ConfigDayResource(true, 'Hari ini libur', $data);
        }
        $data = [
            'status' => 'BIASA',
        ];

        //return failed with Api Resource
        return new ConfigDayResource(false, 'Bukan Hari Libur!', $data);
    }
}
