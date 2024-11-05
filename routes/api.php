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

//route register
Route::post('/register', [App\Http\Controllers\Api\Auth\RegisterController::class, 'register']);

//scan barcode
// Route::post('/scan', [App\Http\Controllers\Api\Public\ScanBarcodeController::class, 'index']);

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

        //config Day
        Route::apiResource('/config-day', App\Http\Controllers\Api\Admin\ConfigDayController::class)
            ->middleware('permission:layanans.index');

        //cek Day
        Route::get('/cek-day/{date}', [\App\Http\Controllers\Api\Admin\ConfigDayController::class, 'cekDay'])
            ->middleware('permission:layanans.index');

        //Holidays
        Route::apiResource('/holidays', App\Http\Controllers\Api\Admin\HolidayController::class)
            ->middleware('permission:layanans.index');

        //Create Tiket
        Route::apiResource('/tiket', App\Http\Controllers\Api\Admin\LayananTiketController::class)
            ->middleware('permission:layanans.index');

        //Ticket Booking
        Route::get('/tiket-booking', [\App\Http\Controllers\Api\Admin\LayananTiketController::class, 'booking'])
            ->middleware('permission:layanans.index');

        //layanans all
        Route::get('/layanans/all', [\App\Http\Controllers\Api\Admin\LayananController::class, 'all'])
            ->middleware('permission:layanans.index');

        //Layanans
        Route::apiResource('/layanans', App\Http\Controllers\Api\Admin\LayananController::class)
            ->middleware('permission:layanans.index|layanans.store|layanans.update|layanans.delete');

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

        // Route untuk index dan store (tanpa parameter ID)
        Route::get('/asets', [App\Http\Controllers\Api\Admin\AsetController::class, 'index'])
            ->middleware('permission:asets.index');
            Route::get('/asets-filter/{kategori?}/{kondisi?}/{status?}/{lokasi?}', [App\Http\Controllers\Api\Admin\AsetController::class, 'filter']);
        Route::post('/asets', [App\Http\Controllers\Api\Admin\AsetController::class, 'store']);
            // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/asets/{id}', [App\Http\Controllers\Api\Admin\AsetController::class, 'show']);
            // ->middleware('permission:asets.show');
        Route::put('/asets/{id}', [App\Http\Controllers\Api\Admin\AsetController::class, 'update']);
            // ->middleware('permission:asets.update');
        Route::delete('/asets/{id}', [App\Http\Controllers\Api\Admin\AsetController::class, 'destroy']);
            // ->middleware('permission:asets.delete');

        // Route kategori aset untuk index dan store (tanpa parameter ID)
        Route::get('/kategori-asets', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'index']);
        // ->middleware('permission:asets.index');
        Route::post('/kategori-asets', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'store']);
        // ->middleware('permission:asets.store');
        // Route kategori aset selectbox
        Route::get('/kategori-asets/all', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'all']);

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/kategori-asets/{id}', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/kategori-asets/{id}', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/kategori-asets/{id}', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');    

        // Route kondisi aset untuk index dan store (tanpa parameter ID)
        Route::get('/kondisi-asets', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'index']);
        Route::get('/kondisi-asets/all', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'all']);
        // ->middleware('permission:asets.index');
        Route::post('/kondisi-asets', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/kondisi-asets/{id}', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/kondisi-asets/{id}', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/kondisi-asets/{id}', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');        

        // Route status aset untuk index dan store (tanpa parameter ID)
        Route::get('/status-asets', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'index']);
        Route::get('/status-asets/all', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'all']);
        // ->middleware('permission:asets.index');
        Route::post('/status-asets', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/status-asets/{id}', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/status-asets/{id}', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/status-asets/{id}', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');  

        // Route lokasi aset untuk index dan store (tanpa parameter ID)
        Route::get('/lokasi-asets', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'index']);
        Route::get('/lokasi-asets/all', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'all']);
        // ->middleware('permission:asets.index');
        Route::post('/lokasi-asets', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/lokasi-asets/{id}', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/lokasi-asets/{id}', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/lokasi-asets/{id}', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');   

         // Route masa aset untuk index dan store (tanpa parameter ID)
         Route::get('/masa-asets', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'index']);
         Route::get('/masa-asets/all', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'all']);
         // ->middleware('permission:asets.index');
         Route::post('/masa-asets', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'store']);
         // ->middleware('permission:asets.store');
 
         // Route untuk show, update, dan destroy (dengan parameter ID)
         Route::get('/masa-asets/{id}', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'show']);
         // ->middleware('permission:asets.show');
         Route::put('/masa-asets/{id}', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'update']);
         // ->middleware('permission:asets.update');
         Route::delete('/masa-asets/{id}', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'destroy']);
         // ->middleware('permission:asets.delete');  
        
        // Route inspeksi aset untuk index dan store (tanpa parameter ID)
        Route::get('/inspeksi-asets', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'index']);
        Route::get('/inspeksi-asets/all', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'all']);
        Route::get('/inspeksi-asets-view/{tanggal_inspeksi}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'view']);
        Route::get('/inspeksi-asets-pdf/{tanggal_inspeksi}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'generatePdf']);
        // ->middleware('permission:asets.index');
        Route::post('/inspeksi-asets', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/inspeksi-asets/{id}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/inspeksi-asets/{id}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/inspeksi-asets/{id}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');   
        
          
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

    //index scan aset
    Route::get('/scan-aset/{kode_aset}', [App\Http\Controllers\Api\Public\ScanBarcodeAsetController::class, 'index']);
    //list lokasi
    Route::get('/scan-aset-lokasi', [App\Http\Controllers\Api\Public\ScanBarcodeAsetController::class, 'lokasi']);
    //list kategori
    Route::get('/scan-aset-kategori', [App\Http\Controllers\Api\Public\ScanBarcodeAsetController::class, 'kategori']);
    //list kondisi
    Route::get('/scan-aset-kondisi', [App\Http\Controllers\Api\Public\ScanBarcodeAsetController::class, 'kondisi']);
    //list status
    Route::get('/scan-aset-status', [App\Http\Controllers\Api\Public\ScanBarcodeAsetController::class, 'status']);
    //inspeksi aset
    Route::post('/inspeksi-asets/{id}', [App\Http\Controllers\Api\Public\ScanBarcodeAsetController::class, 'inspeksiUpdate']);

    //index scan lokasi
    Route::get('/scan-lokasi/{kode_lokasi}', [App\Http\Controllers\Api\Public\ScanBarcodeLokasiController::class, 'index']);
    //index scan aset bylokasi 
    Route::get('/scan-lokasi-aset/{kode_lokasi}', [App\Http\Controllers\Api\Public\ScanBarcodeLokasiController::class, 'aset']);
    //index scan isnpeksi by petugas
    Route::get('/inspeksi-petugas/{petugas}', [App\Http\Controllers\Api\Public\InspeksiPetugasController::class, 'index']);
    //index scan aset by tanggal inspeksi
    Route::get('/inspeksi-aset/{tanggal_inspeksi}', [App\Http\Controllers\Api\Public\InspeksiPetugasController::class, 'view']);

    Route::get('/inspeksi-asets-pdf/{tanggal_inspeksi}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'generatePdf']);

    Route::get('/barcode-aset-pdf/{kode_aset}', [App\Http\Controllers\Api\Public\CetakBarcodeController::class, 'barcodeAset']);

    Route::get('/barcode-lokasi-aset-pdf/{kode_lokasi}', [App\Http\Controllers\Api\Public\CetakBarcodeController::class, 'barcodeLokasiAset']);
});
