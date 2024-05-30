<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Page;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;

class PageController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $pages = Page::oldest()->get();

        //return with Api Resource
        return new PageResource(true, 'List Data Pages', $pages);
    }

    /**
     * show
     *
     * @param  mixed $slug
     * @return void
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->first();

        if ($page) {
            //return with Api Resource
            return new PageResource(true, 'Detail Data Page', $page);
        }

        //return with Api Resource
        return new PageResource(false, 'Detail Data Page Tidak Ditemukan!', null);
    }
}
