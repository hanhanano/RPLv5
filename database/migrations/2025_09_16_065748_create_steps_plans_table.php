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
        Schema::create('steps_plans', function (Blueprint $table) {
            $table->id('step_plan_id');
            $table->string('plan_type');
            $table->string('plan_name');
            $table->date('plan_start_date')->nullable();
            $table->date('plan_end_date')->nullable();
            $table->text('plan_desc')->nullable();
            $table->string('plan_doc')->nullable();
            $table->timestamps();
            
            $table->foreignId('publication_id')
                ->constrained('publications', 'publication_id')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps_plans');
    }
};