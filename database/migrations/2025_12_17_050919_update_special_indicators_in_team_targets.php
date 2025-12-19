<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('team_targets')
            ->whereIn('report_name', [
                'Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar',
                'Indeks Pelayanan Publik - Penilaian Mandiri',
                'Nilai SAKIP oleh Inspektorat',
                'Indeks Implementasi BerAKHLAK',
            ])
            ->update(['is_special_indicator' => 1]);
    }

    public function down()
    {
        DB::table('team_targets')
            ->update(['is_special_indicator' => 0]);
    }
};