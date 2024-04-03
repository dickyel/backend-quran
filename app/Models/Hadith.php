<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hadith extends Model
{
    use HasFactory;

    protected $table = 'hadiths';

    protected $fillable = [
        'name_hadith',
        'total_hadith',
        'slug',
        
    ];

    public function ayatHadiths()
    {
        return $this->hasMany(AyatHadith::class);
    }
}
