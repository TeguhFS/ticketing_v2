<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn('⚠ No admin user found. Run AdminSeeder first.');
            return;
        }

        Blog::truncate();

        $blogs = [
            [
                'user_id'      => $admin->id,
                'title'        => 'Tips Membeli Tiket Event Online dengan Aman',
                'slug'         => 'tips-membeli-tiket-event-online-aman',
                'excerpt'      => 'Panduan lengkap agar pengalaman membeli tiket online Anda aman, mudah, dan bebas penipuan.',
                'content'      => '<h2>Kenapa Keamanan Pembelian Tiket Online Penting?</h2>
<p>Di era digital ini, membeli tiket event secara online sudah menjadi hal yang lumrah. Namun, seiring popularitasnya, penipuan tiket juga semakin marak. Berikut tips dari kami untuk menjaga keamanan transaksi Anda.</p>

<h2>1. Selalu Beli dari Platform Resmi</h2>
<p>Pastikan Anda membeli tiket hanya dari platform ticketing resmi dan terpercaya. Hindari membeli dari orang tidak dikenal di media sosial meskipun harganya lebih murah.</p>

<h2>2. Cek Keamanan Website</h2>
<p>Pastikan URL diawali dengan "https://" dan terdapat ikon gembok di browser Anda. Ini menandakan koneksi terenkripsi SSL yang melindungi data pembayaran Anda.</p>

<h2>3. Gunakan Metode Pembayaran yang Aman</h2>
<p>Platform kami menggunakan Midtrans sebagai payment gateway tersertifikasi PCI-DSS untuk keamanan transaksi maksimal.</p>

<h2>4. Simpan Bukti Pembayaran</h2>
<p>Selalu screenshot atau simpan email konfirmasi pembayaran dan kode tiket Anda. Ini penting sebagai bukti jika terjadi masalah.</p>

<h2>5. Waspada Penawaran Terlalu Murah</h2>
<p>Jika ada yang menawarkan tiket dengan harga jauh di bawah harga normal, besar kemungkinan itu penipuan. Harga yang terlalu murah adalah red flag utama.</p>

<h2>Kesimpulan</h2>
<p>Dengan mengikuti tips di atas, Anda dapat menikmati proses pembelian tiket yang aman dan nyaman. Platform kami berkomitmen untuk selalu menjaga keamanan data dan transaksi seluruh pengguna.</p>',
                'thumbnail'    => null,
                'status'       => 'published',
                'is_featured'  => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'user_id'      => $admin->id,
                'title'        => 'Panduan Lengkap Menggunakan E-Ticket di Smartphone',
                'slug'         => 'panduan-lengkap-menggunakan-e-ticket-smartphone',
                'excerpt'      => 'Cara mudah menyimpan, menampilkan, dan menggunakan e-ticket digital saat masuk ke venue event.',
                'content'      => '<h2>Apa itu E-Ticket?</h2>
<p>E-Ticket atau tiket elektronik adalah tiket digital yang tersimpan di smartphone Anda. Berbeda dengan tiket fisik, e-ticket lebih praktis, tidak bisa hilang, dan ramah lingkungan.</p>

<h2>Cara Mendapatkan E-Ticket</h2>
<p>Setelah pembayaran berhasil dikonfirmasi, e-ticket akan otomatis dikirimkan ke email Anda dan tersedia di halaman "Tiket Saya" pada akun Anda.</p>

<h2>Cara Menggunakan E-Ticket</h2>
<ol>
<li>Login ke akun Anda di website</li>
<li>Buka menu "Tiket Saya"</li>
<li>Pilih tiket untuk event yang akan Anda hadiri</li>
<li>Tunjukkan QR Code kepada petugas di pintu masuk</li>
</ol>

<h2>Tips Penting</h2>
<ul>
<li>Pastikan layar smartphone Anda cukup terang saat scan</li>
<li>Download screenshot QR code sebagai cadangan jika internet lemot</li>
<li>Jangan bagikan kode tiket ke orang lain</li>
<li>Datang lebih awal untuk menghindari antrean panjang</li>
</ul>',
                'thumbnail'    => null,
                'status'       => 'published',
                'is_featured'  => false,
                'published_at' => now()->subDays(10),
            ],
            [
                'user_id'      => $admin->id,
                'title'        => '10 Event Paling Ditunggu-Tunggu di 2025',
                'slug'         => '10-event-paling-ditunggu-2025',
                'excerpt'      => 'Daftar event terbaik dan paling populer yang akan digelar di Indonesia sepanjang tahun 2025.',
                'content'      => '<h2>Event Terbaik 2025</h2>
<p>Tahun 2025 menjanjikan deretan event spektakuler yang sayang untuk dilewatkan. Dari konser musik internasional hingga festival kuliner, berikut 10 event yang paling banyak dicari.</p>

<h2>1. Jakarta Music Festival 2025</h2>
<p>Festival musik terbesar di Indonesia dengan 50+ artis selama 3 hari di Gelora Bung Karno. Tiket sudah tersedia dan cepat habis!</p>

<h2>2. Bali Marathon 2025</h2>
<p>Event lari internasional dengan rute melewati keindahan alam Bali. Tersedia untuk pelari dari berbagai kategori.</p>

<h2>3. Indonesia Digital Summit 2025</h2>
<p>Summit teknologi dengan pembicara dari Google, Meta, Gojek, dan startup unicorn lainnya.</p>

<h2>4. Art Jakarta 2025</h2>
<p>Pameran seni kontemporer terbesar se-Asia Tenggara dengan 200+ seniman dari 30 negara.</p>

<h2>5. Bandung Food & Art Festival</h2>
<p>Festival kuliner dan seni dengan 200+ vendor yang menghadirkan cita rasa nusantara terbaik.</p>

<p><em>Pantau terus platform kami untuk update event terbaru!</em></p>',
                'thumbnail'    => null,
                'status'       => 'published',
                'is_featured'  => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'user_id'      => $admin->id,
                'title'        => 'Cara Mengajukan Refund Tiket dengan Mudah',
                'slug'         => 'cara-mengajukan-refund-tiket',
                'excerpt'      => 'Panduan step-by-step proses pengajuan refund tiket event yang tidak bisa Anda hadiri.',
                'content'      => '<h2>Kapan Refund Bisa Diajukan?</h2>
<p>Refund tiket dapat diajukan dalam kondisi berikut:</p>
<ul>
<li>Event dibatalkan oleh penyelenggara</li>
<li>Ada keadaan darurat yang menghalangi kehadiran</li>
<li>Permintaan diajukan sebelum event dimulai</li>
</ul>

<h2>Langkah Mengajukan Refund</h2>
<ol>
<li>Login ke akun Anda</li>
<li>Buka menu "Pesanan Saya"</li>
<li>Pilih order yang ingin direfund</li>
<li>Klik tombol "Ajukan Refund"</li>
<li>Isi alasan dan informasi rekening bank</li>
<li>Submit pengajuan</li>
</ol>

<h2>Proses Review</h2>
<p>Tim kami akan memproses pengajuan dalam 1-2 hari kerja. Anda akan mendapat notifikasi email mengenai status refund.</p>

<h2>Estimasi Pengembalian Dana</h2>
<p>Setelah disetujui, dana akan dikembalikan ke rekening Anda dalam 3-5 hari kerja tergantung kebijakan bank.</p>',
                'thumbnail'    => null,
                'status'       => 'published',
                'is_featured'  => false,
                'published_at' => now()->subDays(7),
            ],
            [
                'user_id'      => $admin->id,
                'title'        => 'Mengenal Berbagai Jenis Tiket Event dan Keuntungannya',
                'slug'         => 'mengenal-jenis-tiket-event-dan-keuntungannya',
                'excerpt'      => 'Dari Regular hingga VVIP, pahami perbedaan setiap jenis tiket agar pengalaman event Anda makin maksimal.',
                'content'      => '<h2>Jenis-Jenis Tiket Event</h2>
<p>Setiap event biasanya menawarkan beberapa kategori tiket dengan fasilitas berbeda. Berikut penjelasannya:</p>

<h2>Regular / General Admission</h2>
<p>Tiket paling basic dengan akses ke area umum event. Cocok untuk yang ingin menikmati event dengan budget terjangkau.</p>

<h2>VIP (Very Important Person)</h2>
<p>Akses ke area eksklusif yang lebih dekat dengan panggung atau fasilitas lebih baik. Biasanya termasuk F&B, merchandise, atau lounge khusus.</p>

<h2>VVIP (Very Very Important Person)</h2>
<p>Pengalaman premium tertinggi: akses backstage, meet & greet dengan artis/pembicara, concierge service, dan fasilitas eksklusif lainnya.</p>

<h2>Early Bird</h2>
<p>Harga spesial untuk pembelian jauh-jauh hari. Ini adalah cara terbaik untuk mendapatkan tiket dengan harga lebih murah.</p>

<h2>Tips Memilih Tiket</h2>
<p>Pertimbangkan budget, jarak tempuh dari rumah, dan seberapa besar antusias Anda terhadap event tersebut.</p>',
                'thumbnail'    => null,
                'status'       => 'published',
                'is_featured'  => false,
                'published_at' => now()->subDays(14),
            ],
            [
                'user_id'      => $admin->id,
                'title'        => 'Strategi Mendapatkan Tiket Early Bird Sebelum Habis',
                'slug'         => 'strategi-mendapatkan-tiket-early-bird',
                'excerpt'      => 'Jangan sampai kehabisan tiket early bird favoritmu! Ikuti strategi jitu ini.',
                'content'      => '<h2>Kenapa Tiket Early Bird Selalu Cepat Habis?</h2>
<p>Tiket early bird menawarkan harga 30-50% lebih murah dari harga normal, menjadikannya sangat diminati. Tidak heran jika dalam hitungan menit tiket ini langsung ludes.</p>

<h2>Strategi 1: Aktifkan Notifikasi</h2>
<p>Daftarkan email dan aktifkan notifikasi di platform kami. Anda akan mendapat pemberitahuan pertama saat tiket early bird mulai dijual.</p>

<h2>Strategi 2: Siapkan Akun dan Pembayaran</h2>
<p>Pastikan akun Anda sudah terverifikasi dan metode pembayaran sudah tersimpan. Setiap detik sangat berharga saat war tiket dimulai.</p>

<h2>Strategi 3: Buka di Device yang Cepat</h2>
<p>Gunakan laptop atau PC dengan koneksi internet yang stabil. Refresh halaman beberapa menit sebelum jam penjualan dimulai.</p>

<h2>Strategi 4: Siapkan Beberapa Pilihan Pembayaran</h2>
<p>Jika satu metode pembayaran gagal, langsung coba yang lain. Kegagalan pembayaran adalah alasan paling umum tiket lepas di detik terakhir.</p>',
                'thumbnail'    => null,
                'status'       => 'published',
                'is_featured'  => false,
                'published_at' => now()->subDays(2),
            ],
            [
                'user_id'      => $admin->id,
                'title'        => 'Review: Jakarta Music Festival 2024 yang Spektakuler',
                'slug'         => 'review-jakarta-music-festival-2024',
                'excerpt'      => 'Liputan lengkap JMF 2024: penampilan terbaik, suasana venue, dan pengalaman tak terlupakan.',
                'content'      => '<h2>Jakarta Music Festival 2024: Melampaui Ekspektasi</h2>
<p>JMF 2024 yang berlangsung selama 3 hari di Gelora Bung Karno berhasil memukau lebih dari 150.000 penonton. Berikut ulasan lengkap dari tim kami.</p>

<h2>Highlight Penampilan</h2>
<p>Dari 50 artis yang tampil, beberapa momen yang paling berkesan adalah penampilan kejutan kolaborasi dua musisi legendaris Indonesia di hari kedua yang membuat penonton histeris.</p>

<h2>Kualitas Venue dan Fasilitas</h2>
<p>Penyelenggara tampak jauh lebih siap dibanding tahun sebelumnya. Sistem antrian yang lebih rapi, food court yang tersebar merata, dan toilet portable yang cukup jumlahnya menjadi nilai plus.</p>

<h2>Pengalaman VIP</h2>
<p>Area VIP tahun ini benar-benar worth it. Lounge ber-AC, bar eksklusif, dan view panggung yang sempurna membuat pengalaman semakin mewah.</p>

<h2>Kesimpulan</h2>
<p>Rating: ⭐⭐⭐⭐⭐ (5/5). JMF 2024 adalah festival musik terbaik yang pernah ada di Indonesia. Tidak sabar menunggu JMF 2025!</p>',
                'thumbnail'    => null,
                'status'       => 'published',
                'is_featured'  => true,
                'published_at' => now()->subDays(20),
            ],
            [
                'user_id'      => $admin->id,
                'title'        => 'Panduan Event Organizer untuk Pemula',
                'slug'         => 'panduan-event-organizer-pemula',
                'excerpt'      => 'Ingin menjadi event organizer sukses? Panduan lengkap dari A-Z untuk pemula.',
                'content'      => '<p>Konten sedang dalam proses penulisan...</p>',
                'thumbnail'    => null,
                'status'       => 'draft',
                'is_featured'  => false,
                'published_at' => null,
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }

        $this->command->info('✓ Blogs seeded: ' . count($blogs));
    }
}
