<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get pages
        $pages = Page::when(request()->search, function ($pages) {
            $pages = $pages->where('title', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $pages->appends(['search' => request()->search]);

        //return with Api Resource
        return new PageResource(true, 'List Data Pages', $pages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create page
        $page = Page::create([
            'title'     => $request->title,
            'slug'      => Str::slug($request->title),
            'content'   => $request->content,
            'user_id'   => auth()->guard('api')->user()->id
        ]);

        if ($page) {
            //return success with Api Resource
            return new PageResource(true, 'Data Page Berhasil Disimpan!', $page);
        }

        //return failed with Api Resource
        return new PageResource(false, 'Data Page Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::whereId($id)->first();

        if ($page) {
            //return success with Api Resource
            return new PageResource(true, 'Detail Data Page!', $page);
        }

        //return failed with Api Resource
        return new PageResource(false, 'Detail Data Page Tidak DItemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //update page
        $page->update([
            'title'     => $request->title,
            'slug'      => Str::slug($request->title),
            'content'   => $request->content,
            'user_id'   => auth()->guard('api')->user()->id
        ]);

        if ($page) {
            //return success with Api Resource
            return new PageResource(true, 'Data Page Berhasil Diupdate!', $page);
        }

        //return failed with Api Resource
        return new PageResource(false, 'Data Page Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        if ($page->delete()) {
            //return success with Api Resource
            return new PageResource(true, 'Data Page Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PageResource(false, 'Data Page Gagal Dihapus!', null);
    }
}
