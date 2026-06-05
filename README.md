# Toko Roti (Bakery App)

Selamat datang di repository proyek **Toko Roti**. Aplikasi ini merupakan platform berbasis web untuk manajemen penjualan dan pesanan toko roti, dibangun menggunakan kerangka kerja (framework) **Laravel**.

## Isi Web & Fitur Utama

Aplikasi Toko Roti dilengkapi dengan fitur-fitur yang dirancang untuk mempermudah operasional toko, baik bagi pelanggan maupun manajemen toko:

1. **Katalog Produk (Breads)**: Menampilkan daftar roti yang tersedia beserta stok dan harganya. Roti dikelompokkan dalam berbagai kategori seperti Roti, Chiffon & Roll Cakes, Pastry, Cookies, dll.
2. **Manajemen Pengguna (User Management)**: 
   - Aplikasi mencatat data pelanggan secara lengkap, termasuk informasi profil (biodata) dan dukungan *multi-address* (satu pelanggan bisa memiliki lebih dari satu alamat pengiriman).
   - Dilengkapi sistem otentikasi.
3. **Role-Based Access Control (RBAC)**: Menggunakan paket [Spatie Permission](https://spatie.be/docs/laravel-permission), membagi hak akses ke dalam dua peran (roles) utama:
   - `Admin`: Memiliki akses tak terbatas ke seluruh sistem, manajemen produk, manajemen stok, dan laporan penjualan.
   - `User`: Hak akses terbatas untuk melihat produk, mengatur profil, dan melakukan pesanan.
4. **Sistem Pesanan (Order)**: Mendukung keranjang belanja dan checkout dengan perhitungan subtotal dan stok secara transaksional. Checkout juga mendukung pengaturan metode pengiriman dan pembayaran (tercatat di tabel `order_checkout_meta`).

---

## Struktur Database (`db_toko_roti.sql`)

Struktur dan logika aplikasi banyak didelegasikan ke level database untuk memastikan integritas data secara independen. Berikut rincian implementasi di dalam file SQL:

### 1. Tabel & Relasi
Tabel-tabel bisnis utama (seperti `users`, `categories`, `breads`, `orders`, `order_items`) menggunakan mesin InnoDB yang memungkinkan adanya *Foreign Key*. Relasi antar tabel ini digunakan untuk menjaga *Referential Integrity*, sehingga penghapusan satu entitas (contoh: user dihapus) akan otomatis menghapus entitas anak (contoh: pesanan yang terkait).

### 2. Spatie Permission Tables
Menggantikan Enum Role pada tabel pengguna, `db_toko_roti.sql` sekarang memuat struktur tabel RBAC dari Spatie Permission, yaitu: `permissions`, `roles`, `model_has_permissions`, `model_has_roles`, dan `role_has_permissions`.

### 3. Index
Index dibuat pada kolom yang sering dijadikan kriteria pencarian dan *filtering*, seperti `name` pada tabel roti, `email` pada user, dan `status` pada tabel pesanan. Ini diterapkan pada level B-Tree di MySQL untuk mempercepat proses query *read* secara signifikan.

### 4. View
Tabel virtual (*View*) diimplementasikan untuk meringkas *query* yang rumit, menjadikannya sebuah *view* data satu pintu. 
- `view_available_breads`: Menampilkan roti dengan stok lebih dari 0.
- `view_user_orders` & `view_order_details`: Mempermudah akses data pelaporan pesanan pelanggan tanpa harus membuat JOIN manual terus-menerus.

### 5. Trigger
Trigger ditanam di level database untuk event `INSERT`, `UPDATE`, maupun `DELETE` di tabel tertentu guna mengotomatisasi beberapa logika, antara lain:
- Validasi stok secara kaku (jika stok kurang, maka insert `order_items` digagalkan).
- Kalkulasi `subtotal` secara otomatis ketika memasukkan item belanjaan.
- Mencegah perubahan maupun penghapusan pada log masuk/akses admin (`admin_access_logs`).

### 6. Stored Procedure
Blok kode *Stored Procedure* disiapkan untuk menampung *query* beruntun demi menjaga *performance* dengan kompilasi di tingkat *database server*. Contoh prosedurnya meliputi: `sp_checkout_order_bulk` untuk membungkus pembuatan pesanan utama, serta prosedur restock barang dan perubahan status order.

### 7. User Privilege
Untuk keamanan akses *database server* itu sendiri, terdapat pemisahan hak akses menggunakan User Database:
- `alexander` & `admin_db`: Administrator database yang memiliki privilese secara penuh (`GRANT ALL PRIVILEGES`).
- `kasir_db`: Akun yang memiliki akses minimal / terbatas (`SELECT, INSERT, UPDATE`) hanya pada tabel kasir spesifik (seperti `orders` dan `order_items`).

---

## Cara Penggunaan

1. **Instalasi Dependensi**
   Pastikan Anda sudah menjalankan perintah instalasi composer:
   ```bash
   composer install
   ```

2. **Instalasi Spatie Permission (Opsional jika belum)**
   Jika library manajemen role belum dipasang, instal melalui composer:
   ```bash
   composer require spatie/laravel-permission
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   php artisan migrate
   ```

3. **Import Database**
   Karena database dump telah disusun secara lengkap di file `db_toko_roti.sql`, silakan jalankan impor file tersebut langsung ke MySQL/MariaDB:
   ```bash
   mysql -u root -p db_toko_roti < db_toko_roti.sql
   ```
   *(Atau bisa juga diimpor menggunakan tools antarmuka seperti phpMyAdmin, DBeaver, dll)*

4. **Menjalankan Aplikasi**
   Setelah `.env` dikonfigurasi dan diarahkan ke database `db_toko_roti`, jalankan server lokal Laravel:
   ```bash
   php artisan serve
   ```
