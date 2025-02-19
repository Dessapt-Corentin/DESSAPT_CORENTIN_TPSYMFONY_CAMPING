/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: symfony_camping_dc
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `accommodation`
--

DROP TABLE IF EXISTS `accommodation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accommodation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `label` varchar(50) NOT NULL,
  `location_number` varchar(50) NOT NULL,
  `size` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `capacity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `availability` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2D385412C54C8C93` (`type_id`),
  CONSTRAINT `FK_2D385412C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type_accommodation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accommodation`
--

LOCK TABLES `accommodation` WRITE;
/*!40000 ALTER TABLE `accommodation` DISABLE KEYS */;
INSERT INTO `accommodation` VALUES
(1,1,'Jolie chalet','A1',30,'Chalet 4 places',4,'chalet.jpg',1),
(2,2,'Mobil-home','A2',25,'Mobil-home 4 places',4,'mobil-home.jpg',1),
(6,3,'tttttQZDQZ','1Z',114,'41',114,'MCDC.drawio_67aef5de82d87.png',1);
/*!40000 ALTER TABLE `accommodation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accommodation_equipment`
--

DROP TABLE IF EXISTS `accommodation_equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accommodation_equipment` (
  `accommodation_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  PRIMARY KEY (`accommodation_id`,`equipment_id`),
  KEY `IDX_6A0325A58F3692CD` (`accommodation_id`),
  KEY `IDX_6A0325A5517FE9FE` (`equipment_id`),
  CONSTRAINT `FK_6A0325A5517FE9FE` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6A0325A58F3692CD` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accommodation_equipment`
--

