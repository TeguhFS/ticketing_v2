<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Musik & Konser',
                'slug'        => 'musik-konser',
                'description' => 'Event musik, konser, dan pertunjukan live dari artis lokal maupun internasional.',
                'image'        => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Seminar & Konferensi',
                'slug'        => 'seminar-konferensi',
                'description' => 'Seminar, konferensi, workshop, dan event edukasi profesional.',
                'image'        => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Olahraga',
                'slug'        => 'olahraga',
                'description' => 'Event olahraga, turnamen, marathon, dan kompetisi fisik.',
                'image'        => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Festival & Hiburan',
                'slug'        => 'festival-hiburan',
                'description' => 'Festival budaya, seni, kuliner, dan event hiburan keluarga.',
                'image'        => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Teknologi & Startup',
                'slug'        => 'teknologi-startup',
                'description' => 'Tech conference, hackathon, demo day startup, dan event inovasi.',
                'image'        => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Seni & Budaya',
                'slug'        => 'seni-budaya',
                'description' => 'Pameran seni, pertunjukan teater, tari, dan event budaya.',
                'image'        => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Bisnis & Networking',
                'slug'        => 'bisnis-networking',
                'description' => 'Event networking, business forum, dan pertemuan profesional.',
                'image'        => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Pendidikan',
                'slug'        => 'pendidikan',
                'description' => 'Workshop, bootcamp, kelas online, dan event edukasi.',
                'image'        => null,
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✓ Categories seeded: ' . count($categories));
    }
}
