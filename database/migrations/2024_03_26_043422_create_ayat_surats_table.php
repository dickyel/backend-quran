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
        Schema::create('ayat_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            $table->text('nomor_ayat');
            $table->text('teks_arab');
            $table->text('teks_latin');
            $table->text('teks_indonesia');
            $table->text('teks_inggris');
            $table->text('tafsir');
            $table->string('audio_satu');
            $table->string('audio_dua');
            $table->string('audio_tiga');
            $table->string('audio_empat');
            $table->string('audio_lima');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayat_surats');
    }
};
