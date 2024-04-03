<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentThemeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return [
                'id' => $this->id,
                'deskripsi' => $this->deskripsi,
                'referensi' => $this->referensi,
                'hadith_tambah' => $this->hadith_tambah,
                'ayat_surat_id' => $this->ayat_surat_id, 
                'ayat_hadith_id' => $this->ayat_hadith_id,
                'subsub_theme_id' => $this->subsub_theme_id,
                'created_at' => $this->created_at->format('d/m/Y'),
                'updated_at' => $this->updated_at->format('d/m/Y')
            ];
        
    }
}
