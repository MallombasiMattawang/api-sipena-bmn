<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Carbon\Carbon; // Include Class in COntroller

function getUserId($id)
{
    $user = User::find($id);
    return $user;
}

function wa($nomor)
{
    if (substr($nomor, 0, 1) === "0") {
        $nomor = "62" . substr($nomor, 1);
    }
    return $nomor;
}

function hitungPersentase($nilai, $total) {
    if ($total != 0) {
        $persentase = ($nilai / $total) * 100;
        return $persentase;
    } else {
        return 0; // Menghindari pembagian oleh nol
    }
}

if (!function_exists('tgl_waktu')) {
    function tgl_waktu($tgl)
    {
        if ($tgl == "0000-00-00") {
            return "-";
        } else {
            $tanggal    = substr($tgl, 8, 2);
            $bulan      = get_bulan(substr($tgl, 5, 2));
            $tahun      = substr($tgl, 0, 4);
            $jam        = substr($tgl, 11, 2);
            $menit      = substr($tgl, 14, 2);

            $format_waktu = date("h:i A", strtotime("$jam:$menit"));

            return ''.$tanggal . ' ' . $bulan . ' ' . $tahun . ', ' . $format_waktu;
        }
    }

}
if (!function_exists('tgl_waktu_front')) {
    function tgl_waktu_front($tgl)
    {
        if ($tgl == "0000-00-00") {
            return "-";
        } else {
            $tanggal    = substr($tgl, 8, 2);
            $bulan      = substr($tgl, 0, 2);
            $tahun      = substr($tgl, 0, 4);
            $jam        = substr($tgl, 11, 2);
            $menit      = substr($tgl, 14, 2);

            $format_waktu = date("h:i A", strtotime("$jam:$menit"));

            return ''.$tanggal . '-' . $bulan . '-' . $tahun . ' ' . $format_waktu;
        }
    }

}

function getAvatar($id)
{
    $avaName = getUserId($id);
    if (!empty($avaName->foto)) {
        $ava = $avaName->foto;
    } else {
        $ava = 'ava.png';
    }

    //return asset('uploads/avatar/' . $ava . '');
    return asset('uploads/' . $ava . '');
}

function getTahun()
{
    return [
        '2022',
        '2023',
        '2024',
        '2025',
    ];
}

function sex()
{
    return [
        'L' => 'Laki-Laki',
        'P' => 'Perempuan',
    ];
}

function pangkat()
{
    return ['I/a', 'I/b', 'I/c', 'I/d', 'II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'];
}

function statusPegawai()
{
    return ['PNS', 'PPPK', 'PNS Depag', 'CPNS', 'GTY/PTY', 'Guru Honor Sekolah', 'Honor Daerah TK.I Provinsi', 'Honor Daerah TK.II Kab/Kota', 'Tenaga Honor Sekolah', 'Guru Bantu Pusat', 'PNS Diperbantukan'];
}

function getJabatanStruktural()
{
    return [
        'BERITA',
        'AGENDA',
        'PENGUMUMAN',
    ];
}

if (!function_exists('selisih_hari')) {
    function selisih_hari($tgl_1, $tgl_2)
    {
        $tgl1 = strtotime($tgl_1);
        $tgl2 = strtotime($tgl_2);

        $jarak = $tgl2 - $tgl1;

        $hari = $jarak / 60 / 60 / 24;

        return $hari . ' Hari';
    }
}
function formatNumber($number)
{
    if ($number >= 1000000) {
        // ubah angka dalam jutaan menjadi M
        $formatted = number_format($number / 1000000, 1) . ' M';
    } elseif ($number >= 1000) {
        // ubah angka dalam ribuan menjadi K
        $formatted = number_format($number / 1000, 1) . ' K';
    } else {
        $formatted = $number;
    }

    return $formatted;
}

if (!function_exists('limit_text')) {
    function limit_text($text, $limit = 100)
    {
        if (mb_strlen($text) > $limit) {
            $text = mb_substr($text, 0, $limit) . '...';
        }
        return $text;
    }
}


function formatBytes($size, $precision = 0)
{
    $unit = ['Byte', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];

    for ($i = 0; $size >= 1024 && $i < count($unit) - 1; $i++) {
        $size /= 1024;
    }

    return round($size, $precision) . ' ' . $unit[$i];
    // echo formatBytes('1876144', 2);
}




function urlEmbedYt($url)
{

    parse_str(parse_url($url, PHP_URL_QUERY), $youtube_id_v);
    if (!empty($youtube_id_v['v'])) {
        $linkEmbed = "https://www.youtube.com/embed/" . $youtube_id_v['v'] . "";
        return $linkEmbed;
    }
}


