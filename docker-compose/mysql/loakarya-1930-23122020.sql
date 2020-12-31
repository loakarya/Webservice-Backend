-- MySQL dump 10.13  Distrib 8.0.22, for Linux (x86_64)
--
-- Host: localhost    Database: loakarya
-- ------------------------------------------------------
-- Server version	8.0.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `article_categories`
--

DROP TABLE IF EXISTS `article_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `article_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_categories`
--

LOCK TABLES `article_categories` WRITE;
/*!40000 ALTER TABLE `article_categories` DISABLE KEYS */;
INSERT INTO `article_categories` VALUES (1,1,'Integer Lacinia',NULL,'2020-12-23 10:55:39'),(8,1,'Interdum Et Malesuada','2020-12-11 10:59:43','2020-12-23 10:55:56');
/*!40000 ALTER TABLE `article_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int unsigned NOT NULL DEFAULT '0',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `intervention` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (22,1,'Contoh Artikel','Digunakan untuk Mengecek Kompatibilitas Frontend','contoh-artikel','hLtW3Xj3nRaSClJuPcXmbBGWTy3TME1Hx6nZi5G1.jpg',1,'<h2>Ini heading 1</h2><h3>ini heading 2</h3><h4>ini heading 3</h4><p>ini paragraph</p><p><strong>ini bold</strong></p><p><i>ini italic</i></p><p><a href=\"google.com\">Ini link website ke google</a></p><ul><li>ini bullet</li><li>ini bullet</li></ul><ol><li>ini numbered</li><li>ini numbered</li></ol><figure class=\"table\"><table><tbody><tr><td>Ini tabel</td><td>Ini tabel</td><td>Ini tabel</td></tr><tr><td>Ini tabel</td><td>Ini tabel</td><td>Ini tabel</td></tr><tr><td>Ini tabel</td><td>Ini tabel</td><td>Ini tabel</td></tr></tbody></table></figure><blockquote><p>ini quote</p></blockquote><figure class=\"image image_resized\" style=\"width:62.92%;\"><img src=\"http://localhost:8000/storage/article/6JALpwZWznUiD7kZwlmBJ2OXSduiV9CWLsoWrKMf.jpg\"><figcaption>ini gambar</figcaption></figure><figure class=\"media\"><oembed url=\"https://www.youtube.com/watch?v=fjDtcL9CTSk\"></oembed></figure><p>di atas ini embed youtube</p>','2020-12-23 10:39:46','2020-12-23 11:04:04',NULL,NULL);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `question` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (6,1,'Berapa dimensi dari Loka Table?','Loka Table memiliki dimensi 120 cm x 60 cm x 71 cm (p x l x t) dan hanya tersedia dalam satu ukuran',2,NULL,NULL,NULL),(5,1,'Apa perbedaan antara Loka Smart Table dan Loka Regular Table?','Perbedaan terdapat pada fitur, Loka Smart Table memilik fitur uilt-in bluetooth speaker dan controllable lamp.',2,NULL,NULL,NULL),(7,1,'Apakah Loka Table tersedia dalam kondisi modular?','Ya, Loka Table tersedia dalam kondisi modular ataupun non-modular.',2,NULL,NULL,NULL),(8,1,'Bagaimana cara mengendalikan lampu dan speaker? ','Lampu dan speaker dapat dikendalikan menggunakan gawai anda,',2,NULL,NULL,NULL),(9,1,'Apakah Loka Table tersedia warna lain?',' Tidak, Loka Table hanya tersedia dalam satu warna.',2,NULL,NULL,NULL),(10,1,'Apa perbedaan antara modular dan non-modular? ','Non-modular berari sudah dirakit, sedangkan modular berarti meja belum dirakit.',2,NULL,NULL,NULL),(11,1,'Apa material yang digunakan pada Loka Table? ','Material yang digunakan berasal dari kayu jati, multiplex, plastik HDE, akrilik, dan stell.',2,NULL,NULL,NULL),(12,1,'Apakah terdapa limbah yang digunakan dalam produk Loka Table? ','Ya, dalam setiap sau unit Loka Table, kami menggunakan 4 kg limbah plastik HDPE.',2,NULL,NULL,NULL),(13,1,'Berapa lama waktu yang dibutuhkan untuk proses produksi? ','Proses produksi membutuhkan waktu ±1 bulan',4,NULL,NULL,NULL),(14,1,'Apakah harga yang tertera sudah termasuk biaya pengemasan dan pengiriman?','Tidak, harga yang tertera belum ermasuk biaya pengemasan dan pengiriman',4,NULL,NULL,NULL),(15,1,'Berapakah biaya pengemasan dan pengiriman?','Kedua biaya ersebu menyesuaikan dengan lokasi penerima serta jenis meja (modular atau non modular).',4,NULL,NULL,NULL),(16,1,'Apakah terdapat garansi yang diberikan?','Ya, terdapat garansi selama 1 bulan sejak produk diterima.',4,NULL,NULL,NULL),(17,1,'Apa itu Lokarya?','Loakarya menyediakan solusi mudah dengan pengalaman yang menyenangkan untuk mengelola limbah anorganik yang anda punya dengan metode upcycling dan recycling juga mengedepankan konsep eco smartliving.',1,NULL,NULL,NULL),(18,1,'Apa yang dimaksud dengan Upcycling? Apa perbedaanya dengan Recycling?','Dalam upcycle ada jaminan bahwa hasil daur ulang akan memiliki nilai yang lebih baik dibandingkan dengan produk semula karena melalui proses desain sebelumnya.⁣\r\n⁣',1,NULL,NULL,NULL),(19,1,'Apa itu layanan On-Demand?','Jasa On-Demand adalah jasa upcycle limbah menjadi furnitur atau dekorasi ruangan yang dapat dikustomisasi.',3,NULL,NULL,NULL),(20,1,'Limbah apa saja yang dapat diberikan? ','Limbah yang dapat kami terima adalah limbah anorganik, seperti kayu, logam, kaca, ataupun plastik.',3,NULL,NULL,NULL),(21,1,'Bagaimana proses desain dari produk On-Demand? ','Proses desain produk dapat dilakukan dengan ideasi dari konsumen yang dikolaborasikan dengan tim Loakarya.',3,NULL,NULL,NULL),(22,1,'Berapa persentase limbah yang digunakan sebagai materil? ','Pada setiap produk upcycle Loakarya, minimal terdapat 60% limbah yang digunakan sebagai material.',3,NULL,NULL,NULL),(23,1,'Apa itu layanan desain interior?','Jasa desain interior adalah layanan yang diberikan Loakarya dalam mewujudkan kondisi ruangan yang ideal bagi anda. Tentu, dalam produk yang digunakannya terdapat material limbah yang digunkan.',3,NULL,NULL,NULL);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `featured_products`
--

