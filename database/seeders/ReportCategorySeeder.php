<?php

namespace Database\Seeders;

use App\Models\ReportCategory;
use Illuminate\Database\Seeder;

class ReportCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'spam',
                'description' => 'repetitive, unsolicited, or promotional content',
                'applies_to' => 'all',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'harassment or bullying',
                'description' => 'content intended to harass, intimidate, or bully',
                'applies_to' => 'all',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'hate speech',
                'description' => 'content that promotes violence or hatred',
                'applies_to' => 'all',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'misinformation',
                'description' => 'false or misleading information',
                'applies_to' => 'all',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'inappropriate content',
                'description' => 'sexually explicit or graphic violent content',
                'applies_to' => 'all',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'self-harm or suicide',
                'description' => 'content promoting or glorifying self-harm',
                'applies_to' => 'all',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'off-topic',
                'description' => 'content not related to campus or student life',
                'applies_to' => 'snacc',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'impersonation',
                'description' => 'pretending to be someone else',
                'applies_to' => 'user',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'other',
                'description' => 'something else that violates our guidelines',
                'applies_to' => 'all',
                'order' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ReportCategory::updateOrCreate(
                ['name' => $category['name'], 'applies_to' => $category['applies_to']],
                $category
            );
        }
    }
}
