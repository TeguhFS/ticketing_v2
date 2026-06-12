<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin      = User::where('role', 'admin')->first();
        $categories = Category::all()->keyBy('slug');

        if (!$admin) {
            $this->command->warn('⚠ No admin user found. Run AdminSeeder first.');
            return;
        }

        if ($categories->isEmpty()) {
            $this->command->warn('⚠ No categories found. Run CategorySeeder first.');
            return;
        }

        TicketType::query()->delete();
        Event::query()->forceDelete();

        $events = [

            // Musik & Konser
            [
                'event' => [
                    'category_slug'   => 'musik-konser',
                    'title'           => 'Jakarta Music Festival 2025',
                    'slug'            => 'jakarta-music-festival-2025',
                    'description'     => 'Festival musik terbesar di Jakarta yang menampilkan lebih dari 50 artis lokal dan internasional selama 3 hari berturut-turut. Nikmati penampilan spektakuler di 5 panggung berbeda dengan genre musik beragam mulai dari pop, rock, jazz, hingga electronic.Hadir bersama teman dan keluarga untuk pengalaman musik yang tak terlupakan!',
                    'location'        => 'Gelora Bung Karno, Jakarta',
                    'location_detail' => 'Pintu Utama GBK, Gate A - D tersedia. Parkir tersedia di Senayan City dan sekitarnya.',
                    'maps_url'        => 'https://maps.google.com/?q=Gelora+Bung+Karno+Jakarta',
                    'start_date'      => now()->addDays(30)->setTime(14, 0),
                    'end_date'        => now()->addDays(32)->setTime(23, 0),
                    'status'          => 'published',
                    'is_featured'     => true,
                    'max_attendees'   => 50000,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Regular',
                        'description'   => 'Akses ke semua area umum festival selama 3 hari.',
                        'price'         => 250000,
                        'quota'         => 20000,
                        'sold'          => 0,
                        'max_per_order' => 6,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(29),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'VIP',
                        'description'   => 'Area VIP eksklusif, free F&B, merchandise resmi festival.',
                        'price'         => 750000,
                        'quota'         => 5000,
                        'sold'          => 0,
                        'max_per_order' => 4,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(29),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'VVIP',
                        'description'   => 'Akses backstage, meet & greet artis pilihan, exclusive lounge.',
                        'price'         => 1500000,
                        'quota'         => 1000,
                        'sold'          => 0,
                        'max_per_order' => 2,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(29),
                        'is_active'     => true,
                    ],
                ],
            ],
            [
                'event' => [
                    'category_slug'   => 'musik-konser',
                    'title'           => 'Acoustic Night: Indie Session Vol. 3',
                    'slug'            => 'acoustic-night-indie-session-vol-3',
                    'description'     => 'Malam akustik intimate bersama 8 band indie terbaik Indonesia. Nikmati alunan musik yang menyentuh hati dalam suasana venue yang cozy dan eksklusif.',
                    'location'        => 'Dia.lo.gue Artspace, Jakarta Selatan',
                    'location_detail' => 'Lantai 1, ruang utama. Parkir terbatas, disarankan menggunakan transportasi online.',
                    'maps_url'        => 'https://maps.google.com/?q=Dialogue+Artspace+Kemang+Jakarta',
                    'start_date'      => now()->addDays(15)->setTime(19, 0),
                    'end_date'        => now()->addDays(15)->setTime(23, 30),
                    'status'          => 'published',
                    'is_featured'     => false,
                    'max_attendees'   => 500,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'General Admission',
                        'description'   => 'Standing area, akses ke seluruh area venue.',
                        'price'         => 150000,
                        'quota'         => 400,
                        'sold'          => 0,
                        'max_per_order' => 4,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(14),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Seated Premium',
                        'description'   => 'Kursi reserved di area terdepan, view terbaik.',
                        'price'         => 250000,
                        'quota'         => 100,
                        'sold'          => 0,
                        'max_per_order' => 2,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(14),
                        'is_active'     => true,
                    ],
                ],
            ],

            // Teknologi & Startup
            [
                'event' => [
                    'category_slug'   => 'teknologi-startup',
                    'title'           => 'Indonesia Digital Summit 2025',
                    'slug'            => 'indonesia-digital-summit-2025',
                    'description'     => 'Summit teknologi terbesar di Indonesia yang mempertemukan para pemimpin industri digital, startup founder, dan investor dari seluruh Asia Tenggara.Dapatkan insights terbaru tentang AI, blockchain, fintech, dan masa depan ekonomi digital Indonesia.',
                    'location'        => 'Jakarta Convention Center',
                    'location_detail' => 'Hall A & B, Lantai 1. Registrasi dibuka mulai pukul 07.00 WIB.',
                    'maps_url'        => 'https://maps.google.com/?q=Jakarta+Convention+Center',
                    'start_date'      => now()->addDays(45)->setTime(8, 0),
                    'end_date'        => now()->addDays(46)->setTime(18, 0),
                    'status'          => 'published',
                    'is_featured'     => true,
                    'max_attendees'   => 3000,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Standard Pass',
                        'description'   => 'Akses semua sesi, networking lunch, dan coffee break.',
                        'price'         => 500000,
                        'quota'         => 2000,
                        'sold'          => 0,
                        'max_per_order' => 5,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(44),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Pro Pass',
                        'description'   => 'Standard + workshop eksklusif dan direktori networking digital.',
                        'price'         => 1200000,
                        'quota'         => 800,
                        'sold'          => 0,
                        'max_per_order' => 3,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(44),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Enterprise',
                        'description'   => 'All access + VIP dinner, 1-on-1 session dengan speaker, priority seating.',
                        'price'         => 3500000,
                        'quota'         => 200,
                        'sold'          => 0,
                        'max_per_order' => 2,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(44),
                        'is_active'     => true,
                    ],
                ],
            ],
            [
                'event' => [
                    'category_slug'   => 'teknologi-startup',
                    'title'           => 'Google I/O Extended Jakarta 2025',
                    'slug'            => 'google-io-extended-jakarta-2025',
                    'description'     => 'Nonton bareng Google I/O 2025 bersama komunitas developer Jakarta. Saksikan keynote Google secara langsung dan diskusi bersama Google Developer Expert Indonesia.',
                    'location'        => 'Google Indonesia Office, Jakarta',
                    'location_detail' => 'Lantai 28, Pacific Century Place. Bawa ID Card untuk akses gedung.',
                    'maps_url'        => 'https://maps.google.com/?q=Pacific+Century+Place+Jakarta',
                    'start_date'      => now()->addDays(10)->setTime(8, 0),
                    'end_date'        => now()->addDays(10)->setTime(18, 0),
                    'status'          => 'published',
                    'is_featured'     => false,
                    'max_attendees'   => 300,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Free Admission',
                        'description'   => 'Gratis! Termasuk snack, merchandise eksklusif, dan sertifikat digital.',
                        'price'         => 0,
                        'quota'         => 300,
                        'sold'          => 0,
                        'max_per_order' => 2,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(9),
                        'is_active'     => true,
                    ],
                ],
            ],

            // Olahraga
            [
                'event' => [
                    'category_slug'   => 'olahraga',
                    'title'           => 'Bali Marathon 2025',
                    'slug'            => 'bali-marathon-2025',
                    'description'     => 'Berlari melintasi pemandangan Bali yang memukau! Bali Marathon 2025 menawarkan 4 kategori lomba dengan rute yang melewati sawah, pura bersejarah, dan pantai indah.Bergabunglah bersama ribuan pelari dari 50+ negara dalam event lari internasional paling bergengsi di Indonesia.',
                    'location'        => 'Garuda Wisnu Kencana, Bali',
                    'location_detail' => 'Start & Finish di area Plaza Wisnu. Parkir tersedia di area GWK. Shuttle bus disediakan dari hotel-hotel utama.',
                    'maps_url'        => 'https://maps.google.com/?q=Garuda+Wisnu+Kencana+Bali',
                    'start_date'      => now()->addDays(60)->setTime(5, 0),
                    'end_date'        => now()->addDays(60)->setTime(14, 0),
                    'status'          => 'published',
                    'is_featured'     => true,
                    'max_attendees'   => 10000,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Fun Run 5K',
                        'description'   => 'Jersey, finisher medal, e-certificate. Cocok untuk semua usia.',
                        'price'         => 200000,
                        'quota'         => 3000,
                        'sold'          => 0,
                        'max_per_order' => 5,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(55),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Half Marathon 21K',
                        'description'   => 'Jersey premium, medal, timing chip, race bag, finisher bag.',
                        'price'         => 450000,
                        'quota'         => 4000,
                        'sold'          => 0,
                        'max_per_order' => 3,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(55),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Full Marathon 42K',
                        'description'   => 'Jersey premium, medal eksklusif, BIB, finisher bag, post-race meal.',
                        'price'         => 750000,
                        'quota'         => 2000,
                        'sold'          => 0,
                        'max_per_order' => 2,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(55),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Team Relay 42K',
                        'description'   => 'Tim 4 orang, jersey team, medal team, finisher pack masing-masing.',
                        'price'         => 1200000,
                        'quota'         => 1000,
                        'sold'          => 0,
                        'max_per_order' => 1,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(55),
                        'is_active'     => true,
                    ],
                ],
            ],

            // Festival & Hiburan
            [
                'event' => [
                    'category_slug'   => 'festival-hiburan',
                    'title'           => 'Bandung Food & Art Festival 2025',
                    'slug'            => 'bandung-food-art-festival-2025',
                    'description'     => 'Festival kuliner dan seni terbesar di Bandung yang menampilkan 200+ vendor makanan, puluhan seniman lokal, live music performance, dan instalasi seni interaktif.Temukan cita rasa kuliner nusantara dan nikmati karya seni terbaik dalam satu tempat selama 3 hari penuh.',
                    'location'        => 'Lapangan Gasibu, Bandung',
                    'location_detail' => 'Area utama Lapangan Gasibu. Akses dari Jl. Diponegoro. Transportasi umum: angkot dari Alun-Alun Bandung.',
                    'maps_url'        => 'https://maps.google.com/?q=Lapangan+Gasibu+Bandung',
                    'start_date'      => now()->addDays(25)->setTime(10, 0),
                    'end_date'        => now()->addDays(27)->setTime(22, 0),
                    'status'          => 'published',
                    'is_featured'     => true,
                    'max_attendees'   => 30000,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Tiket Harian',
                        'description'   => 'Akses 1 hari sesuai pilihan. Pilih tanggal saat pembelian.',
                        'price'         => 50000,
                        'quota'         => 20000,
                        'sold'          => 0,
                        'max_per_order' => 10,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(24),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => '3-Day Pass',
                        'description'   => 'Akses 3 hari penuh + gelang eksklusif festival.',
                        'price'         => 120000,
                        'quota'         => 10000,
                        'sold'          => 0,
                        'max_per_order' => 10,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(24),
                        'is_active'     => true,
                    ],
                ],
            ],

            // Bisnis & Networking
            [
                'event' => [
                    'category_slug'   => 'bisnis-networking',
                    'title'           => 'Growth Hacking Masterclass 2025',
                    'slug'            => 'growth-hacking-masterclass-2025',
                    'description'     => 'Workshop intensif 1 hari bersama para growth expert dari perusahaan unicorn Indonesia. Pelajari strategi growth yang telah terbukti menghasilkan jutaan pengguna.',
                    'location'        => 'The Westin Jakarta',
                    'location_detail' => 'Ballroom Level 3. Dress code: smart casual. Coffee break dan makan siang disediakan.',
                    'maps_url'        => 'https://maps.google.com/?q=The+Westin+Jakarta',
                    'start_date'      => now()->addDays(20)->setTime(9, 0),
                    'end_date'        => now()->addDays(20)->setTime(18, 0),
                    'status'          => 'published',
                    'is_featured'     => false,
                    'max_attendees'   => 200,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Early Bird',
                        'description'   => 'Harga spesial early bird, tersedia terbatas 50 kursi.',
                        'price'         => 350000,
                        'quota'         => 50,
                        'sold'          => 0,
                        'max_per_order' => 2,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(10),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Regular',
                        'description'   => 'Termasuk materi workshop, lunch, coffee break, dan sertifikat.',
                        'price'         => 500000,
                        'quota'         => 150,
                        'sold'          => 0,
                        'max_per_order' => 3,
                        'sale_start'    => now()->addDays(11),
                        'sale_end'      => now()->addDays(19),
                        'is_active'     => true,
                    ],
                ],
            ],

            // Pendidikan
            [
                'event' => [
                    'category_slug'   => 'pendidikan',
                    'title'           => 'Full-Stack Web Development Bootcamp',
                    'slug'            => 'fullstack-web-development-bootcamp',
                    'description'     => 'Bootcamp intensif 3 hari untuk mempelajari pengembangan web modern menggunakan Laravel, React, dan deployment ke cloud. Cocok untuk developer yang ingin naik level.',
                    'location'        => 'Hacktiv8 Campus, Jakarta Selatan',
                    'location_detail' => 'Lantai 2, ruang workshop. Bawa laptop dengan spesifikasi minimal RAM 8GB.',
                    'maps_url'        => 'https://maps.google.com/?q=Hacktiv8+Jakarta',
                    'start_date'      => now()->addDays(18)->setTime(9, 0),
                    'end_date'        => now()->addDays(20)->setTime(17, 0),
                    'status'          => 'published',
                    'is_featured'     => false,
                    'max_attendees'   => 50,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Online',
                        'description'   => 'Akses live streaming via Zoom + rekaman + materi digital.',
                        'price'         => 750000,
                        'quota'         => 30,
                        'sold'          => 0,
                        'max_per_order' => 1,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(17),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Offline',
                        'description'   => 'Hadir langsung + makan siang 3 hari + kit belajar + akses rekaman.',
                        'price'         => 1500000,
                        'quota'         => 20,
                        'sold'          => 0,
                        'max_per_order' => 1,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(17),
                        'is_active'     => true,
                    ],
                ],
            ],

            // Seni & Budaya
            [
                'event' => [
                    'category_slug'   => 'seni-budaya',
                    'title'           => 'Art Jakarta 2025 — Contemporary Art Fair',
                    'slug'            => 'art-jakarta-2025',
                    'description'     => 'Pameran seni kontemporer terbesar di Asia Tenggara yang menghadirkan karya dari 200+ seniman dari 30 negara. Temukan, apresiasi, dan koleksi karya seni terbaik.',
                    'location'        => 'Jakarta Convention Center',
                    'location_detail' => 'Hall D & E. Panduan venue tersedia di meja registrasi. Audio guide tersedia dalam Bahasa Indonesia dan Inggris.',
                    'maps_url'        => 'https://maps.google.com/?q=Jakarta+Convention+Center',
                    'start_date'      => now()->addDays(35)->setTime(10, 0),
                    'end_date'        => now()->addDays(38)->setTime(21, 0),
                    'status'          => 'published',
                    'is_featured'     => true,
                    'max_attendees'   => 15000,
                ],
                'ticket_types' => [
                    [
                        'name'          => 'Daily Pass',
                        'description'   => 'Akses 1 hari pilihan, termasuk katalog digital seniman.',
                        'price'         => 100000,
                        'quota'         => 10000,
                        'sold'          => 0,
                        'max_per_order' => 5,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(37),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'Full Pass',
                        'description'   => 'Akses seluruh hari pameran + katalog cetak eksklusif.',
                        'price'         => 300000,
                        'quota'         => 5000,
                        'sold'          => 0,
                        'max_per_order' => 3,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(34),
                        'is_active'     => true,
                    ],
                    [
                        'name'          => 'VIP Opening Night',
                        'description'   => 'Opening night eksklusif + champagne reception + priority viewing.',
                        'price'         => 500000,
                        'quota'         => 500,
                        'sold'          => 0,
                        'max_per_order' => 2,
                        'sale_start'    => null,
                        'sale_end'      => now()->addDays(34),
                        'is_active'     => true,
                    ],
                ],
            ],

        ];

        foreach ($events as $data) {
            $category = $categories->get($data['event']['category_slug']);

            if (!$category) {
                $this->command->warn("⚠ Category '{$data['event']['category_slug']}' not found. Skipping.");
                continue;
            }

            $eventData = collect($data['event'])
                ->except('category_slug')
                ->merge([
                    'category_id' => $category->id,
                    'user_id'     => $admin->id,
                ])
                ->toArray();

            $event = Event::updateOrCreate(
                ['slug' => $eventData['slug']],
                $eventData
            );

            // Buat ticket types
            foreach ($data['ticket_types'] as $ticketType) {
                TicketType::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'name'     => $ticketType['name'],
                    ],
                    array_merge($ticketType, ['event_id' => $event->id])
                );
            }

            $typeCount = count($data['ticket_types']);
            $this->command->line(
                "  → {$event->title} ({$typeCount} ticket types)"
            );
        }

        $total = count($events);
        $this->command->info("✓ Events seeded: {$total}");
    }
}
