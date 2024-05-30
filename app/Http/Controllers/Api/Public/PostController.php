<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $posts = Post::with('user', 'category')->latest()->paginate(10);

        //return with Api Resource
        return new PostResource(true, 'List Data Posts', $posts);
    }

    /**
     * show
     *
     * @param  mixed $slug
     * @return void
     */
    public function show($slug)
    {
        $post = Post::with('user', 'category')->where('slug', $slug)->first();

        if ($post) {
            //return with Api Resource
            return new PostResource(true, 'Detail Data Post', $post);
        }

        //return with Api Resource
        return new PostResource(false, 'Detail Data Post Tidak Ditemukan!', null);
    }

    /**
     * homePage
     *
     * @return void
     */
    public function homePage()
    {
        $posts = Post::with('user', 'category')->latest()->take(6)->get();

        //return with Api Resource
        return new PostResource(true, 'List Data Post HomePage', $posts);
    }
}
