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
        Schema::create('publications', function (Blueprint $table) {
            $table->id('publication_id');
            $table->string('publication_name');
            $table->string('publication_report');
            $table->string('publication_pic');
            $table->boolean('is_monthly')->default(0);
            $table->string('slug_publication')->unique();
            $table->timestamps();
            
            $table->foreignId('fk_user_id')
                ->constrained('users', 'id')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};