-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th6 08, 2022 lúc 09:29 AM
-- Phiên bản máy phục vụ: 10.4.21-MariaDB
-- Phiên bản PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `go_3n`
--
CREATE DATABASE IF NOT EXISTS `go_3n` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `go_3n`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `book_truck_informations`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `book_truck_informations`;
CREATE TABLE IF NOT EXISTS `book_truck_informations` (
  `book_truck_information_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `from_city_id` tinyint(4) NOT NULL,
  `to_city_id` tinyint(4) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `category_truck_id` int(11) DEFAULT NULL,
  `weight_product` double(8,2) NOT NULL,
  `price` double(8,2) DEFAULT NULL,
  `width` double(8,2) DEFAULT NULL,
  `length` double(8,2) DEFAULT NULL,
  `height` double(8,2) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`book_truck_information_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `book_truck_informations`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category_truck`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `category_truck`;
CREATE TABLE IF NOT EXISTS `category_truck` (
  `category_truck_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_truck_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `category_truck`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `city`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `city`;
CREATE TABLE IF NOT EXISTS `city` (
  `city_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `city`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--
-- Tạo: Th5 17, 2022 lúc 02:40 AM
-- Cập nhật lần cuối: Th6 06, 2022 lúc 03:24 AM
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` int(11) NOT NULL,
  `customer_type` tinyint(4) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `balance` bigint(20) NOT NULL DEFAULT 0,
  `review` double NOT NULL DEFAULT 5,
  `count_review` int(11) NOT NULL DEFAULT 1,
  `account_status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `customers`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_bill`
--
-- Tạo: Th5 12, 2022 lúc 09:23 AM
--

DROP TABLE IF EXISTS `customer_bill`;
CREATE TABLE IF NOT EXISTS `customer_bill` (
  `customer_bill_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `customer_bill_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` bigint(20) DEFAULT NULL,
  `bank_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `customer_bill`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_comment`
--
-- Tạo: Th6 06, 2022 lúc 03:10 AM
-- Cập nhật lần cuối: Th6 06, 2022 lúc 03:24 AM
--

DROP TABLE IF EXISTS `customer_comment`;
CREATE TABLE IF NOT EXISTS `customer_comment` (
  `customer_comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) NOT NULL,
  `driver_id` bigint(20) NOT NULL,
  `content` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `customer_comment`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_notification`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `customer_notification`;
CREATE TABLE IF NOT EXISTS `customer_notification` (
  `customer_notification_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_status` tinyint(1) NOT NULL DEFAULT 0,
  `customer_id` bigint(20) DEFAULT NULL,
  `customer_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `customer_notification`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `distance_city_vn`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `distance_city_vn`;
CREATE TABLE IF NOT EXISTS `distance_city_vn` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_city_id` tinyint(4) NOT NULL,
  `to_city_id` tinyint(4) NOT NULL,
  `from_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `distance_city_vn`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `district`
--
-- Tạo: Th5 12, 2022 lúc 09:28 AM
--

DROP TABLE IF EXISTS `district`;
CREATE TABLE IF NOT EXISTS `district` (
  `district_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`district_id`),
  KEY `_province_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `district`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorite_post`
--
-- Tạo: Th5 26, 2022 lúc 08:27 AM
--

DROP TABLE IF EXISTS `favorite_post`;
CREATE TABLE IF NOT EXISTS `favorite_post` (
  `favorite_post` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`favorite_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `favorite_post`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `item_type`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `item_type`;
CREATE TABLE IF NOT EXISTS `item_type` (
  `item_type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `item_type`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--
-- Tạo: Th5 13, 2022 lúc 08:01 AM
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender` bigint(20) UNSIGNED NOT NULL,
  `receiver` bigint(20) UNSIGNED NOT NULL,
  `content` varchar(512) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `messages`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_informations`
--
-- Tạo: Th6 06, 2022 lúc 02:50 AM
-- Cập nhật lần cuối: Th6 06, 2022 lúc 06:19 AM
--

DROP TABLE IF EXISTS `order_informations`;
CREATE TABLE IF NOT EXISTS `order_informations` (
  `order_information_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code_order` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_truck_information_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `is_reviewed` tinyint(4) NOT NULL,
  `recieve_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`order_information_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `order_informations`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `password_resets`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
-- Cập nhật lần cuối: Th6 06, 2022 lúc 08:44 AM
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `personal_access_tokens`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personnel`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `personnel`;
CREATE TABLE IF NOT EXISTS `personnel` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `personnel`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personnel_notifications`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `personnel_notifications`;
CREATE TABLE IF NOT EXISTS `personnel_notifications` (
  `personnel_notification_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_status` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) DEFAULT NULL,
  `user_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`personnel_notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `personnel_notifications`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post`
--
-- Tạo: Th5 12, 2022 lúc 09:23 AM
-- Cập nhật lần cuối: Th6 06, 2022 lúc 08:43 AM
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `truck_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_city_id` tinyint(4) NOT NULL,
  `from_district_id` int(11) DEFAULT NULL,
  `to_city_id` tinyint(4) NOT NULL,
  `to_district_id` int(11) DEFAULT NULL,
  `post_type` tinyint(1) NOT NULL DEFAULT 0,
  `weight_product` double(8,2) DEFAULT NULL,
  `lowest_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `highest_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `is_approve` tinyint(1) NOT NULL DEFAULT 0,
  `is_approve_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `post`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_image`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `post_image`;
CREATE TABLE IF NOT EXISTS `post_image` (
  `post_image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `post_image`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_item_type`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `post_item_type`;
CREATE TABLE IF NOT EXISTS `post_item_type` (
  `post_item_type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `post_item_type`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `report_driver`
--
-- Tạo: Th5 26, 2022 lúc 08:27 AM
--

DROP TABLE IF EXISTS `report_driver`;
CREATE TABLE IF NOT EXISTS `report_driver` (
  `report_driver_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `report_type` tinyint(4) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`report_driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `report_driver`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suggest_truck`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `suggest_truck`;
CREATE TABLE IF NOT EXISTS `suggest_truck` (
  `suggest_truck_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `book_truck_information_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`suggest_truck_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `suggest_truck`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `truck`
--
-- Tạo: Th5 12, 2022 lúc 09:05 AM
--

DROP TABLE IF EXISTS `truck`;
CREATE TABLE IF NOT EXISTS `truck` (
  `truck_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `license_plates` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_plates_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `category_truck_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` double(8,2) DEFAULT NULL,
  `length` double(8,2) DEFAULT NULL,
  `height` double(8,2) DEFAULT NULL,
  `weight` double(8,2) NOT NULL,
  `weight_items` double(8,2) NOT NULL,
  `count_order` int(11) NOT NULL DEFAULT 0,
  `location_now_city_id` int(11) DEFAULT NULL,
  `location_now_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_id_accept` int(11) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`truck_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `truck`:
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
