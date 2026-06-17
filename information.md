# Dokumentasi Sistem Informasi dan Basis Data Toko Roti

Dokumen ini berisi penjelasan menyeluruh mengenai rancangan database **db_toko_roti** yang digunakan pada aplikasi Toko Roti (Laravel & Filament). Pembahasan ini mencakup deskripsi sistem, diagram hubungan entitas (ERD), struktur tabel, dan implementasi dari komponen-komponen database seperti *Database Users*, *Indexes*, *Views*, *Triggers*, dan *Stored Procedures*.

---

## 1. Deskripsi Singkat Sistem Informasi Toko Roti

Sistem Informasi Toko Roti dirancang sebagai aplikasi *e-commerce* dan manajemen operasional toko roti modern. Sistem ini memfasilitasi dua peran utama:
1. **Pelanggan (Customer)**: Dapat menjelajahi menu roti berdasarkan kategori, mengelola beberapa alamat pengiriman, menempatkan pesanan (*checkout*), menentukan metode pengiriman (*instant delivery* atau *store pickup*), dan melakukan pembayaran digital (seperti QRIS).
2. **Staf Toko (Admin, Kasir, Gudang, Manager)**: Dapat mengelola inventaris stok roti, memproses transaksi pesanan, mengelola hak akses pengguna secara dinamis melalui mekanisme *Role-Based Access Control* (RBAC), serta memantau log akses panel admin untuk keperluan audit.

Seluruh logika bisnis kritis (seperti validasi stok, penghitungan subtotal belanja secara otomatis, dan perlindungan riwayat log audit) diperkuat langsung di tingkat basis data menggunakan *constraints*, *triggers*, dan *stored procedures* demi menjamin integritas data yang tinggi.

---

## 2. Entity Relationship Diagram (ERD) dan Penjelasan Relasi

#
```

### Penjelasan Entitas dan Relasi Utama

1. **`users` & `user_profiles` (One-to-One / 1:1)**
   Setiap pengguna (`users`) memiliki tepat satu profil detail (`user_profiles`) yang menyimpan informasi tambahan seperti nomor telepon, tanggal lahir, jenis kelamin, dan alamat utama. Relasi ini menggunakan *Foreign Key* `user_id` di tabel `user_profiles` yang merujuk pada `id` di tabel `users` dengan aturan `ON DELETE CASCADE`.
   
2. **`users` & `user_addresses` (One-to-Many / 1:N)**
   Setiap pelanggan dapat menyimpan lebih dari satu alamat pengiriman (misal: "Rumah", "Kantor"). Atribut `is_default` digunakan untuk menandai alamat pengiriman utama. Relasi diikat oleh `user_id` di tabel `user_addresses`.

3. **`categories` & `breads` (One-to-Many / 1:N)**
   Setiap roti (`breads`) tergolong ke dalam satu kategori produk (`categories`), seperti Roti, Pastry, Cookies, dll. Relasi diikat oleh `category_id` di tabel `breads`.

4. **`orders` & `order_items` (One-to-Many / 1:N) dan `breads` & `order_items` (1:N)**
   Sebuah transaksi pesanan (`orders`) dapat terdiri dari beberapa item roti (`order_items`). Tabel `order_items` berfungsi sebagai tabel perantara (*associative table*) yang menghubungkan `orders` dan `breads`, mencatat jumlah kuantitas roti yang dibeli beserta subtotal harganya.

5. **`orders` & `order_checkout_meta` (One-to-One / 1:1)**
   Setiap pesanan memiliki tepat satu informasi detail *checkout* tambahan (`order_checkout_meta`), seperti metode pengiriman (*pickup* / *instant*), waktu ambil, catatan pesanan, alamat pengiriman aktual, status pembayaran, dan waktu kedaluwarsa pembayaran QRIS. Relasi diikat oleh `order_id` yang bersifat unik (`UNIQUE KEY`) di tabel `order_checkout_meta`.

6. **`users` & `admin_access_logs` (One-to-Many / 1:N)**
   Mencatat riwayat akses pengguna (khusus administrator) ke panel admin. Setiap kali administrator login, sistem mencatat alamat IP, *user agent* browser, dan waktu akses (`accessed_at`). Relasi diikat oleh `user_id`.

7. **Spatie RBAC (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`)**
   Skema manajemen hak akses berbasis peran (RBAC) dinamis. Menghubungkan pengguna dengan peran tertentu (Admin, Kasir, Gudang, dll.) dan peran tersebut dengan izin akses spesifik (seperti `create_bread`, `view_any_order`).

