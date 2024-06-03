-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: di_internet_technologies_project
-- ------------------------------------------------------
-- Server version	8.0.28

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
-- Table structure for table `task_assignments`
--

DROP TABLE IF EXISTS `task_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_id` (`task_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `task_assignments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  CONSTRAINT `task_assignments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_assignments`
--

LOCK TABLES `task_assignments` WRITE;
/*!40000 ALTER TABLE `task_assignments` DISABLE KEYS */;
INSERT INTO `task_assignments` VALUES (1,1,1),(14,2,1),(7,3,1),(8,4,1),(6,5,1),(9,6,1),(11,7,1),(19,9,1),(18,9,2);
/*!40000 ALTER TABLE `task_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_lists`
--

DROP TABLE IF EXISTS `task_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_lists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `task_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_lists`
--

LOCK TABLES `task_lists` WRITE;
/*!40000 ALTER TABLE `task_lists` DISABLE KEYS */;
INSERT INTO `task_lists` VALUES (1,1,'Δουλειές σπιτιου','Λίστα για τις δουλειές στο σπίτι.','2024-06-03 14:59:49'),(2,1,'Project','Λίστα με τα πράγματα για το project.','2024-06-03 15:01:32'),(3,1,'Εξεταστική','Να φτιάξω το πρόγραμμα για την εξεταστική.','2024-06-03 15:07:14'),(5,2,'Shared','lista me shared tasks','2024-06-03 15:53:10');
/*!40000 ALTER TABLE `task_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_list_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `status` enum('pending','in-progress','completed') DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_list_id` (`task_list_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`task_list_id`) REFERENCES `task_lists` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,1,'Σκούπισμα','Να σκουπίσω το σπίτι','pending','2024-06-05','2024-06-03 15:00:21','2024-06-03 15:00:21'),(2,1,'Τροφή Γατιά','να αγοράσω τροφή για τα γατιά','in-progress','2024-06-04','2024-06-03 15:00:56','2024-06-03 15:49:51'),(3,2,'Backend','Να τελειώσουμε το backend για την ιστοσελίδα','in-progress','2024-06-03','2024-06-03 15:04:02','2024-06-03 15:05:37'),(4,2,'Video','Να δημιουργήσουμε βίντεο για την λειτουργικότητα της σελίδας','in-progress','2024-06-05','2024-06-03 15:04:35','2024-06-03 15:05:43'),(5,2,'Frontend','Να τελειώσουμε με τη frontend πλευρά της σελίδας.','completed','2024-06-06','2024-06-03 15:05:09','2024-06-03 15:05:30'),(6,3,'Εργασία Τεχνολογίες Διαδικτύου','Να στείλουμε την τελική εργασία για τις τεχνολογίες διαδικτύου.','pending','2024-06-07','2024-06-03 15:08:17','2024-06-03 15:08:17'),(7,3,'Διάβασμα Πιθανότητες','Πρέπει να τελειώσω το διάβασμα και να περάσω το μάθημα.','completed','2024-06-03','2024-06-03 15:08:59','2024-06-03 15:09:09'),(9,5,'Shared task','Shared task me ton user voltmaister','in-progress','2024-06-04','2024-06-03 15:53:29','2024-06-03 15:54:44');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `simplepush_key` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token_expiry` int DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `user_role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `simplepush_key_2` (`simplepush_key`),
  KEY `simplepush_key` (`simplepush_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Nikos','Artinopoulos','Voltmaister','$2y$10$iXLtCiu/V5OwEVET0ZS8GODimX9cU4egU5rTtnxAJhCATHv906Y.K','artinopoulosore@gmail.com','HSSJtb','2024-06-03 14:58:43',NULL,NULL,'admin'),(2,'055e9a28','52d6bf0c','09ae8af923','$2y$10$O1bDYPVPtk6liV.Vk2zn8uR6Ak.lsu1s.c6WrIz86qI.mGGhq2QlO','b2ddc5c5f4@example.com',' ','2024-06-03 15:52:21',NULL,NULL,'user'),(3,'user2','user2','user2','$2y$10$dYOHzLLKtOJ1gLg0FhZkdeTAkF8zzP.iioB3Kh1KD8vreZc4lubW.','user2@gmail.com','user2','2024-06-03 15:58:22',NULL,NULL,'user');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'di_internet_technologies_project'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-03 19:23:57
