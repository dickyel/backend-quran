<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveAyatHadith extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ayat_hadith_id',
    ];

    // SaveAyatHadith.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // SaveAyatHadith.php
    public function ayatHadith()
    {
        return $this->belongsTo(AyatHadith::class);
    }

    
}
