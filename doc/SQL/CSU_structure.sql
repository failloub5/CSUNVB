DROP DATABASE IF EXISTS `csunvb_csu`;
CREATE DATABASE  IF NOT EXISTS `csunvb_csu` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `csunvb_csu`;
-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: csunvb_csu
-- ------------------------------------------------------
-- Server version	5.7.26

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
-- Table structure for table `bases`
--

DROP TABLE IF EXISTS `bases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `batches`
--

DROP TABLE IF EXISTS `batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL DEFAULT 'new',
  `drug_id` int(11) NOT NULL,
  `base_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `number_UNIQUE` (`number`),
  KEY `fk_batches_drugs_idx` (`drug_id`),
  KEY `fk_batches_bases1_idx` (`base_id`),
  CONSTRAINT `fk_batches_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_batches_drugs` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crews`
--

DROP TABLE IF EXISTS `crews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boss` tinyint(4) NOT NULL DEFAULT '0',
  `day` tinyint(4) NOT NULL DEFAULT '1',
  `guardsheet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_crews_guardsheets1_idx` (`guardsheet_id`),
  KEY `fk_crews_users1_idx` (`user_id`),
  CONSTRAINT `fk_crews_guardsheets1` FOREIGN KEY (`guardsheet_id`) REFERENCES `guardsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_crews_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guard_use_nova`
--

DROP TABLE IF EXISTS `guard_use_nova`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guard_use_nova` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nova_id` int(11) NOT NULL,
  `guardsheet_id` int(11) NOT NULL,
  `day` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_novas_has_guardsheets_guardsheets1_idx` (`guardsheet_id`),
  KEY `fk_novas_has_guardsheets_novas1_idx` (`nova_id`),
  CONSTRAINT `fk_novas_has_guardsheets_guardsheets1` FOREIGN KEY (`guardsheet_id`) REFERENCES `guardsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_novas_has_guardsheets_novas1` FOREIGN KEY (`nova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guardcontents`
--

DROP TABLE IF EXISTS `guardcontents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guardcontents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(45) DEFAULT NULL,
  `guard_line_id` int(11) NOT NULL,
  `guardsheet_id` int(11) NOT NULL,
  `day_check_user_id` int(11) DEFAULT NULL,
  `night_check_user_id` int(11) DEFAULT NULL,
  `carry_on` int(11) NOT NULL DEFAULT '0' COMMENT 'If true, the comment must be transferred on to the next guardsheet when it’s opened',
  PRIMARY KEY (`id`),
  KEY `fk_guard_items_guard_lines1_idx` (`guard_line_id`),
  KEY `fk_guard_items_guardsheets1_idx` (`guardsheet_id`),
  KEY `fk_guard_items_users1_idx` (`day_check_user_id`),
  KEY `fk_guard_items_users2_idx` (`night_check_user_id`),
  CONSTRAINT `fk_guard_items_guard_lines1` FOREIGN KEY (`guard_line_id`) REFERENCES `guardlines` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_guard_items_guardsheets1` FOREIGN KEY (`guardsheet_id`) REFERENCES `guardsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_guard_items_users1` FOREIGN KEY (`day_check_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_guard_items_users2` FOREIGN KEY (`night_check_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guardlines`
--

DROP TABLE IF EXISTS `guardlines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guardlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(45) NOT NULL,
  `guard_sections_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_guard_lines_guard_sections1_idx` (`guard_sections_id`),
  CONSTRAINT `fk_guard_lines_guard_sections1` FOREIGN KEY (`guard_sections_id`) REFERENCES `guardsections` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guardsections`
--

DROP TABLE IF EXISTS `guardsections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guardsections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guardsheets`
--

DROP TABLE IF EXISTS `guardsheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guardsheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `state` varchar(45) NOT NULL,
  `base_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`base_id`,`date`),
  KEY `fk_guardsheets_bases1_idx` (`base_id`),
  CONSTRAINT `fk_guardsheets_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `novachecks`
--

DROP TABLE IF EXISTS `novachecks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `novachecks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) DEFAULT NULL,
  `drug_id` int(11) NOT NULL,
  `nova_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stupsheet_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_novachecks_drugs1_idx` (`drug_id`),
  KEY `fk_novachecks_novas1_idx` (`nova_id`),
  KEY `fk_novachecks_users1_idx` (`user_id`),
  KEY `fk_novachecks_stupsheets1_idx` (`stupsheet_id`),
  CONSTRAINT `fk_novachecks_drugs1` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_novachecks_novas1` FOREIGN KEY (`nova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_novachecks_stupsheets1` FOREIGN KEY (`stupsheet_id`) REFERENCES `stupsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_novachecks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=691 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `novas`
--