---

## 3. Dokumentasi Struktur Tabel dan Tipe Data

Berikut adalah rincian struktur tabel bisnis utama yang diimplementasikan dalam skema database:

### A. Tabel `users`
Menyimpan kredensial otentikasi akun pengguna.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik pengguna |
| `name` | VARCHAR(100) | NOT NULL | Nama lengkap pengguna |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | Alamat email unik untuk login |
| `password` | VARCHAR(255) | NOT NULL | Password terenkripsi (bcrypt) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Tanggal pendaftaran akun |
| `updated_at` | TIMESTAMP | NULL, ON UPDATE CURRENT_TIMESTAMP | Tanggal pembaruan akun terakhir |

### B. Tabel `user_profiles`
Menyimpan biodata pelengkap pengguna.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik profil |
| `user_id` | BIGINT UNSIGNED | NOT NULL, UNIQUE, FOREIGN KEY -> `users(id)` ON DELETE CASCADE | ID pengguna pemilik profil |
| `address` | TEXT | NULL | Alamat tempat tinggal |
| `phone` | VARCHAR(20) | NULL | Nomor telepon/WhatsApp |
| `birth_date` | DATE | NULL | Tanggal lahir |
| `gender` | ENUM('Male', 'Female', 'Other') | NULL | Jenis kelamin |

### C. Tabel `user_addresses`
Menyimpan opsi daftar alamat pengiriman multi-address pelanggan.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik alamat |
| `user_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `users(id)` ON DELETE CASCADE | ID pengguna pemilik alamat |
| `label` | VARCHAR(50) | NOT NULL | Nama label alamat (Rumah, Kantor, dll) |
| `recipient_name` | VARCHAR(100) | NOT NULL | Nama penerima paket |
| `phone` | VARCHAR(20) | NOT NULL | Nomor telepon penerima |
| `address` | TEXT | NOT NULL | Alamat pengiriman lengkap |
| `is_default` | TINYINT(1) | NOT NULL, DEFAULT 0 | Menandakan alamat pengiriman utama (1 = Ya, 0 = Tidak) |
| `created_at`| TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Tanggal pembuatan |
| `updated_at`| TIMESTAMP | NULL, ON UPDATE CURRENT_TIMESTAMP | Tanggal perubahan |

### D. Tabel `categories`
Menyimpan daftar kategori produk roti.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik kategori |
| `name` | VARCHAR(50) | NOT NULL | Nama kategori (Roti, Cakes, Pastry, dll) |

### E. Tabel `breads`
Menyimpan informasi menu produk roti dan sediaan stok.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik produk roti |
| `category_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `categories(id)` ON DELETE CASCADE | ID kategori roti |
| `name` | VARCHAR(100) | NOT NULL | Nama produk roti |
| `description` | TEXT | NULL | Deskripsi rasa/bahan roti |
| `image_path` | VARCHAR(255) | DEFAULT NULL | Path file gambar roti di storage |
| `price` | DECIMAL(10,2) | NOT NULL | Harga satuan roti |
| `stock` | INT | NOT NULL, DEFAULT 0 | Ketersediaan stok fisik |

