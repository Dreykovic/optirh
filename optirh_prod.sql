-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mer. 22 oct. 2025 à 17:34
-- Version du serveur : 8.0.42
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `optirh_prod`
--

-- --------------------------------------------------------

--
-- Structure de la table `absences`
--

CREATE TABLE `absences` (
  `id` bigint UNSIGNED NOT NULL,
  `requested_days` int UNSIGNED NOT NULL,
  `level` enum('ZERO','ONE','TWO','THREE','FOUR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ZERO',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_application` datetime NOT NULL DEFAULT '2025-06-10 10:14:05',
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `date_of_approval` date DEFAULT NULL,
  `stage` enum('PENDING','APPROVED','REJECTED','CANCELLED','IN_PROGRESS','COMPLETED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `reasons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `absence_number` bigint DEFAULT NULL,
  `is_deductible` tinyint(1) NOT NULL DEFAULT '1',
  `duty_id` bigint UNSIGNED DEFAULT NULL,
  `absence_type_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `absences`
--

INSERT INTO `absences` (`id`, `requested_days`, `level`, `start_date`, `end_date`, `address`, `date_of_application`, `status`, `date_of_approval`, `stage`, `reasons`, `proof`, `comment`, `absence_number`, `is_deductible`, `duty_id`, `absence_type_id`, `created_at`, `updated_at`) VALUES
(1, 3, 'ZERO', '2025-09-03', '2025-09-05', 'Lomé', '2025-09-02 09:56:04', 'PENDING', NULL, 'PENDING', 'Anniversaire pour moi', NULL, NULL, NULL, 1, 36, 3, '2025-09-02 09:56:04', '2025-09-02 09:56:04'),
(2, 5, 'ZERO', '2025-09-03', '2025-09-07', 'Kpalimé', '2025-09-02 10:09:23', 'PENDING', NULL, 'PENDING', 'Pour mon lune de miel', NULL, NULL, NULL, 1, 12, 3, '2025-09-02 10:09:23', '2025-09-02 10:09:23');

-- --------------------------------------------------------

--
-- Structure de la table `absence_types`
--

CREATE TABLE `absence_types` (
  `id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_deductible` tinyint(1) NOT NULL DEFAULT '1',
  `type` enum('EXCEPTIONAL','NORMAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NORMAL',
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `absence_types`
--

INSERT INTO `absence_types` (`id`, `label`, `description`, `is_deductible`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'annuel', 'Absence pour congés payés annuels', 1, 'NORMAL', 'ACTIVATED', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(2, 'maternité', 'Congé accordé pour les salariées enceintes', 0, 'EXCEPTIONAL', 'ACTIVATED', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(3, 'exceptionnel', 'Absence pour une raison spécifique', 0, 'EXCEPTIONAL', 'ACTIVATED', '2025-06-10 10:14:09', '2025-06-10 10:14:09');

-- --------------------------------------------------------

--
-- Structure de la table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `additional_data` json DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `old_values`, `new_values`, `additional_data`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur hr_manager', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-10 10:14:29', '2025-06-10 10:14:29'),
(2, 2, 'access', NULL, NULL, 'Accès au formulaire de création de demande de document', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-10 10:14:49', '2025-06-10 10:14:49'),
(3, 2, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-10 10:14:51', '2025-06-10 10:14:51'),
(4, 2, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-10 12:17:14', '2025-06-10 12:17:14'),
(5, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur hr_manager', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0', '2025-06-30 06:36:23', '2025-06-30 06:36:23'),
(6, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur hr_manager', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 06:58:50', '2025-06-30 06:58:50'),
(7, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:14:59', '2025-06-30 08:14:59'),
(8, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur director_general', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"4\\\",\\\"deleted_user_name\\\":\\\"director_general\\\"}\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:15:26', '2025-06-30 08:15:26'),
(9, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:16:47', '2025-06-30 08:16:47'),
(10, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur cgbadjavi18', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"5\\\",\\\"deleted_user_name\\\":\\\"cgbadjavi18\\\"}\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:16:58', '2025-06-30 08:16:58'),
(11, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:19:24', '2025-06-30 08:19:24'),
(12, 2, 'updated', 'App\\Models\\User', 2, 'Mise à jour des détails de l\'utilisateur optirh_manager', NULL, NULL, '\"{\\\"old_email\\\":\\\"amonaaudrey16@gmail.com\\\",\\\"new_email\\\":\\\"optirh@arcop.tg\\\",\\\"old_username\\\":\\\"hr_manager\\\",\\\"new_username\\\":\\\"optirh_manager\\\",\\\"old_status\\\":\\\"ACTIVATED\\\",\\\"new_status\\\":\\\"ACTIVATED\\\"}\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:20:18', '2025-06-30 08:20:18'),
(13, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:20:19', '2025-06-30 08:20:19'),
(14, 2, 'updated', 'App\\Models\\User', 2, 'Changement de mot de passe pour l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:20:33', '2025-06-30 08:20:33'),
(15, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:20:34', '2025-06-30 08:20:34'),
(16, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:20:41', '2025-06-30 08:20:41'),
(17, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-30 08:20:47', '2025-06-30 08:20:47'),
(18, NULL, 'denied', NULL, NULL, 'Tentative de connexion échouée pour l\'email: amonaaudrey16@gmail.com', NULL, NULL, '\"{\\\"ip\\\":\\\"127.0.0.1\\\"}\"', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0', '2025-07-02 08:41:21', '2025-07-02 08:41:21'),
(19, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0', '2025-07-02 08:42:24', '2025-07-02 08:42:24'),
(20, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 22:28:28', '2025-08-24 22:28:28'),
(21, 2, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 22:31:38', '2025-08-24 22:31:38'),
(22, 2, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 22:33:56', '2025-08-24 22:33:56'),
(23, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 22:41:43', '2025-08-24 22:41:43'),
(24, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 22:59:28', '2025-08-24 22:59:28'),
(25, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 22:59:51', '2025-08-24 22:59:51'),
(26, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:11:07', '2025-08-24 23:11:07'),
(27, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:12:24', '2025-08-24 23:12:24'),
(28, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:12:53', '2025-08-24 23:12:53'),
(29, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:13:57', '2025-08-24 23:13:57'),
(30, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:15:26', '2025-08-24 23:15:26'),
(31, 2, 'created', 'App\\Models\\User', 6, 'Création d\'un utilisateur avec nom: kakey9 et email: kakey@arcop.tg', NULL, NULL, '\"{\\\"role\\\":\\\"GRH\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:16:06', '2025-08-24 23:16:06'),
(32, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:16:15', '2025-08-24 23:16:15'),
(33, 2, 'updated', 'App\\Models\\User', 6, 'Changement de mot de passe pour l\'utilisateur kakey9', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:16:42', '2025-08-24 23:16:42'),
(34, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:16:44', '2025-08-24 23:16:44'),
(35, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:16:55', '2025-08-24 23:16:55'),
(36, 6, 'login', 'App\\Models\\User', 6, 'Connexion réussie de l\'utilisateur kakey9', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:17:01', '2025-08-24 23:17:01'),
(37, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:27:44', '2025-08-24 23:27:44'),
(38, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:34:22', '2025-08-24 23:34:22'),
(39, 6, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:35:27', '2025-08-24 23:35:27'),
(40, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:35:46', '2025-08-24 23:35:46'),
(41, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-24 23:37:21', '2025-08-24 23:37:21'),
(42, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:21:54', '2025-08-28 16:21:54'),
(43, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:22:17', '2025-08-28 16:22:17'),
(44, 2, 'created', 'App\\Models\\User', 7, 'Création d\'un utilisateur avec nom: cgbadjavi18 et email: cgbadjavi@arcop.tg', NULL, NULL, '\"{\\\"role\\\":\\\"GRH\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:23:38', '2025-08-28 16:23:38'),
(45, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:23:54', '2025-08-28 16:23:54'),
(46, 2, 'updated', 'App\\Models\\User', 7, 'Changement de mot de passe pour l\'utilisateur cgbadjavi18', NULL, NULL, '\"[]\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:24:44', '2025-08-28 16:24:44'),
(47, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:24:46', '2025-08-28 16:24:46'),
(48, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:26:13', '2025-08-28 16:26:13'),
(49, 7, 'login', 'App\\Models\\User', 7, 'Connexion réussie de l\'utilisateur cgbadjavi18', NULL, NULL, '\"[]\"', '10.115.10.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-28 16:27:32', '2025-08-28 16:27:32'),
(50, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 08:45:40', '2025-09-02 08:45:40'),
(51, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:18:55', '2025-09-02 09:18:55'),
(52, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ACTIVATED', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:20:12', '2025-09-02 09:20:12'),
(53, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: DEACTIVATED', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:20:14', '2025-09-02 09:20:14'),
(54, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:20:16', '2025-09-02 09:20:16'),
(55, 2, 'access', NULL, NULL, 'Accès au formulaire de création de demande de document', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:21:05', '2025-09-02 09:21:05'),
(56, 2, 'view', NULL, NULL, 'Consultation de la liste des demandes de documents - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:21:11', '2025-09-02 09:21:11'),
(57, 2, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:22:14', '2025-09-02 09:22:14'),
(58, 2, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:22:30', '2025-09-02 09:22:30'),
(59, 2, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:43:25', '2025-09-02 09:43:25'),
(60, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:44:03', '2025-09-02 09:44:03'),
(61, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:45:08', '2025-09-02 09:45:08'),
(62, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:45:33', '2025-09-02 09:45:33'),
(63, 2, 'created', 'App\\Models\\User', 8, 'Création d\'un utilisateur avec nom: cafoh tchaouta7 et email: cafohtchaouta@arcop.tg', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:46:01', '2025-09-02 09:46:01'),
(64, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:46:13', '2025-09-02 09:46:13'),
(65, 2, 'updated', 'App\\Models\\User', 8, 'Changement de mot de passe pour l\'utilisateur cafoh tchaouta7', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:46:42', '2025-09-02 09:46:42'),
(66, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:46:44', '2025-09-02 09:46:44'),
(67, 2, 'created', 'App\\Models\\User', 9, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:47:19', '2025-09-02 09:47:19'),
(68, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:47:26', '2025-09-02 09:47:26'),
(69, 2, 'updated', 'App\\Models\\User', 9, 'Changement de mot de passe pour l\'utilisateur kadignon38', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:49:51', '2025-09-02 09:49:51'),
(70, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:49:53', '2025-09-02 09:49:53'),
(71, 8, 'login', 'App\\Models\\User', 8, 'Connexion réussie de l\'utilisateur cafoh tchaouta7', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:50:47', '2025-09-02 09:50:47'),
(72, 8, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:51:03', '2025-09-02 09:51:03'),
(73, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:51:09', '2025-09-02 09:51:09'),
(74, NULL, 'login', 'App\\Models\\User', 9, 'Connexion réussie de l\'utilisateur kadignon38', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 09:54:10', '2025-09-02 09:54:10'),
(75, NULL, 'created', 'App\\Models\\OptiHr\\Absence', 1, 'Création d\'une demande d\'absence de type exceptionnel non déductible', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 09:56:04', '2025-09-02 09:56:04'),
(76, NULL, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 09:56:09', '2025-09-02 09:56:09'),
(77, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:56:36', '2025-09-02 09:56:36'),
(78, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:56:39', '2025-09-02 09:56:39'),
(79, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:56:40', '2025-09-02 09:56:40'),
(80, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:56:42', '2025-09-02 09:56:42'),
(81, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:57:26', '2025-09-02 09:57:26'),
(82, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:57:27', '2025-09-02 09:57:27'),
(83, NULL, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: IN_PROGRESS', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 09:57:55', '2025-09-02 09:57:55'),
(84, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:09', '2025-09-02 09:58:09'),
(85, 7, 'login', 'App\\Models\\User', 7, 'Connexion réussie de l\'utilisateur cgbadjavi18', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:16', '2025-09-02 09:58:16'),
(86, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:24', '2025-09-02 09:58:24'),
(87, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:28', '2025-09-02 09:58:28'),
(88, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: IN_PROGRESS', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:30', '2025-09-02 09:58:30'),
(89, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: APPROVED', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:31', '2025-09-02 09:58:31'),
(90, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: REJECTED', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:32', '2025-09-02 09:58:32'),
(91, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:34', '2025-09-02 09:58:34'),
(92, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 09:58:46', '2025-09-02 09:58:46'),
(93, 8, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:09', '2025-09-02 10:00:09'),
(94, 8, 'view', NULL, NULL, 'Consultation de la liste des publications - Statut: all', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:28', '2025-09-02 10:00:28'),
(95, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:31', '2025-09-02 10:00:31'),
(96, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:33', '2025-09-02 10:00:33'),
(97, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:35', '2025-09-02 10:00:35'),
(98, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: IN_PROGRESS', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:36', '2025-09-02 10:00:36'),
(99, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: APPROVED', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:37', '2025-09-02 10:00:37'),
(100, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: REJECTED', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:38', '2025-09-02 10:00:38'),
(101, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:39', '2025-09-02 10:00:39'),
(102, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:00:59', '2025-09-02 10:00:59'),
(103, NULL, 'login', 'App\\Models\\User', 9, 'Connexion réussie de l\'utilisateur kadignon38', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:02:06', '2025-09-02 10:02:06'),
(104, 7, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:03:34', '2025-09-02 10:03:34'),
(105, 7, 'created', 'App\\Models\\User', 10, 'Création d\'un utilisateur avec nom: kdatagni13 et email: fdatagni@arcop.tg', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:06:03', '2025-09-02 10:06:03'),
(106, 7, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:06:13', '2025-09-02 10:06:13'),
(107, 7, 'updated', 'App\\Models\\User', 10, 'Changement de mot de passe pour l\'utilisateur kdatagni13', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:07:02', '2025-09-02 10:07:02'),
(108, 7, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:07:03', '2025-09-02 10:07:03'),
(109, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur kadignon38', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 10:07:39', '2025-09-02 10:07:39'),
(110, 10, 'login', 'App\\Models\\User', 10, 'Connexion réussie de l\'utilisateur kdatagni13', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 10:08:11', '2025-09-02 10:08:11'),
(111, 10, 'created', 'App\\Models\\OptiHr\\Absence', 2, 'Création d\'une demande d\'absence de type exceptionnel non déductible', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 10:09:23', '2025-09-02 10:09:23'),
(112, 10, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-09-02 10:09:25', '2025-09-02 10:09:25'),
(113, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:09:34', '2025-09-02 10:09:34'),
(114, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:09:50', '2025-09-02 10:09:50'),
(115, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:04', '2025-09-02 10:10:04'),
(116, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:08', '2025-09-02 10:10:08'),
(117, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:12', '2025-09-02 10:10:12'),
(118, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:14', '2025-09-02 10:10:14'),
(119, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: IN_PROGRESS', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:16', '2025-09-02 10:10:16'),
(120, 8, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:17', '2025-09-02 10:10:17'),
(121, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: PENDING', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:39', '2025-09-02 10:10:39'),
(122, 7, 'view', NULL, NULL, 'Consultation de la liste des absences - Stage: ALL', NULL, NULL, '\"[]\"', '10.115.10.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 10:10:41', '2025-09-02 10:10:41'),
(123, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:17:12', '2025-09-05 09:17:12'),
(124, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:17:18', '2025-09-05 09:17:18'),
(125, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"9\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:17:32', '2025-09-05 09:17:32'),
(126, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:17:44', '2025-09-05 09:17:44'),
(127, 2, 'created', 'App\\Models\\User', 11, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:17:58', '2025-09-05 09:17:58'),
(128, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:18:12', '2025-09-05 09:18:12'),
(129, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:28:18', '2025-09-05 09:28:18'),
(130, 8, 'login', 'App\\Models\\User', 8, 'Connexion réussie de l\'utilisateur cafoh tchaouta7', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:28:36', '2025-09-05 09:28:36'),
(131, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur cafoh tchaouta7', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:28:46', '2025-09-05 09:28:46'),
(132, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:29:07', '2025-09-05 09:29:07'),
(133, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:29:12', '2025-09-05 09:29:12'),
(134, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"11\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:29:17', '2025-09-05 09:29:17'),
(135, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:29:21', '2025-09-05 09:29:21'),
(136, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-05 09:30:19', '2025-09-05 09:30:19'),
(137, NULL, 'denied', NULL, NULL, 'Tentative de connexion échouée pour l\'email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"ip\\\":\\\"10.115.10.76\\\"}\"', '10.115.10.76', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-10-07 10:36:53', '2025-10-07 10:36:53'),
(138, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:51:52', '2025-10-08 11:51:52'),
(139, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:51:59', '2025-10-08 11:51:59'),
(140, 6, 'login', 'App\\Models\\User', 6, 'Connexion réussie de l\'utilisateur kakey9', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:53:16', '2025-10-08 11:53:16'),
(141, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:53:24', '2025-10-08 11:53:24'),
(142, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur kakey9', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:57:31', '2025-10-08 11:57:31'),
(143, 6, 'login', 'App\\Models\\User', 6, 'Connexion réussie de l\'utilisateur kakey9', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:57:39', '2025-10-08 11:57:39'),
(144, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:57:45', '2025-10-08 11:57:45'),
(145, 6, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"12\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:59:46', '2025-10-08 11:59:46'),
(146, 6, 'created', 'App\\Models\\User', 13, 'Création d\'un utilisateur avec nom: ttegba31 et email: ttegba@arcop.tg', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:59:50', '2025-10-08 11:59:50'),
(147, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 11:59:51', '2025-10-08 11:59:51'),
(148, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:07:31', '2025-10-08 12:07:31'),
(149, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:27:26', '2025-10-08 12:27:26'),
(150, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"14\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:27:46', '2025-10-08 12:27:46'),
(151, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:27:53', '2025-10-08 12:27:53'),
(152, 2, 'created', 'App\\Models\\User', 16, 'Création d\'un utilisateur avec nom: ktrainou34 et email: ktrainou@arcop.tg', NULL, NULL, '\"{\\\"role\\\":\\\"DSAF\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:31:09', '2025-10-08 12:31:09'),
(153, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:32:04', '2025-10-08 12:32:04'),
(154, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"15\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:32:25', '2025-10-08 12:32:25'),
(155, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:32:40', '2025-10-08 12:32:40'),
(156, 2, 'created', 'App\\Models\\User', 17, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:33:08', '2025-10-08 12:33:08'),
(157, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:34:00', '2025-10-08 12:34:00'),
(158, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"17\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:38:46', '2025-10-08 12:38:46'),
(159, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:38:49', '2025-10-08 12:38:49'),
(160, 2, 'created', 'App\\Models\\User', 18, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"GRH\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:39:20', '2025-10-08 12:39:20'),
(161, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-08 12:39:38', '2025-10-08 12:39:38'),
(162, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 08:58:42', '2025-10-09 08:58:42'),
(163, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 08:59:34', '2025-10-09 08:59:34'),
(164, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"18\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 08:59:52', '2025-10-09 08:59:52'),
(165, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 08:59:55', '2025-10-09 08:59:55'),
(166, 2, 'created', 'App\\Models\\User', 19, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:00:12', '2025-10-09 09:00:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `old_values`, `new_values`, `additional_data`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(167, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:00:18', '2025-10-09 09:00:18'),
(168, 2, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"19\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:00:28', '2025-10-09 09:00:28'),
(169, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:00:31', '2025-10-09 09:00:31'),
(170, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:05:38', '2025-10-09 09:05:38'),
(171, 2, 'login', 'App\\Models\\User', 2, 'Connexion réussie de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:05:50', '2025-10-09 09:05:50'),
(172, 2, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:05:55', '2025-10-09 09:05:55'),
(173, NULL, 'logout', NULL, NULL, 'Déconnexion de l\'utilisateur optirh_manager', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:06:09', '2025-10-09 09:06:09'),
(174, 6, 'login', 'App\\Models\\User', 6, 'Connexion réussie de l\'utilisateur kakey9', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:06:12', '2025-10-09 09:06:12'),
(175, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:06:26', '2025-10-09 09:06:26'),
(176, 6, 'created', 'App\\Models\\User', 20, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"EMPLOYEE\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:06:43', '2025-10-09 09:06:43'),
(177, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 09:06:57', '2025-10-09 09:06:57'),
(178, 6, 'login', 'App\\Models\\User', 6, 'Connexion réussie de l\'utilisateur kakey9', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:13:32', '2025-10-09 16:13:32'),
(179, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:24:54', '2025-10-09 16:24:54'),
(180, 6, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"20\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:25:25', '2025-10-09 16:25:25'),
(181, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:25:28', '2025-10-09 16:25:28'),
(182, 6, 'created', 'App\\Models\\User', 21, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"GRH\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:26:04', '2025-10-09 16:26:04'),
(183, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:26:22', '2025-10-09 16:26:22'),
(184, 6, 'deleted', NULL, NULL, 'Suppression de l\'utilisateur kadignon38', NULL, NULL, '\"{\\\"deleted_user_id\\\":\\\"21\\\",\\\"deleted_user_name\\\":\\\"kadignon38\\\"}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:27:22', '2025-10-09 16:27:22'),
(185, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:28:12', '2025-10-09 16:28:12'),
(186, 6, 'created', 'App\\Models\\User', 23, 'Création d\'un utilisateur avec nom: kadignon38 et email: peteradignon1@gmail.com', NULL, NULL, '\"{\\\"role\\\":\\\"GRH\\\",\\\"permission\\\":null,\\\"reset_link_sent\\\":true}\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:28:44', '2025-10-09 16:28:44'),
(187, 6, 'view', NULL, NULL, 'Consultation de la liste des utilisateurs - Statut: ALL', NULL, NULL, '\"[]\"', '10.115.10.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 16:30:03', '2025-10-09 16:30:03');

-- --------------------------------------------------------

--
-- Structure de la table `annual_decisions`
--

CREATE TABLE `annual_decisions` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/ARCOP/DG/DSAF',
  `date` date NOT NULL,
  `pdf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` enum('current','outdated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'current',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `appeals`
--

CREATE TABLE `appeals` (
  `id` bigint UNSIGNED NOT NULL,
  `type` enum('PROCESS','RESULTS','OTHERS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RESULTS',
  `deposit_date` date NOT NULL,
  `deposit_hour` time NOT NULL,
  `object` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notif_date` date DEFAULT NULL,
  `response_date` date DEFAULT NULL,
  `message_date` date DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `day_count` int NOT NULL DEFAULT '0',
  `analyse_status` enum('EN_COURS','RECEVABLE','IRRECEVABLE','INCOMPETENCE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EN_COURS',
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `dac_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `decided_id` bigint UNSIGNED DEFAULT NULL,
  `suspended_id` bigint UNSIGNED DEFAULT NULL,
  `last_updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `applicants`
--

CREATE TABLE `applicants` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `last_updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `authorities`
--

CREATE TABLE `authorities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `last_updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `appeal_id` bigint UNSIGNED NOT NULL,
  `personnal_id` bigint UNSIGNED NOT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `last_updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dacs`
--

CREATE TABLE `dacs` (
  `id` bigint UNSIGNED NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `object` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `last_updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `decisions`
--

CREATE TABLE `decisions` (
  `id` bigint UNSIGNED NOT NULL,
  `decision` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `suspended_ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decided_ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspended_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decided_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `last_updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `director_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `director_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DG', 'Cabinet du Directeur Général', 34, 'ACTIVATED', '2025-06-10 10:14:10', '2025-06-30 08:11:41'),
(3, 'DIE', 'Direction Des Investigations Et Enquêtes', 21, 'ACTIVATED', '2025-06-10 10:30:23', '2025-06-30 07:47:53'),
(4, 'DFAT', 'Direction De La Formation Et Des Appuis Techniques', 8, 'ACTIVATED', '2025-06-10 10:30:40', '2025-06-30 07:18:05'),
(5, 'DRAJ', 'Direction De La Règlementation Et Des Affaires Juridiques', 25, 'ACTIVATED', '2025-06-10 10:31:29', '2025-06-30 07:56:46'),
(6, 'DSDSE', 'Direction Des Statistiques Et De La Documentation', 7, 'ACTIVATED', '2025-06-10 10:31:53', '2025-06-30 07:15:50'),
(7, 'DCRP', 'Direction De La Communication Et Des Relations Publiques', 28, 'ACTIVATED', '2025-06-10 10:32:21', '2025-06-30 08:01:39'),
(8, 'DSAF', 'Direction Des Services Administratif Et Financier', 12, 'ACTIVATED', '2025-06-30 06:59:27', '2025-06-30 07:25:43');

-- --------------------------------------------------------

--
-- Structure de la table `document_requests`
--

CREATE TABLE `document_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `level` enum('ZERO','ONE','TWO','THREE','FOUR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ZERO',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `date_of_application` datetime NOT NULL DEFAULT '2025-06-10 10:14:07',
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `date_of_approval` date DEFAULT NULL,
  `stage` enum('PENDING','APPROVED','REJECTED','CANCELLED','IN_PROGRESS','COMPLETED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `reasons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `document_number` bigint DEFAULT NULL,
  `duty_id` bigint UNSIGNED DEFAULT NULL,
  `document_type_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `document_types`
--

CREATE TABLE `document_types` (
  `id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` enum('EXCEPTIONAL','NORMAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NORMAL',
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `document_types`
--

INSERT INTO `document_types` (`id`, `label`, `description`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Attestation De Stage', 'Attestation Remise en fin de stage', 'NORMAL', 'ACTIVATED', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(2, 'Attestation de travail', '', 'EXCEPTIONAL', 'ACTIVATED', '2025-06-10 10:14:09', '2025-06-10 10:14:09');

-- --------------------------------------------------------

--
-- Structure de la table `duties`
--

CREATE TABLE `duties` (
  `id` bigint UNSIGNED NOT NULL,
  `duration` int NOT NULL DEFAULT '0',
  `begin_date` date NOT NULL,
  `absence_balance` int NOT NULL DEFAULT '30',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `evolution` enum('ON_GOING','ENDED','CANCEL','SUSPENDED','RESIGNED','DISMISSED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ON_GOING',
  `job_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `duties`
--

INSERT INTO `duties` (`id`, `duration`, `begin_date`, `absence_balance`, `type`, `comment`, `status`, `evolution`, `job_id`, `employee_id`, `created_at`, `updated_at`) VALUES
(1, 12, '2019-02-21', 30, 'Full-Time', NULL, 'ACTIVATED', 'ON_GOING', 1, 4, '2025-06-10 10:14:10', '2025-06-10 10:14:10'),
(4, 12, '2012-03-12', 73, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 19, 5, '2025-06-30 06:53:44', '2025-06-30 06:53:44'),
(5, 12, '2012-12-03', 71, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 38, 6, '2025-06-30 07:13:03', '2025-06-30 07:13:03'),
(6, 12, '2023-05-02', 30, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 9, 7, '2025-06-30 07:15:50', '2025-06-30 07:15:50'),
(7, 12, '2012-04-02', 78, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 7, 8, '2025-06-30 07:18:05', '2025-06-30 07:18:05'),
(8, 12, '2012-04-02', 41, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 39, 9, '2025-06-30 07:20:01', '2025-06-30 07:20:01'),
(9, 12, '2012-04-02', 30, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 18, 10, '2025-06-30 07:21:44', '2025-06-30 07:21:44'),
(10, 12, '2016-11-02', 38, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 37, 11, '2025-06-30 07:23:47', '2025-06-30 07:23:47'),
(11, 12, '2011-11-02', 75, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 31, 12, '2025-06-30 07:25:43', '2025-06-30 07:25:43'),
(12, 12, '2013-07-01', 35, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 13, 13, '2025-06-30 07:28:17', '2025-06-30 07:28:17'),
(13, 12, '2024-03-01', 25, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 11, 14, '2025-06-30 07:30:54', '2025-06-30 07:30:54'),
(14, 12, '2012-03-12', 62, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 32, 15, '2025-06-30 07:34:03', '2025-06-30 07:34:03'),
(15, 12, '2017-02-01', 45, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 17, 16, '2025-06-30 07:39:20', '2025-06-30 07:39:20'),
(16, 12, '2024-04-02', 18, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 20, 17, '2025-06-30 07:40:47', '2025-06-30 07:40:47'),
(17, 12, '2017-02-01', 78, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 35, 18, '2025-06-30 07:42:53', '2025-06-30 07:42:53'),
(18, 12, '2021-06-01', 52, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 14, 19, '2025-06-30 07:44:33', '2025-06-30 07:44:33'),
(19, 12, '2015-04-01', 32, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 22, 20, '2025-06-30 07:46:10', '2025-06-30 07:46:10'),
(20, 12, '2012-03-12', 43, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 6, 21, '2025-06-30 07:47:53', '2025-06-30 07:47:53'),
(21, 12, '2011-10-15', 90, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 12, 22, '2025-06-30 07:51:24', '2025-06-30 07:51:24'),
(22, 12, '2012-08-01', 30, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 16, 23, '2025-06-30 07:52:51', '2025-06-30 07:52:51'),
(23, 12, '2012-03-01', 49, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 40, 24, '2025-06-30 07:54:46', '2025-06-30 07:54:46'),
(24, 12, '2013-07-01', 60, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 8, 25, '2025-06-30 07:56:46', '2025-06-30 07:56:46'),
(25, 12, '2012-04-02', 30, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 34, 26, '2025-06-30 07:58:23', '2025-06-30 07:58:23'),
(26, 12, '2015-04-01', 49, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 33, 27, '2025-06-30 07:59:58', '2025-06-30 07:59:58'),
(27, 12, '2012-12-12', 59, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 10, 28, '2025-06-30 08:01:39', '2025-06-30 08:01:39'),
(28, 12, '2017-01-04', 22, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 40, 29, '2025-06-30 08:03:11', '2025-06-30 08:03:11'),
(29, 12, '2017-02-01', 69, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 21, 30, '2025-06-30 08:05:59', '2025-06-30 08:05:59'),
(30, 12, '2014-02-03', 52, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 39, 31, '2025-06-30 08:07:15', '2025-06-30 08:07:15'),
(31, 12, '2011-10-15', 76, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 23, 32, '2025-06-30 08:08:59', '2025-06-30 08:08:59'),
(32, 12, '2024-03-01', 25, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 15, 33, '2025-06-30 08:10:23', '2025-06-30 08:10:23'),
(33, 12, '2014-10-19', 7, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 24, 34, '2025-06-30 08:11:41', '2025-06-30 08:11:41'),
(34, 12, '2013-07-01', 55, 'CDI', NULL, 'ACTIVATED', 'ON_GOING', 36, 35, '2025-06-30 08:13:36', '2025-06-30 08:13:36'),
(36, 0, '2025-08-23', 10, 'stage', NULL, 'ACTIVATED', 'ON_GOING', 41, 38, '2025-08-24 23:32:55', '2025-08-24 23:32:55');

-- --------------------------------------------------------

--
-- Structure de la table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `matricule` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Identifiant unique de l''employé',
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Prénom de l''employé',
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom de famille de l''employé',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email de l''employé',
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Numéro de téléphone de l''employé',
  `gender` enum('FEMALE','MALE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FEMALE' COMMENT 'le genre',
  `address1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Adresse principale',
  `address2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Complément d''adresse',
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ville',
  `state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code de la région ou de l''Etat (ISO)',
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code du pays (ISO 3166-1 alpha-2)',
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nom de la banque',
  `code_bank` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code de la banque',
  `code_guichet` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code du guichet',
  `rib` varchar(23) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro RIB complet',
  `cle_rib` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Clé de vérification du RIB',
  `iban` varchar(34) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IBAN (Numéro international de compte bancaire)',
  `swift` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code SWIFT/BIC',
  `birth_date` date DEFAULT NULL COMMENT 'Date de naissance',
  `nationality` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nationalité',
  `religion` enum('Christian','Muslim','Jewish','Hindu','Buddhist','None','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Religion',
  `marital_status` enum('Single','Married','Divorced','Widowed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Statut matrimonial',
  `emergency_contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contact d''urgence',
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED' COMMENT 'Statut de l''employé',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employees`
--

INSERT INTO `employees` (`id`, `matricule`, `first_name`, `last_name`, `email`, `phone_number`, `gender`, `address1`, `address2`, `city`, `state`, `country`, `bank_name`, `code_bank`, `code_guichet`, `rib`, `cle_rib`, `iban`, `swift`, `birth_date`, `nationality`, `religion`, `marital_status`, `emergency_contact`, `status`, `code`, `created_at`, `updated_at`) VALUES
(2, 'RH001', 'Web Master', 'Manager', 'amonaaudrey16@gmail.com', '1234567891', 'MALE', '2 HR Lane', NULL, 'HR City', 'HC', 'FR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1985-02-15', 'French', NULL, NULL, NULL, 'ACTIVATED', 'MH-02', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(4, 'DG001', 'Aftar Touré', 'MOROU', 'amorou@arcop.tg', '1234567892', 'MALE', 'Lomé', NULL, 'Director City', 'DC', 'FR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1975-05-20', 'French', NULL, NULL, NULL, 'ACTIVATED', 'GD-02', '2025-06-10 10:14:10', '2025-06-30 06:41:39'),
(5, NULL, 'Akpédjé Viwoassi', 'ADAMA-DJIBOM', 'vadama@arcop.tg', '12345678', 'FEMALE', 'Lomé', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AA-02', '2025-06-30 06:53:44', '2025-06-30 06:53:44'),
(6, NULL, 'Koffi', 'ADZIDAGLO', 'kadzidaglo@arcop.tg', '123456781', 'MALE', 'Lomé', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AK-01', '2025-06-30 07:13:03', '2025-06-30 07:13:03'),
(7, NULL, 'Charif', 'AFOH TCHAOUTA', 'cafohtchaouta@arcop.tg', '123456782', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AC-01', '2025-06-30 07:15:50', '2025-06-30 07:15:50'),
(8, NULL, 'Yakouba Yawouvi', 'AGBAN', 'yagban@arcop.tg', '123456783', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AY-01', '2025-06-30 07:18:05', '2025-06-30 07:18:05'),
(9, NULL, 'Komi Mawusi', 'AKEY', 'kakey@arcop.tg', '123456784', 'MALE', 'Lomé', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AK-02', '2025-06-30 07:20:01', '2025-06-30 07:20:01'),
(10, NULL, 'Safouratou', 'ALAHO DJIMA', 'salaho@arcop.tg', '123456785', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AS-01', '2025-06-30 07:21:44', '2025-06-30 07:21:44'),
(11, NULL, 'Ami Séfako Sandra', 'ALAKPA', 'salakpa@arcop.tg', '123456786', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AA-03', '2025-06-30 07:23:47', '2025-06-30 07:23:47'),
(12, NULL, 'Elom Kwami', 'AZIADEKEY', 'eaziadekey@arcop.tg', '123456787', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AE-01', '2025-06-30 07:25:43', '2025-06-30 07:25:43'),
(13, NULL, 'Kpandjapou Fati', 'DATAGNI', 'fdatagni@arcop.tg', '123456788', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'DK-01', '2025-06-30 07:28:17', '2025-06-30 07:28:17'),
(14, NULL, 'Kodjovi', 'DEGBE', 'kdegbe@arcop.tg', '123456711', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'DK-02', '2025-06-30 07:30:54', '2025-06-30 07:30:54'),
(15, NULL, 'Amavi', 'DOUMASSI', 'adoumassi@arcop.tg', '123456712', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'DA-01', '2025-06-30 07:34:03', '2025-06-30 07:34:03'),
(16, NULL, 'Sorou', 'FADAZ', 'sfadaz@arcop.tg', '123456714', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'FS-01', '2025-06-30 07:39:20', '2025-06-30 07:39:20'),
(17, NULL, 'Moubatrak', 'FAFANA', 'mfafana@arcop.tg', '123456715', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'FM-01', '2025-06-30 07:40:47', '2025-06-30 07:40:47'),
(18, NULL, 'Combété', 'GBADJAVI', 'cgbadjavi@arcop.tg', '123456716', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'GC-01', '2025-06-30 07:42:53', '2025-06-30 07:42:53'),
(19, NULL, 'Komi Dodzi', 'GBOLOVI', 'dgbolovi@arcop.tg', '123456717', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'GK-01', '2025-06-30 07:44:33', '2025-06-30 07:44:33'),
(20, NULL, 'Amélé', 'GNONRONFOUN', 'agnonronfoun@arcop.tg', '123456718', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'GA-01', '2025-06-30 07:46:10', '2025-06-30 07:46:10'),
(21, NULL, 'Messan', 'HILLAH', 'mhillah@arcop.tg', '123456721', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'HM-01', '2025-06-30 07:47:53', '2025-06-30 07:47:53'),
(22, NULL, 'Kounouho', 'HOMEFA', 'khomefa@arcop.tg', '123456722', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'HK-01', '2025-06-30 07:51:24', '2025-06-30 07:51:24'),
(23, NULL, 'Kouassi', 'HOUESSOU', 'khouessou@arcop.tg', '123456723', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'HK-02', '2025-06-30 07:52:51', '2025-06-30 07:52:51'),
(24, NULL, 'Batchanag', 'KATANGA', 'bkatanga@arcop.tg', '123456724', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'KB-01', '2025-06-30 07:54:46', '2025-06-30 07:54:46'),
(25, NULL, 'Lardja', 'KOMBATE', 'lkombate@arcop.tg', '123456725', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'KL-01', '2025-06-30 07:56:46', '2025-06-30 07:56:46'),
(26, NULL, 'Yopéde', 'KOMBATE-MANKA', 'ykombate@arcop.tg', '123456726', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'KY-01', '2025-06-30 07:58:23', '2025-06-30 07:58:23'),
(27, NULL, 'Yawavi', 'KOMLAN', 'ykomlan@arcop.tg', '123456727', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'KY-02', '2025-06-30 07:59:58', '2025-06-30 07:59:58'),
(28, NULL, 'Tchodjowiè Mandjabita', 'KPEMOUA', 'mkpemoua@arcop.tg', '123456728', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'KT-01', '2025-06-30 08:01:39', '2025-06-30 08:01:39'),
(29, NULL, 'Komlan Sofo', 'QUASHIE', 'kquashie@arcop.tg', '123456729', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'QK-01', '2025-06-30 08:03:11', '2025-06-30 08:03:11'),
(30, NULL, 'Hezouwe', 'TCHANDAOU', 'htchandaou@arcop.tg', '123456731', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'TH-01', '2025-06-30 08:05:59', '2025-06-30 08:05:59'),
(31, NULL, 'Tchamdja', 'TEGBA', 'ttegba@arcop.tg', '123456732', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'TT-01', '2025-06-30 08:07:15', '2025-06-30 08:07:15'),
(32, NULL, 'Wana', 'TIKPENTIYENA', 'etikpentiyena@arcop.tg', '123456733', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'TW-01', '2025-06-30 08:08:59', '2025-06-30 08:08:59'),
(33, NULL, 'Sinam Hippolyte', 'TOKI', 'stoki@arcop.tg', '123456735', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'TS-01', '2025-06-30 08:10:23', '2025-06-30 08:10:23'),
(34, NULL, 'Koffi', 'TRAINOU', 'ktrainou@arcop.tg', '123456736', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'TK-01', '2025-06-30 08:11:41', '2025-06-30 08:11:41'),
(35, NULL, 'Amivi Djigbodi', 'TREKU', 'atreku@arcop.tg', '123456739', 'FEMALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'TA-01', '2025-06-30 08:13:36', '2025-06-30 08:13:36'),
(38, NULL, 'Kossi Simon', 'ADIGNON', 'peteradignon1@gmail.com', '70621370', 'MALE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVATED', 'AK-03', '2025-08-24 23:32:55', '2025-08-24 23:32:55');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `stage` enum('PENDING','APPROVED','REJECTED','CANCELLED','IN_PROGRESS','COMPLETED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `duty_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

CREATE TABLE `files` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` blob,
  `upload_date` date DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `employee_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `goals`
--

CREATE TABLE `goals` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `self_mark` int DEFAULT NULL,
  `chief_mark` int DEFAULT NULL,
  `mark` int DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `evaluation_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Togo',
  `is_public_holiday` tinyint(1) NOT NULL DEFAULT '1',
  `is_religious` tinyint(1) NOT NULL DEFAULT '0',
  `religion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_fixed` tinyint(1) NOT NULL DEFAULT '1',
  `day_of_week` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurrence_rule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `date`, `country`, `is_public_holiday`, `is_religious`, `religion`, `is_fixed`, `day_of_week`, `recurrence_rule`, `created_at`, `updated_at`) VALUES
(1, 'Jour de l\'An', '2024-01-01', 'Togo', 1, 0, NULL, 1, NULL, NULL, '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(2, 'Fête de l\'Indépendance', '2024-04-27', 'Togo', 1, 0, NULL, 1, NULL, NULL, '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(3, 'Fête du Travail', '2024-05-01', 'Togo', 1, 0, NULL, 1, NULL, NULL, '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(4, 'Aïd el-Fitr', '2024-04-10', 'Togo', 1, 1, 'Islam', 0, NULL, NULL, '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(5, 'Aïd el-Kebir', '2024-06-28', 'Togo', 1, 1, 'Islam', 0, NULL, NULL, '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(6, 'Noël', '2024-12-25', 'Togo', 1, 1, 'Christianisme', 1, NULL, NULL, '2025-06-10 10:14:09', '2025-06-10 10:14:09');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `n_plus_one_job_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `department_id`, `status`, `n_plus_one_job_id`, `created_at`, `updated_at`) VALUES
(1, 'DG', 'Directeur Général p.i.', 1, 'ACTIVATED', NULL, '2025-06-10 10:14:10', '2025-06-10 10:14:10'),
(6, 'Directeur DIE', 'Directeur Direction Des Investigations Et Enquêtes', 3, 'ACTIVATED', 1, '2025-06-10 10:30:23', '2025-06-10 11:00:39'),
(7, 'Directeur DFAT', 'Directeur Direction De La Formation Et Des Appuis Techniques', 4, 'ACTIVATED', 1, '2025-06-10 10:30:40', '2025-06-10 10:59:44'),
(8, 'Directeur DRAJ', 'Directeur Direction De La Règlementation Et Des Affaires Juridiques', 5, 'ACTIVATED', 1, '2025-06-10 10:31:29', '2025-06-10 10:53:05'),
(9, 'Directeur DSDSE', 'Directeur Direction Des Statistiques Et De La Documentation', 6, 'ACTIVATED', 1, '2025-06-10 10:31:53', '2025-06-10 10:48:56'),
(10, 'Directeur DCRP', 'Directeur Direction De La Communication Et Des Relations Publiques', 7, 'ACTIVATED', 1, '2025-06-10 10:32:21', '2025-06-10 10:43:35'),
(11, 'Assistant en communication', 'Assistant en communication', 7, 'ACTIVATED', 10, '2025-06-10 10:43:21', '2025-06-10 10:54:21'),
(12, 'Chargé de protocole', 'Chargé de protocole', 7, 'ACTIVATED', 10, '2025-06-10 10:43:57', '2025-06-10 10:43:57'),
(13, 'Assistante du DSD SE', 'Assistante du DSD SE', 6, 'ACTIVATED', 9, '2025-06-10 10:49:36', '2025-06-10 10:54:39'),
(14, 'Chef Division', 'Chef Division Informatique et documentation', 6, 'ACTIVATED', 9, '2025-06-10 10:49:51', '2025-06-10 10:49:51'),
(15, 'Chargé de statistiques et du suivi évalation', 'Chargé de statistiques et du suivi évalation', 6, 'ACTIVATED', 9, '2025-06-10 10:50:17', '2025-06-10 10:50:17'),
(16, 'Chargé des archives', 'Chargé des archives', 6, 'ACTIVATED', 9, '2025-06-10 10:50:31', '2025-06-10 10:50:31'),
(17, 'Chef division études et réglementation', 'Chef division études et réglementation', 5, 'ACTIVATED', 8, '2025-06-10 10:53:22', '2025-06-30 07:38:02'),
(18, 'Assistante du DRAJ', 'Assistante du DRAJ', 5, 'ACTIVATED', 8, '2025-06-10 10:53:52', '2025-06-10 10:53:52'),
(19, 'Chargé de la formation', 'Chargé de la formation', 4, 'ACTIVATED', 7, '2025-06-10 10:59:56', '2025-06-10 10:59:56'),
(20, 'Chargé des appuis techniques', 'Chargé des appuis techniques', 4, 'ACTIVATED', 7, '2025-06-10 11:00:09', '2025-06-10 11:00:09'),
(21, 'Juriste (DIE)', 'Juriste (DIE)', 3, 'ACTIVATED', 6, '2025-06-10 11:01:40', '2025-06-10 11:01:40'),
(22, 'Assistante du DIE', 'Assistante du DIE', 3, 'ACTIVATED', 6, '2025-06-10 11:01:50', '2025-06-10 11:09:34'),
(23, 'Assistante du DG', 'Assistante du DG', 1, 'ACTIVATED', 1, '2025-06-10 11:04:53', '2025-06-10 11:04:53'),
(24, 'Chauffeur du DG', 'Chauffeur du DG', 1, 'ACTIVATED', 1, '2025-06-10 11:05:11', '2025-06-10 11:05:11'),
(31, 'Directeur·trice DSAF', 'Directeur·trice Direction Des Services Administratif Et Financier', 8, 'ACTIVATED', 1, '2025-06-30 06:59:27', '2025-06-30 06:59:27'),
(32, 'Chef division comptabilité', 'Chef division comptabilité', 8, 'ACTIVATED', 31, '2025-06-30 07:01:57', '2025-06-30 07:01:57'),
(33, 'Comptable', 'Comptable', 8, 'ACTIVATED', 31, '2025-06-30 07:05:12', '2025-06-30 07:05:12'),
(34, 'Chef division finances', 'Chef division finances', 8, 'ACTIVATED', 31, '2025-06-30 07:05:47', '2025-06-30 07:05:47'),
(35, 'Chef division RH et Services généraux', 'Chef division RH et Services généraux', 8, 'ACTIVATED', 31, '2025-06-30 07:06:30', '2025-06-30 07:06:30'),
(36, 'Assistante du DSAF', 'Assistante du DSAF', 8, 'ACTIVATED', 31, '2025-06-30 07:06:52', '2025-06-30 07:06:52'),
(37, 'Secrétaire-standardiste', 'Secrétaire-standardiste', 8, 'ACTIVATED', 31, '2025-06-30 07:07:25', '2025-06-30 07:07:25'),
(38, 'Chef parc', 'Chef parc', 8, 'ACTIVATED', 35, '2025-06-30 07:08:09', '2025-06-30 07:08:55'),
(39, 'Agent de liaison', 'Agent de liaison', 8, 'ACTIVATED', 35, '2025-06-30 07:08:28', '2025-06-30 07:08:28'),
(40, 'Chauffeur', 'Chauffeur', 8, 'ACTIVATED', 35, '2025-06-30 07:08:42', '2025-06-30 07:10:10'),
(41, 'stagiaire', 'stagiaire', 6, 'ACTIVATED', 41, '2025-08-24 22:50:46', '2025-08-24 23:22:38'),
(42, 'nnnn', 'nnnnn', 6, 'ACTIVATED', 42, '2025-08-24 23:23:55', '2025-08-24 23:24:06');

-- --------------------------------------------------------

--
-- Structure de la table `laravel_jobs`
--

CREATE TABLE `laravel_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `laravel_jobs`
--

INSERT INTO `laravel_jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"2d1c40bf-a4df-4bb2-88c9-6a683fa4c758\",\"displayName\":\"App\\\\Jobs\\\\CleanupActivityLogsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\CleanupActivityLogsJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\CleanupActivityLogsJob\\\":1:{s:7:\\\"\\u0000*\\u0000days\\\";i:90;}\"}}', 0, NULL, 1756076388, 1756076388),
(2, 'default', '{\"uuid\":\"cdb3843f-d6bd-4198-8e8f-8e760a951566\",\"displayName\":\"App\\\\Jobs\\\\CleanupActivityLogsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\CleanupActivityLogsJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\CleanupActivityLogsJob\\\":1:{s:7:\\\"\\u0000*\\u0000days\\\";i:90;}\"}}', 0, NULL, 1756077190, 1756077190),
(3, 'default', '{\"uuid\":\"123cd5d4-aec6-4131-93c5-c976cd2a20b1\",\"displayName\":\"App\\\\Jobs\\\\CleanupActivityLogsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\CleanupActivityLogsJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\CleanupActivityLogsJob\\\":1:{s:7:\\\"\\u0000*\\u0000days\\\";i:90;}\"}}', 0, NULL, 1756077287, 1756077287),
(4, 'default', '{\"uuid\":\"e42de78f-e159-4596-89ef-a3106ec14c4d\",\"displayName\":\"App\\\\Mail\\\\AbsenceRequestCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AbsenceRequestCreated\\\":4:{s:40:\\\"\\u0000App\\\\Mail\\\\AbsenceRequestCreated\\u0000receiver\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:39:\\\"\\u0000App\\\\Mail\\\\AbsenceRequestCreated\\u0000absence\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\OptiHr\\\\Absence\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:6:{i:0;s:4:\\\"duty\\\";i:1;s:8:\\\"duty.job\\\";i:2;s:23:\\\"duty.job.n_plus_one_job\\\";i:3;s:30:\\\"duty.job.n_plus_one_job.duties\\\";i:4;s:39:\\\"duty.job.n_plus_one_job.duties.employee\\\";i:5;s:45:\\\"duty.job.n_plus_one_job.duties.employee.users\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:35:\\\"\\u0000App\\\\Mail\\\\AbsenceRequestCreated\\u0000url\\\";s:75:\\\"http:\\/\\/10.115.10.228:8088\\/opti-hr\\/attendances\\/absences\\/requests\\/IN_PROGRESS\\\";s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1756806964, 1756806964),
(5, 'default', '{\"uuid\":\"fcfaad9d-3d58-4005-9f34-b193f7419738\",\"displayName\":\"App\\\\Mail\\\\AbsenceRequestCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AbsenceRequestCreated\\\":4:{s:40:\\\"\\u0000App\\\\Mail\\\\AbsenceRequestCreated\\u0000receiver\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:39:\\\"\\u0000App\\\\Mail\\\\AbsenceRequestCreated\\u0000absence\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\OptiHr\\\\Absence\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:6:{i:0;s:4:\\\"duty\\\";i:1;s:8:\\\"duty.job\\\";i:2;s:23:\\\"duty.job.n_plus_one_job\\\";i:3;s:30:\\\"duty.job.n_plus_one_job.duties\\\";i:4;s:39:\\\"duty.job.n_plus_one_job.duties.employee\\\";i:5;s:45:\\\"duty.job.n_plus_one_job.duties.employee.users\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:35:\\\"\\u0000App\\\\Mail\\\\AbsenceRequestCreated\\u0000url\\\";s:75:\\\"http:\\/\\/10.115.10.228:8088\\/opti-hr\\/attendances\\/absences\\/requests\\/IN_PROGRESS\\\";s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1756807763, 1756807763);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0000_00_00_000000_create_employees_table', 1),
(2, '101_create_personnals_table', 1),
(3, '102_create_dacs_table', 1),
(4, '103_create_decisions_table', 1),
(5, '104_create_applicants_table', 1),
(6, '105_create_authorities_table', 1),
(7, '106_create_appeals_table', 1),
(8, '107_create_comments_table', 1),
(9, '2014_10_12_000000_create_users_table', 1),
(10, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(11, '2019_08_19_000000_create_failed_jobs_table', 1),
(12, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(13, '2024_03_11_133816_create_permission_table', 1),
(14, '2024_12_16_000001_create_departments_table', 1),
(15, '2024_12_16_000002_create_jobs_table', 1),
(16, '2024_12_16_000003_create_duties_table', 1),
(17, '2024_12_16_000004_create_files_table', 1),
(18, '2024_12_16_000005_create_absence_types_table', 1),
(19, '2024_12_16_000006_create_trainings_table', 1),
(20, '2024_12_16_000007_create_absences_table', 1),
(21, '2024_12_16_000008_create_evaluations_table', 1),
(22, '2024_12_16_000009_create_goals_table', 1),
(23, '2024_12_16_000010_create_reports_table', 1),
(24, '2024_12_19_172856_create_holidays_table', 1),
(25, '2025_02_05_084926_create_document_types_table', 1),
(26, '2025_02_05_085316_create_document_requests_table', 1),
(27, '2025_02_24_074423_create_publications_table', 1),
(28, '2025_02_25_095349_create_publication_files_table', 1),
(29, '2025_03_22_101343_create_annual_decisions_table', 1),
(30, '2025_03_22_102605_create_activity_logs_table', 1),
(31, '2025_03_27_122543_create_laravel_jobs_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 13),
(5, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 23);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('cafohtchaouta@arcop.tg', '$2y$12$uVfDvL1.6F2IEUxES1S2duSa7gyZi9zhiZSqhS2rn7jLWKxnN495q', '2025-09-02 09:45:53'),
('cgbadjavi@arcop.tg', '$2y$12$vNd4kcXoGKxp0bAOlS.sn.tey4XPsXtA5crf8Sx0ywYoaI8Z4A4LG', '2025-08-28 16:23:32'),
('fdatagni@arcop.tg', '$2y$12$JEV5NMkKBKyiPgH9YmeFLufMfHnwBF2TYteN3Z8jtlatZZDaiNhje', '2025-09-02 10:05:58'),
('kakey@arcop.tg', '$2y$12$UFrWzZFEbQpzewKigQ3zqevbRO4jxMpxdsyrmDSXxnrbAoZb4iLiy', '2025-08-24 23:16:00'),
('ktrainou@arcop.tg', '$2y$12$Cl/ztzQk3Chvy4YFQi8gVe/Ev/JDTqyLnXg.2zBOKRpoGa7SEzTgC', '2025-10-08 12:30:35'),
('peteradignon1@gmail.com', '$2y$12$Nx5w7LAi.wWC8ixHy.in4.pGj0HJIhLQKFWpB.9yI.s78P2ZiFRWq', '2025-10-09 16:28:22'),
('ttegba@arcop.tg', '$2y$12$50.RDBBnd.Gw7VbmR8D44.Yz43FLVmfUIC7mAop3TG7cRSvcE0H2C', '2025-10-08 11:58:20');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'send-paie', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(2, 'appeal-actions', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(3, 'voir-un-compte', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(4, 'écrire-un-compte', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(5, 'créer-un-compte', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(6, 'configurer-un-compte', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(7, 'voir-un-employee', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(8, 'écrire-un-employee', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(9, 'créer-un-employee', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(10, 'configurer-un-employee', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(11, 'voir-une-attendance', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(12, 'écrire-une-attendance', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(13, 'créer-une-attendance', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(14, 'configurer-une-attendance', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(15, 'voir-un-all', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(16, 'écrire-un-all', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(17, 'créer-un-all', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(18, 'configurer-un-all', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(19, 'voir-une-absence', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(20, 'écrire-une-absence', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(21, 'créer-une-absence', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(22, 'configurer-une-absence', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(23, 'voir-un-document', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(24, 'écrire-un-document', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(25, 'créer-un-document', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(26, 'configurer-un-document', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(27, 'voir-une-publication', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(28, 'écrire-une-publication', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(29, 'créer-une-publication', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(30, 'configurer-une-publication', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(31, 'voir-un-credentials', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(32, 'écrire-un-credentials', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(33, 'créer-un-credentials', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(34, 'configurer-un-credentials', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(35, 'voir-un-role', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(36, 'écrire-un-role', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(37, 'créer-un-role', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(38, 'configurer-un-role', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(39, 'voir-un-férié', 'web', '2025-06-10 10:14:08', '2025-06-10 10:14:08'),
(40, 'écrire-un-férié', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(41, 'créer-un-férié', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(42, 'configurer-un-férié', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(43, 'voir-un-journal', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(44, 'écrire-un-journal', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(45, 'créer-un-journal', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(46, 'configurer-un-journal', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(47, 'access-un-opti-hr', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(48, 'access-un-recours', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(49, 'access-un-all', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnals`
--

CREATE TABLE `personnals` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `sexe` enum('MAN','WOMAN') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'MAN',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `publications`
--

CREATE TABLE `publications` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NOT NULL DEFAULT '2025-06-10 10:14:07',
  `status` enum('pending','draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `author_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `publication_files`
--

CREATE TABLE `publication_files` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` blob,
  `upload_date` date DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `publication_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `salary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_labor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `duty_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(2, 'GRH', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(3, 'DG', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(4, 'EMPLOYEE', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(5, 'DSAF', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(6, 'standart', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(7, 'DRAJ', 'web', '2025-06-10 10:14:09', '2025-06-10 10:14:09');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(1, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(19, 3),
(20, 3),
(21, 3),
(23, 3),
(24, 3),
(25, 3),
(27, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(49, 3),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(11, 4),
(19, 4),
(21, 4),
(23, 4),
(25, 4),
(27, 4),
(32, 4),
(39, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(47, 4),
(3, 5),
(4, 5),
(7, 5),
(11, 5),
(12, 5),
(13, 5),
(19, 5),
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(25, 5),
(26, 5),
(27, 5),
(47, 5),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(11, 6),
(19, 6),
(21, 6),
(23, 6),
(25, 6),
(27, 6),
(32, 6),
(39, 6),
(43, 6),
(44, 6),
(45, 6),
(46, 6),
(49, 6),
(2, 7),
(3, 7),
(4, 7),
(5, 7),
(6, 7),
(11, 7),
(19, 7),
(21, 7),
(23, 7),
(25, 7),
(27, 7),
(32, 7),
(39, 7),
(43, 7),
(44, 7),
(45, 7),
(46, 7),
(49, 7);

-- --------------------------------------------------------

--
-- Structure de la table `trainings`
--

CREATE TABLE `trainings` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `problematic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills_to_acquire` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `training_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indicators_of_success` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `execution_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `implementation_period` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_implementation_period` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `superior_observation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVATED','DEACTIVATED','PENDING','DELETED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `duty_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assets/images/profile_av.png',
  `profile` enum('EMPLOYEE','ADMIN') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EMPLOYEE',
  `status` enum('ACTIVATED','DEACTIVATED','DELETED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVATED',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$2y$12$imaEm0dF3HRacjsGQc5FMOUv/JKGc/9rigbYCNiQwYAvDMWxeBx2u',
  `employee_id` bigint UNSIGNED NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `picture`, `profile`, `status`, `email`, `email_verified_at`, `password`, `employee_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'assets/images/profile_av.png', 'ADMIN', 'ACTIVATED', 'admin@admin.com', NULL, '$2y$12$QgMFg0ytT7muy/H6MLj0HO.U9pwGs.RGVulJRIIPreY3oOpyE/KwS', 1, NULL, '2025-06-10 10:14:09', '2025-06-10 10:14:09'),
(2, 'optirh_manager', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'optirh@arcop.tg', NULL, '$2y$12$UENuS1bbkd/AzrmnAOOxHu40pYVY.PC/sw31HIFWGyUpV0aDo071q', 2, NULL, '2025-06-10 10:14:09', '2025-06-30 08:20:33'),
(6, 'kakey9', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'kakey@arcop.tg', NULL, '$2y$12$ippNa0SXiV7bNAhcUFI5VO6erVd5MOLrwHuurZm6r8jZRL6i92i9C', 9, NULL, '2025-08-24 23:16:00', '2025-08-24 23:16:42'),
(7, 'cgbadjavi18', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'cgbadjavi@arcop.tg', NULL, '$2y$12$k/0GvDLeVbuZePxOW2NqQOhbfQOdWY.Cx4e.2zbiI/q7QzBtEVZ8G', 18, NULL, '2025-08-28 16:23:32', '2025-08-28 16:24:44'),
(8, 'cafoh tchaouta7', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'cafohtchaouta@arcop.tg', NULL, '$2y$12$.N9RoPwhuegVfab2s50r6.FpJrreLhkLA1.PNUMzoeZRILQ9T571m', 7, NULL, '2025-09-02 09:45:53', '2025-09-02 09:46:42'),
(10, 'kdatagni13', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'fdatagni@arcop.tg', NULL, '$2y$12$pLYjFsFiDpaPVznk7PGpee.D9eNlhbogyk23pRuqaUfIOB5IzlMXK', 13, NULL, '2025-09-02 10:05:57', '2025-09-02 10:07:02'),
(13, 'ttegba31', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'ttegba@arcop.tg', NULL, '$2y$12$ffDbaHQIbjQY3aTQOwgSVu1QY5usC06ReSmFc4k./w/R3Zm/p6Rs2', 31, NULL, '2025-10-08 11:58:20', '2025-10-08 11:58:20'),
(16, 'ktrainou34', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'ktrainou@arcop.tg', NULL, '$2y$12$3yZLYU74IUbDT.50ijRFm.VbvdJ.RtSjWLqksiBmFFcnRgGS7GmBG', 34, NULL, '2025-10-08 12:30:35', '2025-10-08 12:30:35'),
(23, 'kadignon38', 'assets/images/profile_av.png', 'EMPLOYEE', 'ACTIVATED', 'peteradignon1@gmail.com', NULL, '$2y$12$qeuLqizaP.FVbEkLPmKAEOGSyZ57YwXp5JY047psIajeIIv9vlSeC', 38, NULL, '2025-10-09 16:28:22', '2025-10-09 16:28:22');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absences_duty_id_foreign` (`duty_id`),
  ADD KEY `absences_absence_type_id_foreign` (`absence_type_id`);

--
-- Index pour la table `absence_types`
--
ALTER TABLE `absence_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Index pour la table `annual_decisions`
--
ALTER TABLE `annual_decisions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `appeals`
--
ALTER TABLE `appeals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appeals_dac_id_foreign` (`dac_id`),
  ADD KEY `appeals_applicant_id_foreign` (`applicant_id`),
  ADD KEY `appeals_created_by_foreign` (`created_by`),
  ADD KEY `appeals_decided_id_foreign` (`decided_id`),
  ADD KEY `appeals_suspended_id_foreign` (`suspended_id`),
  ADD KEY `appeals_last_updated_by_foreign` (`last_updated_by`);

--
-- Index pour la table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applicants_nif_unique` (`nif`),
  ADD KEY `applicants_created_by_foreign` (`created_by`),
  ADD KEY `applicants_last_updated_by_foreign` (`last_updated_by`);

--
-- Index pour la table `authorities`
--
ALTER TABLE `authorities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `authorities_created_by_foreign` (`created_by`),
  ADD KEY `authorities_last_updated_by_foreign` (`last_updated_by`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_appeal_id_foreign` (`appeal_id`),
  ADD KEY `comments_personnal_id_foreign` (`personnal_id`),
  ADD KEY `comments_last_updated_by_foreign` (`last_updated_by`);

--
-- Index pour la table `dacs`
--
ALTER TABLE `dacs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dacs_created_by_foreign` (`created_by`),
  ADD KEY `dacs_last_updated_by_foreign` (`last_updated_by`);

--
-- Index pour la table `decisions`
--
ALTER TABLE `decisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `decisions_created_by_foreign` (`created_by`),
  ADD KEY `decisions_last_updated_by_foreign` (`last_updated_by`);

--
-- Index pour la table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD UNIQUE KEY `departments_description_unique` (`description`),
  ADD KEY `departments_director_id_foreign` (`director_id`);

--
-- Index pour la table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_requests_duty_id_foreign` (`duty_id`),
  ADD KEY `document_requests_document_type_id_foreign` (`document_type_id`);

--
-- Index pour la table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `duties`
--
ALTER TABLE `duties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `duties_job_id_foreign` (`job_id`),
  ADD KEY `duties_employee_id_foreign` (`employee_id`);

--
-- Index pour la table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD UNIQUE KEY `employees_phone_number_unique` (`phone_number`),
  ADD UNIQUE KEY `employees_matricule_unique` (`matricule`),
  ADD UNIQUE KEY `employees_rib_unique` (`rib`),
  ADD UNIQUE KEY `employees_iban_unique` (`iban`),
  ADD UNIQUE KEY `employees_code_unique` (`code`),
  ADD KEY `employees_email_phone_number_matricule_index` (`email`,`phone_number`,`matricule`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_duty_id_foreign` (`duty_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_employee_id_foreign` (`employee_id`);

--
-- Index pour la table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goals_evaluation_id_foreign` (`evaluation_id`);

--
-- Index pour la table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holidays_name_unique` (`name`),
  ADD UNIQUE KEY `holidays_date_unique` (`date`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jobs_title_unique` (`title`),
  ADD KEY `jobs_department_id_foreign` (`department_id`),
  ADD KEY `jobs_n_plus_one_job_id_foreign` (`n_plus_one_job_id`);

--
-- Index pour la table `laravel_jobs`
--
ALTER TABLE `laravel_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laravel_jobs_queue_index` (`queue`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `personnals`
--
ALTER TABLE `personnals`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publications_author_id_foreign` (`author_id`);

--
-- Index pour la table `publication_files`
--
ALTER TABLE `publication_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publication_files_publication_id_foreign` (`publication_id`);

--
-- Index pour la table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_duty_id_foreign` (`duty_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `absence_types`
--
ALTER TABLE `absence_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT pour la table `annual_decisions`
--
ALTER TABLE `annual_decisions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `appeals`
--
ALTER TABLE `appeals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `authorities`
--
ALTER TABLE `authorities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dacs`
--
ALTER TABLE `dacs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `decisions`
--
ALTER TABLE `decisions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `duties`
--
ALTER TABLE `duties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `laravel_jobs`
--
ALTER TABLE `laravel_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personnals`
--
ALTER TABLE `personnals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `publications`
--
ALTER TABLE `publications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `publication_files`
--
ALTER TABLE `publication_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_absence_type_id_foreign` FOREIGN KEY (`absence_type_id`) REFERENCES `absence_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `absences_duty_id_foreign` FOREIGN KEY (`duty_id`) REFERENCES `duties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `appeals`
--
ALTER TABLE `appeals`
  ADD CONSTRAINT `appeals_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appeals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appeals_dac_id_foreign` FOREIGN KEY (`dac_id`) REFERENCES `dacs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appeals_decided_id_foreign` FOREIGN KEY (`decided_id`) REFERENCES `decisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appeals_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appeals_suspended_id_foreign` FOREIGN KEY (`suspended_id`) REFERENCES `decisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applicants_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `authorities`
--
ALTER TABLE `authorities`
  ADD CONSTRAINT `authorities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authorities_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_appeal_id_foreign` FOREIGN KEY (`appeal_id`) REFERENCES `appeals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_personnal_id_foreign` FOREIGN KEY (`personnal_id`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dacs`
--
ALTER TABLE `dacs`
  ADD CONSTRAINT `dacs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dacs_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `decisions`
--
ALTER TABLE `decisions`
  ADD CONSTRAINT `decisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decisions_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `personnals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_director_id_foreign` FOREIGN KEY (`director_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `document_requests_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `document_requests_duty_id_foreign` FOREIGN KEY (`duty_id`) REFERENCES `duties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `duties`
--
ALTER TABLE `duties`
  ADD CONSTRAINT `duties_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `duties_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_duty_id_foreign` FOREIGN KEY (`duty_id`) REFERENCES `duties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_evaluation_id_foreign` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_n_plus_one_job_id_foreign` FOREIGN KEY (`n_plus_one_job_id`) REFERENCES `jobs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `publications`
--
ALTER TABLE `publications`
  ADD CONSTRAINT `publications_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `publication_files`
--
ALTER TABLE `publication_files`
  ADD CONSTRAINT `publication_files_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_duty_id_foreign` FOREIGN KEY (`duty_id`) REFERENCES `duties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
