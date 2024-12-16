-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 16 déc. 2024 à 13:01
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ci-secure-api`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `retainer_fee` int NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) DEFAULT '0',
  `softdelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `retainer_fee` (`retainer_fee`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `name`, `email`, `retainer_fee`, `updated_at`, `created_at`, `active`, `softdelete`) VALUES
(1, 'Miss Lyda Lesch I', 'hellen.sporer@gmail.com', 38879894, NULL, '2023-10-16 11:49:37', 1, 0),
(2, 'Dr. Lucienne Gerlach DVM', 'esta.swift@johnson.com', 25980168, NULL, '2023-10-16 11:49:37', 0, 0),
(3, 'Jillian Ebert', 'jewel.gerlach@gmail.com', 37608028, NULL, '2023-10-16 11:49:37', 0, 0),
(4, 'Orrin O\'Reilly', 'nolan.kenna@gmail.com', 47557388, NULL, '2023-10-16 11:49:37', 0, 0),
(5, 'Shawna Bednar', 'roel32@gmail.com', 91516595, NULL, '2023-10-16 11:49:37', 0, 0),
(6, 'Cordell Nitzsche', 'london.grady@schmitt.info', 15326474, NULL, '2023-10-16 11:49:37', 0, 0),
(7, 'Everette Kovacek', 'yvonne.kunde@hotmail.com', 35687201, NULL, '2023-10-16 11:49:37', 0, 0),
(8, 'Katlynn Effertz', 'carlee07@hotmail.com', 16740931, NULL, '2023-10-16 11:49:37', 0, 0),
(9, 'Morgan Roob DDS', 'beau82@buckridge.com', 39434942, NULL, '2023-10-16 11:49:37', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2023-10-16-093040', 'App\\Database\\Migrations\\AddClient', 'default', 'App', 1697449205, 1),
(2, '2023-10-16-093057', 'App\\Database\\Migrations\\AddUser', 'default', 'App', 1697449205, 1),
(3, '2023-10-24-092315', 'App\\Database\\Migrations\\Moderateur', 'default', 'App', 1698139602, 2),
(4, '2023-11-10-075036', 'App\\Database\\Migrations\\AddRole', 'default', 'App', 1710600353, 3),
(5, '2023-11-12-073721', 'App\\Database\\Migrations\\UserRoles', 'default', 'App', 1710600353, 3),
(6, '2023-11-20-084116', 'App\\Database\\Migrations\\AddUserActive', 'default', 'App', 1710600353, 3),
(7, '2023-11-22-123755', 'App\\Database\\Migrations\\AddClientActive', 'default', 'App', 1710600353, 3),
(8, '2023-11-24-083406', 'App\\Database\\Migrations\\AddSoftDeleteClient', 'default', 'App', 1710600353, 3);

-- --------------------------------------------------------

--
-- Structure de la table `moderator`
--

DROP TABLE IF EXISTS `moderator`;
CREATE TABLE IF NOT EXISTS `moderator` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password` (`password`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`) VALUES
(1, 'admin', '2024-03-16 15:53:26'),
(2, 'moderator', '2024-03-16 15:53:26'),
(3, 'superadmin', '2024-03-16 15:53:26');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) DEFAULT '0',
  `softdelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password` (`password`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `updated_at`, `created_at`, `active`, `softdelete`) VALUES
(1, 'gabriele', 'gabriel_cassano@hotmail.com', '$2y$10$j0accELo127Gtu8/u4Tw4.1HcdzXMNd8BiOjz2hHpBOp/K5tUacEe', NULL, '2023-10-23 08:29:31', 1, 0),
(17, 'usertest', 'user_test@hotmail.com', '$2y$10$1a2W3Vfzz6GoRHHTu5m8lelsGvfyZf.ShS2xlQlCp8kIQKEFwt1y2', NULL, '2024-03-16 15:53:02', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  KEY `user_roles_user_id_foreign` (`user_id`),
  KEY `user_roles_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `create_at`, `updated_at`, `created_at`) VALUES
(1, 1, NULL, NULL, '2024-03-16 15:52:17'),
(17, 1, NULL, NULL, '2024-03-16 17:57:34'),
(1, 3, NULL, NULL, '2024-03-16 15:52:17'),
(17, 2, NULL, NULL, '2024-03-16 17:57:34'),
(18, 2, NULL, NULL, '2024-03-16 17:58:18'),
(19, 1, NULL, NULL, '2024-03-16 18:00:26');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
