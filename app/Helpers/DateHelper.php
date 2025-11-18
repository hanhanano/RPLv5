<?php

use Carbon\Carbon;

if (! function_exists('getQuarter')) {
    function getQuarter($date) {
        if (!$date) return null;
        $month = Carbon::parse($date)->month;

        if ($month >= 1 && $month <= 3) return 1;
        if ($month >= 4 && $month <= 6) return 2;
        if ($month >= 7 && $month <= 9) return 3;
        return 4;
    }
}

// Cek apakah realisasi tepat waktu
if (! function_exists('isOnTime')) {
    function isOnTime($planQuarter, $actualQuarter) {
        if (!$planQuarter || !$actualQuarter) return null;
        return $actualQuarter <= $planQuarter;
    }
}

// Hitung selisih triwulan
if (! function_exists('getDelayQuarters')) {
    function getDelayQuarters($planQuarter, $actualQuarter) {
        if (!$planQuarter || !$actualQuarter) return 0;
        return max(0, $actualQuarter - $planQuarter);
    }
}