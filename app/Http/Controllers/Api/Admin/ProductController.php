<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get products
        $products = Product::when(request()->search, function ($products) {
            $products = $products->where('title', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $products->appends(['search' => request()->search]);

        //return with Api Resource
        return new ProductResource(true, 'List Data Products', $products);
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
            'image'    => 'required|mimes:jpeg,jpg,png|max:2000',
            'title'    => 'required',
            'content'  => 'required',
            'owner'    => 'required',
            'price'    => 'required',
            'address'  => 'required',
            'phone'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create Product
        $product = Product::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'slug'  => Str::slug($request->title, '-'),
            'content' => $request->content,
            'owner' => $request->owner,
            'price' => $request->price,
            'address' => $request->address,
            'phone' => $request->phone,
            'user_id'     => auth()->guard('api')->user()->id,
        ]);

        if ($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Disimpan!', $product);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::whereId($id)->first();

        if ($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Detail Data Product!', $product);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Detail Data Product Tidak DItemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'content'  => 'required',
            'owner'    => 'required',
            'price'    => 'required',
            'address'  => 'required',
            'phone'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/products/' . basename($product->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            //update Product with new image
            $product->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'slug'  => Str::slug($request->title, '-'),
                'content' => $request->content,
                'owner' => $request->owner,
                'price' => $request->price,
                'address' => $request->address,
                'phone' => $request->phone,
                'user_id'     => auth()->guard('api')->user()->id,
            ]);
        }

        //update Product without image
        $product->update([
            'title' => $request->title,
            'slug'  => Str::slug($request->title, '-'),
            'content' => $request->content,
            'owner' => $request->owner,
            'price' => $request->price,
            'address' => $request->address,
            'phone' => $request->phone,
            'user_id'     => auth()->guard('api')->user()->id,
        ]);

        if ($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Diupdate!', $product);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //remove image
        Storage::disk('local')->delete('public/products/' . basename($product->image));

        if ($product->delete()) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Dihapus!', null);
    }
}
