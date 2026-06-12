<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::truncate();

        $faqs = [
            // Umum
            [
                'question'  => 'Apa itu ' . setting('app_name') . '?',
                'answer'    => setting('app_name') . ' adalah platform ticketing event online terpercaya di Indonesia yang memudahkan Anda membeli tiket berbagai event seperti konser, seminar, festival, olahraga, dan masih banyak lagi. Kami berkomitmen memberikan pengalaman pembelian tiket yang mudah, aman, dan cepat.',
                'is_active' => true,
                'order'     => 1,
            ],
            [
                'question'  => 'Apakah perlu membuat akun untuk membeli tiket?',
                'answer'    => 'Ya, Anda perlu membuat akun untuk membeli tiket. Akun diperlukan untuk menyimpan riwayat pembelian, mengelola tiket, dan memudahkan proses refund jika diperlukan. Pendaftaran akun 100% gratis dan hanya membutuhkan email dan password.',
                'is_active' => true,
                'order'     => 2,
            ],
            [
                'question'  => 'Apakah data pribadi saya aman?',
                'answer'    => 'Keamanan data Anda adalah prioritas utama kami. Semua data dienkripsi menggunakan SSL 256-bit dan kami tidak pernah menjual atau membagikan data pribadi Anda kepada pihak ketiga tanpa izin. Kami juga mematuhi peraturan perlindungan data yang berlaku di Indonesia.',
                'is_active' => true,
                'order'     => 3,
            ],

            // Pembelian
            [
                'question'  => 'Bagaimana cara membeli tiket?',
                'answer'    => 'Cara membeli tiket sangat mudah: (1) Buat akun atau login, (2) Cari event yang diinginkan, (3) Pilih jenis dan jumlah tiket, (4) Klik "Beli Tiket", (5) Selesaikan pembayaran via Midtrans. Setelah pembayaran dikonfirmasi, tiket digital langsung tersedia di akun Anda.',
                'is_active' => true,
                'order'     => 4,
            ],
            [
                'question'  => 'Berapa batas maksimal tiket yang bisa dibeli dalam satu transaksi?',
                'answer'    => 'Batas pembelian tiket per transaksi bervariasi tergantung kebijakan setiap event dan jenis tiket. Biasanya berkisar antara 1-10 tiket per transaksi. Batas ini tertera di halaman pembelian masing-masing tiket.',
                'is_active' => true,
                'order'     => 5,
            ],
            [
                'question'  => 'Berapa lama waktu untuk menyelesaikan pembayaran?',
                'answer'    => 'Anda memiliki waktu 24 jam untuk menyelesaikan pembayaran setelah order dibuat. Jika pembayaran tidak diselesaikan dalam waktu tersebut, order akan otomatis dibatalkan dan kuota tiket dikembalikan ke sistem.',
                'is_active' => true,
                'order'     => 6,
            ],
            [
                'question'  => 'Bisakah saya membeli tiket untuk orang lain?',
                'answer'    => 'Ya, Anda bisa membeli tiket untuk orang lain. Namun perlu diperhatikan bahwa data pemegang tiket akan tercatat atas nama akun pembeli. Beberapa event mungkin memerlukan verifikasi identitas pemegang tiket di lokasi event.',
                'is_active' => true,
                'order'     => 7,
            ],

            // Pembayaran
            [
                'question'  => 'Metode pembayaran apa saja yang tersedia?',
                'answer'    => 'Kami mendukung berbagai metode pembayaran melalui Midtrans, termasuk: Transfer Bank (BCA, Mandiri, BNI, BRI, dan lainnya), Kartu Kredit/Debit (Visa, Mastercard, JCB), E-Wallet (GoPay, OVO, Dana, ShopeePay), dan QRIS. Semua transaksi diproses secara aman oleh Midtrans yang tersertifikasi PCI-DSS.',
                'is_active' => true,
                'order'     => 8,
            ],
            [
                'question'  => 'Apakah ada biaya tambahan saat membayar?',
                'answer'    => 'Harga yang tertera sudah merupakan harga final termasuk semua biaya. Tidak ada biaya tersembunyi atau biaya layanan tambahan. Beberapa metode pembayaran seperti kartu kredit mungkin dikenakan biaya tambahan sesuai kebijakan bank penerbit kartu Anda.',
                'is_active' => true,
                'order'     => 9,
            ],
            [
                'question'  => 'Pembayaran saya gagal, apa yang harus dilakukan?',
                'answer'    => 'Jika pembayaran gagal, order Anda masih aktif selama 24 jam. Anda bisa mencoba kembali pembayaran dengan metode yang sama atau berbeda melalui halaman "Pesanan Saya". Jika masalah berlanjut, hubungi tim support kami via WhatsApp atau email.',
                'is_active' => true,
                'order'     => 10,
            ],
            [
                'question'  => 'Kapan pembayaran saya dikonfirmasi?',
                'answer'    => 'Pembayaran via e-wallet dan kartu kredit biasanya dikonfirmasi secara instan (real-time). Transfer bank membutuhkan waktu 1-15 menit untuk dikonfirmasi secara otomatis. Setelah dikonfirmasi, Anda akan menerima email konfirmasi dan tiket langsung tersedia di akun Anda.',
                'is_active' => true,
                'order'     => 11,
            ],

            // Tiket
            [
                'question'  => 'Bagaimana cara mendapatkan tiket setelah pembayaran?',
                'answer'    => 'Tiket digital (e-ticket) akan otomatis tersedia di akun Anda setelah pembayaran dikonfirmasi. Anda juga akan menerima email berisi detail tiket. Tiket dapat diakses kapan saja melalui menu "Tiket Saya" di website.',
                'is_active' => true,
                'order'     => 12,
            ],
            [
                'question'  => 'Bagaimana cara menggunakan e-ticket di lokasi event?',
                'answer'    => 'Buka tiket Anda di menu "Tiket Saya", lalu tunjukkan QR code pada layar smartphone kepada petugas di pintu masuk. Pastikan kecerahan layar maksimal agar QR code mudah di-scan. Tidak perlu print tiket, cukup tunjukkan dari smartphone.',
                'is_active' => true,
                'order'     => 13,
            ],
            [
                'question'  => 'Apa yang harus dilakukan jika tiket tidak muncul setelah pembayaran?',
                'answer'    => 'Jika tiket belum muncul 30 menit setelah pembayaran dikonfirmasi, coba refresh halaman "Tiket Saya". Jika masih tidak muncul, hubungi tim support kami dengan menyertakan nomor order dan bukti pembayaran. Kami akan membantu dalam waktu 1x24 jam.',
                'is_active' => true,
                'order'     => 14,
            ],
            [
                'question'  => 'Apakah tiket bisa dipindahtangankan ke orang lain?',
                'answer'    => 'Kebijakan pemindahtanganan tiket berbeda-beda tergantung penyelenggara event. Secara umum, tiket tidak dapat dipindahtangankan karena terikat dengan akun pembeli. Untuk informasi lebih detail, silakan hubungi penyelenggara event terkait.',
                'is_active' => true,
                'order'     => 15,
            ],

            // Refund & Pembatalan
            [
                'question'  => 'Apakah tiket bisa dikembalikan atau direfund?',
                'answer'    => 'Refund dapat diajukan jika: (1) Event dibatalkan oleh penyelenggara, (2) Ada keadaan darurat yang dapat dibuktikan, (3) Pengajuan dilakukan sebelum event dimulai. Proses review membutuhkan 1-2 hari kerja dan dana dikembalikan dalam 3-5 hari kerja setelah disetujui.',
                'is_active' => true,
                'order'     => 16,
            ],
            [
                'question'  => 'Bagaimana cara mengajukan refund?',
                'answer'    => 'Untuk mengajukan refund: (1) Login ke akun, (2) Buka "Pesanan Saya", (3) Pilih order yang ingin direfund, (4) Klik "Ajukan Refund", (5) Isi alasan dan informasi rekening bank tujuan, (6) Submit. Tim kami akan memproses pengajuan Anda dalam 1-2 hari kerja.',
                'is_active' => true,
                'order'     => 17,
            ],
            [
                'question'  => 'Bisakah saya membatalkan order yang masih pending?',
                'answer'    => 'Ya, order dengan status "Pending" (belum dibayar) dapat dibatalkan kapan saja sebelum expired. Buka halaman detail order dan klik tombol "Batalkan Order". Pembatalan order pending tidak memerlukan proses refund karena belum ada pembayaran.',
                'is_active' => true,
                'order'     => 18,
            ],

            // Akun
            [
                'question'  => 'Bagaimana cara mengubah password?',
                'answer'    => 'Untuk mengubah password: (1) Login ke akun, (2) Buka menu "Profil", (3) Pilih tab "Keamanan", (4) Masukkan password lama dan password baru, (5) Klik "Update Password". Jika lupa password, gunakan fitur "Lupa Password" di halaman login.',
                'is_active' => true,
                'order'     => 19,
            ],
            [
                'question'  => 'Bagaimana cara menghapus akun?',
                'answer'    => 'Untuk menghapus akun secara permanen: Buka Profil → tab Zona Bahaya → Hapus Akun. Penghapusan akun bersifat permanen dan tidak dapat dibatalkan. Semua data, riwayat order, dan tiket akan terhapus.',
                'is_active' => true,
                'order'     => 20,
            ],
            [
                'question'  => 'Bagaimana jika saya lupa email yang digunakan untuk mendaftar?',
                'answer'    => 'Jika Anda lupa email yang digunakan, hubungi tim support kami via WhatsApp atau email support. Sertakan informasi identitas Anda (nama lengkap, nomor HP, atau nomor order) untuk verifikasi. Tim kami akan membantu menemukan akun Anda.',
                'is_active' => true,
                'order'     => 21,
            ],

            // Lainnya
            [
                'question'  => 'Bagaimana cara menghubungi tim support?',
                'answer'    => 'Anda bisa menghubungi tim support kami melalui WhatsApp, Email, atau form kontak di website. Tim support kami siap membantu Senin-Minggu pukul 08.00-22.00 WIB.',
                'is_active' => true,
                'order'     => 22,
            ],
            [
                'question'  => 'Apakah ada aplikasi mobile yang tersedia?',
                'answer'    => 'Saat ini ' . config('app.name') . ' dapat diakses melalui browser mobile dengan tampilan yang sudah dioptimalkan untuk smartphone. Aplikasi native (iOS & Android) sedang dalam pengembangan dan akan segera tersedia.',
                'is_active' => true,
                'order'     => 23,
            ],
            [
                'question'  => 'Saya adalah event organizer, bagaimana cara mendaftarkan event saya?',
                'answer'    => 'Kami dengan senang hati bekerja sama dengan event organizer! Untuk mendaftarkan event Anda di platform kami, silakan hubungi tim kami via email dengan subject "Partnership EO". Tim kami akan menghubungi Anda dalam 1-2 hari kerja.',
                'is_active' => true,
                'order'     => 24,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('✓ FAQs seeded: ' . count($faqs));
    }
}
