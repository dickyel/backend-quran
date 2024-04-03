<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Surat;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Fetch data from the first API
        $response1 = Http::get('https://equran.id/api/v2/surat');
        
        // Fetch data from the second API
        $response2 = Http::get('http://api.alquran.cloud/v1/surah');

        if ($response1->ok() && $response2->ok()) {
            $surats1 = $response1->json()['data'];
            $surats2 = $response2->json()['data'];

            foreach ($surats1 as $index => $surat) {
                Surat::create([
                    'nomor' => $surat['nomor'],
                    'nama_surat' => $surat['nama'],
                    'nama_latin' => $surat['namaLatin'],
                    'jumlah_ayat' => $surat['jumlahAyat'],
                    'tempat_turun' => $surat['tempatTurun'],
                    'arti' => $surat['arti'],
                    'deskripsi' => $surat['deskripsi'],
                    'audio_satu' => $surat['audioFull']['01'],
                    'audio_dua' => $surat['audioFull']['02'],
                    'audio_tiga' => $surat['audioFull']['03'],
                    'audio_empat' => $surat['audioFull']['04'],
                    'audio_lima' => $surat['audioFull']['05'],
                    'nama_inggris' => $surats2[$index]['englishName'],
                    'arti_inggris' => $surats2[$index]['englishNameTranslation'],
                    'slug' => Str::slug($surat['namaLatin']), // Generate slug using Laravel's Str class
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            $this->command->error('Failed to fetch data from the API.');
        }
    }
}
