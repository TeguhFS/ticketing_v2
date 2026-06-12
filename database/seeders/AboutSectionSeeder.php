<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key'      => 'hero',
                'title'    => 'Tentang Kami',
                'subtitle' => 'Platform tiket event terpercaya yang menghubungkan penyelenggara dengan jutaan penggemar event di seluruh Indonesia.',
                'content'  => 'Kami hadir sejak 2020 dengan misi membuat pengalaman membeli tiket event menjadi lebih mudah, aman, dan menyenangkan. Dari konser musik hingga seminar bisnis, kami ada untuk memastikan Anda tidak melewatkan momen berharga.',
                'image'    => null,
                'is_active' => true,
                'order'    => 1,
                'items'    => null,
            ],
            [
                'key'      => 'stats',
                'title'    => 'Kami dalam Angka',
                'subtitle' => 'Dipercaya oleh ribuan pengguna dan penyelenggara event di Indonesia',
                'content'  => null,
                'image'    => null,
                'is_active' => true,
                'order'    => 2,
                'items'    => [
                    ['value' => '10.000+', 'label' => 'Event Terlaksana',  'icon' => 'ti-calendar-event'],
                    ['value' => '500.000+', 'label' => 'Tiket Terjual',     'icon' => 'ti-ticket'],
                    ['value' => '250.000+', 'label' => 'Pengguna Aktif',    'icon' => 'ti-users'],
                    ['value' => '4.9/5',   'label' => 'Rating Pengguna',   'icon' => 'ti-star-filled'],
                ],
            ],
            [
                'key'      => 'vision',
                'title'    => 'Visi Kami',
                'subtitle' => null,
                'content'  => 'Menjadi platform ticketing event nomor satu di Asia Tenggara yang menghubungkan jutaan orang dengan pengalaman hiburan, edukasi, dan budaya terbaik.',
                'image'    => null,
                'is_active' => true,
                'order'    => 3,
                'items'    => null,
            ],
            [
                'key'      => 'mission',
                'title'    => 'Misi Kami',
                'subtitle' => null,
                'content'  => null,
                'image'    => null,
                'is_active' => true,
                'order'    => 4,
                'items'    => [
                    ['title' => 'Mudah & Cepat',        'desc' => 'Beli tiket dalam hitungan menit dengan proses yang simpel dan intuitif.'],
                    ['title' => 'Aman & Terpercaya',    'desc' => 'Sistem pembayaran terenkripsi dan tiket digital yang tidak bisa dipalsukan.'],
                    ['title' => 'Dukungan 24/7',        'desc' => 'Tim support kami siap membantu kapan saja Anda membutuhkan bantuan.'],
                    ['title' => 'Ramah Penyelenggara',  'desc' => 'Alat manajemen event yang powerful untuk penyelenggara dari skala kecil hingga besar.'],
                ],
            ],
            [
                'key'      => 'values',
                'title'    => 'Nilai-Nilai Kami',
                'subtitle' => 'Prinsip yang menjadi fondasi dalam setiap langkah kami',
                'content'  => null,
                'image'    => null,
                'is_active' => true,
                'order'    => 5,
                'items'    => [
                    ['icon' => 'ti-heart',        'title' => 'Passion',      'desc' => 'Kami mencintai dunia event dan selalu bersemangat memberikan yang terbaik.'],
                    ['icon' => 'ti-shield-check', 'title' => 'Integritas',   'desc' => 'Kejujuran dan transparansi adalah prioritas utama dalam setiap keputusan.'],
                    ['icon' => 'ti-bulb',         'title' => 'Inovasi',      'desc' => 'Terus berinovasi untuk menghadirkan fitur terbaik bagi pengguna kami.'],
                    ['icon' => 'ti-users',        'title' => 'Komunitas',    'desc' => 'Membangun ekosistem yang saling menguntungkan antara pengguna dan penyelenggara.'],
                    ['icon' => 'ti-rocket',       'title' => 'Excellence',   'desc' => 'Standar kualitas tinggi dalam setiap aspek layanan yang kami berikan.'],
                    ['icon' => 'ti-world',        'title' => 'Inklusivitas', 'desc' => 'Platform yang dapat diakses dan dinikmati oleh semua kalangan masyarakat.'],
                ],
            ],
            [
                'key'      => 'team',
                'title'    => 'Tim Kami',
                'subtitle' => 'Orang-orang berdedikasi di balik layar yang bekerja keras untuk Anda',
                'content'  => null,
                'image'    => null,
                'is_active' => true,
                'order'    => 6,
                'items'    => [
                    ['name' => 'Ahmad Rizki',    'role' => 'CEO & Co-Founder',    'avatar' => null, 'bio' => '10+ tahun pengalaman di industri teknologi dan event.'],
                    ['name' => 'Sari Dewi',      'role' => 'CTO & Co-Founder',    'avatar' => null, 'bio' => 'Engineer berpengalaman dengan passion di produk digital.'],
                    ['name' => 'Budi Santoso',   'role' => 'Head of Operations',  'avatar' => null, 'bio' => 'Ahli operasional dengan rekam jejak di perusahaan Fortune 500.'],
                    ['name' => 'Maya Putri',     'role' => 'Head of Marketing',   'avatar' => null, 'bio' => 'Strategist marketing digital yang telah mengelola 500+ kampanye.'],
                ],
            ],
            [
                'key'      => 'cta',
                'title'    => 'Siap Bergabung Bersama Kami?',
                'subtitle' => 'Temukan ribuan event seru dan beli tiket dengan mudah, aman, dan cepat.',
                'content'  => null,
                'image'    => null,
                'is_active' => true,
                'order'    => 7,
                'items'    => null,
            ],
        ];

        foreach ($sections as $section) {
            AboutSection::updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }
}