### F. Tabel `orders`
Menyimpan transaksi pemesanan utama.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik pesanan |
| `user_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `users(id)` ON DELETE CASCADE | ID pembeli (pelanggan) |
| `total_amount` | DECIMAL(10,2) | NOT NULL, DEFAULT 0.00 | Total nominal belanja keseluruhan |
| `status` | ENUM('Pending', 'Processing', 'Completed', 'Cancelled') | DEFAULT 'Pending' | Tahap status proses transaksi |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Tanggal transaksi dibuat |
| `updated_at` | TIMESTAMP | NULL, ON UPDATE CURRENT_TIMESTAMP | Tanggal status diperbarui |

### G. Tabel `order_items`
Menyimpan daftar item detail roti yang dibeli di setiap transaksi.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik detail pesanan |
| `order_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `orders(id)` ON DELETE CASCADE | ID induk pesanan |
| `bread_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `breads(id)` ON DELETE CASCADE | ID produk roti yang dibeli |
| `quantity` | INT | NOT NULL | Jumlah item roti yang dibeli |
| `subtotal` | DECIMAL(10,2) | NOT NULL | Subtotal harga item (`price * quantity`) |

### H. Tabel `order_checkout_meta`
Menyimpan metadata checkout tambahan (opsi logistik dan alur pembayaran QRIS).
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik metadata |
| `order_id` | BIGINT UNSIGNED | NOT NULL, UNIQUE, FOREIGN KEY -> `orders(id)` ON DELETE CASCADE | ID transaksi pesanan |
| `delivery_method` | VARCHAR(20) | NOT NULL, DEFAULT 'instant' | Opsi pengiriman (`instant` atau `pickup`) |
| `pickup_time` | VARCHAR(5) | NULL | Jam pengambilan jika memilih opsi `pickup` |
| `order_notes` | TEXT | NULL | Catatan tambahan dari pembeli |
| `shipping_address` | TEXT | NULL | Alamat tujuan pengiriman fisik aktual |
| `payment_method` | VARCHAR(20) | NOT NULL, DEFAULT 'qris' | Metode pembayaran digital (default: `qris`) |
| `payment_expires_at`| TIMESTAMP | NULL | Batas waktu pembayaran (10 menit sejak checkout) |
| `paid_at` | TIMESTAMP | NULL | Waktu konfirmasi pelunasan pembayaran |
| `expired_at` | TIMESTAMP | NULL | Waktu transaksi kedaluwarsa karena telat bayar |
| `created_at` | TIMESTAMP | NULL | Waktu pembuatan metadata |
| `updated_at` | TIMESTAMP | NULL | Waktu perubahan metadata |

### I. Tabel `admin_access_logs`
Menyimpan log audit keamanan akses masuk administrator.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik log |
| `user_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `users(id)` ON DELETE CASCADE | ID user admin yang melakukan akses |
| `ip_address` | VARCHAR(45) | NOT NULL | Alamat IP perangkat pengakses |
| `user_agent` | TEXT | NOT NULL | Detail string browser dan OS pengakses |
| `accessed_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu saat aktivitas akses terjadi |

### J. Tabel Spatie Permission (RBAC)

Tabel-tabel ini digunakan oleh Spatie Laravel-Permission untuk mengatur otorisasi berbasis peran secara dinamis.

#### 1. Tabel `permissions`
Menyimpan daftar hak akses/izin individual yang ada di aplikasi.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik izin |
| `name` | VARCHAR(255) | NOT NULL | Nama izin (e.g., `create_bread`) |
| `guard_name` | VARCHAR(255) | NOT NULL | Guard yang memvalidasi (default: `web`) |
| `created_at` | TIMESTAMP | NULL | Tanggal izin didaftarkan |
| `updated_at` | TIMESTAMP | NULL | Tanggal izin diperbarui |

#### 2. Tabel `roles`
Menyimpan daftar peran (roles) pengguna.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik peran |
| `name` | VARCHAR(255) | NOT NULL | Nama peran (e.g., `Admin`, `Kasir`, `Gudang`) |
| `guard_name` | VARCHAR(255) | NOT NULL | Guard yang memvalidasi (default: `web`) |
| `created_at` | TIMESTAMP | NULL | Tanggal peran didaftarkan |
| `updated_at` | TIMESTAMP | NULL | Tanggal peran diperbarui |

#### 3. Tabel `role_has_permissions`
Relasi Many-to-Many antara tabel `roles` dan `permissions`.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `permission_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `permissions(id)` ON DELETE CASCADE | ID izin yang diberikan |
| `role_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `roles(id)` ON DELETE CASCADE | ID peran penerima izin |

#### 4. Tabel `model_has_roles`
Relasi polimorfik Many-to-Many yang menghubungkan Peran dengan Model Pengguna (biasanya `App\Models\User`).
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `role_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `roles(id)` ON DELETE CASCADE | ID peran yang dimiliki pengguna |
| `model_type` | VARCHAR(255) | NOT NULL | Namespace Class Model (e.g., `App\Models\User`) |
| `model_id` | BIGINT UNSIGNED | NOT NULL | ID instansi pengguna |

