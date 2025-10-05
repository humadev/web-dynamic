/*
 Navicat Premium Data Transfer

 Source Server         : XAMPP
 Source Server Type    : MySQL
 Source Server Version : 90300
 Source Host           : localhost:3306
 Source Schema         : mahasiswa

 Target Server Type    : MySQL
 Target Server Version : 90300
 File Encoding         : 65001

 Date: 05/10/2025 22:03:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mahasiswa
-- ----------------------------
DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deskripsi` text,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` text,
  `website` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `linkedln` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `keahlian` text,
  `hobi` text,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

SET FOREIGN_KEY_CHECKS = 1;
