<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConfigDayResource;
use App\Models\ConfigDay;
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
}
