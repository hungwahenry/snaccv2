<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(base_path('uni.json'));
        $universities = json_decode($json, true);

        foreach ($universities as $university) {
            University::updateOrCreate(
                ['acronym' => $university['acronym']],
                [
                    'name' => $university['name'],
                    'motto' => $university['motto'] === 'Non Found' ? null : $university['motto'],
                    'web' => $university['web'],
                    'logo' => $university['logo'],
                ]
            );
        }
    }
}
