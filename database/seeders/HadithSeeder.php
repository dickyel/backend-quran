<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Hadith;

class HadithSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $response = Http::get('https://hadis-api-id.vercel.app/hadith');

        if ($response->ok()) {
            $hadiths = $response->json();

            foreach ($hadiths as $hadith) {
                Hadith::create([
                    'name_hadith' => $hadith['name'],
                    'total_hadith' => $hadith['total'],
                    'slug' => $hadith['slug'],    
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            $this->command->error('Failed to fetch data from the API.');
        }
    }
}