#### 5. Tabel `model_has_permissions`
Relasi polimorfik Many-to-Many yang menghubungkan Izin secara langsung ke Model Pengguna (melewati Peran).
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `permission_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY -> `permissions(id)` ON DELETE CASCADE | ID izin yang diberikan langsung |
| `model_type` | VARCHAR(255) | NOT NULL | Namespace Class Model (e.g., `App\Models\User`) |
| `model_id` | BIGINT UNSIGNED | NOT NULL | ID instansi pengguna |

---

### K. Tabel Infrastruktur Laravel

Tabel-tabel bawaan Laravel yang menangani sesi pengguna, antrean tugas, token reset password, migrasi database, dan manajemen caching.

#### 1. Tabel `sessions`
Menyimpan status sesi (session state) browser pelanggan atau admin secara aman di tingkat database.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | VARCHAR(255) | PRIMARY KEY | ID unik sesi browser |
| `user_id` | BIGINT UNSIGNED | DEFAULT NULL | ID pengguna jika sudah terotentikasi |
| `ip_address` | VARCHAR(45) | DEFAULT NULL | Alamat IP perangkat pengguna |
| `user_agent` | TEXT | DEFAULT NULL | String identifikasi browser pengguna |
| `payload` | LONGTEXT | NOT NULL | Data terenkripsi status sesi |
| `last_activity` | INT | NOT NULL | Timestamp aktivitas pengguna terakhir |

#### 2. Tabel `password_reset_tokens`
Menyimpan token verifikasi sementara ketika pengguna meminta reset password.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `email` | VARCHAR(255) | PRIMARY KEY | Email pemohon reset password |
| `token` | VARCHAR(255) | NOT NULL | Token reset terenkripsi |
| `created_at` | TIMESTAMP | NULL | Waktu token dibuat |

#### 3. Tabel `cache` dan `cache_locks`
Digunakan oleh laravel untuk menyimpan data cache sementara (caching) dan kunci pelindung (*mutex lock*) untuk mencegah balapan proses (*race condition*).
* **Tabel `cache`**:
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `key` | VARCHAR(255) | PRIMARY KEY | Nama key cache unik |
| `value` | MEDIUMTEXT | NOT NULL | Nilai cache yang disimpan |
| `expiration` | BIGINT | NOT NULL | Batas waktu kedaluwarsa cache |

* **Tabel `cache_locks`**:
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `key` | VARCHAR(255) | PRIMARY KEY | Nama key lock unik |
| `owner` | VARCHAR(255) | NOT NULL | Pemilik hak kunci |
| `expiration` | BIGINT | NOT NULL | Waktu kedaluwarsa kunci |

#### 4. Tabel Antrean Tugas (`jobs`, `job_batches`, `failed_jobs`)
Mengelola pengerjaan tugas asinkron di belakang layar (*background job queue*), seperti pengiriman email struk pembayaran otomatis atau pemrosesan batch data.
* **Tabel `jobs`**:
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik pekerjaan antrean |
| `queue` | VARCHAR(255) | NOT NULL | Nama antrean kerja (e.g. `default`) |
| `payload` | LONGTEXT | NOT NULL | Data objek/job ter-serialize |
| `attempts` | TINYINT UNSIGNED | NOT NULL | Jumlah percobaan jalannya tugas |
| `reserved_at` | INT UNSIGNED | DEFAULT NULL | Waktu tugas diambil worker |
| `available_at` | INT UNSIGNED | NOT NULL | Waktu tugas siap dijalankan |
| `created_at` | INT UNSIGNED | NOT NULL | Waktu pembuatan tugas |

* **Tabel `job_batches`**:
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | VARCHAR(255) | PRIMARY KEY | ID unik batch tugas |
| `name` | VARCHAR(255) | NOT NULL | Nama grup batch |
| `total_jobs` | INT | NOT NULL | Jumlah total tugas dalam batch |
| `pending_jobs` | INT | NOT NULL | Jumlah tugas tertunda |
| `failed_jobs` | INT | NOT NULL | Jumlah tugas gagal |
| `failed_job_ids` | LONGTEXT | NOT NULL | List ID tugas yang gagal |
| `options` | MEDIUMTEXT | DEFAULT NULL | Opsi serialized batch |
| `cancelled_at` | INT | DEFAULT NULL | Waktu batch dibatalkan |
| `created_at` | INT | NOT NULL | Waktu inisiasi batch |
| `finished_at` | INT | DEFAULT NULL | Waktu batch selesai diproses |

* **Tabel `failed_jobs`**:
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik riwayat gagal |
| `uuid` | VARCHAR(255) | NOT NULL, UNIQUE | UUID unik pekerjaan |
| `connection` | TEXT | NOT NULL | Tipe koneksi queue driver |
| `queue` | TEXT | NOT NULL | Nama queue antrean |
| `payload` | LONGTEXT | NOT NULL | Payload detail objek |
| `exception` | LONGTEXT | NOT NULL | Stack trace error penyebab gagal |
| `failed_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu kesalahan terdeteksi |

