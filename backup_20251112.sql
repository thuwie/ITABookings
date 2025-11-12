-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: booking
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_court_images`
--

LOCK TABLES `food_court_images` WRITE;
/*!40000 ALTER TABLE `food_court_images` DISABLE KEYS */;
INSERT INTO `food_court_images` VALUES (1,1,'/uploads/food-court/bun-ca-long-xuyen/food-court_68f505755d32e4.10122862_download.jpg','download.jpg','2025-10-19 15:36:53','2025-10-19 15:36:53'),(2,1,'/uploads/food-court/bun-ca-long-xuyen/food-court_68f50575645250.17852601_download__4_.jpg','download (4).jpg','2025-10-19 15:36:53','2025-10-19 15:36:53'),(3,2,'/uploads/food-court/bun-ca-hieu-thuan-thanh-pho-long-xuyen/food-court_68f7a07cc9ad08.70165132_download__2_.jpg','download (2).jpg','2025-10-21 15:02:21','2025-10-21 15:02:21'),(4,2,'/uploads/food-court/bun-ca-hieu-thuan-thanh-pho-long-xuyen/food-court_68f7a07cd44f39.87258486_download.jpg','download.jpg','2025-10-21 15:02:21','2025-10-21 15:02:21'),(5,2,'/uploads/food-court/bun-ca-hieu-thuan-thanh-pho-long-xuyen/food-court_68f7a07cdc0326.50066432_download__1_.jpg','download (1).jpg','2025-10-21 15:02:21','2025-10-21 15:02:21');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_courts`
--

LOCK TABLES `food_courts` WRITE;
/*!40000 ALTER TABLE `food_courts` DISABLE KEYS */;
INSERT INTO `food_courts` VALUES (1,'Bún cá Long Xuyên','Là món ăn đầu tiên trong danh sách đặc sản An Giang muốn giới thiệu cho bạn món bún cá Long Xuyên. Đây là một món ăn khá quen thuộc và bình dị đối với dân địa phương, nó còn có tên gọi khác là bún nước lèo.\r\n\r\nVới hương vị nước lèo ngọt thanh, vị hơi nhạt, và đặc trưng là cá được ướp qua nghệ vàng ươm, giúp màu nước lèo vàng và có mùi thơm nghệ và khiến món bún cá thêm đậm đà hơn. Cá lóc hoặc cá kèo thường được chọn để nấu nước dùng, ngoài ra bạn cũng có thể ăn kèm với thịt heo, và không thể thiếu những món rau đặc trưng vùng sông nước như giá, bông điên điển, bắp chuối, rau răm,...','18/2A Lê Lợi, Mỹ Bình, Tp Long Xuyên',1,0,'09:30:00','22:02:00',0.00,0,15000.00,25000.00,'2025-10-19 15:36:13','2025-10-19 15:36:13'),(2,'Bún cá Hiếu Thuận (Thành phố Long Xuyên)','Khách du lịch đến với An Giang chắc chắn không thể bỏ qua món bún cá đặc sản. Bún cá ở đây nổi tiếng là do nước dùng đậm đà được nấu bằng ngải bún và nghệ tươi, thịt cá lóc chắc nịch, tươi ngon. Và một trong những quán bán bún cá trứ danh ở An Giang chính là bún cá Hiếu Thuận. Quán bún cá này đã duy trì ở đất An Giang đến 30 năm, trở thành “quán bún kỷ lục” trong lòng người địa phương.','18/2A Lê Lợi, P. Mỹ Bình, Thành phố Long Xuyên, An Giang, Việt Nam',1,0,'07:30:00','18:00:00',0.00,0,25000.00,35000.00,'2025-10-21 15:02:20','2025-10-21 15:02:20');
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
-- Table structure for table `province_ratings`
--

DROP TABLE IF EXISTS `province_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `province_ratings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `province_id` int unsigned NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `average_rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_rates` int unsigned NOT NULL DEFAULT '0',
  `price_from` decimal(12,2) DEFAULT NULL,
  `price_to` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `province_ratings`
--

