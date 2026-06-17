# Peta Kode — Toko Roti (Laravel & Filament)

Dokumen ini adalah panduan lengkap lokasi **setiap file** dalam proyek. Setiap bagian menjelaskan folder dan nama file yang bertanggung jawab atas fitur tersebut.

---

## Daftar Isi

1. [Landing Page (Halaman Utama)](#1-landing-page-halaman-utama)
2. [Katalog Produk (Livewire)](#2-katalog-produk-livewire)
3. [Detail Produk](#3-detail-produk)
4. [Keranjang Belanja](#4-keranjang-belanja)
5. [Checkout & Pembayaran](#5-checkout--pembayaran)
6. [Riwayat & Detail Pesanan](#6-riwayat--detail-pesanan)
7. [Autentikasi (Login & Register)](#7-autentikasi-login--register)
8. [Akun Pengguna (Profil, Alamat, Riwayat)](#8-akun-pengguna-profil-alamat-riwayat)
9. [Tentang Kami](#9-tentang-kami)
10. [Layout & Komponen Global](#10-layout--komponen-global)
11. [Admin Panel (Filament)](#11-admin-panel-filament)
12. [RBAC — Roles & Permissions](#12-rbac--roles--permissions)
13. [Model (Eloquent)](#13-model-eloquent)
14. [Service Layer](#14-service-layer)
15. [Middleware](#15-middleware)
16. [Routes (URL)](#16-routes-url)
17. [Database (Migrasi, Seeder, Factory)](#17-database-migrasi-seeder-factory)
18. [Konfigurasi & Bootstrap](#18-konfigurasi--bootstrap)
19. [Resources (CSS, JS Sumber)](#19-resources-css-js-sumber)
20. [Asset Publik (CSS, JS, Gambar)](#20-asset-publik-css-js-gambar)
21. [File Root & Konfigurasi Proyek](#21-file-root--konfigurasi-proyek)
22. [Storage (File Upload)](#22-storage-file-upload)

---

## 1. Landing Page (Halaman Utama)

Halaman pertama yang dilihat pengunjung saat membuka website.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| View (Blade) | `welcome.blade.php` | `resources/views/` |
| Route | `web.php` → `Route::get('/')` | `routes/` |

**Isi halaman:** Hero section dengan CTA, fitur unggulan, preview produk terlaris & baru, footer singkat.

---

## 2. Katalog Produk (Livewire)

Halaman daftar semua produk roti dengan filter kategori dan pencarian real-time.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| View halaman katalog | `index.blade.php` | `resources/views/orders/` |
| Livewire View (katalog) | `product-catalog.blade.php` | `resources/views/livewire/` |
| Livewire Class (katalog) | `ProductCatalog.php` | `app/Livewire/` |
| Livewire View (quick-add popup) | `bread-quick-add.blade.php` | `resources/views/livewire/` |
| Livewire Class (quick-add popup) | `BreadQuickAdd.php` | `app/Livewire/` |
| Route | `web.php` → `Route::get('/catalog', ...)` | `routes/` |

---

## 3. Detail Produk

Halaman detail satu produk roti (nama, deskripsi, harga, stok, tombol tambah ke keranjang).

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| View | `show.blade.php` | `resources/views/breads/` |
| Controller | `BreadController.php` | `app/Http/Controllers/` |
| Route | `web.php` → `Route::get('/breads/{bread}', ...)` | `routes/` |

---

## 4. Keranjang Belanja

Keranjang belanja berbasis session — tampil sebagai floating popup di semua halaman.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| Livewire View (floating cart) | `floating-cart-popup.blade.php` | `resources/views/livewire/` |
| Livewire Class (floating cart) | `FloatingCartPopup.php` | `app/Livewire/` |
| Service Keranjang | `Keranjang.php` | `app/Support/` |
| Controller (tambah/hapus item) | `OrderController.php` | `app/Http/Controllers/` |

> **Catatan:** Ada juga `Controller.php` di `app/Http/Controllers/` — ini adalah base class kosong bawaan Laravel yang diextend oleh semua controller di atas.

**Catatan:** Keranjang disimpan di **session** Laravel (bukan database). Logika mutasi dan kalkulasi ada di `Keranjang.php`. `FloatingCartPopup.php` menggunakan service tersebut untuk menampilkan data dan memicu aksi ke controller.

---

## 5. Checkout & Pembayaran

Alur dari ringkasan pesanan → input alamat & metode pengiriman → tampilan QRIS → konfirmasi.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| View halaman checkout | `checkout.blade.php` | `resources/views/orders/` |
| View halaman QRIS / pembayaran | `payment.blade.php` | `resources/views/orders/` |
| Controller (semua logika checkout) | `OrderController.php` | `app/Http/Controllers/` |
| Service checkout | `CheckoutKeranjangService.php` | `app/Support/` |
| Gambar QRIS | `qris-full.png` | `public/images/` |
| Route | `web.php` → semua route `/checkout/*` & `/payment/*` | `routes/` |

**Alur kerja:**
1. User klik "Checkout" → `OrderController::showCheckout()`
2. User isi alamat, metode pengiriman, catatan → `OrderController::processCheckout()`
3. `CheckoutKeranjangService` memanggil stored procedure `sp_checkout_order_bulk` untuk membuat record `orders` secara transaksional
4. User diarahkan ke halaman pembayaran QRIS → `OrderController::showPayment()`
5. Admin konfirmasi pembayaran di panel Filament → status pesanan menjadi `Processing`

---

## 6. Riwayat & Detail Pesanan

Halaman untuk pelanggan melihat semua pesanan yang pernah dibuat.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| View daftar riwayat | `history.blade.php` | `resources/views/orders/` |
| View detail satu pesanan | `show.blade.php` | `resources/views/orders/` |
| Controller | `OrderController.php` | `app/Http/Controllers/` |
| Route | `web.php` → `/orders/history` & `/orders/{order}` | `routes/` |

---

## 7. Autentikasi (Login & Register)

Sistem login dan pendaftaran akun pelanggan (bukan Filament auth).

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| View halaman login | `login.blade.php` | `resources/views/auth/` |
| View halaman register | `register.blade.php` | `resources/views/auth/` |
| Controller | `AuthController.php` | `app/Http/Controllers/` |
| Route | `web.php` → `/login`, `/register`, `/logout` | `routes/` |

**Catatan:** Autentikasi panel Filament (admin) dikelola secara terpisah oleh Filament, bukan oleh `AuthController.php`.

---

## 8. Akun Pengguna (Profil, Alamat, Riwayat)

Halaman manajemen akun milik pelanggan yang sudah login.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| Layout khusus akun | `layout.blade.php` | `resources/views/account/` |
| View profil pengguna | `profile.blade.php` | `resources/views/account/` |
| View manajemen alamat | `address.blade.php` | `resources/views/account/` |
| View riwayat pesanan akun | `orders.blade.php` | `resources/views/account/` |
| Controller | `AccountController.php` | `app/Http/Controllers/` |
| Route | `web.php` → semua `/account/*` (middleware `auth`) | `routes/` |

---

## 9. Tentang Kami

Halaman statis yang menjelaskan latar belakang dan filosofi toko.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| View | `tentang-kami.blade.php` | `resources/views/` |
| Route | `web.php` → `Route::get('/tentang-kami', ...)` | `routes/` |

---

## 10. Layout & Komponen Global

File-file yang digunakan oleh semua (atau hampir semua) halaman frontend.

| Tipe | File | Lokasi Folder |
|------|------|---------------|
| Master Layout utama | `app.blade.php` | `resources/views/layouts/` |
| Navbar (Blade Component) | `navbar.blade.php` | `resources/views/components/` |
| Layout untuk Livewire (vendor override) | `app.blade.php` | `resources/views/components/layouts/` |

**Catatan:** `layouts/app.blade.php` adalah file induk yang di-`@extend` oleh semua halaman. Di dalamnya sudah ter-include Navbar dan Floating Cart Popup secara global.

---

## 10b. Vendor View Overrides

File-file ini adalah **override tampilan bawaan package** (Livewire & Laravel Pagination) yang dipublish ke folder `resources/views/vendor/`. File ini **tidak diedit langsung** kecuali untuk kustomisasi tampilan paginasi atau animasi Livewire.

### Livewire Loading Views

| File | Lokasi Folder |
|------|---------------|
| `tailwind.blade.php` | `resources/views/vendor/livewire/` |
| `bootstrap.blade.php` | `resources/views/vendor/livewire/` |
| `simple-tailwind.blade.php` | `resources/views/vendor/livewire/` |
| `simple-bootstrap.blade.php` | `resources/views/vendor/livewire/` |

### Pagination Views

| File | Lokasi Folder |
|------|---------------|
| `bootstrap-5.blade.php` | `resources/views/vendor/pagination/` |
| `tailwind.blade.php` | `resources/views/vendor/pagination/` |
| `bootstrap-3.blade.php` | `resources/views/vendor/pagination/` |
| `bootstrap-4.blade.php` | `resources/views/vendor/pagination/` |
| `semantic-ui.blade.php` | `resources/views/vendor/pagination/` |
| `simple-bootstrap-3.blade.php` | `resources/views/vendor/pagination/` |
| `simple-bootstrap-4.blade.php` | `resources/views/vendor/pagination/` |
| `simple-bootstrap-5.blade.php` | `resources/views/vendor/pagination/` |
| `simple-tailwind.blade.php` | `resources/views/vendor/pagination/` |

> Untuk mengganti tampilan paginasi default, edit file yang sesuai di `vendor/pagination/` (misalnya `bootstrap-5.blade.php` jika menggunakan Bootstrap 5).

---

## 11. Admin Panel (Filament)

Panel administrasi berbasis Filament v3 untuk staf toko. Diakses melalui URL `/admin`.

### Konfigurasi Utama

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `AdminPanelProvider.php` | `app/Providers/Filament/` | Konfigurasi panel: menu navigasi, middleware, tema, widget, logo |
| `logo.blade.php` | `resources/views/filament/` | Tampilan logo kustom di header panel Filament |
| `AppServiceProvider.php` | `app/Providers/` | Registrasi service provider global aplikasi |

### Resource: Manajemen Produk (Breads)

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `BreadResource.php` | `app/Filament/Resources/Breads/` | Definisi resource (navigasi, model, pages) |
| `BreadForm.php` | `app/Filament/Resources/Breads/Schemas/` | Schema form input/edit produk |
| `BreadsTable.php` | `app/Filament/Resources/Breads/Tables/` | Schema tabel daftar produk |
| `ListBreads.php` | `app/Filament/Resources/Breads/Pages/` | Halaman list semua produk |
| `CreateBread.php` | `app/Filament/Resources/Breads/Pages/` | Halaman tambah produk baru |
| `EditBread.php` | `app/Filament/Resources/Breads/Pages/` | Halaman edit produk |

### Resource: Manajemen Kategori

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `CategoryResource.php` | `app/Filament/Resources/Categories/` | Definisi resource kategori |
| `CategoryForm.php` | `app/Filament/Resources/Categories/Schemas/` | Schema form input/edit kategori |
| `CategoriesTable.php` | `app/Filament/Resources/Categories/Tables/` | Schema tabel daftar kategori |
| `ListCategories.php` | `app/Filament/Resources/Categories/Pages/` | Halaman list semua kategori |
| `CreateCategory.php` | `app/Filament/Resources/Categories/Pages/` | Halaman tambah kategori baru |
| `EditCategory.php` | `app/Filament/Resources/Categories/Pages/` | Halaman edit kategori |

### Resource: Manajemen Pesanan (Orders)

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `OrderResource.php` | `app/Filament/Resources/Orders/` | Definisi resource pesanan |
| `OrderForm.php` | `app/Filament/Resources/Orders/Schemas/` | Schema form input/edit pesanan |
| `OrdersTable.php` | `app/Filament/Resources/Orders/Tables/` | Schema tabel daftar pesanan (dengan filter status) |
| `ListOrders.php` | `app/Filament/Resources/Orders/Pages/` | Halaman list semua pesanan |
| `CreateOrder.php` | `app/Filament/Resources/Orders/Pages/` | Halaman tambah pesanan manual |
| `EditOrder.php` | `app/Filament/Resources/Orders/Pages/` | Halaman edit/ubah status pesanan |
| `ItemsRelationManager.php` | `app/Filament/Resources/Orders/RelationManagers/` | Tampilan item detail pesanan (relasi) di dalam edit pesanan |

### Resource: Manajemen Pengguna (Users)

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `UserResource.php` | `app/Filament/Resources/Users/` | Definisi resource pengguna |
| `UserForm.php` | `app/Filament/Resources/Users/Schemas/` | Schema form input/edit pengguna (termasuk assign role) |
| `UsersTable.php` | `app/Filament/Resources/Users/Tables/` | Schema tabel daftar pengguna |
| `ListUsers.php` | `app/Filament/Resources/Users/Pages/` | Halaman list semua pengguna |
| `CreateUser.php` | `app/Filament/Resources/Users/Pages/` | Halaman tambah pengguna baru |
| `EditUser.php` | `app/Filament/Resources/Users/Pages/` | Halaman edit pengguna |

### Resource: Log Akses Admin

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `AdminAccessLogResource.php` | `app/Filament/Resources/` | Definisi resource log akses (read-only) |
| `ListAdminAccessLogs.php` | `app/Filament/Resources/AdminAccessLogResource/Pages/` | Halaman daftar log akses admin |

### Widget Dashboard

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `AvailableBreadsWidget.php` | `app/Filament/Widgets/` | Widget tabel produk tersedia (stok > 0) dari DB View |
| `UserOrdersWidget.php` | `app/Filament/Widgets/` | Widget tabel pesanan masuk dari DB View |

---

## 12. RBAC — Roles & Permissions

Sistem hak akses berbasis peran menggunakan **Spatie Laravel Permission**.

| File | Lokasi Folder | Fungsi |
|------|---------------|--------|
| `RoleResource.php` | `app/Filament/Resources/` | Definisi resource manajemen role |
| `ListRoles.php` | `app/Filament/Resources/RoleResource/Pages/` | Halaman daftar semua role |
| `CreateRole.php` | `app/Filament/Resources/RoleResource/Pages/` | Halaman buat role baru |
| `EditRole.php` | `app/Filament/Resources/RoleResource/Pages/` | Halaman edit role & assign permissions |
| `PermissionResource.php` | `app/Filament/Resources/` | Definisi resource manajemen permission |
| `ListPermissions.php` | `app/Filament/Resources/PermissionResource/Pages/` | Halaman daftar semua permission |
| `CreatePermission.php` | `app/Filament/Resources/PermissionResource/Pages/` | Halaman buat permission baru |
| `EditPermission.php` | `app/Filament/Resources/PermissionResource/Pages/` | Halaman edit permission |
| `permission.php` | `config/` | Konfigurasi package Spatie Permission |
| `2026_06_05_144327_create_permission_tables.php` | `database/migrations/` | Migrasi tabel RBAC Spatie |

**Peran yang tersedia:** `Admin`, `Kasir`, `Gudang`, `Manager`

---

## 13. Model (Eloquent)

Semua model Eloquent yang merepresentasikan tabel dan view di database.

| Model | File | Folder | Tabel/View |
|-------|------|--------|------------|
| `User` | `User.php` | `app/Models/` | `users` |
| `UserProfile` | `UserProfile.php` | `app/Models/` | `user_profiles` |
| `UserAddress` | `UserAddress.php` | `app/Models/` | `user_addresses` |
| `Bread` | `Bread.php` | `app/Models/` | `breads` |
| `Category` | `Category.php` | `app/Models/` | `categories` |
| `Order` | `Order.php` | `app/Models/` | `orders` |
| `OrderItem` | `OrderItem.php` | `app/Models/` | `order_items` |
| `OrderCheckoutMeta` | `OrderCheckoutMeta.php` | `app/Models/` | `order_checkout_meta` |
| `AdminAccessLog` | `AdminAccessLog.php` | `app/Models/` | `admin_access_logs` |
| `ViewAvailableBread` | `ViewAvailableBread.php` | `app/Models/` | `view_available_breads` *(DB View)* |
| `ViewUserOrder` | `ViewUserOrder.php` | `app/Models/` | `view_user_orders` *(DB View)* |

---

## 14. Service Layer

Kelas pembantu yang memisahkan logika bisnis dari controller.

| File | Folder | Fungsi |
|------|--------|--------|
| `Keranjang.php` | `app/Support/` | Manajemen keranjang berbasis session: tambah item, hapus item, hitung total, kosongkan |
| `CheckoutKeranjangService.php` | `app/Support/` | Memproses checkout: panggil SP `sp_checkout_order_bulk`, buat `order_checkout_meta`, kosongkan keranjang |
| `HollandBakeryMenuSqlGenerator.php` | `app/Support/` | Utilitas generator SQL data seed menu roti (digunakan saat development/seeding) |

---

## 15. Middleware

Middleware yang berjalan sebelum request mencapai controller.

| File | Folder | Fungsi |
|------|--------|--------|
| `LogAdminAccess.php` | `app/Http/Middleware/` | Mencatat log setiap kali admin mengakses panel. Menyimpan IP, user agent, dan timestamp ke tabel `admin_access_logs` |

Middleware standar Laravel (auth, guest, dll.) diatur di `bootstrap/app.php`.

---

## 16. Routes (URL)

Semua URL aplikasi didefinisikan di sini.

| File | Folder | Isi |
|------|--------|-----|
| `web.php` | `routes/` | Semua route frontend: landing page, katalog, detail, keranjang, checkout, pembayaran, riwayat, auth, akun, tentang kami |
| `console.php` | `routes/` | Perintah artisan custom dan scheduled tasks (maintenance) |

**Kelompok route di `web.php`:**
- **Publik:** `/`, `/catalog`, `/breads/{bread}`, `/tentang-kami`
- **Guest only:** `/login`, `/register`
- **Auth required:** `/checkout`, `/payment`, `/orders/*`, `/account/*`
- **Admin (Filament):** `/admin/*` — dikelola otomatis oleh Filament

---

## 17. Database (Migrasi, Seeder, Factory)

### File SQL Utama

| File | Folder | Isi |
|------|--------|-----|
| `db_toko_roti.sql` | Root proyek `/` | Skema lengkap: semua `CREATE TABLE`, indexes, DB views, triggers, stored procedures, database users |

### Migrasi Laravel

| File | Folder | Tabel yang dibuat |
|------|--------|-------------------|
| `0001_01_01_000001_create_cache_table.php` | `database/migrations/` | `cache`, `cache_locks` |
| `0001_01_01_000002_create_jobs_table.php` | `database/migrations/` | `jobs`, `job_batches`, `failed_jobs` |
| `0001_01_01_000003_create_order_checkout_meta_table.php` | `database/migrations/` | `order_checkout_meta` |
| `2026_06_05_144327_create_permission_tables.php` | `database/migrations/` | Semua tabel Spatie RBAC |

> **Catatan:** Tabel utama (`users`, `breads`, `orders`, dll.) dibuat via `db_toko_roti.sql`, bukan lewat migrasi Laravel standar.

### Seeder

| File | Folder | Fungsi |
|------|--------|--------|
| `DatabaseSeeder.php` | `database/seeders/` | Entry point seeder (`php artisan db:seed`) |
| `holland_bakery_menu_seed.sql` | `database/seeders/` | Data produk menu roti siap pakai (diimport langsung ke database) |

### Factory

| File | Folder | Fungsi |
|------|--------|--------|
| `UserFactory.php` | `database/factories/` | Factory pembuatan data dummy `User` untuk testing (`php artisan tinker` / PHPUnit) |

---

## 18. Konfigurasi & Bootstrap

### File Konfigurasi Utama (`config/`)

| File | Fungsi |
|------|--------|
| `app.php` | Konfigurasi utama aplikasi (nama, timezone, locale, providers) |
| `auth.php` | Konfigurasi guard dan provider autentikasi |
| `database.php` | Konfigurasi koneksi database (MySQL, SQLite, dll.) |
| `filesystems.php` | Konfigurasi disk penyimpanan file (local, public, S3) |
| `livewire.php` | Konfigurasi package Livewire |
| `permission.php` | Konfigurasi package Spatie Laravel Permission |
| `session.php` | Konfigurasi sesi browser (driver, lifetime, cookie) |
| `cache.php` | Konfigurasi cache driver |
| `queue.php` | Konfigurasi antrean tugas (job queue driver) |
| `logging.php` | Konfigurasi logging (channel, level) |
| `mail.php` | Konfigurasi layanan email |
| `services.php` | Konfigurasi service pihak ketiga (Stripe, dll.) |

### Bootstrap

| File | Folder | Fungsi |
|------|--------|--------|
| `app.php` | `bootstrap/` | Entry point bootstrapping Laravel (middleware global, providers) |
| `providers.php` | `bootstrap/` | Daftar service providers yang diload otomatis |

### Environment

| File | Folder | Fungsi |
|------|--------|--------|
| `.env` | Root `/` | Konfigurasi environment aktif (DB, APP_KEY, URL, dll.) |
| `.env.example` | Root `/` | Template `.env` untuk developer baru |

### Bundling Asset

| File | Folder | Fungsi |
|------|--------|--------|
| `vite.config.js` | Root `/` | Konfigurasi Vite untuk bundling CSS/JS ke `public/` |
| `package.json` | Root `/` | Daftar dependensi Node.js (Vite, dll.) |
| `package-lock.json` | Root `/` | Lock file versi Node.js (jangan diedit manual) |
| `composer.json` | Root `/` | Daftar dependensi PHP (Laravel, Filament, Spatie, Livewire, dll.) |

---

## 19. Resources (CSS, JS Sumber)

File sumber sebelum di-compile oleh Vite. Diedit di sini, lalu di-build ke `public/`.

| File | Folder | Fungsi |
|------|--------|--------|
| `app.css` | `resources/css/` | Entry point CSS utama (di-import ke layout) |
| `app.js` | `resources/js/` | Entry point JavaScript utama |

---

## 20. Asset Publik (CSS, JS, Gambar)

File yang langsung bisa diakses browser dari URL publik.

| Tipe | Folder | Keterangan |
|------|--------|------------|
| CSS compiled | `public/css/` | Hasil build Vite dari `resources/css/` |
| JS compiled | `public/js/` | Hasil build Vite dari `resources/js/` |
| Logo website | `public/images/logo.png` | Logo utama di navbar & panel Filament |
| Gambar QRIS | `public/images/qris-full.png` | Gambar QR code pembayaran di halaman payment |
| Placeholder produk | `public/images/roti-placeholder.svg` | Fallback gambar jika foto produk belum diupload |
| Foto produk (60+ item) | `public/images/*.jpg` | Gambar produk statis (dari Holland Bakery menu seed) |
| Font | `public/fonts/` | Font lokal yang digunakan website |
| JS Filament (compiled) | `public/js/filament/` | Asset JS Filament yang dipublish (forms, tables, notifications, widgets, dll.) — **jangan diedit** |
| Gambar upload (symlink) | `public/storage/roti-images/` | Symlink ke `storage/app/public/roti-images/` — foto produk yang diupload via panel admin |

**Menghubungkan storage ke public:**
```bash
php artisan storage:link
```

---

## 21. File Root & Konfigurasi Proyek

File-file yang ada langsung di root proyek.

| File | Fungsi |
|------|--------|
| `artisan` | CLI Laravel — entry point untuk semua perintah `php artisan ...` |
| `composer.json` | Dependensi PHP & autoload class |
| `composer.lock` | Lock file versi PHP (jangan diedit manual) |
| `package.json` | Dependensi Node.js |
| `package-lock.json` | Lock file versi Node.js (jangan diedit manual) |
| `vite.config.js` | Konfigurasi bundler Vite |
| `.env` | Variabel environment aktif |
| `.env.example` | Template `.env` untuk developer baru |
| `.npmrc` | Konfigurasi npm (registry, dll.) |
| `.editorconfig` | Konfigurasi gaya penulisan kode (indent, line ending) untuk editor |
| `.gitignore` | Daftar file/folder yang dikecualikan dari Git |
| `.gitattributes` | Konfigurasi atribut Git (line ending, diff, dll.) |
| `README.md` | Dokumentasi singkat proyek (untuk GitHub) |
| `information.md` | Dokumentasi database sistem lengkap (ERD, tabel, triggers, SP, dll.) |
| `peta-kode.md` | **Dokumen ini** — peta lokasi semua file dalam proyek |
| `db_toko_roti.sql` | File SQL skema database lengkap |

---

## 22. Storage (File Upload)

Folder penyimpanan file yang diupload pengguna/admin. **Tidak dicommit ke Git.**

| Folder | Keterangan |
|--------|------------|
| `storage/app/public/roti-images/` | Foto produk yang diupload via panel Filament (versi canonical) |
| `storage/app/private/roti-images/` | Upload sementara (private, sebelum dipindahkan ke public) |
| `storage/app/private/livewire-tmp/` | File temporary saat proses upload via Livewire file input |
| `storage/app/public/livewire-tmp/` | File temporary upload Livewire yang sudah dipindahkan ke public area |
| `public/storage/roti-images/` | **Symlink** ke `storage/app/public/roti-images/` — URL yang diakses browser |

> **Penting:** Folder `storage/` tidak dicommit ke Git (ada `.gitignore` di dalamnya). Jalankan `php artisan storage:link` setelah clone repository untuk membuat symlink.

---

## Ringkasan Cepat — Lokasi per Fitur

| Fitur | Controller | View Utama | Livewire / Service |
|-------|-----------|-----------|---------|
| Landing Page | *(route langsung)* | `views/welcome.blade.php` | — |
| Katalog | — | `views/orders/index.blade.php` | `Livewire/ProductCatalog.php` |
| Quick Add | — | `views/livewire/bread-quick-add.blade.php` | `Livewire/BreadQuickAdd.php` |
| Detail Produk | `BreadController` | `views/breads/show.blade.php` | — |
| Keranjang (Popup) | `OrderController` | `views/livewire/floating-cart-popup.blade.php` | `Livewire/FloatingCartPopup.php` |
| Checkout | `OrderController` | `views/orders/checkout.blade.php` | `Support/CheckoutKeranjangService.php` |
| Pembayaran QRIS | `OrderController` | `views/orders/payment.blade.php` | — |
| Riwayat Pesanan | `OrderController` | `views/orders/history.blade.php` | — |
| Detail Pesanan | `OrderController` | `views/orders/show.blade.php` | — |
| Login | `AuthController` | `views/auth/login.blade.php` | — |
| Register | `AuthController` | `views/auth/register.blade.php` | — |
| Profil Akun | `AccountController` | `views/account/profile.blade.php` | — |
| Alamat | `AccountController` | `views/account/address.blade.php` | — |
| Riwayat (Akun) | `AccountController` | `views/account/orders.blade.php` | — |
| Tentang Kami | *(route langsung)* | `views/tentang-kami.blade.php` | — |
| Admin Panel | Filament otomatis | Filament otomatis | `Providers/Filament/AdminPanelProvider.php` |
| RBAC | Filament otomatis | Filament otomatis | `RoleResource.php`, `PermissionResource.php` |
