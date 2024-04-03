<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveThema extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'thema_id',
     
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    //thema
    public function thema()
    {
        return $this->belongsTo(Thema::class);
    }

}
