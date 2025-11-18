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
        Schema::create('steps_finals', function (Blueprint $table) {
            $table->id('step_final_id'); // Primary Key custom
            $table->date('actual_started');
            $table->date('actual_ended');
            $table->text('final_desc');
            $table->string('next_step')->nullable();
            $table->string('final_doc')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign Key ke steps_plans table
            $table->foreignId('step_plan_id')
                  ->constrained('steps_plans', 'step_plan_id')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps_finals');
    }
};