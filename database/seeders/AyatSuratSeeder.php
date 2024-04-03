<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\AyatSurat;
use App\Models\Surat;

class AyatSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $surats = Surat::all();

        foreach ($surats as $surat) {
            $responseAyat = Http::get('https://equran.id/api/v2/surat/' . $surat->nomor);
            $responseTafsir = Http::get('https://equran.id/api/v2/tafsir/' . $surat->nomor);
            $responseTranslation = Http::get('https://cdn.jsdelivr.net/npm/quran-json@3.1.2/dist/quran_en.json');

            if ($responseAyat->ok() && $responseTafsir->ok() && $responseTranslation->ok()) {
                $dataAyat = $responseAyat->json()['data'];
                $dataTafsir = $responseTafsir->json()['data'];
                $dataTranslation = $responseTranslation->json();

                $ayats = $dataAyat['ayat'];
                $tafsirs = $dataTafsir['tafsir'];
                $translations = $dataTranslation[$surat->nomor - 1]['verses'];

                foreach ($ayats as $index => $ayat) {
                    AyatSurat::create([
                        'surat_id' => $surat->id,
                        'nomor_ayat' => $ayat['nomorAyat'],
                        'teks_arab' => $ayat['teksArab'],
                        'teks_latin' => $ayat['teksLatin'],
                        'teks_indonesia' => $ayat['teksIndonesia'],
                        'teks_inggris' => $translations[$index]['translation'],
                        'audio_satu' => $ayat['audio']['01'],
                        'audio_dua' => $ayat['audio']['02'],
                        'audio_tiga' => $ayat['audio']['03'],
                        'audio_empat' => $ayat['audio']['04'],
                        'audio_lima' => $ayat['audio']['05'],
                        'tafsir' => $tafsirs[$index]['teks'],
                    ]);
                }
            } else {
                $this->command->error('Failed to fetch data for Surat ' . $surat->nomor);
            }
        }
    }
}
