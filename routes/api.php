<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//route login
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'index']);

//group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function () {

    //logout
    Route::post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        //dashboard
        Route::get('/dashboard', App\Http\Controllers\Api\Admin\DashboardController::class);

        //permissions
        Route::get('/permissions', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'index'])
            ->middleware('permission:permissions.index');

        //permissions all
        Route::get('/permissions/all', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'all'])
            ->middleware('permission:permissions.index');

        //roles all
        Route::get('/roles/all', [\App\Http\Controllers\Api\Admin\RoleController::class, 'all'])
            ->middleware('permission:roles.index');

        //roles
        Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class)
            ->middleware('permission:roles.index|roles.store|roles.update|roles.delete');

        //users
        Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class)
            ->middleware('permission:users.index|users.store|users.update|users.delete');

        //categories all
        Route::get('/categories/all', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'all'])
            ->middleware('permission:categories.index');

        //Categories
        Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class)
            ->middleware('permission:categories.index|categories.store|categories.update|categories.delete');

        //Posts
        Route::apiResource('/posts', App\Http\Controllers\Api\Admin\PostController::class)
            ->middleware('permission:posts.index|posts.store|posts.update|posts.delete');

        //Products
        Route::apiResource('/products', App\Http\Controllers\Api\Admin\ProductController::class)
            ->middleware('permission:products.index|products.store|products.update|products.delete');

        //Pages
        Route::apiResource('/pages', App\Http\Controllers\Api\Admin\PageController::class)
            ->middleware('permission:pages.index|pages.store|pages.update|pages.delete');

        //Photos
        Route::apiResource('/photos', App\Http\Controllers\Api\Admin\PhotoController::class, ['except' => ['create', 'show', 'update']])
            ->middleware('permission:photos.index|photos.store|photos.delete');

        //Sliders
        Route::apiResource('/sliders', App\Http\Controllers\Api\Admin\SliderController::class, ['except' => ['create', 'show', 'update']])
            ->middleware('permission:sliders.index|sliders.store|sliders.delete');

        //Aparaturs
        Route::apiResource('/aparaturs', App\Http\Controllers\Api\Admin\AparaturController::class)
            ->middleware('permission:aparaturs.index|aparaturs.store|aparaturs.update|aparaturs.delete');
    });
});

//group route with prefix "public"
Route::prefix('public')->group(function () {

    //index posts
    Route::get('/posts', [App\Http\Controllers\Api\Public\PostController::class, 'index']);

    //show posts
    Route::get('/posts/{slug}', [App\Http\Controllers\Api\Public\PostController::class, 'show']);

    //index posts home
    Route::get('/posts_home', [App\Http\Controllers\Api\Public\PostController::class, 'homePage']);

    //index products
    Route::get('/products', [App\Http\Controllers\Api\Public\ProductController::class, 'index']);

    //show page
    Route::get('/products/{slug}', [App\Http\Controllers\Api\Public\ProductController::class, 'show']);

    //index products home
    Route::get('/products_home', [App\Http\Controllers\Api\Public\ProductController::class, 'homePage']);

    //index pages
    Route::get('/pages', [App\Http\Controllers\Api\Public\PageController::class, 'index']);

    //show page
    Route::get('/pages/{slug}', [App\Http\Controllers\Api\Public\PageController::class, 'show']);

    //index aparaturs
    Route::get('/aparaturs', [App\Http\Controllers\Api\Public\AparaturController::class, 'index']);

    //index photos
    Route::get('/photos', [App\Http\Controllers\Api\Public\PhotoController::class, 'index']);

    //index sliders
    Route::get('/sliders', [App\Http\Controllers\Api\Public\SliderController::class, 'index']);
});
