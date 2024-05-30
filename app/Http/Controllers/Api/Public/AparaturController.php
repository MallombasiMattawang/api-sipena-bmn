<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Aparatur;
use App\Http\Controllers\Controller;
use App\Http\Resources\AparaturResource;

class AparaturController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $aparaturs = Aparatur::oldest()->get();

        //return with Api Resource
        return new AparaturResource(true, 'List Data Aparaturs', $aparaturs);
    }
}
