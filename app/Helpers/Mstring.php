<?php

namespace App\Helpers;

class Mstring
{
    public static function penjumlahan($angka_1, $angka_2)
    {
        $hasil = $angka_1 + $angka_2;
        return $hasil;
    }

    public function strip_only_tags($str, $stripped_tags = null)
    {
        // Tidak ada tag yang dihapus
        if ($stripped_tags == null) {
            return $str;
        }
        // Dapatkan daftar tag
        // Misal: <b><i><u> menjadi array('b','i','u')
        $tags = explode('>', str_replace('<', '', $stripped_tags));
        $result = preg_replace('#</?(' . implode('|', $tags) . ').*?>#is', '', $str);
        return $result;
    }

    public function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}
