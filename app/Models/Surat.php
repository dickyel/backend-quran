<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';
    protected $fillable = [
        'nomor',
        'nama_surat',
        'nama_inggris',
        'nama_latin',
        'jumlah_ayat',
        'tempat_turun',
        'arti_inggris',
        'arti',
        'deskripsi',
        'audio_satu',
        'audio_dua',
        'audio_tiga',
        'audio_empat',
        'audio_lima',
        'slug'
    ];

    public function ayatSurats()
    {
        return $this->hasMany(AyatSurat::class);
    }
}
