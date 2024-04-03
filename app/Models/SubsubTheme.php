<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsubTheme extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'subsub_theme_id','slug'];

    public function subtheme()
    {
        return $this->belongsTo(Theme::class);
    }

    
    /**
     * Get the content themes for the surat ayat.
     */
    public function contentThemes()
    {
        return $this->hasMany(ContentTheme::class);
    }

 
}