DROP TABLE IF EXISTS `featured_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `featured_products`
--

LOCK TABLES `featured_products` WRITE;
/*!40000 ALTER TABLE `featured_products` DISABLE KEYS */;
INSERT INTO `featured_products` VALUES (1,1,0,'2020-12-02 10:59:26','2020-12-02 10:59:26'),(2,2,0,'2020-12-02 10:59:26','2020-12-02 10:59:26'),(3,1,0,'2020-12-02 10:59:26','2020-12-02 10:59:26'),(4,2,0,'2020-12-02 10:59:26','2020-12-02 10:59:26'),(5,1,0,'2020-12-02 10:59:26','2020-12-02 10:59:26');
/*!40000 ALTER TABLE `featured_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2020_11_25_080435_create_products_table',1),(5,'2020_11_25_080522_create_f_a_qs_table',1),(6,'2020_11_26_042719_create_articles_table',1),(7,'2020_12_02_175637_create_featured_products_table',2),(8,'2020_12_04_095200_create_article_categories_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(210) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture_url_1` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture_url_2` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture_url_3` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture_url_4` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture_url_5` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int unsigned NOT NULL DEFAULT '0',
  `discount` int unsigned NOT NULL DEFAULT '0',
  `category` int unsigned NOT NULL DEFAULT '0',
  `tokopedia_order_link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shopee_order_link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukalapak_order_link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (6,1,'Loka Regular Table','loka-regular-table','Loka Regular Table hadir dengan desain minimalis dan ergonomis, memenuhi semua kebutuhanmu dalam bekerja. Bukan hanya itu, meja ini juga menunjang anda untuk lebih produktif.\r\n\r\nTentunya Loka Smart Table hadir dengan fitur-fitur pendukung:\r\n1. Spacious Storage\r\n2. USB Plug \r\n3. Power sources','Menggunakan bahan dasar kayu jati. Memiliki ukuran 120 cm x 60 cm x 71 cm,  di setiap produk Loka table, terdapat 4 kg plastik HDPE yang diupcycle menjadi material dari Loka Table.','https://resources.loakarya.co/products/R1.jpg','https://resources.loakarya.co/products/R1.jpg','https://resources.loakarya.co/products/R2.jpg','https://resources.loakarya.co/products/R3.jpg','https://resources.loakarya.co/products/R4.jpg',NULL,3600000,0,0,'https://www.tokopedia.com/loakarya','https://shopee.co.id/loakarya','https://www.bukalapak.com/u/loakarya_indonesia',NULL,NULL,NULL),(4,1,'Loka Smart Table','loka-smart-table','Loka Smart Table hadir dengan desain minimalis dan ergonomis, memenuhi semua kebutuhanmu dalam bekerja. Bukan hanya itu, meja ini juga menunjang anda untuk lebih produktif.\r\n\r\nTentunya Loka Smart Table hadir dengan fitur-fitur pendukung:\r\n1. Spacious Storage\r\n2. USB Plug and power sources\r\n3. Controllable lamp\r\n4. Built-in bluetooth speaker.','Menggunakan bahan dasar kayu jati. Memiliki ukuran 120 cm x 60 cm x 71 cm,  di setiap produk Loka Smart table, terdapat 4 kg plastik HDPE yang diupcycle menjadi material dari Loka Table.','https://resources.loakarya.co/products/S1.jpg','https://resources.loakarya.co/products/LokaSmartTable_1.png','https://resources.loakarya.co/products/LokaSmartTable_2.png','https://resources.loakarya.co/products/S3.jpg','https://resources.loakarya.co/products/S4.jpg',NULL,4500000,0,0,'https://www.tokopedia.com/loakarya','https://shopee.co.id/loakarya','https://www.bukalapak.com/u/loakarya_indonesia',NULL,NULL,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `acl` int unsigned NOT NULL DEFAULT '0',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Fikri','Rida Pebriansyah','fikriultimate18@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','2020-11-25 21:34:53','Taman Rahayu 2 blok C3 no 8 RT 03 RW 01. Desa Cigondewah Hilir. Kecamatan Margaasih.','40214','Kabupaten Bandung','Jawa Barat','Indonesia','0.0.0.0',2,'2Z6YFKuBNK','2020-11-25 21:34:53','2020-11-25 21:34:53',NULL),(2,'Dummy','Admin','admin@loakarya.co','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','2020-11-25 21:34:53','27837 Maryse Ways Apt. 369\nLake Roxannefurt, HI 47048','23454','Leorachester','Utah','Christmas Island','0.0.0.0',1,'1PLGEBZnAy','2020-11-25 21:34:53','2020-11-25 21:34:53',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-23 12:31:27
