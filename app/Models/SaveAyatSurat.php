<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveAyatSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ayat_surat_id',
     
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // SaveAyatSurat.php
    public function ayatSurat()
    {
        return $this->belongsTo(AyatSurat::class);
    }

}