LOCK TABLES `province_ratings` WRITE;
/*!40000 ALTER TABLE `province_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `province_ratings` ENABLE KEYS */;
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
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `routes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `from_location_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance_km` int unsigned DEFAULT NULL,
  `duration_min` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (1,'91','24','Đường cao tốc Bắc Nam phía Đông',1902,1740,'2025-11-12 05:03:00','2025-11-12 05:03:01'),(2,'91','96','Đường xuyên Á',199,287,'2025-11-12 07:22:38','2025-11-12 07:22:39'),(3,'91','92','Quốc lộ 91',91,192,'2025-11-12 07:25:44','2025-11-12 07:25:45'),(4,'91','04','cao tốc 01',2143,2100,'2025-11-12 07:29:32','2025-11-12 07:29:32'),(5,'91','48','cao tốc 01',1142,1126,'2025-11-12 07:31:35','2025-11-12 07:31:36'),(6,'91','66','quốc lộ 14',589,755,'2025-11-12 07:34:26','2025-11-12 07:34:26'),(7,'91','11','cao tốc 01',2261,2340,'2025-11-12 07:35:53','2025-11-12 07:35:53'),(8,'91','75','cao tốc Bắc - Nam phía Đông',318,395,'2025-11-12 07:37:49','2025-11-12 07:37:49'),(9,'91','82','quốc lộ 91',103,138,'2025-11-12 07:39:16','2025-11-12 07:39:16'),(10,'91','52','cao tốc Bắc - Nam phía Đông',878,915,'2025-11-12 07:42:06','2025-11-12 07:42:06'),(11,'91','01','tuyến đường 13',1564,1740,'2025-11-12 07:44:17','2025-11-12 07:44:17'),(12,'91','42','cao tốc 01',1293,1560,'2025-11-12 07:45:29','2025-11-12 07:45:29'),(13,'91','31','tuyến đường 13',1640,1800,'2025-11-12 07:46:57','2025-11-12 07:46:58'),(14,'91','79','cao tốc 01',229,288,'2025-11-12 07:49:06','2025-11-12 07:49:06'),(15,'91','46','xuyên Á',1020,1216,'2025-11-12 07:50:50','2025-11-12 07:50:50'),(16,'91','33','tuyến đường 13',1532,1680,'2025-11-12 07:51:52','2025-11-12 07:51:53'),(17,'91','56','cao tốc 01',625,587,'2025-11-12 07:53:19','2025-11-12 07:53:19'),(18,'91','12','cao tốc 05',1952,2040,'2025-11-12 07:54:32','2025-11-12 07:54:32'),(19,'91','68','quốc lộ 20',459,545,'2025-11-12 07:55:32','2025-11-12 07:55:33'),(20,'24','96','cao tốc Bắc - Nam phía Đông',1965,1800,'2025-11-12 07:57:46','2025-11-12 07:57:46'),(21,'24','92','cao tốc 01',1825,1620,'2025-11-12 07:59:16','2025-11-12 07:59:17'),(22,'24','04','quốc lộ 4A',242,292,'2025-11-12 08:00:26','2025-11-12 08:00:27'),(23,'24','48','cao tốc 01',769,680,'2025-11-12 08:01:34','2025-11-12 08:01:34'),(24,'24','66','cao tốc 01',1317,1289,'2025-11-12 08:02:39','2025-11-12 08:02:39'),(25,'24','11','quốc lộ 6',542,702,'2025-11-12 08:03:38','2025-11-12 08:03:39'),(26,'24','75','cao tốc 01',1630,1440,'2025-11-12 08:04:39','2025-11-12 08:04:39'),(27,'24','82',' quốc lộ AH11/NR7',1607,1680,'2025-11-12 08:06:31','2025-11-12 08:06:31'),(28,'24','52','cao tốc 01',1186,1149,'2025-11-12 08:07:30','2025-11-12 08:07:31'),(29,'24','01','quốc lộ 1A',42,66,'2025-11-12 08:09:33','2025-11-12 08:09:34'),(30,'24','42','cao tốc 01',374,321,'2025-11-12 08:10:42','2025-11-12 08:10:42'),(31,'24','31','cao tốc Hà Nội - Hải Phòng',132,116,'2025-11-12 08:11:55','2025-11-12 08:11:56'),(32,'24','79','cao tốc 01',1676,1500,'2025-11-12 08:13:04','2025-11-12 08:13:04'),(33,'24','46','cao tốc 01',688,584,'2025-11-12 08:13:53','2025-11-12 08:13:53'),(34,'24','33','cao tốc 16',76,78,'2025-11-12 08:14:49','2025-11-12 08:14:49'),(35,'24','56','cao tốc 01',1307,1247,'2025-11-12 08:16:04','2025-11-12 08:16:05'),(36,'24','12','cao tốc 05',387,404,'2025-11-12 08:17:12','2025-11-12 08:17:13'),(37,'24','68','quốc lộ 24',1523,1560,'2025-11-12 08:18:12','2025-11-12 08:18:13'),(38,'96','92','quốc lộ Quản Lộ - Phụng Hiệp',174,252,'2025-11-12 08:20:54','2025-11-12 08:20:55'),(39,'96','04','tuyến đường 212',1993,2160,'2025-11-12 08:22:19','2025-11-12 08:22:19'),(40,'96','48','quốc lộ 24',1349,1382,'2025-11-12 08:23:41','2025-11-12 08:23:41'),(41,'96','66','quốc lộ 14',650,823,'2025-11-12 08:34:20','2025-11-12 08:34:20'),(42,'96','11','cao tốc 01',2260,2340,'2025-11-12 08:35:28','2025-11-12 08:35:28'),(43,'96','75','cao tốc Bắc - Nam phía Đông',395,591,'2025-11-12 08:36:50','2025-11-12 08:36:51'),(44,'96','82','quốc lộ Quản Lộ - Phụng Hiệp',221,282,'2025-11-12 08:37:43','2025-11-12 08:37:44'),(45,'96','52','cao tốc Bắc Nam',943,964,'2025-11-12 08:38:58','2025-11-12 08:38:59'),(46,'96','01','quốc lộ AH11/NR7',1771,1860,'2025-11-12 08:40:32','2025-11-12 08:40:33'),(47,'96','42','cao tốc 01',1432,1560,'2025-11-12 08:41:36','2025-11-12 08:41:37'),(48,'96','31','tuyến đường 13',1835,1980,'2025-11-12 08:42:31','2025-11-12 08:42:32'),(49,'96','79','quốc lộ Quản Lộ - Phụng Hiệp',295,333,'2025-11-12 08:43:36','2025-11-12 08:43:37'),(50,'96','46','cao tốc Bắc - Nam',1291,1274,'2025-11-12 08:44:23','2025-11-12 08:44:24'),(51,'96','33','tuyến đường 13',1727,1920,'2025-11-12 08:45:18','2025-11-12 08:45:18'),(52,'96','56','cao tốc 01',676,645,'2025-11-12 08:46:27','2025-11-12 08:46:28'),(53,'96','12','cao tốc 05',2147,2280,'2025-11-12 08:47:26','2025-11-12 08:47:26'),(54,'96','68','quốc lộ 20',525,642,'2025-11-12 08:48:09','2025-11-12 08:48:09'),(55,'92','04','tuyến đường 13',1896,2100,'2025-11-12 08:51:17','2025-11-12 08:51:18'),(56,'92','48','cao tốc 01',1105,1080,'2025-11-12 08:52:22','2025-11-12 08:52:22'),(57,'92','66','quốc lộ 14',546,706,'2025-11-12 08:54:02','2025-11-12 08:54:02'),(58,'92','11','cao tốc AH13',2149,2220,'2025-11-12 08:56:18','2025-11-12 08:56:18'),(59,'92','75','quốc lộ N2',275,440,'2025-11-12 08:57:54','2025-11-12 08:57:54'),(60,'92','82','cao tốc Cao Lãnh - Lộ Tẻ',67,94,'2025-11-12 08:58:54','2025-11-12 08:58:55'),(61,'92','52','cao tốc Bắc - Nam',840,849,'2025-11-12 09:00:10','2025-11-12 09:00:10'),(62,'92','01','tuyến đường 13',1628,1800,'2025-11-12 09:01:27','2025-11-12 09:01:27'),(63,'92','42','cao tốc 01',1301,1423,'2025-11-12 09:02:44','2025-11-12 09:02:44'),(64,'92','31','quốc lộ AH11/NR7',1716,1740,'2025-11-12 09:04:10','2025-11-12 09:04:10'),(65,'92','79','cao tốc 02',188,293,'2025-11-12 09:05:24','2025-11-12 09:05:25'),(66,'92','46','cao tốc Bắc - Nam',1188,1168,'2025-11-12 09:06:27','2025-11-12 09:06:28'),(67,'92','33','cao tốc Bắc - Nam',1188,1168,'2025-11-12 09:07:30','2025-11-12 09:07:30'),(68,'92','56','cao tốc Bắc - Nam',572,537,'2025-11-12 09:09:18','2025-11-12 09:09:18'),(69,'92','12','cao tốc 05',2016,2100,'2025-11-12 09:10:07','2025-11-12 09:10:07'),(70,'92','68','quốc lộ 20',421,532,'2025-11-12 09:10:57','2025-11-12 09:10:57'),(71,'04','48','cao tốc 01',1010,961,'2025-11-12 09:21:13','2025-11-12 09:21:14'),(72,'04','66','cao tốc 01',1558,1560,'2025-11-12 09:22:00','2025-11-12 09:22:01'),(73,'04','11','quốc lộ 4A',722,885,'2025-11-12 09:23:37','2025-11-12 09:23:37'),(74,'04','75','quốc lộ AH11/NR7',1814,1920,'2025-11-12 09:25:01','2025-11-12 09:25:02'),(75,'04','82','quốc lộ AH11/NR7',1847,1920,'2025-11-12 09:25:51','2025-11-12 09:25:51'),(76,'04','52','cao tốc 01',1426,1440,'2025-11-12 09:26:36','2025-11-12 09:26:36'),(77,'04','01','Hồ Chí Minh',276,360,'2025-11-12 09:27:47','2025-11-12 09:27:47'),(78,'04','42','quốc lộ 3',616,625,'2025-11-12 09:28:55','2025-11-12 09:28:56'),(79,'04','31','quốc lộ 5B',374,408,'2025-11-12 09:30:48','2025-11-12 09:30:48'),(80,'04','79','cao tốc 01',1921,1800,'2025-11-12 09:31:57','2025-11-12 09:31:57'),(81,'04','46','quốc lộ 3',929,888,'2025-11-12 09:33:11','2025-11-12 09:33:12'),(82,'04','33','Hồ Chí Minh',318,368,'2025-11-12 09:34:09','2025-11-12 09:34:09'),(83,'04','56','cao tốc 01',1547,1500,'2025-11-12 09:35:48','2025-11-12 09:35:48'),(84,'04','12','cao tốc 05',612,685,'2025-11-12 09:36:54','2025-11-12 09:36:54'),(85,'04','68','cao tốc 01',1725,1680,'2025-11-12 09:38:08','2025-11-12 09:38:08'),(86,'48','66','quốc lộ 24',558,622,'2025-11-12 09:39:53','2025-11-12 09:39:53'),(87,'48','11','cao tốc 01',1062,1140,'2025-11-12 09:40:44','2025-11-12 09:40:45'),(88,'48','75','cao tốc Bắc - Nam',870,810,'2025-11-12 09:42:05','2025-11-12 09:42:05'),(89,'48','82','quốc lộ 14',1056,1200,'2025-11-12 09:43:23','2025-11-12 09:43:23'),(90,'48','82','quốc lộ 14',1056,1200,'2025-11-12 09:43:23','2025-11-12 09:43:23'),(91,'48','52','cao tốc 01',441,514,'2025-11-12 09:45:19','2025-11-12 09:45:20'),(92,'48','01','cao tốc 01',741,660,'2025-11-12 09:46:14','2025-11-12 09:46:14'),(93,'48','42','cao tốc 01',401,374,'2025-11-12 09:47:05','2025-11-12 09:47:05'),(94,'48','31','cao tốc 01',816,717,'2025-11-12 09:47:48','2025-11-12 09:47:48'),(95,'48','79','quốc lộ 14',893,1080,'2025-11-12 09:48:51','2025-11-12 09:48:51'),(96,'48','46','quốc lộ 1A',92,124,'2025-11-12 09:49:41','2025-11-12 09:49:41'),(97,'48','33','cao tốc 01',708,612,'2025-11-12 09:50:20','2025-11-12 09:50:21'),(98,'48','56','cao tốc 01',547,585,'2025-11-12 09:51:18','2025-11-12 09:51:19'),(99,'48','12','cao tốc 01',1116,1064,'2025-11-12 09:52:06','2025-11-12 09:52:06'),(100,'48','68','quốc lộ 24',763,905,'2025-11-12 09:53:04','2025-11-12 09:53:05'),(101,'66','11','quốc lộ 6',1797,2040,'2025-11-12 09:55:00','2025-11-12 09:55:01'),(102,'66','75','Hồ Chí Minh',1797,2040,'2025-11-12 09:56:48','2025-11-12 09:56:48'),(103,'66','82','đường tỉnh 741',489,632,'2025-11-12 09:58:29','2025-11-12 09:58:30'),(104,'66','52','Trường Sơn Đông',177,243,'2025-11-12 09:59:27','2025-11-12 09:59:28'),(105,'66','01','cao tốc Đà Nẵng - Quãng Ngãi',1290,1270,'2025-11-12 10:00:44','2025-11-12 10:00:45'),(106,'66','42','cao tốc Đà Nẵng - Quãng Ngãi',939,1007,'2025-11-12 10:02:01','2025-11-12 10:02:01'),(107,'66','31','cao tốc Đà Nẵng - Quãng Ngãi',1353,1354,'2025-11-12 10:02:56','2025-11-12 10:02:56'),(108,'66','79','đường tỉnh 741',367,515,'2025-11-12 10:04:04','2025-11-12 10:04:05'),(109,'66','46','quốc lộ 24',641,717,'2025-11-12 10:04:48','2025-11-12 10:04:48'),(110,'66','33','cao tốc 01',1258,1217,'2025-11-12 10:05:37','2025-11-12 10:05:38'),(111,'66','56','quốc lộ 36',173,220,'2025-11-12 10:06:44','2025-11-12 10:06:45'),(112,'66','12','cao tốc 05',1790,1920,'2025-11-12 10:07:41','2025-11-12 10:07:41'),(113,'66','68','quốc lộ 27',224,331,'2025-11-12 10:08:26','2025-11-12 10:08:27'),(114,'11','75','cao tốc 01',1923,1860,'2025-11-12 10:11:25','2025-11-12 10:11:26'),(115,'11','82','quốc lộ 6',1901,2100,'2025-11-12 10:12:21','2025-11-12 10:12:22'),(116,'11','52','cao tốc 01',1480,1620,'2025-11-12 10:13:05','2025-11-12 10:13:06'),(117,'11','01','quốc lộ 37',454,626,'2025-11-12 10:14:01','2025-11-12 10:14:01'),(119,'11','42','cao tốc Bắc - Nam',668,764,'2025-11-12 10:14:58','2025-11-12 10:14:58'),(120,'11','31','quốc lộ 6',571,688,'2025-11-12 10:23:05','2025-11-12 10:23:06'),(121,'11','79','quốc lộ AH11/NR7',1832,2100,'2025-11-12 10:24:14','2025-11-12 10:24:15'),(122,'11','46','cao tốc 01',981,1035,'2025-11-12 10:24:57','2025-11-12 10:24:57'),(123,'11','33','quốc lộ 279',489,706,'2025-11-12 10:25:40','2025-11-12 10:25:40'),(124,'11','56','cao tốc 01',1601,1680,'2025-11-12 10:26:41','2025-11-12 10:26:42'),(125,'11','12','quốc lộ 12',200,266,'2025-11-12 10:27:25','2025-11-12 10:27:25'),(126,'11','68','cao tốc 01',1778,1860,'2025-11-12 10:28:17','2025-11-12 10:28:17'),(127,'75','82','quốc lộ N2',223,305,'2025-11-12 10:29:48','2025-11-12 10:29:49'),(128,'75','52','Trường Sơn Đông',608,604,'2025-11-12 10:30:44','2025-11-12 10:30:44'),(129,'75','01','cao tốc 01',1603,1500,'2025-11-12 10:31:39','2025-11-12 10:31:39'),(130,'75','42','cao tốc Bắc - Nam',1264,1200,'2025-11-12 10:33:30','2025-11-12 10:33:31'),(131,'75','31','tuyến đường 13',1608,1560,'2025-11-12 10:34:18','2025-11-12 10:34:19'),(132,'75','79','cao tốc 29',101,154,'2025-11-12 10:35:44','2025-11-12 10:35:45'),(133,'75','46','cao tốc 01',955,934,'2025-11-12 10:36:38','2025-11-12 10:36:39'),(134,'75','33','cao tốc 01',1571,1440,'2025-11-12 10:37:19','2025-11-12 10:37:20'),(135,'75','56','tỉnh 763',1571,1440,'2025-11-12 10:38:07','2025-11-12 10:38:08'),(136,'75','12','tuyến đường 13',1908,2100,'2025-11-12 10:39:34','2025-11-12 10:39:34'),(137,'75','68','quốc lộ 20',148,224,'2025-11-12 10:40:41','2025-11-12 10:40:41'),(138,'82','52','quốc lộ 14',633,820,'2025-11-12 10:44:39','2025-11-12 10:44:40'),(139,'82','01','tuyến đường 13',1566,1740,'2025-11-12 10:46:06','2025-11-12 10:46:06'),(140,'82','42','cao tốc 01',1239,1357,'2025-11-12 10:46:57','2025-11-12 10:46:57'),(141,'82','31','quốc lộ AH11/NR7',1654,1680,'2025-11-12 10:47:52','2025-11-12 10:47:53'),(142,'82','79','quốc lộ N2',129,211,'2025-11-12 10:48:39','2025-11-12 10:48:40'),(143,'82','46','cao tốc Bắc - Nam',1141,1117,'2025-11-12 10:49:45','2025-11-12 10:49:45'),(144,'82','33','cao tốc 01',1758,1620,'2025-11-12 10:50:35','2025-11-12 10:50:35'),(145,'82','56','cao tốc 01',525,491,'2025-11-12 10:51:19','2025-11-12 10:51:20'),(146,'82','12','cao tốc 05',1954,2040,'2025-11-12 10:52:03','2025-11-12 10:52:03'),(147,'82','68','quốc lộ 20',375,475,'2025-11-12 10:52:58','2025-11-12 10:52:58'),(148,'52','01','cao tốc 01',1158,1147,'2025-11-12 10:54:38','2025-11-12 10:54:38'),(149,'52','42','quốc lộ 24',819,885,'2025-11-12 10:55:26','2025-11-12 10:55:27'),(150,'52','31','cao tốc 01',1233,1197,'2025-11-12 10:56:23','2025-11-12 10:56:23'),(151,'52','79','Hồ Chí Minh',512,660,'2025-11-12 10:57:30','2025-11-12 10:57:30'),(152,'52','46','quốc lộ 24',510,602,'2025-11-12 10:58:16','2025-11-12 10:58:16'),(153,'52','33','cao tốc 01',1125,1104,'2025-11-12 10:59:18','2025-11-12 10:59:18'),(154,'52','56','quốc lộ 26',289,355,'2025-11-12 11:00:03','2025-11-12 11:00:03'),(155,'52','12','cao tốc nội bài Lào Cai',1662,1800,'2025-11-12 11:00:45','2025-11-12 11:00:45'),(156,'52','68','quốc lộ 27',384,527,'2025-11-12 11:01:28','2025-11-12 11:01:28'),(157,'01','42','cao tốc Cầu Giẽ - Ninh Bình',355,373,'2025-11-12 11:03:09','2025-11-12 11:03:09'),(158,'01','31','vành đai 2',121,127,'2025-11-12 11:04:06','2025-11-12 11:04:06'),(159,'01','79','cao tốc 01',1656,1560,'2025-11-12 11:04:59','2025-11-12 11:05:00'),(160,'01','46','cao tốc 01',660,592,'2025-11-12 11:05:36','2025-11-12 11:05:36'),(161,'01','33','cao tốc Hà Nội - Ninh Bình',60,89,'2025-11-12 11:07:23','2025-11-12 11:07:23'),(162,'01','56','cao tốc 01',1285,1237,'2025-11-12 11:08:23','2025-11-12 11:08:23'),(163,'01','12','cao tốc Nội Bài -Lào Cai',383,422,'2025-11-12 11:09:50','2025-11-12 11:09:50'),(164,'01','68','cao tốc 01',1459,1440,'2025-11-12 11:10:44','2025-11-12 11:10:44'),(165,'42','31','quốc lộ 10',391,357,'2025-11-12 11:12:14','2025-11-12 11:12:15'),(166,'42','79','quốc lộ AH11/NR7',1158,1273,'2025-11-12 11:13:11','2025-11-12 11:13:12'),(167,'42','46','quốc lộ 1A',312,318,'2025-11-12 11:13:48','2025-11-12 11:13:48'),(168,'42','33','đường tỉnh 56',314,291,'2025-11-12 11:14:57','2025-11-12 11:14:57'),(169,'42','56','quốc lộ 24',1088,1168,'2025-11-12 11:15:41','2025-11-12 11:15:42'),(170,'42','12','cao tốc Nội Bài - Lào Cai',725,687,'2025-11-12 11:16:21','2025-11-12 11:16:21'),(171,'42','68','quốc lộ 24',1156,1299,'2025-11-12 11:17:13','2025-11-12 11:17:13'),(172,'31','79','cao tốc 01',1726,1560,'2025-11-12 11:19:11','2025-11-12 11:19:11'),(173,'31','46','cao tốc 01',735,640,'2025-11-12 11:19:50','2025-11-12 11:19:50'),(174,'31','33','cao tốc Nội Bài - Hải Phòng',112,98,'2025-11-12 11:20:34','2025-11-12 11:20:34'),(175,'31','56','quốc lộ 24',1502,1500,'2025-11-12 11:21:27','2025-11-12 11:21:27'),(176,'31','12','cao tốc Nội Bài - Lào Cai',505,506,'2025-11-12 11:22:15','2025-11-12 11:22:15'),(177,'31','68','quốc lộ 24',1571,1620,'2025-11-12 11:22:57','2025-11-12 11:22:58'),(178,'79','46','cao tốc 02',976,1140,'2025-11-12 11:24:47','2025-11-12 11:24:47'),(179,'79','33','cao tốc NR76',1612,1560,'2025-11-12 11:25:56','2025-11-12 11:25:56'),(180,'79','56','cao tốc Bắc Nam',390,351,'2025-11-12 11:26:45','2025-11-12 11:26:46'),(181,'79','12','cao tốc AH11/NR7',1877,1980,'2025-11-12 11:27:47','2025-11-12 11:27:48'),(182,'79','68','cao tốc Bắc - Nam',274,312,'2025-11-12 11:28:32','2025-11-12 11:28:32'),(183,'46','33','cao tốc 01',627,541,'2025-11-12 11:29:21','2025-11-12 11:29:21'),(184,'46','56','cao tốc Đà Nẵng - Quãng Ngãi',637,663,'2025-11-12 11:30:13','2025-11-12 11:30:13'),(185,'46','12','quốc lộ 6',1015,1127,'2025-11-12 11:31:07','2025-11-12 11:31:07'),(186,'46','68','quốc lộ 24',847,999,'2025-11-12 11:31:53','2025-11-12 11:31:53'),(187,'33','56','cao tốc 01',1253,1200,'2025-11-12 11:32:48','2025-11-12 11:32:48'),(188,'33','12','cao tốc Nội Bài - Lào Cai',447,474,'2025-11-12 11:34:36','2025-11-12 11:34:36'),(189,'33','68','cao tốc 01',1427,1427,'2025-11-12 11:35:33','2025-11-12 11:35:33'),(190,'56','12','cao tốc 01',1657,1560,'2025-11-12 11:36:13','2025-11-12 11:36:13'),(191,'56','68','quốc lộ 27C',204,268,'2025-11-12 11:37:02','2025-11-12 11:37:02'),(192,'12','68','cao tốc 01',1834,1860,'2025-11-12 11:37:50','2025-11-12 11:37:50');
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_imgs`
--

