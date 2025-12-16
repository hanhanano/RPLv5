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
        Schema::create('struggles', function (Blueprint $table) {
            $table->id('struggle_id');
            $table->text('struggle_desc');
            $table->text('solution_desc');
            $table->string('solution_doc')->nullable();
            $table->timestamps();
            
            $table->foreignId('step_final_id')
                ->constrained('steps_finals', 'step_final_id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struggles');
    }
};