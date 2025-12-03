<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('team_targets', function (Blueprint $table) {
            $table->id();
            $table->string('team_name'); // Nama Tim (Sosial, Neraca, dll)
            $table->string('activity_name'); // Nama Kegiatan
            
            // --- Data TAHAPAN (Rencana) ---
            $table->integer('q1_plan')->default(0);
            $table->integer('q2_plan')->default(0);
            $table->integer('q3_plan')->default(0);
            $table->integer('q4_plan')->default(0);

            // --- Data TAHAPAN (Realisasi) ---
            $table->integer('q1_real')->default(0);
            $table->integer('q2_real')->default(0);
            $table->integer('q3_real')->default(0);
            $table->integer('q4_real')->default(0);

            // Cukup 2 kolom ini karena angkanya berlaku setahun (sama tiap triwulan)
            $table->integer('output_plan')->nullable()->default(0); 
            $table->integer('output_real')->nullable()->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_targets');
    }
};