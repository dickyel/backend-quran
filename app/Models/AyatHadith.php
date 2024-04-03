<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyatHadith extends Model
{
    use HasFactory;

    protected $table = 'ayat_hadiths';
    protected $fillable = [
        'hadith_id',
        'number',
        'arab',
        'indonesia',
    ];

    public function hadith()
    {
        return $this->belongsTo(Hadith::class);
    }

    // AyatHadith.php
    public function saveAyatHadiths()
    {
        return $this->hasMany(SaveAyatHadith::class);
    }

    
    /**
     * Get the content themes for the hadith ayat.
     */
    public function contentThemes()
    {
        return $this->hasMany(ContentTheme::class);
    }

   
    
}