DROP TABLE IF EXISTS `novas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `novas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `number_UNIQUE` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pharmachecks`
--

DROP TABLE IF EXISTS `pharmachecks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pharmachecks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) DEFAULT NULL,
  `batch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stupsheet_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pharmachecks_batches1_idx` (`batch_id`),
  KEY `fk_pharmachecks_users1_idx` (`user_id`),
  KEY `fk_pharmachecks_stupsheets1_idx` (`stupsheet_id`),
  CONSTRAINT `fk_pharmachecks_batches1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pharmachecks_stupsheets1` FOREIGN KEY (`stupsheet_id`) REFERENCES `stupsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pharmachecks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5159 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restocks`
--

DROP TABLE IF EXISTS `restocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `quantity` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `nova_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restocks_batches1_idx` (`batch_id`),
  KEY `fk_restocks_novas1_idx` (`nova_id`),
  KEY `fk_restocks_users1_idx` (`user_id`),
  CONSTRAINT `fk_restocks_batches1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restocks_novas1` FOREIGN KEY (`nova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restocks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stupsheet_use_batch`
--

DROP TABLE IF EXISTS `stupsheet_use_batch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stupsheet_use_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stupsheet_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_use` (`stupsheet_id`,`batch_id`),
  KEY `fk_stupsheet_use_batch_stupsheets1_idx` (`stupsheet_id`),
  KEY `fk_stupsheet_use_batch_batches1_idx` (`batch_id`),
  CONSTRAINT `fk_stupsheet_use_batch_batches1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_stupsheet_use_batch_stupsheets1` FOREIGN KEY (`stupsheet_id`) REFERENCES `stupsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stupsheet_use_nova`
--

DROP TABLE IF EXISTS `stupsheet_use_nova`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stupsheet_use_nova` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stupsheet_id` int(11) NOT NULL,
  `nova_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_use` (`stupsheet_id`,`nova_id`),
  KEY `fk_stupsheet_use_nova_stupsheets1_idx` (`stupsheet_id`),
  KEY `fk_stupsheet_use_nova_novas1_idx` (`nova_id`),
  CONSTRAINT `fk_stupsheet_use_nova_novas1` FOREIGN KEY (`nova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_stupsheet_use_nova_stupsheets1` FOREIGN KEY (`stupsheet_id`) REFERENCES `stupsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stupsheets`
--

DROP TABLE IF EXISTS `stupsheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stupsheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `week` int(11) NOT NULL,
  `state` varchar(45) NOT NULL,
  `base_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `STUPSHEETUNIQ` (`week`,`base_id`),
  KEY `fk_stupsheets_bases1_idx` (`base_id`),
  CONSTRAINT `fk_stupsheets_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stupsignatures`
--

DROP TABLE IF EXISTS `stupsignatures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stupsignatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `day` int(11) NOT NULL,
  `stupsheet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stupsignatures_stupsheets1_idx` (`stupsheet_id`),
  KEY `fk_stupsignatures_users1_idx` (`user_id`),
  CONSTRAINT `fk_stupsignatures_stupsheets1` FOREIGN KEY (`stupsheet_id`) REFERENCES `stupsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_stupsignatures_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `todos`
--

DROP TABLE IF EXISTS `todos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `todothing_id` int(11) NOT NULL,
  `todosheet_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `done_at` datetime DEFAULT NULL,
  `day_of_week` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_todoitems_todotexts1_idx` (`todothing_id`),
  KEY `fk_todoitems_todosheets1_idx` (`todosheet_id`),
  KEY `fk_todoitems_users1_idx` (`user_id`),
  CONSTRAINT `fk_todoitems_todosheets1` FOREIGN KEY (`todosheet_id`) REFERENCES `todosheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_todoitems_todotexts1` FOREIGN KEY (`todothing_id`) REFERENCES `todothings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_todoitems_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `todosheets`
--

DROP TABLE IF EXISTS `todosheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todosheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `week` int(11) NOT NULL,
  `state` varchar(45) NOT NULL,
  `base_id` int(11) NOT NULL,
  `template_name` varchar(45) DEFAULT NULL COMMENT 'The name under which the stupsheet may be identified as a templatre to be create new sheets. Copies will NOT carry that name',
  PRIMARY KEY (`id`),
  UNIQUE KEY `model_name_UNIQUE` (`template_name`),
  KEY `fk_todosheets_bases1_idx` (`base_id`),
  CONSTRAINT `fk_todosheets_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `todothings`
--

DROP TABLE IF EXISTS `todothings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todothings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `daything` tinyint(4) NOT NULL DEFAULT '1',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1: done/not done\n2: has a value',
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `text_UNIQUE` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `initials` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(4) NOT NULL,
  `firstconnect` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initials_UNIQUE` (`initials`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-01  7:30:56
