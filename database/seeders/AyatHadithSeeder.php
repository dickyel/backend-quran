<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\AyatHadith;
use App\Models\Hadith;

class AyatHadithSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve all hadiths
        $hadiths = Hadith::all();

        foreach ($hadiths as $hadith) {
            $currentPage = 1;
            $totalPages = PHP_INT_MAX; // Initialize totalPages to a very large value

            while ($currentPage <= $totalPages) {
                // Make HTTP request to get data for current page
                $response = Http::get('https://hadis-api-id.vercel.app/hadith/' . $hadith->slug . '?page=' . $currentPage . '&limit=20');

                if ($response->ok()) {
                    $data = $response->json();
                    $totalPages = $data['pagination']['totalPages'];

                    foreach ($data['items'] as $ayat) {
                        AyatHadith::create([
                            'hadith_id' => $hadith->id,
                            'number' => $ayat['number'],
                            'arab' => $ayat['arab'],
                            'indonesia' => $ayat['id'],
                        ]);
                    }

                    // Move to the next page
                    $currentPage++;
                } else {
                    $this->command->error('Failed to fetch data for Hadith ' . $hadith->slug);
                    break; // Exit the loop if failed to fetch data for current hadith
                }
            }
        }
    }
}
