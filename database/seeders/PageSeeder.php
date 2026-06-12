<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['type' => 'privacy'],
            [
                'title'        => 'Kebijakan Privasi',
                'slug'         => 'kebijakan-privasi',
                'type'         => 'privacy',
                'is_active'    => true,
                'published_at' => now(),
                'content'      => $this->privacyContent(),
            ]
        );

        Page::updateOrCreate(
            ['type' => 'terms'],
            [
                'title'        => 'Syarat & Ketentuan',
                'slug'         => 'syarat-ketentuan',
                'type'         => 'terms',
                'is_active'    => true,
                'published_at' => now(),
                'content'      => $this->termsContent(),
            ]
        );
    }

    private function privacyContent(): string
    {
        return '<h2>1. Informasi yang Kami Kumpulkan</h2>
<p>Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, seperti nama, alamat email, nomor telepon, dan informasi pembayaran saat Anda membuat akun atau melakukan pembelian tiket.</p>

<h2>2. Penggunaan Informasi</h2>
<p>Informasi yang kami kumpulkan digunakan untuk:</p>
<ul>
<li>Memproses transaksi dan mengirimkan tiket elektronik</li>
<li>Mengirimkan notifikasi terkait event dan pembelian</li>
<li>Meningkatkan layanan dan pengalaman pengguna</li>
<li>Memenuhi kewajiban hukum yang berlaku</li>
</ul>

<h2>3. Keamanan Data</h2>
<p>Kami menggunakan enkripsi SSL dan langkah-langkah keamanan industri standar untuk melindungi data pribadi Anda dari akses tidak sah.</p>

<h2>4. Berbagi Informasi</h2>
<p>Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Informasi hanya dibagikan kepada mitra terpercaya yang membantu kami menjalankan layanan.</p>

<h2>5. Hak Pengguna</h2>
<p>Anda berhak untuk mengakses, memperbarui, atau menghapus data pribadi Anda kapan saja melalui halaman profil atau dengan menghubungi kami.</p>

<h2>6. Perubahan Kebijakan</h2>
<p>Kami berhak memperbarui kebijakan privasi ini sewaktu-waktu. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di platform.</p>

<h2>7. Kontak</h2>
<p>Jika Anda memiliki pertanyaan terkait kebijakan privasi, silakan hubungi kami di privacy@ticketin.id</p>';
    }

    private function termsContent(): string
    {
        return '<h2>1. Penerimaan Syarat</h2>
<p>Dengan menggunakan platform kami, Anda menyetujui syarat dan ketentuan yang berlaku. Jika tidak setuju, harap tidak menggunakan layanan kami.</p>

<h2>2. Penggunaan Akun</h2>
<p>Anda bertanggung jawab menjaga kerahasiaan akun dan password. Setiap aktivitas yang terjadi melalui akun Anda adalah tanggung jawab Anda.</p>

<h2>3. Pembelian Tiket</h2>
<ul>
<li>Pembelian tiket bersifat final setelah pembayaran dikonfirmasi</li>
<li>Tiket tidak dapat dipindahtangankan tanpa persetujuan penyelenggara</li>
<li>Pembeli wajib menyimpan kode tiket dan QR code dengan aman</li>
</ul>

<h2>4. Kebijakan Refund</h2>
<p>Refund dapat diajukan dalam kondisi:</p>
<ul>
<li>Event dibatalkan oleh penyelenggara</li>
<li>Terjadi kesalahan teknis dalam pemrosesan pembayaran</li>
<li>Permintaan refund diajukan dalam 24 jam setelah pembelian</li>
</ul>

<h2>5. Larangan Penggunaan</h2>
<p>Pengguna dilarang:</p>
<ul>
<li>Melakukan pemalsuan tiket atau QR code</li>
<li>Menjual kembali tiket di atas harga resmi (scalping)</li>
<li>Menggunakan platform untuk aktivitas ilegal</li>
</ul>

<h2>6. Batasan Tanggung Jawab</h2>
<p>Platform kami tidak bertanggung jawab atas kerugian yang timbul akibat pembatalan event oleh penyelenggara, bencana alam, atau kejadian di luar kendali kami.</p>

<h2>7. Hukum yang Berlaku</h2>
<p>Syarat dan ketentuan ini diatur oleh hukum Republik Indonesia. Segala sengketa akan diselesaikan melalui pengadilan yang berwenang di Indonesia.</p>';
    }
}
