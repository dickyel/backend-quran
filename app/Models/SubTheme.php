<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTheme extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'theme_id','slug'];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function subsubThemes()
    {
        return $this->hasMany(SubsubTheme::class);
    }
}
