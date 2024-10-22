-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: permission_system
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `mor_acl`
--

DROP TABLE IF EXISTS `mor_acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mor_acl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `mor_acl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `mor_user` (`id`),
  CONSTRAINT `mor_acl_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `mor_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mor_acl`
--

LOCK TABLES `mor_acl` WRITE;
/*!40000 ALTER TABLE `mor_acl` DISABLE KEYS */;
INSERT INTO `mor_acl` VALUES (2,3,2),(3,3,1);
/*!40000 ALTER TABLE `mor_acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mor_permission`
--

DROP TABLE IF EXISTS `mor_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mor_permission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mor_permission`
--

LOCK TABLES `mor_permission` WRITE;
/*!40000 ALTER TABLE `mor_permission` DISABLE KEYS */;
INSERT INTO `mor_permission` VALUES (6,'xem','xem được nhiều thông tin'),(7,'chỉnh sửa \'','cập nhật thông tin '),(8,'xóa','được xóa hệ thống');
/*!40000 ALTER TABLE `mor_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mor_role`
--

DROP TABLE IF EXISTS `mor_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mor_role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mor_role`
--

LOCK TABLES `mor_role` WRITE;
/*!40000 ALTER TABLE `mor_role` DISABLE KEYS */;
INSERT INTO `mor_role` VALUES (1,'admin','là admin'),(2,'edit users','là edit users');
/*!40000 ALTER TABLE `mor_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mor_role_permission`
--

DROP TABLE IF EXISTS `mor_role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mor_role_permission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int DEFAULT NULL,
  `permission_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `mor_role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `mor_role` (`id`),
  CONSTRAINT `mor_role_permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `mor_permission` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mor_role_permission`
--

LOCK TABLES `mor_role_permission` WRITE;
/*!40000 ALTER TABLE `mor_role_permission` DISABLE KEYS */;
INSERT INTO `mor_role_permission` VALUES (1,2,7);
/*!40000 ALTER TABLE `mor_role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mor_user`
--

DROP TABLE IF EXISTS `mor_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mor_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mor_user`
--

LOCK TABLES `mor_user` WRITE;
/*!40000 ALTER TABLE `mor_user` DISABLE KEYS */;
INSERT INTO `mor_user` VALUES (3,'Liem','liem moi su'),(4,'Hung','ngu su '),(5,'Tien','nguoi trong gia toc');
/*!40000 ALTER TABLE `mor_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-22 18:17:51
