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
        Schema::create('content_themes', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi')->nullable();
            $table->foreignId('ayat_surat_id')->constrained('ayat_surats')->onDelete('cascade');
            $table->foreignId('ayat_hadith_id')->nullable()->constrained('ayat_hadiths')->onDelete('cascade');
            $table->foreignId('subsub_theme_id')->constrained('sub_themes')->onDelete('cascade');
            $table->text('hadith_tambah')->nullable();
            $table->text('referensi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_themes');
    }
};
