# Mustaka Lite

Mustaka Lite adalah sistem manajemen sekolah yang dirancang untuk memudahkan administrasi kelas, karyawan, siswa, dan pendaftaran siswa baru. Dibangun dengan **Filament PHP v3** dan **Livewire**, aplikasi ini menawarkan dua panel utama: **Admin** dan **PSB** (Penerimaan Siswa Baru).

## Fitur Utama

### 1. Landing Page
- Menyediakan halaman depan informatif untuk pengunjung.

### 2. Pengumuman
- Fitur untuk mengelola dan menampilkan pengumuman penting bagi siswa, karyawan, dan orang tua.

### 3. Penjadwalan Wawancara
- Sistem untuk mengatur jadwal wawancara siswa baru secara efisien.

### 4. Manajemen Pendaftaran Pergelombang
- Mendukung pendaftaran siswa baru berdasarkan gelombang pendaftaran dengan pengelolaan yang mudah.

### 5. Manajemen Pembayaran dan Invoicing
- Fasilitas untuk mengelola pembayaran, menghasilkan faktur (invoice), dan melacak pembayaran yang dilakukan oleh siswa.

### 6. Notifikasi WhatsApp
- Mengintegrasikan notifikasi melalui WhatsApp untuk memberikan informasi secara real-time kepada siswa dan orang tua.

## Panel Admin
- Mengelola semua aspek administrasi sekolah, termasuk data kelas, karyawan, siswa, dan keuangan.

## Panel PSB (Penerimaan Siswa Baru)
- Dirancang khusus untuk mengelola proses pendaftaran siswa baru dari awal hingga selesai, termasuk wawancara dan pembayaran.

## Teknologi yang Digunakan
- **Filament PHP v3**: Framework PHP untuk membangun panel admin.
- **Livewire**: Komponen Laravel untuk membuat aplikasi dinamis tanpa meninggalkan ekosistem Laravel.
- **WhatsApp API**: Untuk notifikasi instan kepada pengguna.

## Instalasi
1. Clone repo ini ke lokal Anda.
    ```bash
    git clone https://github.com/username/mustaka-lite.git
    cd mustaka-lite
    ```
2. Instal dependensi dengan Composer.
    ```bash
    composer install
    ```
3. Salin file `.env.example` menjadi `.env` dan konfigurasi sesuai kebutuhan.
    ```bash
    cp .env.example .env
    ```
4. Jalankan migration dan seeder.
    ```bash
    php artisan migrate --seed
    ```
5. Jalankan server lokal.
    ```bash
    php artisan serve
    ```

## Kontribusi
Kami menerima kontribusi dari siapa saja. Silakan lakukan fork, buat branch fitur baru, dan kirimkan pull request.

## Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).