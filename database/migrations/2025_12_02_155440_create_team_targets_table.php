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
            $table->string('team_name');
            $table->string('activity_name');
            $table->string('report_name')->nullable();
            
            // Tahapan - Rencana
            $table->integer('q1_plan')->default(0);
            $table->integer('q2_plan')->default(0);
            $table->integer('q3_plan')->default(0);
            $table->integer('q4_plan')->default(0);

            // Tahapan - Realisasi
            $table->integer('q1_real')->default(0);
            $table->integer('q2_real')->default(0);
            $table->integer('q3_real')->default(0);
            $table->integer('q4_real')->default(0);

            // Output - Target dan Realisasi (Total)
            $table->decimal('output_plan', 8, 2)->default(0);
            $table->decimal('output_real', 8, 2)->default(0);
            
            // Output - Realisasi per Quarter
            $table->decimal('output_real_q1', 8, 2)->default(0);
            $table->decimal('output_real_q2', 8, 2)->default(0);
            $table->decimal('output_real_q3', 8, 2)->default(0);
            $table->decimal('output_real_q4', 8, 2)->default(0);
            
            // Actual Output Points (untuk indikator spesial)
            $table->decimal('actual_output_q1', 8, 2)->default(0);
            $table->decimal('actual_output_q2', 8, 2)->default(0);
            $table->decimal('actual_output_q3', 8, 2)->default(0);
            $table->decimal('actual_output_q4', 8, 2)->default(0);
            
            // Flag untuk indikator spesial
            $table->boolean('is_special_indicator')->default(false);
            
            $table->timestamps();
            
            $table->foreignId('publication_id')
                ->constrained('publications', 'publication_id')
                ->onDelete('cascade');
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