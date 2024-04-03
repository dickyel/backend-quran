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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor');
            $table->string('nama_surat');
            $table->string('nama_latin');
            $table->string('nama_inggris');  
            $table->integer('jumlah_ayat');
            $table->string('tempat_turun');
            $table->string('arti');
            $table->string('arti_inggris');
            $table->text('deskripsi');
            $table->string('audio_satu');
            $table->string('audio_dua');
            $table->string('audio_tiga');
            $table->string('audio_empat');
            $table->string('audio_lima');
            $table->string('slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