#### 5. Tabel `migrations`
Mencatat riwayat eksekusi skrip perubahan skema database (*database schema migrations*) oleh Laravel.
| Nama Kolom | Tipe Data | Atribut / Constraint | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID migrasi |
| `migration` | VARCHAR(255) | NOT NULL | Nama file migrasi yang telah dijalankan |
| `batch` | INT | NOT NULL | Nomor batch eksekusi migrasi |

---

## 4. Penjelasan dan Kode SQL Komponen Database

Bagian ini menjelaskan komponen-komponen lanjutan database, lengkap dengan kode SQL pembuatannya, tempat implementasi di dalam proyek, kegunaannya, dan skenario pemakaian.

### A. Database Users & Privileges (Hak Akses Pengguna)

#### Kode SQL
```sql
CREATE USER IF NOT EXISTS 'alexander'@'localhost' IDENTIFIED BY 'dhio14827';
GRANT ALL PRIVILEGES ON db_toko_roti.* TO 'alexander'@'localhost';

-- Administrator Database (Akses Penuh)
CREATE USER IF NOT EXISTS 'admin_db'@'localhost' IDENTIFIED BY 'pass123';
GRANT ALL PRIVILEGES ON db_toko_roti.* TO 'admin_db'@'localhost';

-- Kasir (Akses Terbatas)
CREATE USER IF NOT EXISTS 'kasir_db'@'localhost' IDENTIFIED BY 'pass456';
GRANT SELECT, INSERT, UPDATE ON db_toko_roti.orders TO 'kasir_db'@'localhost';
GRANT SELECT, INSERT, UPDATE ON db_toko_roti.order_items TO 'kasir_db'@'localhost';

FLUSH PRIVILEGES;
```

