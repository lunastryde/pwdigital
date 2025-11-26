-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pdao_db
CREATE DATABASE IF NOT EXISTS `pdao_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pdao_db`;

-- Dumping structure for table pdao_db.accounts_master
CREATE TABLE IF NOT EXISTS `accounts_master` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(100) NOT NULL,
  `identifier` tinyint NOT NULL DEFAULT (0),
  `role` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.accounts_profile
CREATE TABLE IF NOT EXISTS `accounts_profile` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int DEFAULT NULL,
  `pwd_number` varchar(20) DEFAULT NULL,
  `fname` varchar(50) CHARACTER SET utf8mb4 DEFAULT '',
  `mname` varchar(50) CHARACTER SET utf8mb4 DEFAULT '',
  `lname` varchar(50) CHARACTER SET utf8mb4 DEFAULT '',
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 DEFAULT '',
  `contact_no` varchar(15) CHARACTER SET utf8mb4 DEFAULT '',
  `sex` varchar(10) CHARACTER SET utf8mb4 DEFAULT '',
  `age` int DEFAULT NULL,
  `civil_status` varchar(20) CHARACTER SET utf8mb4 DEFAULT '',
  `disability_type` varchar(45) CHARACTER SET utf8mb4 DEFAULT '',
  `birthdate` date DEFAULT NULL,
  `house_no` varchar(20) DEFAULT NULL,
  `street` varchar(20) DEFAULT NULL,
  `barangay` varchar(20) DEFAULT NULL,
  `municipality` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_profileTOmaster_id` (`account_id`),
  CONSTRAINT `FK_profileTOmaster_id` FOREIGN KEY (`account_id`) REFERENCES `accounts_master` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.announcements
CREATE TABLE IF NOT EXISTS `announcements` (
  `announcement_id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `image_path` int DEFAULT NULL,
  `priority_level` int DEFAULT NULL,
  `is_published` int DEFAULT NULL,
  `published_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`announcement_id`) USING BTREE,
  KEY `FK_announceTOmaster_accountid` (`account_id`),
  CONSTRAINT `FK_announceTOmaster_accountid` FOREIGN KEY (`account_id`) REFERENCES `accounts_master` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.email_otps
CREATE TABLE IF NOT EXISTS `email_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `expires_at` datetime NOT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `applicant_id` int NOT NULL,
  `psa` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `id_picture` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `cert_of_disability` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `med_cert` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `endorsement_letter` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `file_metadata` json DEFAULT NULL,
  `status` enum('incomplete','pending','approved','rejected') NOT NULL DEFAULT 'incomplete',
  `admin_notes` text,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` int unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_filesTOpersonal_applicantid` (`applicant_id`),
  KEY `files_status_index (status)` (`status`),
  CONSTRAINT `FK_filesTOpersonal_applicantid` FOREIGN KEY (`applicant_id`) REFERENCES `form_personal` (`applicant_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_guardian
CREATE TABLE IF NOT EXISTS `form_guardian` (
  `applicant_id` int DEFAULT NULL,
  `mother_fname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `mother_mname` varchar(50) DEFAULT NULL,
  `mother_lname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `mother_contact` varchar(50) DEFAULT NULL,
  `father_fname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `father_mname` varchar(50) DEFAULT NULL,
  `father_lname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `father_contact` varchar(50) DEFAULT NULL,
  `spouse_guardian_fname` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `spouse_guardian_mname` varchar(50) DEFAULT NULL,
  `spouse_guardian_lname` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `spouse_guardian_contact` varchar(40) CHARACTER SET utf8mb4 NOT NULL,
  `physician_name` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  KEY `FK_guardianTOpersonal_applicantid` (`applicant_id`),
  CONSTRAINT `FK_guardianTOpersonal_applicantid` FOREIGN KEY (`applicant_id`) REFERENCES `form_personal` (`applicant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_occupation
CREATE TABLE IF NOT EXISTS `form_occupation` (
  `applicant_id` int DEFAULT NULL,
  `occupation` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `employment_status` varchar(20) DEFAULT NULL,
  `employment_category` varchar(20) DEFAULT NULL,
  `employment_type` varchar(50) DEFAULT NULL,
  `four_pmem` varchar(5) DEFAULT NULL,
  KEY `FK_occupationTOpersonal_applicantid` (`applicant_id`),
  CONSTRAINT `FK_occupationTOpersonal_applicantid` FOREIGN KEY (`applicant_id`) REFERENCES `form_personal` (`applicant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_oi
CREATE TABLE IF NOT EXISTS `form_oi` (
  `applicant_id` int DEFAULT NULL,
  `oi_affiliated` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `oi_contactperson` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `oi_house_no` varchar(20) DEFAULT NULL,
  `oi_province` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `oi_street` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `oi_brgy` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `oi_municipality` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `oi_telno` varchar(40) CHARACTER SET utf8mb4 DEFAULT NULL,
  KEY `FK_applicantTOpersonal_applicantid` (`applicant_id`),
  CONSTRAINT `FK_applicantTOpersonal_applicantid` FOREIGN KEY (`applicant_id`) REFERENCES `form_personal` (`applicant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_personal
CREATE TABLE IF NOT EXISTS `form_personal` (
  `applicant_id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `applicant_type` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
  `pwd_number` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `fname` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `mname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `lname` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `suffix` varchar(10) CHARACTER SET utf8mb4 DEFAULT NULL,
  `birthdate` date NOT NULL,
  `age` int DEFAULT NULL,
  `sex` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `blood_type` varchar(5) CHARACTER SET utf8mb4 NOT NULL,
  `civil_status` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
  `disability_type` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `disability_cause` varchar(45) CHARACTER SET utf8mb4 DEFAULT NULL,
  `disability_cause_other` varchar(45) CHARACTER SET utf8mb4 DEFAULT NULL,
  `house_no` varchar(20) CHARACTER SET utf8mb4 DEFAULT '',
  `street` varchar(20) CHARACTER SET utf8mb4 NOT NULL DEFAULT '0',
  `barangay` varchar(20) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `municipality` varchar(20) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `province` varchar(20) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `landline_no` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `contact_no` varchar(40) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `educ_attainment` varchar(45) NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `submitted_at` timestamp NOT NULL,
  `reviewed_by` int DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `date_issued` timestamp NULL DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`applicant_id`),
  KEY `Fk_personalTOmaster_id` (`account_id`),
  CONSTRAINT `Fk_personalTOmaster_id` FOREIGN KEY (`account_id`) REFERENCES `accounts_master` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_refnos
CREATE TABLE IF NOT EXISTS `form_refnos` (
  `applicant_id` int DEFAULT NULL,
  `refno_sss` varchar(30) DEFAULT NULL,
  `refno_gsis` varchar(30) DEFAULT NULL,
  `refno_pagibig` varchar(30) DEFAULT NULL,
  `refno_philhealth` varchar(30) DEFAULT NULL,
  `refno_others` varchar(30) DEFAULT NULL,
  KEY `FK_refnosTOpersonal_applicantid` (`applicant_id`),
  CONSTRAINT `FK_refnosTOpersonal_applicantid` FOREIGN KEY (`applicant_id`) REFERENCES `form_personal` (`applicant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_requests
CREATE TABLE IF NOT EXISTS `form_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `applicant_id` int NOT NULL,
  `request_type` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Pending',
  `reviewed_by` varchar(50) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NOT NULL,
  `remarks` text CHARACTER SET utf8mb4,
  PRIMARY KEY (`request_id`),
  KEY `FK_applicantTOpersonal_applicantid` (`applicant_id`) USING BTREE,
  CONSTRAINT `FK_requestsTOpersonal_applicantid` FOREIGN KEY (`applicant_id`) REFERENCES `form_personal` (`applicant_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_request_booklet
CREATE TABLE IF NOT EXISTS `form_request_booklet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `booklet_type` enum('grocery','medicine') NOT NULL,
  `reason_for_request` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_bookletTOrequests_requestid` (`request_id`),
  CONSTRAINT `FK_bookletTOrequests_requestid` FOREIGN KEY (`request_id`) REFERENCES `form_requests` (`request_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_request_device
CREATE TABLE IF NOT EXISTS `form_request_device` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `reason_for_request` text NOT NULL,
  `device_requested` text CHARACTER SET utf8mb4,
  `local_social_pension` enum('Y','N') CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_deviceTOrequests_requestid` (`request_id`),
  CONSTRAINT `FK_deviceTOrequests_requestid` FOREIGN KEY (`request_id`) REFERENCES `form_requests` (`request_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_request_financial
CREATE TABLE IF NOT EXISTS `form_request_financial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `reason_for_request` text CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_financialTOrequests_requestid` (`request_id`),
  CONSTRAINT `FK_financialTOrequests_requestid` FOREIGN KEY (`request_id`) REFERENCES `form_requests` (`request_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_request_loss
CREATE TABLE IF NOT EXISTS `form_request_loss` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `file_affidavit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_financialTOrequests_requestid` (`request_id`) USING BTREE,
  CONSTRAINT `form_request_loss_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `form_requests` (`request_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.form_request_renewal
CREATE TABLE IF NOT EXISTS `form_request_renewal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `file_medcert` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_financialTOrequests_requestid` (`request_id`) USING BTREE,
  CONSTRAINT `form_request_renewal_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `form_requests` (`request_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4,
  `payload` longtext CHARACTER SET utf8mb4 NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.support_messages
CREATE TABLE IF NOT EXISTS `support_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `thread_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `sender_is_staff` tinyint NOT NULL DEFAULT '0',
  `body` text,
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_original_name` varchar(255) DEFAULT NULL,
  `attachment_mime` varchar(100) DEFAULT NULL,
  `attachment_size` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_senderTOmaster_ID` (`sender_id`),
  KEY `FK_threadTOthreads_ID` (`thread_id`),
  CONSTRAINT `FK_senderTOmaster_ID` FOREIGN KEY (`sender_id`) REFERENCES `accounts_master` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_threadTOthreads_ID` FOREIGN KEY (`thread_id`) REFERENCES `support_threads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.support_threads
CREATE TABLE IF NOT EXISTS `support_threads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `staff_id` int DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `status` enum('open','pending','resolved','closed') NOT NULL DEFAULT 'open',
  `last_message_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_threadsTOmaster_id` (`user_id`),
  KEY `FK_staffTOmaster_id` (`staff_id`),
  CONSTRAINT `FK_staffTOmaster_id` FOREIGN KEY (`staff_id`) REFERENCES `accounts_master` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_threadsTOmaster_id` FOREIGN KEY (`user_id`) REFERENCES `accounts_master` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdao_db.user_notifications
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `reference_id` int NOT NULL,
  `reference_type` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `is_read` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_account_idTOaccounts_master_id` (`account_id`),
  CONSTRAINT `FK_account_idTOaccounts_master_id` FOREIGN KEY (`account_id`) REFERENCES `accounts_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