function age($bornDate)
{
    $dateYear = substr($bornDate, 0, 4);
    $dateMonth = substr($bornDate, 5, 2);
    $dateDay = substr($bornDate, 8, 2);


    $age = Carbon::parse($bornDate)->diff(Carbon::now())->y;
    return $age . " Tahun";

    // $Born = Carbon::create($dateYear, $dateMonth, $dateDay);
    // $Age = $Born->diff(Carbon::now())->format('%y Tahun %m Bulan %d Hari');
    // return $Age;

}


if (!function_exists('waktu')) {
    function waktu($wkt)
    {
        $jam = substr($wkt, 0, 2);
        $menit = substr($wkt, 3, 2);
        if ($jam < 12) {
            $AMPM = "AM";
            if ($jam == 0) $jam = 12;
        } else {
            $AMPM = "PM";
            if ($jam != 12) $jam = $jam - 12;
        }

        return $jam . ':' . $menit . ' ' . $AMPM;
    }
}

if (!function_exists('tgl_indo')) {
    function tgl_indo($tgl)
    {
        if ($tgl == "0000-00-00") {
            return "-";
        } else {
            $tanggal    = substr($tgl, 8, 2);
            $bulan      = get_bulan(substr($tgl, 5, 2));
            $tahun      = substr($tgl, 0, 4);
            // var_dump($tgl);
            return $tanggal . ' ' . $bulan . ' ' . $tahun;
        }
    }
}

if (!function_exists('tgl_no_tahun')) {
    function tgl_no_tahun($tgl)
    {
        if ($tgl == "0000-00-00") {
            return "-";
        } else {
            $tanggal    = substr($tgl, 8, 2);
            $bulan      = get_bulan(substr($tgl, 5, 2));
            $tahun      = substr($tgl, 0, 4);
            // var_dump($tgl);
            return $tanggal . ' ' . $bulan;
        }
    }
}

if (!function_exists('format_tgl')) {
    function format_tgl($tgl, $indo = FALSE)
    {
        if (substr($tgl, 2, 1) == '-') { // ini format dd-mm-yyyy
            $tanggal = substr($tgl, 0, 2);
            $bulan = substr($tgl, 3, 2);
            $tahun = substr($tgl, 6, 4);
            return $tahun . '-' . $bulan . '-' . $tanggal;
        } else if (substr($tgl, 4, 1) == '-') { // ini format yyyy-mm-dd
            $tahun = substr($tgl, 0, 4);
            $bulan = substr($tgl, 5, 2);
            $tanggal = substr($tgl, 8, 2);
            return $indo ? $tanggal . ' ' . get_bulan($bulan) . ' ' . $tahun : $tanggal . '-' . $bulan . '-' . $tahun;
        }
    }
}


if (!function_exists('list_hari')) {
    function list_hari()
    {
        $hari = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];

        return $hari;
    }
}



if (!function_exists('get_hari')) {
    function get_hari($day)
    {
        switch ($day) {
            case 0:
                return "Ahad";
                break;
            case 1:
                return "Senin";
                break;
            case 2:
                return "Selasa";
                break;
            case 3:
                return "Rabu";
                break;
            case 4:
                return "Kamis";
                break;
            case 5:
                return "Jumat";
                break;
            case 6:
                return "Sabtu";
                break;
        }
    }
}

if (!function_exists('get_bulan')) {
    function get_bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
}
// Fungsi untuk mengonversi angka menjadi angka Romawi
function convertToRoman($number)
{
    $map = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];

    return $map[$number];
}
if (!function_exists('number_to_roman')) {
    function number_to_roman($num)
    {
        $n = intval($num);
        $res = '';

        /*** roman_numerals array  ***/
        $roman_numerals = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1
        );

        foreach ($roman_numerals as $roman => $number) {
            /*** divide to get  matches ***/
            $matches = intval($n / $number);

            /*** assign the roman char * $matches ***/
            $res .= str_repeat($roman, $matches);

            /*** substract from the number ***/
            $n = $n % $number;
        }

        /*** return the res ***/
        return $res;
    }
}

if (!function_exists('cari_query')) {
    function cari_query($q, $data)
    {
        $builder = "IFNULL($data[0], '') LIKE CONCAT('%', '$q', '%')";
        for ($i = 1; $i < sizeof($data); $i++) {
            $builder .= " OR IFNULL($data[$i], '') LIKE CONCAT('%', '$q', '%')";
        }
        return $builder;
    }
}

if (!function_exists('do_hash')) {
    function do_hash($data, $type = 'bcrypt')
    {
        if ($type == 'bcrypt') return password_hash($data, PASSWORD_BCRYPT);
        else if ($type == 'md5') return md5(md5($data . md5($data)));
    }
}

if (!function_exists('compare_hash')) {
    function compare_hash($data1, $data2, $sama)
    {
        if ($sama) $stat = ($data1 === $data2) ? TRUE : FALSE;
        else $stat = password_verify($data1, $data2);
        return $stat;
    }
}


