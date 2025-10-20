-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: booking
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `food_court_images`
--

DROP TABLE IF EXISTS `food_court_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_court_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `food_court_id` int unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `food_court_images_food_court_id_foreign` (`food_court_id`),
  CONSTRAINT `food_court_images_food_court_id_foreign` FOREIGN KEY (`food_court_id`) REFERENCES `food_courts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_court_images`
--

LOCK TABLES `food_court_images` WRITE;
/*!40000 ALTER TABLE `food_court_images` DISABLE KEYS */;
INSERT INTO `food_court_images` VALUES (1,1,'/uploads/food-court/bun-ca-long-xuyen/food-court_68f505755d32e4.10122862_download.jpg','download.jpg','2025-10-19 15:36:53','2025-10-19 15:36:53'),(2,1,'/uploads/food-court/bun-ca-long-xuyen/food-court_68f50575645250.17852601_download__4_.jpg','download (4).jpg','2025-10-19 15:36:53','2025-10-19 15:36:53');
/*!40000 ALTER TABLE `food_court_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `food_courts`
--

DROP TABLE IF EXISTS `food_courts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_courts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int unsigned NOT NULL,
  `travel_spot_id` int unsigned NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `average_star` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_rates` int unsigned NOT NULL DEFAULT '0',
  `price_from` decimal(10,2) DEFAULT NULL,
  `price_to` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `food_courts_province_id_foreign` (`province_id`),
  KEY `food_courts_travel_spot_id_foreign` (`travel_spot_id`),
  CONSTRAINT `food_courts_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_courts`
--

