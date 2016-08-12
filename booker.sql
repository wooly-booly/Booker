CREATE DATABASE  IF NOT EXISTS `booker` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `booker`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: booker
-- ------------------------------------------------------
-- Server version	5.1.36-community-log

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
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` bigint(20) NOT NULL,
  `finish_time` bigint(20) NOT NULL,
  `description` text,
  `boardroom` tinyint(4) NOT NULL,
  `recurrence` enum('once','weekly','bi-weekly','monthly') NOT NULL DEFAULT 'once',
  `to_delete_recurrence` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (61,1413176400,1413180000,'desc',1,'once',NULL),(51,1412895120,1412895180,'desc',1,'once',NULL),(48,1412881800,1412882100,'desc',1,'once',NULL),(47,1412881560,1412881800,'desc',1,'once',NULL),(62,1412930700,1412931600,'desc',1,'once',NULL),(60,1413003600,1413010800,'desc',1,'once',NULL),(58,1412896020,1412896080,'desc',1,'once',NULL),(66,1413010800,1413014400,'test',1,'once',NULL),(67,1413014400,1413018000,'test1',1,'weekly',1412941571),(68,1413619200,1413622800,'test2',1,'once',0),(69,1414224000,1414227600,'test1',1,'once',NULL),(70,1414832400,1414836000,'test1',1,'weekly',1412941571),(85,1414389600,1414400400,'test',1,'bi-weekly',1412949513),(72,1413694800,1413698400,'tt1',1,'weekly',1412942608),(73,1414303200,1414306800,'tt1',1,'weekly',1412942608),(74,1413262800,1413266400,'test 1',1,'weekly',1412945751),(75,1413867600,1413871200,'test 1',1,'weekly',1412945751),(76,1414476000,1414479600,'test 1',1,'weekly',1412945751),(82,1417327200,1417334400,'test!',1,'monthly',1412948607),(81,1414648800,1414656000,'test!',1,'monthly',1412948607),(80,1414735200,1414738800,'test',1,'once',NULL),(83,1413003600,1413007200,'test!!',2,'bi-weekly',1412948687),(84,1414213200,1414216800,'test!',2,'bi-weekly',1412948687),(86,1415599200,1415610000,'test',1,'bi-weekly',1412949513),(87,1416808800,1416819600,'test',1,'bi-weekly',1412949513),(88,1413007200,1413010800,'test',3,'once',NULL);
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_to_appointment`
--

DROP TABLE IF EXISTS `employee_to_appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_to_appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appointment_id_UNIQUE` (`appointment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_to_appointment`
--

LOCK TABLES `employee_to_appointment` WRITE;
/*!40000 ALTER TABLE `employee_to_appointment` DISABLE KEYS */;
INSERT INTO `employee_to_appointment` VALUES (85,3,85),(70,6,70),(69,5,69),(68,5,68),(61,8,61),(51,8,51),(48,8,48),(47,8,47),(67,6,67),(72,5,72),(66,3,66),(62,8,62),(60,8,60),(58,8,58),(73,5,73),(74,3,74),(75,3,75),(76,3,76),(82,3,82),(81,3,81),(80,3,80),(83,4,83),(84,4,84),(86,3,86),(87,3,87),(88,3,88);
/*!40000 ALTER TABLE `employee_to_appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(300) NOT NULL,
  `status` enum('works','retired') DEFAULT 'works',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (2,'Bob Marley','sdafsd@sfsd.com','retired'),(3,'Jeff Loomis','ssdafsd@sfsd.com','works'),(4,'Paul Gilbert','boom@boom.com','works'),(5,'Joe Satriani','sdsdf@sdffsd.com','works'),(6,'Joe Pass','werwerwer@wew.com','works'),(10,'Marty Friedman','marty@friean.com','works'),(8,'Rusty Cooley','rusty@cooley.com','works'),(9,'Musi Lucy','musi@lucy.com','retired'),(11,'Kirk Hammet','kirk@ham.com','works'),(12,'Bucket Head','bucket@head.com','works');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-10 17:22:55
