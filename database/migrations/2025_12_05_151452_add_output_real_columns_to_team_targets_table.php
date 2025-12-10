<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('team_targets', function (Blueprint $table) {
            // Menambahkan 4 kolom baru setelah kolom output_real (total)
            // Kita beri default 0 agar data lama tidak error/null
            $table->integer('output_real_q1')->default(0)->after('output_real');
            $table->integer('output_real_q2')->default(0)->after('output_real_q1');
            $table->integer('output_real_q3')->default(0)->after('output_real_q2');
            $table->integer('output_real_q4')->default(0)->after('output_real_q3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_targets', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn([
                'output_real_q1',
                'output_real_q2',
                'output_real_q3',
                'output_real_q4'
            ]);
        });
    }
};