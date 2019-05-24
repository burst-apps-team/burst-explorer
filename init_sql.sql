-- MySQL dump 10.17  Distrib 10.3.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: brs_master
-- BRS 2.3.0
-- ------------------------------------------------------
-- Server version	10.3.14-MariaDB-1:10.3.14+maria~bionic-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `block_forger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block_forger` (
  `db_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `generator_id` bigint(20) NOT NULL,
  `recipient_id` bigint(20) NOT NULL,
  `height` int(11) NOT NULL,
  PRIMARY KEY (`db_id`),
  UNIQUE KEY `height` (`height`),
  KEY `generator_id` (`generator_id`),
  KEY `recipient_id` (`recipient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=854863 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monitor`
--

DROP TABLE IF EXISTS `monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor` (
  `db_id` int(11) NOT NULL AUTO_INCREMENT,
  `send_mails` int(11) DEFAULT 0,
  `account_id` bigint(20) NOT NULL DEFAULT 0,
  `passphrase` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `welcome` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `balance` bigint(20) DEFAULT 0,
  PRIMARY KEY (`db_id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monitor_block`
--

DROP TABLE IF EXISTS `monitor_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor_block` (
  `last_height` int(11) NOT NULL,
  PRIMARY KEY (`last_height`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parseMultiOut`
--

DROP TABLE IF EXISTS `parseMultiOut`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parseMultiOut` (
  `recipient_id` bigint(20) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `db_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`recipient_id`,`transaction_id`,`amount`),
  KEY `transaction_id` (`transaction_id`),
  KEY `recipient_id` (`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parseMultiOutSame`
--

DROP TABLE IF EXISTS `parseMultiOutSame`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parseMultiOutSame` (
  `recipient_id` bigint(20) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `db_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`recipient_id`,`transaction_id`,`amount`),
  KEY `transaction_id` (`transaction_id`),
  KEY `recipient_id` (`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `peer_char`
--

DROP TABLE IF EXISTS `peer_char`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peer_char` (
  `address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `peer_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `brs_version` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`db_id`),
  UNIQUE KEY `address` (`address`)
) ENGINE=InnoDB AUTO_INCREMENT=2585 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;