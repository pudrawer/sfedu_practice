-- MySQL dump 10.13  Distrib 5.7.39, for Linux (x86_64)
--
-- Host: localhost    Database: Cars
-- ------------------------------------------------------
-- Server version	5.7.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `car`
--

DROP TABLE IF EXISTS `car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` varchar(8) NOT NULL,
  `year` year(4) NOT NULL,
  `vrc` varchar(10) NOT NULL,
  `car_model_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vrc` (`vrc`),
  KEY `car_model_id` (`car_model_id`),
  KEY `car_user_id_fk` (`user_id`),
  CONSTRAINT `car_ibfk_1` FOREIGN KEY (`car_model_id`) REFERENCES `car_model` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `car_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car`
--

LOCK TABLES `car` WRITE;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` VALUES (1,'vb345v',2022,'54vfc',14,4),(2,'v543bv',2022,'cfv45',15,4);
/*!40000 ALTER TABLE `car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_brand`
--

DROP TABLE IF EXISTS `car_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `car_brand_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_brand`
--

LOCK TABLES `car_brand` WRITE;
/*!40000 ALTER TABLE `car_brand` DISABLE KEYS */;
INSERT INTO `car_brand` VALUES (1,'BNW',1),(3,'FORD',3),(5,'MERCEDES',3),(6,'TAYOTA',8),(7,'LADA',7),(8,'RANGE ROVER',11),(9,'SKODA',8),(10,'HONDA',8),(11,'NISSAN',1),(13,'HINDAAY',4);
/*!40000 ALTER TABLE `car_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_line`
--

DROP TABLE IF EXISTS `car_line`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `car_brand_id` (`car_brand_id`,`name`),
  CONSTRAINT `car_line_ibfk_1` FOREIGN KEY (`car_brand_id`) REFERENCES `car_brand` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_line`
--

LOCK TABLES `car_line` WRITE;
/*!40000 ALTER TABLE `car_line` DISABLE KEYS */;
INSERT INTO `car_line` VALUES (1,1,'M10'),(2,1,'M4'),(7,3,'FOCUS'),(5,3,'MUSTANG'),(6,3,'RAPTOR'),(8,9,'OCTAVIA'),(9,9,'RAPID');
/*!40000 ALTER TABLE `car_line` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_model`
--

DROP TABLE IF EXISTS `car_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_line_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` year(4) NOT NULL,
  `previous_line_model` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `car_line_id` (`car_line_id`,`name`),
  UNIQUE KEY `car_line_id_2` (`car_line_id`,`year`),
  KEY `previous_line_model` (`previous_line_model`),
  CONSTRAINT `car_model_ibfk_3` FOREIGN KEY (`car_line_id`) REFERENCES `car_line` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `car_model_ibfk_4` FOREIGN KEY (`previous_line_model`) REFERENCES `car_model` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_model`
--

LOCK TABLES `car_model` WRITE;
/*!40000 ALTER TABLE `car_model` DISABLE KEYS */;
INSERT INTO `car_model` VALUES (14,8,'DADAYAAA',2000,14),(15,2,'comp',2023,14),(16,1,'DADA',2020,15),(17,1,'DADANET',2007,16);
/*!40000 ALTER TABLE `car_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (11,'CANADA'),(7,'CHINA'),(3,'ENGLAND'),(4,'FINLAND'),(2,'GERMANY'),(8,'JAPAN'),(5,'POLAND'),(1,'RUSSIA'),(6,'SWEDEN'),(9,'USA');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factory`
--

DROP TABLE IF EXISTS `factory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `car_brand_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `car_brand_id` (`car_brand_id`,`name`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `factory_ibfk_3` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `factory_ibfk_4` FOREIGN KEY (`car_brand_id`) REFERENCES `car_brand` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factory`
--

LOCK TABLES `factory` WRITE;
/*!40000 ALTER TABLE `factory` DISABLE KEYS */;
INSERT INTO `factory` VALUES (2,'BMW SPORT',8,1),(3,'LADA NE SPORT',1,7),(4,'TAYOTA SPORT',9,6),(6,'FORD USA',9,3),(7,'SKODA CHO-TO S CHEM-TO',7,9),(8,'RANGE ROVER DA',7,8),(9,'MERCEDES PREMIUM',2,5),(10,'HONDA DRIFT',8,10);
/*!40000 ALTER TABLE `factory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factory_line_model`
--

DROP TABLE IF EXISTS `factory_line_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factory_line_model` (
  `factory_id` int(11) NOT NULL,
  `car_model_id` int(11) NOT NULL,
  PRIMARY KEY (`factory_id`,`car_model_id`),
  KEY `car_model_id` (`car_model_id`),
  CONSTRAINT `factory_line_model_ibfk_3` FOREIGN KEY (`factory_id`) REFERENCES `factory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `factory_line_model_ibfk_4` FOREIGN KEY (`car_model_id`) REFERENCES `car_model` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factory_line_model`
--

LOCK TABLES `factory_line_model` WRITE;
/*!40000 ALTER TABLE `factory_line_model` DISABLE KEYS */;
/*!40000 ALTER TABLE `factory_line_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (4,'abutenko@lachestry.com','$2y$10$j971.73x2Z60AXK5sWhNk.lINGKIIjEKG4KlDoVB/9HmBDFS7E66W','Sasha','Butenko','89885319146');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-12 20:23:17