LOCK TABLES `travel_imgs` WRITE;
/*!40000 ALTER TABLE `travel_imgs` DISABLE KEYS */;
INSERT INTO `travel_imgs` VALUES (1,1,'/uploads/travel-spots/rung-tram-tra-su-an-giang/travel_spot_68f3b59976e973.44179453_download__2_.jpg','download (2).jpg','2025-10-18 15:43:21','2025-10-18 15:43:21'),(2,1,'/uploads/travel-spots/rung-tram-tra-su-an-giang/travel_spot_68f3b599874de2.89197200_download.jpg','download.jpg','2025-10-18 15:43:21','2025-10-18 15:43:21'),(3,1,'/uploads/travel-spots/rung-tram-tra-su-an-giang/travel_spot_68f3b599960165.44575558_download__1_.jpg','download (1).jpg','2025-10-18 15:43:21','2025-10-18 15:43:21'),(4,2,'/uploads/travel-spots/thot-not-trai-tim-an-giang/travel_spot_68f79e76305a02.01440812_download__2_.jpg','download (2).jpg','2025-10-21 14:53:42','2025-10-21 14:53:42'),(5,2,'/uploads/travel-spots/thot-not-trai-tim-an-giang/travel_spot_68f79e763fd5c8.35465781_download.jpg','download.jpg','2025-10-21 14:53:42','2025-10-21 14:53:42'),(6,2,'/uploads/travel-spots/thot-not-trai-tim-an-giang/travel_spot_68f79e7648b9c1.68096892_download__3_.jpg','download (3).jpg','2025-10-21 14:53:42','2025-10-21 14:53:42'),(7,3,'/uploads/travel-spots/bun-ca-hieu-thuan-thanh-pho-long-xuyen-an-giang/travel_spot_68f79ff73eed88.38077967_download__2_.jpg','download (2).jpg','2025-10-21 15:00:07','2025-10-21 15:00:07'),(8,3,'/uploads/travel-spots/bun-ca-hieu-thuan-thanh-pho-long-xuyen-an-giang/travel_spot_68f79ff74768d3.55209833_download.jpg','download.jpg','2025-10-21 15:00:07','2025-10-21 15:00:07'),(9,3,'/uploads/travel-spots/bun-ca-hieu-thuan-thanh-pho-long-xuyen-an-giang/travel_spot_68f79ff74fa312.96216901_download__1_.jpg','download (1).jpg','2025-10-21 15:00:07','2025-10-21 15:00:07');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_spots`
--

LOCK TABLES `travel_spots` WRITE;
/*!40000 ALTER TABLE `travel_spots` DISABLE KEYS */;
INSERT INTO `travel_spots` VALUES (1,'Rừng Tràm Trà Sư - An Giang','Được xem là khu rừng tràm đẹp nhất và nổi tiếng nhất Việt Nam, rừng tràm Trà Sư mang đến cho du khách một cái nhìn tuyệt vời về vùng sinh thái đặc trưng của đồng bằng sông Cửu Long. Khi mùa nước nổi về, hãy đến đây để hòa mình vào khung cảnh thiên nhiên tuyệt đẹp.',1,'07:30:00','16:30:00',0.00,150000.00,170000.00,0,'Xã Văn Giáo, huyện Tịnh Biên, Tỉnh An Giang','2025-10-18 15:43:21','2025-10-18 15:43:21'),(2,'Thốt Nốt Trái Tim - An Giang','Điểm nhấn độc đáo và không thể bỏ qua trên hành trình khám phá An Giang chính là cây thốt nốt trái tim. Đây là cụm cây thốt nốt mà phần thân và tán lá tạo thành hình trái tim tự nhiên vô cùng ấn tượng. Tọa lạc trên đoạn đường từ Hồ Tà Pạ đến Hồ Ô Thum, cây thốt nốt trái tim nổi bật giữa bức tranh thiên nhiên xanh mát với bầu trời trong xanh, đồng lúa mướt mát và hồ nước lung linh.',1,'07:30:00','17:00:00',0.00,120000.00,150000.00,0,' Xã An Tức, huyện Tri Tôn, Tỉnh An Giang','2025-10-21 14:53:42','2025-10-21 14:53:42'),(3,'Bún cá Hiếu Thuận (Thành phố Long Xuyên) - An Giang','Khách du lịch đến với An Giang chắc chắn không thể bỏ qua món bún cá đặc sản. Bún cá ở đây nổi tiếng là do nước dùng đậm đà được nấu bằng ngải bún và nghệ tươi, thịt cá lóc chắc nịch, tươi ngon. Và một trong những quán bán bún cá trứ danh ở An Giang chính là bún cá Hiếu Thuận. Quán bún cá này đã duy trì ở đất An Giang đến 30 năm, trở thành “quán bún kỷ lục” trong lòng người địa phương.',1,'07:30:00','18:00:00',0.00,30000.00,40000.00,0,'18/2A Lê Lợi, P. Mỹ Bình, Thành phố Long Xuyên, An Giang, Việt Nam, Tỉnh An Giang','2025-10-21 15:00:07','2025-10-21 15:00:07');
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

-- Dump completed on 2025-11-12 18:40:54
