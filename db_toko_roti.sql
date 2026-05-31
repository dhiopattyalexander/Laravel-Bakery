-- ==========================================================
-- FULL DATABASE DUMP: db_toko_roti (FINAL VERSION)
-- DESKRIPSI: Struktur lengkap (Tabel, View, Trigger, Prosedur)
-- ==========================================================

SET FOREIGN_KEY_CHECKS=0;
DROP DATABASE IF EXISTS db_toko_roti;
CREATE DATABASE db_toko_roti CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_toko_roti;

-- ==========================================
-- 1. TABEL INFRASTRUKTUR LARAVEL
-- ==========================================
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB;

-- >>> ADDED: cache tables untuk cache store database dan Livewire pagination
CREATE TABLE `cache` (
    `key` varchar(255) NOT NULL,
    `value` mediumtext NOT NULL,
    `expiration` bigint NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB;

CREATE TABLE `cache_locks` (
    `key` varchar(255) NOT NULL,
    `owner` varchar(255) NOT NULL,
    `expiration` bigint NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB;

-- >>> ADDED: jobs, job_batches, failed_jobs tables for Laravel Database Queue
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned AUTO_INCREMENT PRIMARY KEY,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned AUTO_INCREMENT PRIMARY KEY,
  `uuid` varchar(255) NOT NULL UNIQUE,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================
-- 2. TABEL BISNIS UTAMA
-- ==========================================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'User') DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE user_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    address TEXT,
    phone VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- >>> ADDED: biodata pelanggan dan dukungan multi-alamat
ALTER TABLE user_profiles
    ADD COLUMN birth_date DATE NULL AFTER phone,
    ADD COLUMN gender ENUM('Male', 'Female', 'Other') NULL AFTER birth_date;

CREATE TABLE user_addresses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    label VARCHAR(50) NOT NULL,
    recipient_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_user_addresses_user_id ON user_addresses(user_id);
CREATE INDEX idx_user_addresses_default ON user_addresses(user_id, is_default);

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE breads (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_path VARCHAR(255) DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Pending', 'Processing', 'Completed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    bread_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (bread_id) REFERENCES breads(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- >>> ADDED: order_checkout_meta table for delivery/payment options
CREATE TABLE IF NOT EXISTS `order_checkout_meta` (
  `id` bigint unsigned AUTO_INCREMENT PRIMARY KEY,
  `order_id` bigint unsigned NOT NULL UNIQUE,
  `delivery_method` varchar(20) NOT NULL DEFAULT 'instant',
  `pickup_time` varchar(5) DEFAULT NULL,
  `order_notes` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(20) NOT NULL DEFAULT 'qris',
  `payment_expires_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- 3. INDEXES
-- ==========================================
CREATE INDEX idx_bread_name ON breads(name);
CREATE INDEX idx_order_status ON orders(status);
CREATE INDEX idx_user_email ON users(email);

-- ==========================================
-- 4. VIEWS
-- ==========================================
CREATE VIEW view_available_breads AS
SELECT b.id, b.name, b.price, b.stock, c.name AS category_name
FROM breads b
JOIN categories c ON b.category_id = c.id
WHERE b.stock > 0;

CREATE VIEW view_user_orders AS
SELECT o.id AS order_id, u.name AS customer_name, o.total_amount, o.status, o.created_at
FROM orders o
JOIN users u ON o.user_id = u.id;

CREATE VIEW view_order_details AS
SELECT oi.order_id, b.name AS bread_name, oi.quantity, oi.subtotal
FROM order_items oi
JOIN breads b ON oi.bread_id = b.id;

-- ==========================================
-- 5. TRIGGERS (Otomatisasi Stok & Subtotal)
-- ==========================================
DELIMITER //

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

-- >>> MODIFIED: Removed stock reduction from trigger. Stock is now managed by Laravel Order model event (booted).
CREATE TRIGGER tr_after_order_item_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE orders SET total_amount = total_amount + NEW.subtotal WHERE id = NEW.order_id;
END //

-- >>> MODIFIED: Removed stock restoration from trigger. Stock is now managed by Laravel Order model event (booted).
CREATE TRIGGER tr_after_order_item_delete
AFTER DELETE ON order_items
FOR EACH ROW
BEGIN
    UPDATE orders SET total_amount = total_amount - OLD.subtotal WHERE id = OLD.order_id;
END //

DELIMITER ;

-- ==========================================
-- 6. STORED PROCEDURES (Bulk Checkout)
-- ==========================================
DELIMITER //

-- Prosedur ini membuat induk pesanan (Header)
CREATE PROCEDURE sp_checkout_order_bulk (
    IN p_user_id BIGINT UNSIGNED,
    IN p_total_amount DECIMAL(10,2),
    OUT p_order_id BIGINT UNSIGNED
)
BEGIN
    INSERT INTO orders (user_id, total_amount, status) VALUES (p_user_id, p_total_amount, 'Pending');
    SET p_order_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE sp_restock_bread (
    IN p_bread_id BIGINT UNSIGNED,
    IN p_added_qty INT
)
BEGIN
    UPDATE breads SET stock = stock + p_added_qty WHERE id = p_bread_id;
END //

CREATE PROCEDURE sp_update_order_status (
    IN p_order_id BIGINT UNSIGNED,
    IN p_new_status ENUM('Pending', 'Processing', 'Completed', 'Cancelled')
)
BEGIN
    UPDATE orders SET status = p_new_status WHERE id = p_order_id;
END //

DELIMITER ;

-- ==========================================
-- 7. DUMMY DATA
-- ==========================================
INSERT INTO users (name, email, password, role) VALUES 
('Admin Utama', 'admin@roti.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin'),
('Alex Pelanggan', 'alex@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User');

INSERT INTO user_profiles (user_id, address, phone) VALUES 
(1, 'Jl. Sistem Admin', '08000000000'),
(2, 'Jl. Depok Raya No. 12', '08123456789');

-- >>> ADDED: data biodata contoh untuk field baru
UPDATE user_profiles SET birth_date = '1985-01-01', gender = 'Male' WHERE user_id = 1;
UPDATE user_profiles SET birth_date = '1998-07-21', gender = 'Male' WHERE user_id = 2;

-- >>> ADDED: contoh beberapa alamat per pengguna
INSERT INTO user_addresses (user_id, label, recipient_name, phone, address, is_default) VALUES 
(1, 'Alamat Rumah', 'Admin Utama', '08000000000', 'Jl. Sistem Admin No. 1', 1),
(1, 'Alamat Kantor', 'Admin Utama', '08000000000', 'Jl. Teknologi Blok A-10', 0),
(2, 'Rumah', 'Alex Pelanggan', '08123456789', 'Jl. Depok Raya No. 12', 1),
(2, 'Kantor', 'Alex Pelanggan', '08123456789', 'Jl. Margonda Raya No. 45', 0);

-- >>> REMOVED: kategori lama toko roti generik
-- INSERT INTO categories (name) VALUES 
-- ('Roti Tawar'), ('Roti Manis'), ('Pastry'), ('Donat'), ('Kue Kering');

-- >>> ADDED: kategori baru sesuai referensi Holland Bakery
INSERT INTO categories (name) VALUES 
('Roti'),
('Chiffon & Roll Cakes'),
('Cakes'),
('Pastry & Danish'),
('Cookies'),
('Traditional Snack');

-- >>> REMOVED: seed menu lama generik
-- INSERT INTO breads (category_id, name, description, price, stock) VALUES
-- (1, 'Roti Tawar Klasik', 'Roti tawar putih biasa', 15000, 50),
-- (1, 'Roti Tawar Gandum', 'Roti tawar gandum utuh sehat', 20000, 30),
-- (1, 'Roti Tawar Pandan', 'Roti tawar dengan aroma pandan asli', 18000, 40),
-- (1, 'Roti Tawar Kupas', 'Roti tawar tanpa kulit tepi', 17000, 35),
-- (2, 'Roti Sobek Coklat', 'Roti sobek isi coklat lumer', 25000, 20),
-- (2, 'Roti Sobek Keju', 'Roti sobek isi keju manis gurih', 26000, 25),
-- (2, 'Roti Kacang Merah', 'Roti manis dengan isian pasta kacang merah', 12000, 50),
-- (2, 'Roti Sosis Kater', 'Roti gulung sosis ayam pilihan', 15000, 40),
-- (2, 'Roti Abon Sapi', 'Roti dengan taburan abon sapi melimpah', 16000, 30),
-- (2, 'Roti Pisang Coklat', 'Roti isi perpaduan pisang manis dan coklat', 14000, 45),
-- (2, 'Roti Mocca', 'Roti bundar krim mocca', 12000, 30),
-- (3, 'Croissant Butter', 'Croissant berlapis mentega asli', 22000, 20),
-- (3, 'Danish Coklat', 'Pastry danish dengan filling coklat padat', 24000, 15),
-- (3, 'Puff Cheese', 'Puff pastry panjang dengan taburan keju', 20000, 25),
-- (3, 'Cinnamon Roll', 'Gulungan pastry kayu manis khas', 18000, 30),
-- (4, 'Donat Gula', 'Donat klasik empuk tabur gula halus', 8000, 100),
-- (4, 'Donat Coklat Meises', 'Donat glaze coklat dan taburan meises', 10000, 80),
-- (4, 'Donat Keju', 'Donat dengan krim dan parutan keju chedar', 12000, 60),
-- (4, 'Donat Bomboloni Vanilla', 'Bomboloni lumer isi vla vanilla', 15000, 40),
-- (5, 'Nastar Nanas', 'Kue kering nastar isi selai nanas (Per Toples)', 60000, 15),
-- (5, 'Kastengel Keju Edam', 'Kue kering kastengel keju edam premium (Per Toples)', 75000, 10);

-- >>> ADDED: seed menu baru dari Holland Bakery
INSERT INTO breads (category_id, name, description, price, stock) VALUES
-- >>> ADDED [Roti]
(1, 'Danish Coklat Belepotan', 'Pastry berlapis renyah yang dipanggang sempurna dengan siraman cokelat lumer melimpah di bagian luar. Dibuat dengan mentega premium untuk menghasilkan tekstur flaky yang memanjakan lidah. Sangat cocok dinikmati bersama secangkir kopi pahit hangat.', 19500, 20),
(1, 'Chocolate Custard Bread', 'Roti manis bertekstur super lembut yang menyimpan kejutan isian krim custard cokelat kental di dalamnya. Rasanya tidak terlalu manis sehingga tidak membuat enek, menjadikannya pilihan sarapan favorit yang praktis dan mengenyangkan.', 14700, 25),
(1, 'Roti Coklat Muisjes Gulung', 'Roti gulung klasik bernuansa nostalgia dengan taburan meises cokelat tebal yang menutupi seluruh permukaannya. Bagian dalamnya juga dilapisi krim mentega lembut yang menyatu sempurna dengan roti empuk di setiap gigitannya.', 11400, 26),
(1, 'Roti Bakso Sapi', 'Roti gurih andalan dengan isian daging sapi cincang premium yang dimasak bersama bumbu rempah rahasia. Tekstur rotinya padat namun empuk, memberikan keseimbangan rasa manis dari roti dan gurih pekat dari daging sapi.', 15500, 22),
(1, 'Multi Grain Smoked Beef Cheese Sandwich', 'Pilihan tepat untuk gaya hidup sehat. Sandwich ini menggunakan roti biji-bijian utuh (multi-grain) yang kaya serat, diapit dengan potongan daging asap tebal dan lembaran keju lumer. Cocok untuk makan siang yang ringan namun padat nutrisi.', 18300, 16),
(1, 'Roti Bakso Ayam', 'Alternatif gurih selain sapi, roti ini diisi dengan olahan daging ayam cincang yang dimasak dengan bumbu kecap manis gurih. Aromanya sangat menggugah selera, dibalut dalam adonan roti empuk yang dipanggang hingga kecokelatan.', 14700, 24),
(1, 'Roti Coklat Keju', 'Jawaban untuk kamu yang bingung memilih antara manis dan gurih. Roti ini memadukan kelembutan pasta cokelat legit dengan potongan keju cheddar gurih di bagian dalam, menciptakan ledakan rasa yang harmonis di mulut.', 14300, 30),
(1, 'Roti Abon Sapi', 'Roti bertekstur empuk yang dilapisi mayones manis gurih, lalu ditaburi dengan abon sapi asli berkualitas tinggi secara melimpah ruah. Tekstur abon yang berserat berpadu sempurna dengan kelembutan roti, menjadikannya camilan yang sulit ditolak.', 15200, 30),
(1, 'Roti Coklat', 'Kesederhanaan yang dieksekusi dengan sempurna. Roti klasik bertekstur empuk selembut kapas ini menyembunyikan isian pasta cokelat pekat yang meleleh saat digigit. Favorit sepanjang masa untuk segala usia, dari anak-anak hingga dewasa.', 11400, 34),
(1, 'Korean Garlic Cream Cheese', 'Adaptasi jajanan kekinian dengan cita rasa autentik. Roti dibelah dan disiram dengan lelehan mentega bawang putih yang sangat harum, kemudian diisi dengan cream cheese manis gurih yang tebal. Dipanggang hingga bagian luarnya bertekstur krispi.', 17100, 18),

-- >>> ADDED [Chiffon & Roll Cakes]
(2, 'Bolu Gulung Mocca', 'Bolu gulung tradisional bertekstur spons yang sangat ringan dan empuk, diolesi dengan krim moka beraroma kopi klasik. Rasa manisnya pas, sangat ideal disajikan sebagai suguhan tamu atau teman minum teh di sore hari.', 109000, 11),
(2, 'Chocolate Muffin', 'Muffin padat bergaya Amerika yang menawarkan rasa cokelat pekat di setiap remahannya. Ditambah dengan taburan choco chips ekstra di atas dan di dalamnya, memberikan sensasi leleh yang nikmat jika dihangatkan sebentar sebelum dimakan.', 12100, 35),
(2, 'Chiffon Cake Keju', 'Kue chiffon berukuran besar dengan tekstur seringan awan yang dijamin tidak seret di tenggorokan. Menggunakan ekstrak keju di dalam adonan dan ditaburi parutan keju kering berlimpah di seluruh permukaannya.', 89000, 14),
(2, 'Bolu Gulung Lemon', 'Sensasi segar dalam gigitan bolu lembut. Bolu gulung ini menggunakan selai lemon asli yang memberikan profil rasa asam manis menyegarkan, memecah rasa pekat dari adonan kue. Sangat pas untuk hidangan penutup yang ringan.', 109000, 10),
(2, 'Chiffon Chocolate Chips', 'Varian chiffon manis yang menggunakan dasar adonan vanilla ringan, diselingi dengan butiran cokelat premium yang tersebar merata. Teksturnya yang membal seperti spons membuatnya sangat adiktif untuk dimakan terus-menerus.', 91000, 14),
(2, 'Zebra Cake 19 Cm', 'Kue mentega (butter cake) klasik dengan motif belang hitam putih yang ikonik. Menggabungkan rasa manis vanilla dan sedikit pahit dari bubuk kakao asli. Teksturnya lebih padat dari chiffon namun tetap lumer di mulut.', 69000, 9),
(2, 'Raisin Muffin', 'Muffin bertekstur lembut dengan cita rasa mentega yang kuat, dipenuhi dengan kismis pilihan yang manis dan legit. Memberikan sensasi gigitan kenyal dari kismis yang berpadu dengan remah muffin yang gurih.', 12100, 32),
(2, 'Bolu Gulung Keju', 'Bolu gulung premium yang ditujukan bagi para pencinta keju sejati. Adonan bolu empuk ini digulung dengan krim mentega dan keju parut, lalu lapisan luarnya kembali dibalut dengan parutan keju cheddar tebal yang gurih maksimal.', 121500, 12),
(2, 'Bolu Gulung Pandan', 'Bolu gulung bercita rasa lokal yang menggunakan ekstrak daun pandan asli, menghasilkan warna hijau alami dan aroma yang sangat wangi. Diisi dengan olesan krim lembut yang menyeimbangkan rasa legit adonannya.', 109000, 10),
(2, 'Chiffon Cake Pandan', 'Kue chiffon klasik kebanggaan keluarga yang bertekstur ekstra empuk dan ringan. Aroma daun pandan aslinya langsung tercium saat kemasan dibuka. Sangat cocok disajikan untuk acara kumpul keluarga atau hantaran.', 78000, 15),

-- >>> ADDED [Cakes]
(3, 'Lemon Taart 15 Cm', 'Kue tart berukuran 15 cm yang dirancang elegan. Menggunakan dasar kue vanilla berpadu dengan krim lemon segar yang sama sekali tidak membuat enek. Pilihan sempurna untuk merayakan hari ulang tahun kecil-kecilan bersama orang terdekat.', 152000, 9),
(3, 'Lemon Taart 19 Cm', 'Versi besar dari tart lemon andalan kami, berukuran 19 cm yang cocok untuk pesta keluarga. Lapisan kue bolu dan krim lemonnya tersusun rapi, memberikan kesegaran asam manis yang pas untuk menetralisir hidangan pesta yang berat.', 232000, 8),
(3, 'Black Forest Cake 15x15 Cm', 'Kue cokelat legendaris berbentuk persegi (15x15 cm) dengan lapisan sirup ceri, krim kocok segar, dan serutan cokelat hitam tebal. Dilengkapi dengan hiasan buah ceri merah di atasnya, membawa nuansa perayaan klasik yang mewah.', 200000, 7),
(3, 'Fun Taartjes Siram Coklat', 'Kue tart mini personal yang dilapisi seluruhnya dengan siraman cokelat lumer mengkilap (chocolate glaze). Meskipun ukurannya kecil, rasa cokelatnya sangat intens dan premium, cocok untuk *self-reward* setelah hari yang panjang.', 20400, 20),
(3, 'Black Forest 19 Cm', 'Tart Black Forest berbentuk bundar ukuran 19 cm yang megah. Terdiri dari beberapa lapis bolu cokelat lembap yang diselingi ceri gelap dan krim lembut. Menjadi *centerpiece* yang sempurna untuk setiap acara perayaan ulang tahun.', 298000, 6),
(3, 'Fun Taartjes', 'Tartlet mini beraneka rasa dengan hiasan krim warna-warni yang menarik perhatian. Bentuknya yang kecil dan cantik membuatnya sangat disukai anak-anak dan cocok digunakan sebagai pemanis meja hidangan pesta.', 19300, 24),
(3, 'Brownies Keju', 'Perpaduan brilian antara brownies cokelat yang sangat pekat (fudgy) dengan lapisan adonan keju panggang di atasnya. Cokelat yang pahit manis berpadu dengan keju yang gurih asin, menciptakan profil rasa yang kaya dan kompleks.', 95100, 18),
(3, 'Japanese Cheesecake', 'Kue keju khas Jepang yang terkenal dengan tekstur *jiggly* dan seringan kapas. Rasanya tidak seberat cheesecake bergaya New York, memberikan lumeran keju yang lembut, halus, dan langsung meleleh seketika di lidah.', 46600, 15),
(3, 'Lemon Tart 15 X 15 Cm', 'Tart lemon berbentuk kotak (15x15 cm) yang menawarkan estetika modern. Basis kuenya yang lembut diimbangi dengan *lemon curd* yang tajam dan menyegarkan, dihias cantik dan siap disajikan untuk momen spesial.', 156000, 8),
(3, 'Brownies Almond', 'Brownies cokelat klasik dengan tekstur padat, lembap, dan sangat nyokelat. Permukaannya dihiasi dengan taburan irisan kacang almond panggang yang melimpah, memberikan ekstra tekstur renyah di setiap gigitan.', 93100, 18),

-- >>> ADDED [Pastry & Danish]
(4, 'Cromboloni Strawberry', 'Inovasi pastry kekinian yang menggabungkan adonan berlapis renyah ala croissant dengan bentuk bundar bomboloni. Ketika dibelah, krim stroberi segar yang melimpah akan langsung lumer keluar, memanjakan mata dan lidah.', 22100, 16),
(4, 'Kue Soes', 'Kue sus klasik andalan dengan kulit choux yang dipanggang sempurna hingga garing di luar namun berongga di dalam. Rongga tersebut diisi penuh dengan vla vanilla dingin yang sangat creamy, manis, dan beraroma rum ringan.', 10300, 40),
(4, 'Cromboloni Coklat', 'Pastry bundar viral bertekstur luar biasa garing yang menyembunyikan lumeran krim cokelat pekat di dalamnya. Sensasi suara renyah saat digigit berpadu dengan krim cokelat dingin menjadikannya favorit semua kalangan.', 22100, 18),
(4, 'Croissant Penyet', 'Cara baru menikmati croissant. Adonan croissant bermentega tinggi ini sengaja dipipihkan dan dipanggang ulang (smash) hingga menciptakan karamelisasi garing yang maksimal. Cocok sebagai camilan manis yang renyah seperti biskuit.', 11800, 25),
(4, 'Cromboloni Sweet Cheese', 'Varian gurih manis dari pastry bundar berlapis ini diisi dengan krim keju manis yang melimpah. Tekstur kulit pastry yang *flaky* (berlapis-lapis) mengurung krim keju yang lumer sempurna, menciptakan sensasi makan yang mewah.', 22100, 16),
(4, 'Chicken Mushroom Puff', 'Pastry gurih berbentuk kotak dengan kulit berlapis-lapis tipis yang garing renyah. Isiannya padat, terdiri dari potongan daging ayam dan jamur kancing segar yang dimasak dengan bumbu krim kental bercita rasa kaldu kuat.', 12100, 30),
(4, 'Danish Keju Apik', 'Pastry Danish autentik bertekstur garing dengan lapisan adonan yang terlihat jelas. Di bagian tengahnya terdapat lelehan keju panggang yang sedikit terkaramelisasi, memancarkan aroma mentega dan keju yang menggoda selera.', 17200, 20),
(4, 'Danish Raisin', 'Pastry ringan dan renyah beraroma mentega pekat ala Eropa, digulung cantik dengan sisipan kismis berlimpah. Kismis yang terkaramelisasi saat dipanggang memberikan semburat rasa manis legit di tengah gurihnya pastry.', 13800, 20),
(4, 'Chicken Pie', 'Pai klasik dengan mangkuk kulit *crust* tebal nan renyah yang menahan isian sup ayam krim panas di dalamnya. Sayuran dan daging ayam yang dimasak lambat menghasilkan rasa gurih rumahan yang sangat *comforting*.', 12100, 28),
(4, 'Cromboloni Lemon', 'Pastry bundar garing berongga ini diisi penuh dengan krim lemon segar (*lemon curd*) yang tajam dan beraroma sitrus kuat. Pilihan rasa yang paling menyeimbangkan ketebalan mentega pada adonan pastry, asam dan gurih bersatu sempurna.', 22100, 16),

-- >>> ADDED [Cookies]
(5, 'Kaasstengels Toples Segi 4', 'Kue kering kastengel premium yang dibuat menggunakan keju edam asli pilihan. Menghasilkan tekstur super renyah namun langsung lumer di mulut dengan rasa gurih keju yang tertinggal lama. Dikemas elegan dalam toples segi empat.', 142000, 10),
(5, 'Lidah Kucing Toples Segi 4', 'Kue kering legendaris berbentuk pipih memanjang setipis lidah kucing. Bertekstur luar biasa renyah dan rapuh, memancarkan aroma mentega (roombutter) berkualitas tinggi yang sangat wangi. Susah berhenti jika sudah memakannya.', 93500, 12),
(5, 'Putri Salju Toples Segi 8', 'Kue kering klasik berbentuk bulan sabit yang dipanggang hingga renyah, lalu dibalur tebal dengan gula bubuk khusus yang memberikan sensasi dingin di lidah. Dikemas rapi dalam toples segi delapan yang aman untuk pengiriman.', 96500, 10),
(5, 'Cokelat Hati Toples Segi 8', 'Kue kering cokelat pekat berbentuk hati yang dicetak presisi. Teksturnya sangat renyah bak biskuit premium dengan *aftertaste* cokelat murni yang dominan manis dan sedikit pahit khas kakao asli.', 96500, 14),
(5, 'Kaasstengels Toples Segi 8', 'Varian kastengel keju lumer ukuran besar dalam kemasan toples segi delapan. Taburan keju cheddar panggang di atasnya menambahkan ekstra tekstur garing, menjadikannya kue kering wajib untuk suguhan hari raya keluarga.', 154000, 8),
(5, 'Nastar Jambu ( Satuan )', 'Inovasi bentuk nastar yang dicetak cantik menyerupai buah jambu kecil. Kulit kuenya beraroma mentega kuat, diisi penuh dengan selai nanas murni buatan sendiri yang bertekstur legit dan berserat. Dijual satuan untuk camilan praktis.', 8800, 80),
(5, 'Putri Salju Toples Segi 4', 'Kue kering putri salju klasik dalam ukuran toples segi empat yang ringkas. Kacang tanah sangrai yang dihaluskan ke dalam adonan memberikan profil rasa gurih yang mengimbangi manisnya balutan tebal gula salju di luarnya.', 77000, 12),
(5, 'Nastar Toples Segi 8', 'Nastar nanas premium berukuran proporsional yang disusun rapi dalam toples segi delapan. Keseimbangan sempurna antara kulit kue yang lumer, olesan kuning telur yang mengkilap, dan isian selai nanas yang berlimpah.', 142000, 8),
(5, 'Roti Bagelen', 'Roti kering tradisional berukuran bulat manis, dioles dengan mentega dan taburan gula pasir, lalu dipanggang lambat hingga kadar airnya habis. Menghasilkan camilan super renyah yang sempurna untuk diseduh bersama kopi panas.', 33700, 24),
(5, 'Nastar Toples Segi 4', 'Kue nastar klasik yang dibuat dengan takaran mentega presisi agar tidak mudah hancur namun tetap lumer saat dikunyah. Isian selai nanasnya memiliki tingkat keasaman dan manis yang pas, dibungkus dalam kemasan toples kotak praktis.', 121500, 10),

-- >>> ADDED [Traditional Snack]
(6, 'Lemper Ayam', 'Jajanan tradisional berbahan dasar beras ketan pulen yang ditanak dengan santan kental gurih, diisi dengan suwiran daging ayam berbumbu manis gurih. Dibungkus rapat menggunakan daun pisang asli untuk menjaga aroma otentiknya.', 10800, 34),
(6, 'Coconut Sugar Steamed Cake', 'Bolu kukus mekar tradisional merekah sempurna ke empat sisi. Dibuat menggunakan gula merah kelapa murni yang menghasilkan warna kecokelatan alami dan aroma karamel tradisional yang khas serta tekstur yang sangat empuk.', 7100, 28),
(6, 'Bika Ambon', 'Kue basah eksotis khas nusantara berwarna kuning cerah dengan tekstur kenyal bersarang lebah dari atas ke bawah. Aromanya sangat memikat berkat penggunaan serai, daun jeruk purut, dan fermentasi nira yang dibakar sempurna.', 147000, 12),
(6, 'Bika Ambon Potong', 'Potongan praktis dari loyang Bika Ambon utuh. Didesain untuk pembeli yang menginginkan porsi personal tanpa harus membeli satu loyang besar. Tekstur kenyalnya yang khas tetap terjaga kelembapannya dalam kemasan plastik satuan.', 7700, 30),
(6, 'Pastel Ayam', 'Jajanan pasar berbentuk setengah lingkaran dengan pinggiran yang dikepang rapi secara manual. Kulitnya digoreng berbintik (pringisan) super garing, diisi padat dengan tumisan bihun, wortel, telur rebus, dan ayam cincang berbumbu merica.', 10800, 34),
(6, 'Kue Ku Ketan / Kue Ku Kacang Hijau', 'Kue tradisional kenyal peranakan berwarna merah cerah yang dicetak menggunakan cetakan kayu berbentuk tempurung kura-kura, melambangkan umur panjang. Bagian luarnya kenyal terbuat dari tepung ketan, berisi pasta kacang hijau manis yang lumer.', 6600, 36),
(6, 'Kue Pepe Roll', 'Inovasi kue lapis kanji tradisional yang bertekstur luar biasa kenyal, legit, dan manis. Disajikan dengan cara digulung cantik menyerupai bolu gulung, memperlihatkan gradasi warna yang menarik dan rapi, dengan aroma pandan yang wangi.', 7700, 30),
(6, 'Bika Ambon Cup', 'Bika ambon otentik bersarang penuh dalam ukuran cup cetakan bundar personal yang mungil. Sangat praktis disantap sebagai teman minum teh atau dijadikan isian kotak snack (*snack box*) untuk berbagai acara formal maupun santai.', 10400, 24),
(6, 'Nastar Jambu ( Isi 10 )', 'Satu kotak mika transparan eksklusif berisi 10 buah kue nastar unik berbentuk buah jambu. Isian selai nanas aslinya tebal dan legit. Tampilan visualnya yang sangat cantik menjadikannya pilihan tepat untuk hantaran (*hampers*) kecil yang pantas.', 82500, 18),
(6, 'Bugis Mandi', 'Kue tradisional berbahan tepung ketan kenyal berisi unti (kelapa parut manis). Keunikannya terletak pada penyajiannya yang direndam dalam saus santan (kanil) kental yang gurih asin, menyeimbangkan rasa manis legit dari kue ketannya.', 7200, 28);

SET FOREIGN_KEY_CHECKS=1;