LOCK TABLES `accommodation_equipment` WRITE;
/*!40000 ALTER TABLE `accommodation_equipment` DISABLE KEYS */;
INSERT INTO `accommodation_equipment` VALUES
(1,1),
(1,2),
(1,3),
(1,4),
(2,1),
(2,2),
(2,3),
(2,4),
(6,1),
(6,8),
(6,9),
(6,17),
(6,18),
(6,19);
/*!40000 ALTER TABLE `accommodation_equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20250210103044','2025-02-10 20:07:50',409),
('DoctrineMigrations\\Version20250210125142','2025-02-10 20:07:51',49),
('DoctrineMigrations\\Version20250210130734','2025-02-10 20:07:51',149);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment`
--

LOCK TABLES `equipment` WRITE;
/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
INSERT INTO `equipment` VALUES
(1,'Climatisation'),
(2,'Chauffage'),
(3,'Télévision'),
(4,'Wifi'),
(5,'Machine à laver'),
(6,'Lave-vaisselle'),
(7,'Micro-ondes'),
(8,'Four'),
(9,'Réfrigérateur'),
(10,'Congélateur'),
(11,'Cafetière'),
(12,'Bouilloire'),
(13,'Grille-pain'),
(14,'Barbecue'),
(15,'Salon de jardin'),
(16,'Terrasse'),
(17,'Balcon'),
(18,'Piscine'),
(19,'Spa'),
(20,'Sauna'),
(21,'Jacuzzi'),
(22,'Hammam'),
(23,'Salle de sport'),
(24,'Parking'),
(25,'Animaux acceptés'),
(26,'Non-fumeur'),
(27,'Accès handicapé'),
(28,'Draps fournis'),
(29,'Linge de toilette fourni');
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pricing`
--

DROP TABLE IF EXISTS `pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accommodation_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E5F1AC338F3692CD` (`accommodation_id`),
  KEY `IDX_E5F1AC334EC001D1` (`season_id`),
  CONSTRAINT `FK_E5F1AC334EC001D1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`),
  CONSTRAINT `FK_E5F1AC338F3692CD` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pricing`
--

LOCK TABLES `pricing` WRITE;
/*!40000 ALTER TABLE `pricing` DISABLE KEYS */;
INSERT INTO `pricing` VALUES
(1,1,1,50.00),
(2,1,2,75.00),
(3,1,3,0.00),
(4,2,1,50.00),
(5,2,2,75.00),
(6,2,3,0.00),
(7,6,1,1141.00),
(8,6,2,4114.00),
(9,6,3,0.00);
/*!40000 ALTER TABLE `pricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rental`
--

DROP TABLE IF EXISTS `rental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rental` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `adult_number` int(11) NOT NULL,
  `child_number` int(11) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1619C27DA76ED395` (`user_id`),
  KEY `IDX_1619C27D8F3692CD` (`accommodation_id`),
  CONSTRAINT `FK_1619C27D8F3692CD` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodation` (`id`),
  CONSTRAINT `FK_1619C27DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental`
--

LOCK TABLES `rental` WRITE;
/*!40000 ALTER TABLE `rental` DISABLE KEYS */;
INSERT INTO `rental` VALUES
(2,4,2,2,2,'2025-02-12 10:12:00','2025-02-19 10:12:00'),
(3,2,2,1,1,'2025-02-06 00:00:00','2025-02-07 00:00:00'),
(42,4,1,1,1,'2025-04-18 00:00:00','2025-04-20 00:00:00'),
(55,4,6,14,14,'2025-08-01 00:00:00','2025-08-03 00:00:00'),
(66,4,1,2,2,'2025-02-18 00:00:00','2025-02-22 00:00:00'),
(67,1,1,1,1,'2025-08-16 00:00:00','2025-08-17 00:00:00'),
(68,2,2,1,1,'2025-07-19 00:00:00','2025-04-23 00:00:00'),
(72,4,6,2,2,'2025-08-20 00:00:00','2025-08-21 00:00:00'),
(74,4,2,1,1,'2025-02-20 00:00:00','2025-02-24 00:00:00'),
(75,1,6,1,1,'2025-02-19 00:00:00','2025-02-20 00:00:00');
/*!40000 ALTER TABLE `rental` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `season`
--

DROP TABLE IF EXISTS `season`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `season` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `season`
--

LOCK TABLES `season` WRITE;
/*!40000 ALTER TABLE `season` DISABLE KEYS */;
INSERT INTO `season` VALUES
(1,'Basse saison','2025-04-01 00:00:00','2025-06-30 00:00:00'),
(2,'Haute saison','2025-07-01 00:00:00','2025-08-31 00:00:00'),
(3,'Fermeture annuelle','2024-10-01 00:00:00','2025-03-31 00:00:00');
/*!40000 ALTER TABLE `season` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_accommodation`
--

DROP TABLE IF EXISTS `type_accommodation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_accommodation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(75) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_accommodation`
--

LOCK TABLES `type_accommodation` WRITE;
/*!40000 ALTER TABLE `type_accommodation` DISABLE KEYS */;
INSERT INTO `type_accommodation` VALUES
(1,'Chalet'),
(2,'Mobil-home'),
(3,'Tente'),
(4,'Yourte'),
(5,'Tipi'),
(6,'Camping-car'),
(7,'Caravane'),
(8,'Bungalow'),
(9,'Emplacement vide');
/*!40000 ALTER TABLE `type_accommodation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'admin@admin.com','[\"ROLE_ADMIN\"]','$2y$13$knSoSw9Qh7qqGe.sdTbIEeEho960VABUwxCxQvE/1twcHlzBECPuK','Ad','min','0614256898','1 rue du jaune'),
(2,'user@user.com','[\"ROLE_USER\"]','$2y$13$tLSwgONAoRnysTS8UKKMf.PxI5JItvNhBpLBIGPUBMgirUicEPxTa','Uss','err','6142568989','1 rue du verte'),
(4,'oz@oz.com','[\"ROLE_USER\"]','$2y$13$diK3gBXvihYBSwyrbLy27etthPNt3QoJ3WPFIkn80BtDtq2ZmMiJW','qzdqzd','qzdqdqd','4574561','qzdqd');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-02-19  9:03:56
