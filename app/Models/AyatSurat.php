<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyatSurat extends Model
{
    use HasFactory;

    protected $table = 'ayat_surats';

    protected $fillable = [
        'surat_id',
        'nomor_ayat',
        'teks_arab',
        'teks_indonesia',
        
        'teks_latin',
        'teks_inggris',
        'tafsir',
        'audio_satu',
        'audio_dua',
        'audio_tiga',
        'audio_empat',
        'audio_lima',
    
        
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    // AyatSurat.php
    public function saveAyatSurats()
    {
        return $this->hasMany(SaveAyatSurat::class);
    }

    
    /**
     * Get the content themes for the surat ayat.
     */
    public function contentThemes()
    {
        return $this->hasMany(ContentTheme::class);
    }
    

 
    
}
