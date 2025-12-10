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
        Schema::table('team_targets', function (Blueprint $table) {
            // Menambahkan kolom foreign key setelah kolom id
            // Kita buat nullable dulu untuk mencegah error pada data lama
            $table->unsignedBigInteger('publication_id')->nullable()->after('id');

            // Definisikan Foreign Key ke tabel publications
            // Mengacu pada 'publication_id' di tabel 'publications'
            $table->foreign('publication_id')
                  ->references('publication_id')
                  ->on('publications')
                  ->onDelete('cascade'); // Jika publikasi dihapus, target juga terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_targets', function (Blueprint $table) {
            $table->dropForeign(['publication_id']);
            $table->dropColumn('publication_id');
        });
    }
};