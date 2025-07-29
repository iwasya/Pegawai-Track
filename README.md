# Pegawai-Track
![WhatsApp Image 2025-06-29 at 18 57 03_7208dd40](https://github.com/user-attachments/assets/ffe16dcd-357d-4708-a585-05a820a0a3c5)

Pegawai-Track adalah aplikasi web berbasis Laravel yang berfungsi untuk mencatat absensi dan menghitung gaji pegawai secara otomatis dan efisien, cocok digunakan untuk kebutuhan internal manajemen SDM perusahaan.

## Fitur dan Fungsionalitas

*   **Manajemen User:**
    *   Tambah, edit, dan hapus user dengan role yang berbeda (admin, HRD, pegawai).
    *   Pengaturan status akun (aktif/nonaktif).
    *   Export data user ke file Excel.
    *   Menampilkan daftar user yang sedang aktif (online) dan IP Address terakhir.

*   **Manajemen Jabatan:**
    *   Tambah, edit, dan hapus data jabatan.
    *   Input nama jabatan dan gaji pokok.
    *   Export data jabatan ke file Excel.

*   **Manajemen Pegawai:**
    *   Tambah, edit, dan hapus data pegawai (soft delete).
    *   Input NIP, nama lengkap, jabatan, jenis kelamin, tanggal lahir, tanggal masuk, nomor telepon, alamat, email, status kerja, dan foto.
    *   Restore data pegawai yang terhapus.
    *   Hapus permanen data pegawai.
    *   Export data pegawai ke file Excel.

*   **Manajemen Absensi:**
    *   (Fitur ini belum diimplementasikan secara detail berdasarkan file yang ada, namun terdapat di navigasi sidebar sehingga diasumsikan ada).

*   **Manajemen Cuti:**
    *   Pengajuan cuti oleh pegawai dengan pilihan jenis cuti (tahunan, sakit, khusus).
    *   Upload foto sebagai bukti (khusus cuti sakit).
    *   Persetujuan/penolakan cuti oleh HRD.
    *   Export data cuti ke file Excel.

*   **Manajemen Jadwal Kerja:**
    *   Tambah, edit, dan hapus jadwal kerja pegawai.
    *   Input hari, tanggal, shift, jam mulai, jam selesai, dan keterangan.
    *   Generate jadwal kerja mingguan secara otomatis.
    *   Export data jadwal kerja ke file Excel.

*   **Manajemen Gaji:**
    *   (Fitur ini belum diimplementasikan secara detail berdasarkan file yang ada, namun terdapat di navigasi sidebar sehingga diasumsikan ada).

*   **Laporan:**
    *   (Fitur ini belum diimplementasikan secara detail berdasarkan file yang ada, namun terdapat di navigasi sidebar sehingga diasumsikan ada).

*   **Profil Pegawai:**
    *   Pegawai dapat melihat data profilnya sendiri.
    *   Menampilkan jadwal kerja pegawai (kalender).
    *   Menampilkan tanggal merah (libur nasional).

*   **Autentikasi dan Otorisasi:**
    *   Login dan register user.
    *   Logout user.
    *   Middleware untuk membatasi akses berdasarkan role (admin, HRD, pegawai).
    *   Pengecekan status akun (aktif/nonaktif) sebelum login.

*   **Log Aktivitas:**
    *   Mencatat aktivitas login user (ID user, aktivitas, tanggal/waktu, IP address).

## Teknologi yang Digunakan

*   **PHP:** Bahasa pemrograman utama.
*   **Laravel:** Framework PHP yang digunakan.
*   **MySQL:** Database yang digunakan (diasumsikan berdasarkan konfigurasi database).
*   **Bootstrap:** Framework CSS untuk tampilan antarmuka.
*   **Maatwebsite/Excel:** Library untuk export data ke file Excel.
*   **Calendarific API:** API untuk mendapatkan data tanggal merah (libur nasional).
*   **FullCalendar:** Library JavaScript untuk menampilkan kalender jadwal pegawai.
*   **Composer:** Dependency Manager untuk PHP

## Prasyarat

*   PHP >= 8.1
*   Composer
*   MySQL
*   Web Server (Apache atau Nginx)
*   Node.js dan NPM (jika ingin melakukan kompilasi ulang assets)
*   API Key untuk Calendarific (optional)

## Instalasi

1.  Clone repositori:

    ```bash
    git clone https://github.com/iwasya/Pegawai-Track.git
    cd Pegawai-Track
    ```

2.  Install dependencies Composer:

    ```bash
    composer install
    ```

3.  Copy file `.env.example` menjadi `.env`:

    ```bash
    cp .env.example .env
    ```

4.  Generate application key:

    ```bash
    php artisan key:generate
    ```

5.  Konfigurasi database pada file `.env`.  Sesuaikan dengan konfigurasi database MySQL Anda:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pegawai_track  # Ganti dengan nama database Anda
    DB_USERNAME=root         # Ganti dengan username database Anda
    DB_PASSWORD=             # Ganti dengan password database Anda
    ```

6.  Migrasi database:

    ```bash
    php artisan migrate
    ```

7.  Jalankan seeder (opsional, jika ingin data dummy):
        ```bash
    php artisan db:seed
    ```

8.  Symlink direktori `storage`:

    ```bash
    php artisan storage:link
    ```

9.  Install dan compile assets (jika diperlukan):

    ```bash
    npm install
    npm run build
    ```

10. Jalankan server:

    ```bash
    php artisan serve
    ```

    Aplikasi akan berjalan pada `http://localhost:8000`.

## Panduan Penggunaan

1.  Akses aplikasi melalui browser (`http://localhost:8000`).
2.  Login dengan akun yang sudah terdaftar. Jika belum memiliki akun Hubungi HRD.
3.  Setelah login, Anda akan diarahkan ke halaman dashboard.
4.  Navigasi ke menu-menu yang tersedia di sidebar untuk mengelola data pegawai, absensi, cuti, jadwal, dan lainnya.
5.  Gunakan fitur pencarian dan filter untuk memudahkan pengelolaan data.
6.  Gunakan tombol export untuk mengekspor data ke format Excel.

### Konfigurasi Tambahan
* Untuk menggunakan fitur upload foto cuti, pastikan direktori `storage/app/public/foto_cuti` memiliki permission yang benar (writeable).
* Untuk mendapatkan data libur nasional, daftarkan API Key di [Calendarific](https://calendarific.com/) dan tambahkan ke file `.env`
    ```
    CALENDARIFIC_API_KEY=YOUR_API_KEY
    ```

## API Documentation

Saat ini tidak ada API khusus yang diimplementasikan.  Aplikasi ini berfokus pada antarmuka web untuk manajemen data.

## Kontribusi

Kontribusi sangat dipersilakan! Jika Anda ingin berkontribusi pada proyek ini, silakan ikuti langkah-langkah berikut:

1.  Fork repositori ini.
2.  Buat branch dengan nama fitur atau perbaikan bug: `git checkout -b fitur-baru` atau `git checkout -b perbaikan-bug`.
3.  Lakukan perubahan dan commit dengan pesan yang jelas.
4.  Push branch ke repositori Anda.
5.  Buat Pull Request ke repositori ini.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).


## Kontak/Support

*   [Iwasya](https://github.com/iwasya)
*   Silakan ajukan issue di GitHub untuk pertanyaan atau laporan bug.
