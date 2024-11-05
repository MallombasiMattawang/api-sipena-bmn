<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function index()
    {
        //get holidays
        $holidays = Holiday::when(request()->search, function ($holidays) {
            $holidays = $holidays->where('tgl_libur', 'like', '%' . request()->search . '%');
        })->latest()->paginate(25);

        //append query string to pagination links
        $holidays->appends(['search' => request()->search]);

        //return with Api Resource
        return new HolidayResource(true, 'List Data Holiday', $holidays);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_libur'      => 'required|date',
            'ket'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create holiday
        $holiday = Holiday::create([
            'tgl_libur' => $request->tgl_libur,
            'ket' => $request->ket,
        ]);

        if ($holiday) {
            //return success with Api Resource
            return new HolidayResource(true, 'Data Holiday Berhasil Disimpan!', $holiday);
        }

        //return failed with Api Resource
        return new HolidayResource(false, 'Data Holiday Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $holiday = Holiday::whereId($id)->first();

        if ($holiday) {
            //return success with Api Resource
            return new HolidayResource(true, 'Detail Data LHoliday', $holiday);
        }

        //return failed with Api Resource
        return new HolidayResource(false, 'Detail Data Holiday Tidak DItemukan!', null);
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validator = Validator::make($request->all(), [
            'tgl_libur'     => 'required',
            'ket'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $holiday->update([
            'tgl_libur'  => $request->tgl_libur,
            'ket' => $request->ket,
        ]);

        if ($holiday) {
            //return success with Api Resource
            return new HolidayResource(true, 'Data HolidayBerhasil Diupdate!', $holiday);
        }

        //return failed with Api Resource
        return new HolidayResource(false, 'Data Holiday Gagal Diupdate!', null);
    }

    public function destroy(Holiday $holiday)
    {

        if ($holiday->delete()) {
            //return success with Api Resource
            return new HolidayResource(true, 'Data Holiday Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new HolidayResource(false, 'Data Holiday Gagal Dihapus!', null);
    }
}