if (!function_exists('multiselect_tostring')) {
    function multiselect_tostring($string)
    {
        if (!is_array($string)) {
            if (($string == "") or ($string == null))
                return "";
            else
                return $string;
        } else {
            if (count($string) > 0) {
                // return TRUE;
                $string = implode(",", $string);
                return $string;
            } else {
                return "";
            }
        }
    }
}
if (!function_exists('is_array_empty')) {
    function is_array_empty($arr)
    {
        if (is_array($arr)) {
            foreach ($arr as $value) {
                if (!empty($value)) {
                    return false;
                }
            }
        }
        return true;
    }
}
if (!function_exists('flatten_json')) {
    function flatten_json(array $array)
    {
        $output = array();
        foreach ($array as $v) {
            $output[$v['id']] = $v['nama'];
        }
        return $output;
    }
}



if (!function_exists('rupiah')) {
    function rupiah($angka, $kurs = '')
    {
        if ($angka === '') {
            $angka = 0;
        }

        if ($kurs) {
            return $kurs . ' ' . number_format($angka);
        } else {
            return 'Rp' . number_format($angka, 0, ',', '.') . ',-';
        }
    }
}

if (!function_exists('debug')) {
    function debug()
    {
        $numArgs = func_num_args();

        echo 'Total Arguments:' . $numArgs . "\n";

        $args = func_get_args();
        echo '<pre>';
        foreach ($args as $index => $arg) {
            echo 'Argument ke-' . $index . ':' . "\n";
            var_dump($arg);
            echo "\n";
            unset($args[$index]);
        }
        echo '</pre>';
        die();
    }
}

if (!function_exists('daterange_filter')) {
    function daterange_filter($date_range = array(), $pre = '')
    {
        $start = '';
        $end = '';
        if (empty($date_range))
            return false;

        if (($date_range['start'] === '' && $date_range['end'] !== '')) {
            $end = $pre . ' <= "' . $date_range['end'] . '"';
        } else if (($date_range['start'] !== '' && $date_range['end'] === '')) {
            $start = $pre . ' >= "' . $date_range['start'] . '"';
        } else if ($date_range['start'] === date("Y-m-d", strtotime('tomorrow')) && $date_range['end'] === date("Y-m-d", strtotime('tomorrow'))) {
            $end = $pre . ' <= "' . $date_range['end'] . '"';
        } else {
            if ($date_range['start'] === '' && $date_range['end'] === '') {
                $date_range['start'] = date("Y-m-d") . '  00:00:00';
                $date_range['end'] = date("Y-m-d") . ' 23:59:59';
            }
            $start =  $pre . ' >= "' . $date_range['start'] . '"' . ' AND ';
            $end = $pre . ' <= "' . $date_range['end'] . '"';
        }

        $where = $start . $end;
        return $where;
    }
}


if (!function_exists('secToTimes')) {
    function secToTimes($secs)
    {
        $t = round($secs);
        $jam = $t / 3600;
        $menit = $t / 60 % 60;
        $detik = $t % 60;
        if ($detik > 0) {
            return sprintf('%02d Jam %02d Menit %02d Detik', $jam, $menit, $detik);
        } else {
            return sprintf('%02d Jam %02d Menit', $jam, $menit);
        }
    }
}

function middlewareCheck($roles)
{
    $list = new Collection($roles);
    $roleActive = activeGuard();
    if (!$list->contains($roleActive)) {
        return abort(403, 'Oops, you cant access this page. more information contact admin. Thanks :) ');
    }
}

function activeGuard()
{
    foreach (array_keys(config('auth.guards')) as $guard) {
        if (
            auth()
            ->guard($guard)
            ->check()
        ) {
            return $guard;
        }
    }
    return null;
}



function upload($destination, $fileUpload, $fileName)
{
    // return $fileUpload->storeAs($destination, $fileName);
    return $fileUpload->move($destination, $fileName);
}

function singkat_angka($n, $presisi = 1)
{
    if ($n < 900) {
        $format_angka = number_format($n, $presisi);
        $simbol = '';
    } else if ($n < 900000) {
        $format_angka = number_format($n, $presisi);
        $simbol = '';
    } else if ($n < 900000000) {
        $format_angka = number_format($n / 1000000, $presisi);
        $simbol = 'jt';
    } else if ($n < 900000000000) {
        $format_angka = number_format($n / 1000000000, $presisi);
        $simbol = 'M';
    } else {
        $format_angka = number_format($n / 1000000000000, $presisi);
        $simbol = 'T';
    }

    if ($presisi > 0) {
        $pisah = '.' . str_repeat('0', $presisi);
        $format_angka = str_replace($pisah, ' ', $format_angka);
    }

    return $format_angka . $simbol;
    //return $n;
}
function separator($output)
{
    $format_angka = number_format(floatval($output), 0, '.', '.');
    return $format_angka;
}

function getYoutubeThumbnailUrl($videoId, $thumbnailType = 'default')
{
    $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/{$thumbnailType}.jpg";
    return $thumbnailUrl;
}