LOCK TABLES `food_courts` WRITE;
/*!40000 ALTER TABLE `food_courts` DISABLE KEYS */;
INSERT INTO `food_courts` VALUES (1,'Bún cá Long Xuyên','Là món ăn đầu tiên trong danh sách đặc sản An Giang muốn giới thiệu cho bạn món bún cá Long Xuyên. Đây là một món ăn khá quen thuộc và bình dị đối với dân địa phương, nó còn có tên gọi khác là bún nước lèo.\r\n\r\nVới hương vị nước lèo ngọt thanh, vị hơi nhạt, và đặc trưng là cá được ướp qua nghệ vàng ươm, giúp màu nước lèo vàng và có mùi thơm nghệ và khiến món bún cá thêm đậm đà hơn. Cá lóc hoặc cá kèo thường được chọn để nấu nước dùng, ngoài ra bạn cũng có thể ăn kèm với thịt heo, và không thể thiếu những món rau đặc trưng vùng sông nước như giá, bông điên điển, bắp chuối, rau răm,...','18/2A Lê Lợi, Mỹ Bình, Tp Long Xuyên',1,0,'09:30:00','22:02:00',0.00,0,15000.00,25000.00,'2025-10-19 15:36:13','2025-10-19 15:36:13');
/*!40000 ALTER TABLE `food_courts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `province_images`
--

DROP TABLE IF EXISTS `province_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `province_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `province_id` int unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publicUrl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `province_images_province_id_foreign` (`province_id`),
  CONSTRAINT `province_images_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `province_images`
--

LOCK TABLES `province_images` WRITE;
/*!40000 ALTER TABLE `province_images` DISABLE KEYS */;
INSERT INTO `province_images` VALUES (1,1,'/uploads/provinces/an-giang/province_68f3aced017187.16611289_an-giang-3.png','an-giang-3.png','2025-10-18 15:06:21','2025-10-18 15:06:21'),(2,1,'/uploads/provinces/an-giang/province_68f3aced1967c7.08573124_an-giang-2.jpg','an-giang-2.jpg','2025-10-18 15:06:21','2025-10-18 15:06:21'),(3,1,'/uploads/provinces/an-giang/province_68f3aced256f40.45934556_an-giang-1.jpg','an-giang-1.jpg','2025-10-18 15:06:21','2025-10-18 15:06:21'),(4,2,'/uploads/provinces/bac-ninh/province_68f3ad471b6531.22595087_bac-ninh-1-16077848708512064768942.jpg','bac-ninh-1-16077848708512064768942.jpg','2025-10-18 15:07:51','2025-10-18 15:07:51'),(5,2,'/uploads/provinces/bac-ninh/province_68f3ad472ce0d3.80455388_image-20200714225347-1.jpg','image-20200714225347-1.jpg','2025-10-18 15:07:51','2025-10-18 15:07:51'),(6,2,'/uploads/provinces/bac-ninh/province_68f3ad47480ab5.09200974_44d69adc-du-lich-bac-ninh-24.jpg','44d69adc-du-lich-bac-ninh-24.jpg','2025-10-18 15:07:51','2025-10-18 15:07:51'),(7,3,'/uploads/provinces/ca-mau/province_68f3adbeb70622.86873079_c__-mau2.jpg','cà-mau2.jpg','2025-10-18 15:09:51','2025-10-18 15:09:51'),(8,3,'/uploads/provinces/ca-mau/province_68f3adbed92c77.76033543_7C-1.jpg','7C-1.jpg','2025-10-18 15:09:51','2025-10-18 15:09:51'),(9,3,'/uploads/provinces/ca-mau/province_68f3adbeeac1f8.20467011_1737958354_muicamau-.jpg','1737958354_muicamau-.jpg','2025-10-18 15:09:51','2025-10-18 15:09:51'),(10,4,'/uploads/provinces/can-tho/province_68f3ae44e16945.03703688_download__2_.jpg','download (2).jpg','2025-10-18 15:12:05','2025-10-18 15:12:05'),(11,4,'/uploads/provinces/can-tho/province_68f3ae44f1cb87.97572118_download__1_.jpg','download (1).jpg','2025-10-18 15:12:05','2025-10-18 15:12:05'),(12,4,'/uploads/provinces/can-tho/province_68f3ae450878a5.92585839_download.jpg','download.jpg','2025-10-18 15:12:05','2025-10-18 15:12:05'),(13,5,'/uploads/provinces/cao-bang/province_68f3af021f81e1.66252542_du-lich-cao-bang-cam-nang-du-lich-va-18-dia-diem-nen-den-nhat-202305051452598981.jpg','du-lich-cao-bang-cam-nang-du-lich-va-18-dia-diem-nen-den-nhat-202305051452598981.jpg','2025-10-18 15:15:14','2025-10-18 15:15:14'),(14,5,'/uploads/provinces/cao-bang/province_68f3af023921b8.27022886_1.jpg','1.jpg','2025-10-18 15:15:14','2025-10-18 15:15:14'),(15,5,'/uploads/provinces/cao-bang/province_68f3af02484ae9.09007856_du-lich-cao-bang-ivivu-1.jpg','du-lich-cao-bang-ivivu-1.jpg','2025-10-18 15:15:14','2025-10-18 15:15:14'),(16,6,'/uploads/provinces/da-nang/province_68f3af5e3f7926.40587645_images.jpg','images.jpg','2025-10-18 15:16:46','2025-10-18 15:16:46'),(17,6,'/uploads/provinces/da-nang/province_68f3af5e4bcc37.55919266_download.jpg','download.jpg','2025-10-18 15:16:46','2025-10-18 15:16:46'),(18,6,'/uploads/provinces/da-nang/province_68f3af5e547086.96649837_download__3_.jpg','download (3).jpg','2025-10-18 15:16:46','2025-10-18 15:16:46'),(19,7,'/uploads/provinces/dak-lak/province_68f3afb5d622b9.43469631_images.jpg','images.jpg','2025-10-18 15:18:14','2025-10-18 15:18:14'),(20,7,'/uploads/provinces/dak-lak/province_68f3afb5f162a8.02094681_download.jpg','download.jpg','2025-10-18 15:18:14','2025-10-18 15:18:14'),(21,7,'/uploads/provinces/dak-lak/province_68f3afb60b4bb4.86372628_download__1_.jpg','download (1).jpg','2025-10-18 15:18:14','2025-10-18 15:18:14'),(22,8,'/uploads/provinces/dien-bien/province_68f3b0069de302.78007957_download__1_.jpg','download (1).jpg','2025-10-18 15:19:34','2025-10-18 15:19:34'),(23,8,'/uploads/provinces/dien-bien/province_68f3b006aff911.40903492_download.jpg','download.jpg','2025-10-18 15:19:34','2025-10-18 15:19:34'),(24,8,'/uploads/provinces/dien-bien/province_68f3b006be1555.30713157_download__2_.jpg','download (2).jpg','2025-10-18 15:19:34','2025-10-18 15:19:34'),(25,9,'/uploads/provinces/dong-nai/province_68f3b07f514559.90165532_download__1_.jpg','download (1).jpg','2025-10-18 15:21:35','2025-10-18 15:21:35'),(26,9,'/uploads/provinces/dong-nai/province_68f3b07f5c4c06.96717414_download.jpg','download.jpg','2025-10-18 15:21:35','2025-10-18 15:21:35'),(27,9,'/uploads/provinces/dong-nai/province_68f3b07f650b16.70601006_download__4_.jpg','download (4).jpg','2025-10-18 15:21:35','2025-10-18 15:21:35'),(28,10,'/uploads/provinces/dong-thap/province_68f3b0afabfe84.96662974_download__1_.jpg','download (1).jpg','2025-10-18 15:22:23','2025-10-18 15:22:23'),(29,10,'/uploads/provinces/dong-thap/province_68f3b0afbd96f4.05822191_download.jpg','download.jpg','2025-10-18 15:22:23','2025-10-18 15:22:23'),(30,10,'/uploads/provinces/dong-thap/province_68f3b0afd173b7.19602025_download__2_.jpg','download (2).jpg','2025-10-18 15:22:23','2025-10-18 15:22:23'),(31,11,'/uploads/provinces/gia-lai/province_68f3b0e72ff199.86695579_download__1_.jpg','download (1).jpg','2025-10-18 15:23:19','2025-10-18 15:23:19'),(32,11,'/uploads/provinces/gia-lai/province_68f3b0e73da3e9.19899296_download.jpg','download.jpg','2025-10-18 15:23:19','2025-10-18 15:23:19'),(33,11,'/uploads/provinces/gia-lai/province_68f3b0e74800b3.71964270_download__3_.jpg','download (3).jpg','2025-10-18 15:23:19','2025-10-18 15:23:19'),(34,12,'/uploads/provinces/ha-noi/province_68f3b163d9e800.47941628_images.jpg','images.jpg','2025-10-18 15:25:24','2025-10-18 15:25:24'),(35,12,'/uploads/provinces/ha-noi/province_68f3b163ef7cc1.36246898_download__1_.jpg','download (1).jpg','2025-10-18 15:25:24','2025-10-18 15:25:24'),(36,12,'/uploads/provinces/ha-noi/province_68f3b1640b7822.93758954_download.jpg','download.jpg','2025-10-18 15:25:24','2025-10-18 15:25:24'),(37,13,'/uploads/provinces/ha-tinh/province_68f3b1a1845829.96894592_images.jpg','images.jpg','2025-10-18 15:26:25','2025-10-18 15:26:25'),(38,13,'/uploads/provinces/ha-tinh/province_68f3b1a1925192.37891024_download.jpg','download.jpg','2025-10-18 15:26:25','2025-10-18 15:26:25'),(39,13,'/uploads/provinces/ha-tinh/province_68f3b1a19ac997.78958376_download__2_.jpg','download (2).jpg','2025-10-18 15:26:25','2025-10-18 15:26:25'),(40,14,'/uploads/provinces/hai-phong/province_68f3b1d59a01c3.06731740_download__2_.jpg','download (2).jpg','2025-10-18 15:27:17','2025-10-18 15:27:17'),(41,14,'/uploads/provinces/hai-phong/province_68f3b1d5a52274.42605686_download.jpg','download.jpg','2025-10-18 15:27:17','2025-10-18 15:27:17'),(42,14,'/uploads/provinces/hai-phong/province_68f3b1d5b2bbf8.62371780_download__1_.jpg','download (1).jpg','2025-10-18 15:27:17','2025-10-18 15:27:17'),(43,15,'/uploads/provinces/ho-chi-minh/province_68f3b267ab8951.10402278_download__1_.jpg','download (1).jpg','2025-10-18 15:29:43','2025-10-18 15:29:43'),(44,15,'/uploads/provinces/ho-chi-minh/province_68f3b267c19526.39984731_download.jpg','download.jpg','2025-10-18 15:29:43','2025-10-18 15:29:43'),(45,15,'/uploads/provinces/ho-chi-minh/province_68f3b267cf6572.37607170_download__3_.jpg','download (3).jpg','2025-10-18 15:29:43','2025-10-18 15:29:43'),(46,16,'/uploads/provinces/hue/province_68f3b29c0d3296.11720666_download__1_.jpg','download (1).jpg','2025-10-18 15:30:36','2025-10-18 15:30:36'),(47,16,'/uploads/provinces/hue/province_68f3b29c1c2973.82431739_download.jpg','download.jpg','2025-10-18 15:30:36','2025-10-18 15:30:36'),(48,16,'/uploads/provinces/hue/province_68f3b29c241886.41328638_download__2_.jpg','download (2).jpg','2025-10-18 15:30:36','2025-10-18 15:30:36'),(49,17,'/uploads/provinces/hung-yen/province_68f3b2cc829539.94581831_download__1_.jpg','download (1).jpg','2025-10-18 15:31:24','2025-10-18 15:31:24'),(50,17,'/uploads/provinces/hung-yen/province_68f3b2cc8b45b8.87848900_download.jpg','download.jpg','2025-10-18 15:31:24','2025-10-18 15:31:24'),(51,17,'/uploads/provinces/hung-yen/province_68f3b2cc98f909.33052999_download__3_.jpg','download (3).jpg','2025-10-18 15:31:24','2025-10-18 15:31:24'),(52,18,'/uploads/provinces/khanh-hoa/province_68f3b2fbb40ab3.94803740_download__1_.jpg','download (1).jpg','2025-10-18 15:32:11','2025-10-18 15:32:11'),(53,18,'/uploads/provinces/khanh-hoa/province_68f3b2fbc12fa9.29063590_download.jpg','download.jpg','2025-10-18 15:32:11','2025-10-18 15:32:11'),(54,18,'/uploads/provinces/khanh-hoa/province_68f3b2fbc9c2a6.11586965_download__2_.jpg','download (2).jpg','2025-10-18 15:32:11','2025-10-18 15:32:11'),(55,19,'/uploads/provinces/lai-chau/province_68f3b342599b26.96098952_download.jpg','download.jpg','2025-10-18 15:33:22','2025-10-18 15:33:22'),(56,19,'/uploads/provinces/lai-chau/province_68f3b3426adda7.56521137_dia-diem-du-lich-lai-chau_1755228424.jpg','dia-diem-du-lich-lai-chau_1755228424.jpg','2025-10-18 15:33:22','2025-10-18 15:33:22'),(57,19,'/uploads/provinces/lai-chau/province_68f3b34279d3e8.17817221_top-10-dia-diem-du-lich-lai-chau.jpg','top-10-dia-diem-du-lich-lai-chau.jpg','2025-10-18 15:33:22','2025-10-18 15:33:22'),(58,20,'/uploads/provinces/lam-dong/province_68f3b39c8f8c30.59793183_images.jpg','images.jpg','2025-10-18 15:34:52','2025-10-18 15:34:52'),(59,20,'/uploads/provinces/lam-dong/province_68f3b39c967276.94159883_download.jpg','download.jpg','2025-10-18 15:34:52','2025-10-18 15:34:52'),(60,20,'/uploads/provinces/lam-dong/province_68f3b39c9c15f1.96677257_download__1_.jpg','download (1).jpg','2025-10-18 15:34:52','2025-10-18 15:34:52');
/*!40000 ALTER TABLE `province_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinces` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provinces_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES (1,'91','An Giang','Tỉnh','An Giang sở hữu diện tích khá lớn ở miền Tây Nam Bộ, trong đó có nhiều cảnh quan thiên nhiên tươi đẹp, có sông nước mênh mông, có núi non kỳ vĩ, có rừng tràm, có đồng ruộng bát ngát,...  An Giang cũng sở hữu một trong những hòn đảo đẹp nhất ở Việt Nam là đảo Phú Quốc, nơi hàng năm thu hút hàng triệu lượt khách quốc tế','2025-10-18 15:06:19','2025-10-18 15:06:19'),(2,'24','Bắc Ninh','Tỉnh','Bắc Ninh là tỉnh với dân ca quan họ. Bắc Ninh là trung tâm xứ Kinh Bắc cổ xưa. Hiện nay trên địa bàn tỉnh Bắc Ninh có khoảng 41 lễ hội đáng chú ý trong năm được duy trì. Trong đó có những lễ hội lớn như: hội chùa Dâu, Hội Lim, hội Đền Đô (Đền Lý Bát Đế - thờ 8 vị vua nhà Lý), hội đền Bà Chúa Kho. Bắc Ninh được biết đến là vùng đất với nghề tơ tằm, gốm sứ, đúc đồng, khắc gỗ, làm giấy, tranh vẽ dân gian... nổi bật là những làn điệu dân ca quan họ','2025-10-18 15:07:50','2025-10-18 15:07:50'),(3,'96','Cà Mau','Tỉnh','Tên gọi Cà Mau (chính tả cũ: Cà-mau) được hình thành do người Khmer gọi tên vùng đất này là \"Tưk Kha-mau\" có nghĩa là nước đen. Do Nước đen là màu nước đặc trưng do lá tràm của thảm rừng tràm U Minh bạt ngàn rụng xuống làm đổi màu nước. Cà Mau là xứ đầm lầy ngập nước, có nhiều bụi lác mọc tự nhiên và hoang dã. ','2025-10-18 15:09:50','2025-10-18 15:09:50'),(4,'92','Cần Thơ','Thành phố','Cần Thơ là thủ phủ và là đô thị hạt nhân của miền Tây Nam Bộ từ thời Pháp thuộc, nay tiếp tục là trung tâm kinh tế của vùng Đồng bằng Sông Cửu Long. Ngoài đặc trưng về địa lý là đầu mối giao thông quan trọng giữa các tỉnh trong khu vực, thành phố Cần Thơ còn được biết đến như một đô thị miền sông nước. Thành phố có hệ thống sông ngòi chằng chịt, diện tích vườn cây ăn trái và đồng ruộng rộng lớn, nổi tiếng với Bến Ninh Kiều, Chợ nổi Cái Răng một nét sinh hoạt đặc trưng văn hóa Nam Bộ.','2025-10-18 15:12:04','2025-10-18 15:12:04'),(5,'04','Cao Bằng','Tỉnh','Cao Bằng là một tỉnh thuộc vùng Đông Bắc Bộ, Việt Nam.[6][7][8] Theo dữ liệu Sáp nhập tỉnh, thành Việt Nam 2025, Cao Bằng có diện tích: 6.700 km², xếp thứ 23; dân số: 573.119 người, xếp thứ 33; GRDP 2024: 25.203.769 triệu VNĐ , xếp thứ 34; thu ngân sách 2024: 2.476.011 triệu VNĐ, xếp thứ 33; thu nhập bình quân: 30,70 triệu VNĐ/năm, xếp thứ 32','2025-10-18 15:15:13','2025-10-18 15:15:13'),(6,'48','Đà Nẵng','Thành phố','Đà Nẵng là một tên dịch theo kiểu dịch âm kiêm dịch ý một phần, nếu phiên âm Hán-Việt thì đọc thành Đà Nhương, địa danh cần dịch đã được dịch bằng chữ Hán có âm đọc (âm Hán Việt) tương cận, ý nghĩa của chữ Hán dùng để dịch có liên quan nhất định với ý nghĩa của tên gọi được dịch. Phần lớn các ý kiến đều cho rằng tên gọi Đà Nẵng xuất phát từ vị trí nằm ở cửa sông Hàn của thành phố. Đó là một biến dạng của từ Chăm cổ \"Da nak\", được dịch là \"cửa sông lớn\"','2025-10-18 15:16:45','2025-10-18 15:16:45'),(7,'66','Đắk Lăk','Tỉnh','Đắk Lắk (trước đây ghi là Darlac) là một tỉnh nằm ở duyên hải Nam Trung Bộ và Tây Nguyên, miền Trung Việt Nam và là tỉnh có diện tích lớn thứ ba Việt Nam. Trung tâm hành chính của tỉnh là phường Buôn Ma Thuột. ','2025-10-18 15:18:13','2025-10-18 15:18:13'),(8,'11','Điện Biên','Tỉnh','Điện Biên có địa hình phức tạp, chủ yếu là đồi núi dốc, hiểm trở và chia cắt mạnh, được cấu tạo bởi những dãy núi chạy dài theo hướng Tây Bắc - Đông Nam với độ cao biến đổi từ 200 m đến hơn 1.800 m. Địa hình thấp dần từ Bắc xuống Nam và nghiêng dần từ Tây sang Đông. Ở phía Bắc có các điểm cao 1.085 m, 1.162 m và 1.856 m (thuộc huyện Mường Nhé), cao nhất là đỉnh Pu Đen Đinh (1.886 m).','2025-10-18 15:19:34','2025-10-18 15:19:34'),(9,'75','Đồng Nai','Tỉnh','Đồng Nai là một tỉnh thuộc vùng Đông Nam Bộ, Việt Nam. Đồng Nai được xem là một cửa ngõ đi vào vùng kinh tế trọng điểm Nam bộ - vùng kinh tế phát triển và năng động nhất cả nước. Đồng thời, Đồng Nai là một trong 4 góc nhọn của Tứ giác phát triển Thành phố Hồ Chí Minh nay gồm (Bình Dương - Bà Rịa – Vũng Tàu) - Đồng Nai. Dân cư tập trung phần lớn ở Biên Hòa với hơn 1 triệu dân và ở 2 huyện Trảng Bom, Long Thành.','2025-10-18 15:21:34','2025-10-18 15:21:34'),(10,'82','Đồng Tháp','Tỉnh','Đồng Tháp là một tỉnh mới thành lập thuộc vùng Đồng bằng sông Cửu Long, Việt Nam. Vùng đất Đồng Tháp đã được Chúa Nguyễn khai phá vào khoảng thế kỷ XVII, XVIII. Tỉnh Đồng Tháp được thành lập trên cơ sở hợp nhất tỉnh Kiến Phong và tỉnh Sa Đéc vào năm 1976, và sau đó là trên cơ sở sáp nhập tỉnh Đồng Tháp và tỉnh Tiền Giang vào năm 2025.','2025-10-18 15:22:23','2025-10-18 15:22:23'),(11,'52','Gia Lai','Tỉnh','Gia Lai là một tỉnh nằm ở khu vực duyên hải Nam Trung bộ và Tây Nguyên, miền Trung Việt Nam và là tỉnh có diện tích lớn thứ hai Việt Nam.[3] Trung tâm hành chính của tỉnh là phường Quy Nhơn. Nguồn gốc tên gọi Gia Lai bắt nguồn từ chữ Jarai, tên gọi của một dân tộc thiểu số trong tỉnh, cách gọi này vẫn còn giữ trong tiếng của người Ê-đê, Ba Na, Lào, Thái Lan và Campuchia để gọi vùng đất này là Jarai, Charay có nghĩa là vùng đất của người Jarai, có lẽ ám chỉ vùng đất của Thủy Xá và Hỏa Xá thuộc tiểu quốc Jarai xưa.','2025-10-18 15:23:18','2025-10-18 15:23:18'),(12,'01','Hà Nội','Thành phố','Hà Nội đã sớm trở thành trung tâm chính trị, kinh tế và văn hóa ngay từ những buổi đầu của lịch sử Việt Nam. Với vai trò thủ đô, Hà Nội là nơi tập trung nhiều địa điểm văn hóa giải trí, công trình thể thao quan trọng của đất nước, đồng thời cũng là địa điểm được lựa chọn để tổ chức nhiều sự kiện chính trị và thể thao quốc tế. Đây là nơi tập trung nhiều làng nghề truyền thống, đồng thời cũng là 1 trong 3 vùng tập trung nhiều hội lễ của miền Bắc Việt Nam. Thành phố có chỉ số phát triển con người ở mức cao, dẫn đầu trong số các đơn vị hành chính của Việt Nam. Nền ẩm thực Hà Nội với nhiều nét riêng biệt cũng là một trong những yếu tố thu hút khách du lịch tới thành phố. Năm 2019, Hà Nội là đơn vị hành chính Việt Nam xếp thứ 2 về tổng sản phẩm trên địa bàn (GRDP), xếp thứ 8 về GRDP bình quân đầu người và đứng thứ 41 về tốc độ tăng trưởng GRDP. Thành phố được UNESCO trao tặng danh hiệu \"Thành phố vì hòa bình\" vào ngày 16 tháng 7 năm 1999. Khu Hoàng thành Thăng Long cũng được tổ chức UNESCO công nhận là di sản văn hóa thế giới.','2025-10-18 15:25:23','2025-10-18 15:25:23'),(13,'42','Hà Tĩnh','Tỉnh','Tỉnh Hà Tĩnh được thành lập lần đầu tiên năm 1831, đời vua Minh Mạng trên cơ sở chia trấn Nghệ An thành 2 tỉnh: Nghệ An (phía Bắc sông Lam); Hà Tĩnh (phía nam sông Lam). Theo đó, tỉnh Hà Tĩnh lúc thành lập với gồm 2 phủ Đức Thọ và Hà Hoa của trấn Nghệ An trước đó.','2025-10-18 15:26:25','2025-10-18 15:26:25'),(14,'31','Hải Phòng','Thành phố','Hải Phòng còn được gọi là Đất Cảng hay Thành phố Cảng. Việc hoa phượng đỏ được trồng rộng rãi nơi đây, và sắc hoa đặc trưng trên những con phố, cũng khiến Hải Phòng được biết đến với mỹ danh Thành phố Hoa Phượng Đỏ. Không chỉ là một thành phố cảng công nghiệp, Hải Phòng còn là một trong những nơi có tiềm năng du lịch rất lớn. Hải Phòng hiện lưu giữ nhiều nét hấp dẫn về kiến trúc, bao gồm kiến trúc truyền thống với các chùa, đình, miếu cổ và kiến trúc tân cổ điển Pháp tọa lạc trên các khu phố cũ.','2025-10-18 15:27:17','2025-10-18 15:27:17'),(15,'79','Hồ Chí Minh','Thành phố','Địa danh Sài Gòn có trên 300 năm và từng được dùng để chỉ một khu vực với diện tích khoảng 1 km² (Chợ Lớn) có đông người Hoa sinh sống trong thế kỷ 18. Địa bàn đó gần tương ứng với khu Chợ Lớn ngày nay. Năm 1747, theo danh mục các họ đạo trong Launay, Histoire de la Mission Cochinchine, có ghi chép \"Rai Gon Thong\" (Sài Gòn Thượng) và \"Rai Gon Ha\" (Sài Gòn Hạ). Theo Phủ biên tạp lục của Lê Quý Đôn viết năm 1776, năm 1674, Thống suất Nguyễn Dương Lâm vâng lệnh chúa Nguyễn đánh Cao Miên và phá vỡ \"Lũy Sài Gòn\" (theo chữ Hán viết là 柴棍 – \"Sài Côn\").[15] Đây là lần đầu tiên chữ \"Sài Gòn\" xuất hiện trong sử liệu của người Việt. Vì họ mượn âm của chữ 棍 – \"Côn\" được dùng thế cho \"Gòn\".','2025-10-18 15:29:43','2025-10-18 15:29:43'),(16,'46','Huế','Thành phố','Huế từng là kinh đô (Cố đô Huế) của Việt Nam dưới triều Tây Sơn (1788–1801) và triều Nguyễn (1802–1945). Hiện nay, thành phố là một trong những trung tâm về văn hóa – du lịch, y tế chuyên sâu, giáo dục đào tạo, khoa học công nghệ của Miền Trung – Tây Nguyên và cả nước. Những địa danh nổi bật là sông Hương và những di sản để lại của triều đại phong kiến, Thành phố có năm danh hiệu UNESCO ở Việt Nam: Quần thể di tích Cố đô Huế (1993), Nhã nhạc cung đình Huế (2003), Mộc bản triều Nguyễn (2009), Châu bản triều Nguyễn (2014) và Hệ thống thơ văn trên kiến trúc cung đình Huế (2016). ','2025-10-18 15:30:35','2025-10-18 15:30:35'),(17,'33','Hưng Yên','Tỉnh','Hưng Yên là một tỉnh thuộc vùng đồng bằng sông Hồng, nằm ở miền Bắc Việt Nam, với trung tâm hành chính cách thủ đô Hà Nội khoảng hơn 55 km về phía Đông Nam. Đây là tỉnh duy nhất ở phía Bắc Việt Nam thuần khiết đồng bằng, không có đồi núi.  Hưng Yên là vùng đất lưu giữ nhiều di tích lịch sử với truyền thống và văn hóa lâu đời, là quê hương của nhiều danh nhân và nhà lãnh đạo như Triệu Việt Vương, Trần Thủ Độ, Phạm Ngũ Lão, Hải Thượng Lãn Ông, Lê Quý Đôn, Hoàng Hoa Thám, Hoàng Văn Thái, Nguyễn Văn Linh, Tô Lâm...','2025-10-18 15:31:24','2025-10-18 15:31:24'),(18,'56','Khánh Hòa','Tỉnh','Khánh Hòa ngày nay là phần đất cũ của xứ Kauthara thuộc vương quốc Chăm Pa. Năm 1653, lấy cớ vua Chiêm Thành là Bà Tấm quấy nhiễu dân Việt ở Phú Yên, Chúa Nguyễn Phúc Tần sai quan cai cơ Hùng Lộc đem quân sang đánh chiếm được vùng đất từ sông Phan Rang trở ra đến Phú Yên. Năm 1831, Vua Minh Mạng thành lập tỉnh Khánh Hòa trên cơ sở trấn Bình Hòa. Sau lần hợp nhất vào năm 1975, đến năm 1989, Quốc hội lại chia tỉnh Phú Khánh thành hai tỉnh Phú Yên và Khánh Hòa cho đến ngày nay. Khánh Hòa cũng là một trong những tỉnh được định hướng trở thành thành phố trực thuộc trung ương tại Việt Nam.','2025-10-18 15:32:11','2025-10-18 15:32:11'),(19,'12','Lai Châu','Tỉnh','Lai Châu là một tỉnh thuộc vùng Tây Bắc Bộ, Việt Nam.  Trước năm 2004, diện tích hành chính tỉnh Lai Châu bao gồm cả tỉnh Điện Biên. Sau khi tách, Lai Châu hiện tại có vị trí phía bắc giáp tỉnh Vân Nam của Trung Quốc, phía tây và phía tây nam giáp tỉnh Điện Biên, phía đông giáp tỉnh Lào Cai, và phía nam giáp tỉnh Sơn La. Đây là tỉnh có diện tích lớn thứ 10/63 tỉnh thành Việt Nam','2025-10-18 15:33:22','2025-10-18 15:33:22'),(20,'68','Lâm Đồng','Tỉnh','Lâm Đồng là một tỉnh thuộc vùng duyên hải Nam Trung bộ và Tây Nguyên, Miền Trung của Việt Nam. Tên tỉnh Lâm Đồng có nguồn gốc từ sự kết hợp của 2 tỉnh là: tỉnh Lâm Viên (còn được gọi là Langbiang hay Lâm Biên) và tỉnh Đồng Nai Thượng. \"Lâm (林/Lin)\" có nghĩa là rừng, \"Đồng (同/Tong)\" có nghĩa là đồng ruộng; vậy nên tên gọi \"Lâm Đồng (林同/Lin Tong)\" có nghĩa là \"rừng và đồng ruộng\".','2025-10-18 15:34:52','2025-10-18 15:34:52');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travel_imgs`
--

DROP TABLE IF EXISTS `travel_imgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `travel_imgs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_travel_spot` int unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publicUrl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `travel_imgs_id_travel_spot_foreign` (`id_travel_spot`),
  CONSTRAINT `travel_imgs_id_travel_spot_foreign` FOREIGN KEY (`id_travel_spot`) REFERENCES `travel_spots` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_imgs`
--

LOCK TABLES `travel_imgs` WRITE;
/*!40000 ALTER TABLE `travel_imgs` DISABLE KEYS */;
INSERT INTO `travel_imgs` VALUES (1,1,'/uploads/travel-spots/rung-tram-tra-su-an-giang/travel_spot_68f3b59976e973.44179453_download__2_.jpg','download (2).jpg','2025-10-18 15:43:21','2025-10-18 15:43:21'),(2,1,'/uploads/travel-spots/rung-tram-tra-su-an-giang/travel_spot_68f3b599874de2.89197200_download.jpg','download.jpg','2025-10-18 15:43:21','2025-10-18 15:43:21'),(3,1,'/uploads/travel-spots/rung-tram-tra-su-an-giang/travel_spot_68f3b599960165.44575558_download__1_.jpg','download (1).jpg','2025-10-18 15:43:21','2025-10-18 15:43:21');
/*!40000 ALTER TABLE `travel_imgs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travel_spots`
--

DROP TABLE IF EXISTS `travel_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `travel_spots` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `province_id` int unsigned NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `average_rate` decimal(3,2) NOT NULL DEFAULT '0.00',
  `price_from` decimal(10,2) DEFAULT NULL,
  `price_to` decimal(10,2) DEFAULT NULL,
  `total_rates` int unsigned NOT NULL DEFAULT '0',
  `full_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `travel_spots_province_id_foreign` (`province_id`),
  CONSTRAINT `travel_spots_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_spots`
--

LOCK TABLES `travel_spots` WRITE;
/*!40000 ALTER TABLE `travel_spots` DISABLE KEYS */;
INSERT INTO `travel_spots` VALUES (1,'Rừng Tràm Trà Sư - An Giang','Được xem là khu rừng tràm đẹp nhất và nổi tiếng nhất Việt Nam, rừng tràm Trà Sư mang đến cho du khách một cái nhìn tuyệt vời về vùng sinh thái đặc trưng của đồng bằng sông Cửu Long. Khi mùa nước nổi về, hãy đến đây để hòa mình vào khung cảnh thiên nhiên tuyệt đẹp.',1,'07:30:00','16:30:00',0.00,150000.00,170000.00,0,'Xã Văn Giáo, huyện Tịnh Biên, Tỉnh An Giang','2025-10-18 15:43:21','2025-10-18 15:43:21');
/*!40000 ALTER TABLE `travel_spots` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-20 15:37:01
