<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Product;
use App\Models\Aparatur;
use App\Models\Category;
use App\Models\LayananTiket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //tanggal hari ini
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month; // Mendapatkan bulan saat ini
        $currentYear = Carbon::now()->year; // Mendapatkan tahun saat ini

        $day = date('d-m-Y');

        //bulan ini
        $moon = date('M, Y');

        //sum perusda today
        $perusdaToday = LayananTiket::whereDate('created_at', $today)
            ->sum('tarif_pemda');

        //sum pnbpToday
        $pnbpToday = LayananTiket::whereDate('created_at', $today)->sum('tarif_pnbp');

        //sum asuransiToday
        $asuransiToday = LayananTiket::whereDate('created_at', $today)->sum('tarif_asuransi');

        //total today
        $totalToday = $perusdaToday + $pnbpToday + $asuransiToday;

        //sum perusdaMoon
        $perusdaMoon = LayananTiket::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->sum('tarif_pemda');

        //sum pnbpMoon
        $pnbpMoon = LayananTiket::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->sum('tarif_pnbp');

        //sum asuransiMoon
        $asuransiMoon = LayananTiket::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->sum('tarif_asuransi');

        //total moon
        $totalMoon = $perusdaMoon + $pnbpMoon + $asuransiMoon;

        //sum perusdaTotal
        $perusdaTotal = LayananTiket::sum('tarif_pemda');

        //sum pnbpdaTotal
        $pnbpTotal = LayananTiket::sum('tarif_pnbp');

        //sum asuransiTotal
        $asuransiTotal = LayananTiket::sum('tarif_asuransi');

        //total semua
        $totalAll = $perusdaTotal + $pnbpTotal + $asuransiTotal;

        //count categories
        $categories = Category::count();

        //count posts
        $posts = Post::count();

        //count products
        $products = Product::count();

        //count aparaturs
        $aparaturs = Aparatur::count();

        //return response json
        return response()->json([
            'success'   => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'categories' => $categories,
                'posts'      => $posts,
                'products'   => $products,
                'aparaturs'  => $aparaturs,

                "today" => $day,
                "moon" => $moon,
                'perusdaToday' => $perusdaToday,
                'pnbpToday' => $pnbpToday,
                'asuransiToday' => $asuransiToday,
                'perusdaMoon' => $perusdaMoon,
                'pnbpMoon' => $pnbpMoon,
                'asuransiMoon' => $asuransiMoon,
                'perusdaTotal' => $perusdaTotal,
                'pnbpTotal' => $pnbpTotal,
                'asuransiTotal' => $asuransiTotal,
                'totalToday' => $totalToday,
                'totalMoon' => $totalMoon,
                'totalAll' => $totalAll,
            ]
        ]);
    }
}
