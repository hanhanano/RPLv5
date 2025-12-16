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
        Schema::create('publication_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->date('plan_date');
            $table->date('actual_date')->nullable();
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('publication_plans');
    }
};