#### Penjelasan Komponen
* **Diimplementasikan pada**: Server Basis Data MySQL/MariaDB. Konfigurasi kredensial koneksi diletakkan di file konfigurasi proyek [.env](file:///c:/laragon/www/toko-roti/.env).
* **Bisa untuk apa saja**:
  * `alexander` dan `admin_db`: Digunakan oleh developer dan tim DevOps untuk mengelola seluruh siklus hidup skema (seperti menjalankan migrasi `php artisan migrate`, memulihkan database, dan melakukan seeding data).
  * `kasir_db`: Digunakan khusus oleh sistem kasir / perangkat kasir toko. Akun ini sengaja dibatasi hak aksesnya agar hanya bisa membaca, menambah, dan memperbarui data transaksi (`orders` & `order_items`). Akun ini tidak bisa menghapus transaksi atau memodifikasi daftar pengguna dan log audit.
* **Untuk apa (Tujuan)**: Menerapkan prinsip keamanan **Least Privilege** di level infrastruktur database. Memastikan apabila salah satu akun kasir bocor, penyerang tidak dapat melakukan kerusakan total pada data sistem informasi (seperti menghapus tabel, memodifikasi harga roti langsung, atau melihat informasi pribadi pengguna lain).

---

### B. Indexes (Indeks)

#### Kode SQL
```sql
CREATE INDEX idx_bread_name ON breads(name);
CREATE INDEX idx_order_status ON orders(status);
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_addresses_user_id ON user_addresses(user_id);
CREATE INDEX idx_user_addresses_default ON user_addresses(user_id, is_default);
```

#### Penjelasan Komponen
* **Diimplementasikan pada**: Struktur data B-Tree internal MySQL di dalam tabel terkait. Dideklarasikan dalam file [db_toko_roti.sql](file:///c:/laragon/www/toko-roti/db_toko_roti.sql#L193-L194) pada baris 193-194 dan baris 265-267.
* **Bisa untuk apa saja**:
  * `idx_bread_name`: Digunakan saat pelanggan mencari menu roti di katalog depan atau kasir mencari nama produk pada formulir kasir.
  * `idx_order_status`: Digunakan saat mem-filter riwayat transaksi berdasarkan status (misalnya menampilkan pesanan `Pending` yang belum dibayar, atau pesanan `Processing` yang siap dibuat).
  * `idx_user_email`: Digunakan setiap kali sistem melakukan proses otentikasi login pengguna.
  * `idx_user_addresses_user_id` & `idx_user_addresses_default`: Digunakan oleh Laravel saat memproses keranjang belanja ke halaman *checkout* untuk memuat alamat pengiriman default secara cepat.
* **Untuk apa (Tujuan)**: Mempercepat waktu eksekusi query pembacaan data (`SELECT`). Database engine tidak perlu memindai baris data satu-per-satu dari awal hingga akhir (*full-table scan*), melainkan langsung mencari lokasinya melalui struktur indeks B-Tree yang telah terurut. Hal ini menghemat sumber daya CPU server dan mempercepat respon aplikasi.

---

### C. Views (Tabel Virtual)

#### Kode SQL
```sql
-- 1. View Roti Tersedia
CREATE VIEW view_available_breads AS
SELECT b.id, b.name, b.price, b.stock, c.name AS category_name
FROM breads b
JOIN categories c ON b.category_id = c.id
WHERE b.stock > 0;

-- 2. View Pesanan Pengguna
CREATE VIEW view_user_orders AS
SELECT o.id AS order_id, u.name AS customer_name, o.total_amount, o.status, o.created_at
FROM orders o
JOIN users u ON o.user_id = u.id;

-- 3. View Detail Pesanan
CREATE VIEW view_order_details AS
SELECT oi.order_id, b.name AS bread_name, oi.quantity, oi.subtotal
FROM order_items oi
JOIN breads b ON oi.bread_id = b.id;
```

#### Penjelasan Komponen
* **Diimplementasikan pada**: 
  * Di database server sebagai tabel virtual.
  * Di sisi Laravel, dihubungkan ke model Eloquent:
    * `view_available_breads` dipetakan ke [ViewAvailableBread.php](file:///c:/laragon/www/toko-roti/app/Models/ViewAvailableBread.php) dan ditampilkan pada widget dashboard [AvailableBreadsWidget.php](file:///c:/laragon/www/toko-roti/app/Filament/Widgets/AvailableBreadsWidget.php) serta dimuat di [OrderController.php](file:///c:/laragon/www/toko-roti/app/Http/Controllers/OrderController.php#L24).
    * `view_user_orders` dipetakan ke [ViewUserOrder.php](file:///c:/laragon/www/toko-roti/app/Models/ViewUserOrder.php) dan digunakan untuk menampilkan transaksi di dashboard Filament [UserOrdersWidget.php](file:///c:/laragon/www/toko-roti/app/Filament/Widgets/UserOrdersWidget.php).
    * `view_order_details` dipanggil menggunakan Query Builder di [OrderController.php](file:///c:/laragon/www/toko-roti/app/Http/Controllers/OrderController.php#L33) untuk memuat detail belanja pelanggan pada halaman detail pesanan.
* **Bisa untuk apa saja**:
  * Menampilkan katalog roti siap jual secara real-time (hanya menampilkan roti dengan stok > 0).
  * Menyajikan data visual ringkas pada dashboard admin untuk memantau transaksi masuk beserta nama pelanggan secara langsung tanpa menulis query gabungan manual.
  * Menyediakan ringkasan struktur belanjaan per invoice untuk mempermudah pencetakan bukti pembayaran.
* **Untuk apa (Tujuan)**: Menyederhanakan penulisan query JOIN yang kompleks di sisi aplikasi. Dengan memindahkan logika JOIN rumit ke database View, kode di sisi Laravel menjadi jauh lebih bersih, lebih modular, dan performa pembacaannya lebih dioptimalkan oleh sistem database.

---

### D. Triggers (Pemicu Otomatis)

#### Kode SQL
```sql
DELIMITER //

-- 1. TRIGGER: BEFORE INSERT (Validasi Stok & Perhitungan Subtotal)
CREATE TRIGGER tr_before_order_item_insert
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE current_stock INT;
    DECLARE bread_price DECIMAL(10,2);
    
    SELECT stock, price INTO current_stock, bread_price 
    FROM breads WHERE id = NEW.bread_id;
    
    IF current_stock < NEW.quantity THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok roti tidak mencukupi untuk pesanan ini.';
    END IF;
    
    SET NEW.subtotal = bread_price * NEW.quantity;
END //

-- 2. TRIGGER: AFTER INSERT (Sinkronisasi Penambahan Total Transaksi)
CREATE TRIGGER tr_after_order_item_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE orders SET total_amount = total_amount + NEW.subtotal WHERE id = NEW.order_id;
END //

-- 3. TRIGGER: AFTER DELETE (Sinkronisasi Pengurangan Total Transaksi)
CREATE TRIGGER tr_after_order_item_delete
AFTER DELETE ON order_items
FOR EACH ROW
BEGIN
    UPDATE orders SET total_amount = total_amount - OLD.subtotal WHERE id = OLD.order_id;
END //

-- 4. TRIGGER: BEFORE UPDATE (Perlindungan Log Audit - Read-Only)
CREATE TRIGGER tr_prevent_admin_log_update
BEFORE UPDATE ON admin_access_logs
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Session log tidak dapat diubah.';
END //

-- 5. TRIGGER: BEFORE DELETE (Perlindungan Log Audit - Non-Delete)
CREATE TRIGGER tr_prevent_admin_log_delete
BEFORE DELETE ON admin_access_logs
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Session log tidak dapat dihapus.';
END //

DELIMITER ;
```

#### Penjelasan Komponen
* **Diimplementasikan pada**: Event DML tabel target (`order_items` dan `admin_access_logs`) di level database. Terpicu secara implisit oleh operasi database.
* **Bisa untuk apa saja**:
  * Mencegah pemesanan roti yang kuantitasnya melebihi stok fisik gudang. Ketika kasir atau pelanggan menekan tombol checkout dan stok tidak memadai, transaksi langsung digagalkan dan pesan kesalahan dikirim ke controller Laravel di [OrderController.php](file:///c:/laragon/www/toko-roti/app/Http/Controllers/OrderController.php#L257-L261).
  * Mengisi nilai `subtotal` secara otomatis di baris item pesanan tanpa perlu dihitung manual oleh aplikasi.
  * Memastikan nilai `total_amount` di tabel `orders` selalu akurat dan sinkron dengan jumlah seluruh subtotal item pesanan terkait secara otomatis (baik saat item baru ditambahkan maupun dibatalkan/dihapus).
  * Mengunci tabel audit log `admin_access_logs` sehingga tidak ada admin nakal yang bisa mengubah atau menghapus riwayat log masuk mereka untuk menghilangkan jejak akses.
* **Untuk apa (Tujuan)**: Menjaga integritas data (*data consistency*) tingkat tinggi. Logika validasi dan sinkronisasi dijalankan sedekat mungkin dengan data (di sisi DB), sehingga aturan bisnis tetap berjalan dengan aman meskipun ada transaksi langsung di luar aplikasi Laravel (misal saat developer memanipulasi data lewat DBMS desktop).

---

### E. Stored Procedures (Prosedur Tersimpan)

#### Kode SQL
```sql
DELIMITER //

-- 1. STORED PROCEDURE: sp_checkout_order_bulk
CREATE PROCEDURE sp_checkout_order_bulk (
    IN p_user_id BIGINT UNSIGNED,
    IN p_total_amount DECIMAL(10,2),
    OUT p_order_id BIGINT UNSIGNED
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    INSERT INTO orders (user_id, total_amount, status) VALUES (p_user_id, p_total_amount, 'Pending');
    SET p_order_id = LAST_INSERT_ID();
    COMMIT;
END //

-- 2. STORED PROCEDURE: sp_restock_bread
CREATE PROCEDURE sp_restock_bread (
    IN p_bread_id BIGINT UNSIGNED,
    IN p_added_qty INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    UPDATE breads SET stock = stock + p_added_qty WHERE id = p_bread_id;
    COMMIT;
END //

-- 3. STORED PROCEDURE: sp_update_order_status
CREATE PROCEDURE sp_update_order_status (
    IN p_order_id BIGINT UNSIGNED,
    IN p_new_status ENUM('Pending', 'Processing', 'Completed', 'Cancelled')
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    UPDATE orders SET status = p_new_status WHERE id = p_order_id;
    COMMIT;
END //

-- 4. STORED PROCEDURE: sp_delete_cancelled_order
CREATE PROCEDURE sp_delete_cancelled_order (
    IN p_order_id BIGINT UNSIGNED
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    DELETE FROM orders WHERE id = p_order_id AND status = 'Cancelled';
    COMMIT;
END //

DELIMITER ;
```

#### Penjelasan Komponen
* **Diimplementasikan pada**: Level database server. Dipanggil secara terprogram dari kode backend aplikasi.
  * Prosedur `sp_checkout_order_bulk` dipanggil di dalam file [CheckoutKeranjangService.php](file:///c:/laragon/www/toko-roti/app/Support/CheckoutKeranjangService.php#L26) baris 26-29 untuk memproses inisiasi transaksi pemesanan secara aman.
  * Prosedur lainnya (`sp_restock_bread`, `sp_update_order_status`, `sp_delete_cancelled_order`) disiapkan sebagai fungsionalitas siap-pakai untuk integrasi masa depan, konsol manajemen data, atau tugas terjadwal (*scheduled cron jobs*) pemeliharaan data.
* **Bisa untuk apa saja**:
  * Melakukan checkout keranjang belanja secara berkelompok secara aman dan transaksional.
  * Melakukan *restocking* (penambahan stok roti) secara aman dan transaksional untuk menghindari masalah *race conditions* saat beberapa staf gudang mengupdate stok bersamaan.
  * Memproses pembaruan status pesanan secara terisolasi.
  * Melakukan pembersihan data sampah (pesanan berstatus *Cancelled*) secara otomatis lewat skrip pemeliharaan database tanpa membebani server aplikasi.
* **Untuk apa (Tujuan)**:
  * **Kompilasi Cepat**: Blok SQL sudah dikompilasi sebelumnya oleh server database, sehingga eksekusi prosedurnya jauh lebih cepat daripada mengirim kueri SQL mentah berulang kali.
  * **Transaksional (ACID)**: Menjamin agar proses transaksi yang terdiri dari beberapa perintah SQL selalu dieksekusi secara aman (`START TRANSACTION` & `COMMIT`), dan akan otomatis membatalkan seluruh operasi (`ROLLBACK`) jika salah satu baris mengalami kegagalan.
  * **Mengurangi Overhead Jaringan**: Cukup mengirimkan satu perintah pemanggilan `CALL nama_prosedur()` melalui jaringan web server ke DB server, bukan serangkaian kueri yang panjang.

---

## 5. Kesimpulan

Rancangan database **db_toko_roti** telah disusun dengan prinsip basis data relasional modern yang efisien dan aman. Struktur ini tidak hanya berfungsi sebagai penyimpanan data mentah, tetapi juga berperan aktif dalam menegakkan aturan bisnis aplikasi (*business logic execution*). 

Dengan mengombinasikan berbagai fitur bawaan MySQL/MariaDB:
* **Hak akses terisolasi** meminimalkan risiko keamanan data.
* **Indeksasi kolom penting** menjamin kecepatan pencarian data menu dan otentikasi login.
* **Database Views** mempermudah tim pengembang menyajikan data dashboard pelaporan yang rapi.
* **Triggers** secara cerdas mengotomatisasi sinkronisasi nominal transaksi belanja dan menjaga keamanan log audit.
* **Stored Procedures** menyederhanakan alur transaksi kritis (seperti checkout dan restock) agar berjalan dengan aman, cepat, dan transaksional.

Integrasi erat antara backend Laravel/Filament dengan keunggulan server database ini menghasilkan sistem informasi Toko Roti yang stabil, responsif, memiliki performa tinggi, serta tangguh terhadap potensi inkonsistensi data.
