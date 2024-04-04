<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTheme extends Model
{
    use HasFactory;

    protected $fillable = ['ayat_surat_id', 'ayat_hadith_id', 'subsub_theme_id', 'hadith_tambah', 'deskripsi', 'referensi'];

    /**
     * Get the ayat surat that owns the content theme.
     */
    public function ayatSurat()
    {
        return $this->belongsTo(AyatSurat::class);
    }

    /**
     * Get the ayat hadith that owns the content theme.
     */
    public function ayatHadith()
    {
        return $this->belongsTo(AyatHadith::class);
    }

    /**
     * Get the subsub theme that owns the content theme.
     */
    public function subsubTheme()
    {
        return $this->belongsTo(SubsubTheme::class);
    }


    public function contentThemes()
    {
        return $this->hasMany(ContentTheme::class);
    }
}
