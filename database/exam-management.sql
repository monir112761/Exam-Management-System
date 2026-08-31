-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 25, 2026 at 03:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam-management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Sahil', 'sahil@gmail.com', '123456', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `title`, `description`, `duration`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Laravel Fundamentals Test', 'This exam covers Laravel basics including routing, migrations, controllers, models, Blade templates, and Eloquent ORM.', 10, 1, '2026-06-24 01:24:34', '2026-06-25 06:19:13'),
(2, 'PHP Programming Quiz', 'This exam tests PHP fundamentals such as variables, loops, arrays, functions, forms, and sessions.', 10, 1, '2026-06-24 01:25:03', '2026-06-25 06:19:23'),
(3, 'Web Development Assessment', 'MCQ test covering HTML, CSS, JavaScript, Bootstrap, PHP, and Laravel concepts.', 10, 1, '2026-06-24 01:25:20', '2026-06-25 07:40:14'),
(4, 'Full Stack Developer Test', 'Assessment for front-end and back-end development skills including Laravel framework.', 10, 0, '2026-06-24 01:25:42', '2026-06-24 06:00:37');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_24_052602_admin', 2),
(5, '2026_06_24_054614_users', 3),
(6, '2026_06_24_060336_question', 4),
(7, '2026_06_24_063902_exams', 5),
(8, '2026_06_24_064711_question', 5),
(9, '2026_06_24_090157_results', 6),
(10, '2026_06_25_063803_user_answer', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `created_at`, `updated_at`) VALUES
(2, 1, 'Which file is used to define web routes in Laravel?', 'api.php', 'web.php', 'app.php', 'config.php', 'B', '2026-06-24 01:32:22', '2026-06-24 01:32:22'),
(3, 1, 'Which Artisan command creates a controller?', 'php artisan make:model', 'php artisan make:controller', 'php artisan controller:create', 'php artisan new', 'B', '2026-06-24 01:32:54', '2026-06-24 01:32:54'),
(4, 1, 'Which command runs database migrations?', 'php artisan migrate', 'php artisan serve', 'php artisan db', 'php artisan migrate:refresh', 'A', '2026-06-24 01:34:30', '2026-06-24 01:34:30'),
(5, 1, 'Which ORM does Laravel use?', 'Doctrine', 'Hibernate', 'Eloquent', 'Sequelize', 'C', '2026-06-24 01:35:01', '2026-06-24 01:35:01'),
(6, 1, 'Which Blade syntax is used to print a variable?', '<?php ?>', '{!! !!}', '{{ }}', '[[ ]]', 'C', '2026-06-24 01:35:47', '2026-06-24 01:35:47'),
(7, 1, 'Which command creates a migration?', 'php artisan make:model', 'php artisan make:migration', 'php artisan migrate', 'php artisan db:create', 'B', '2026-06-24 01:36:23', '2026-06-24 01:36:23'),
(8, 1, 'Which folder stores Blade templates?', 'resources/views', 'public/views', 'app/views', 'storage/views', 'A', '2026-06-24 01:36:58', '2026-06-24 01:36:58'),
(9, 1, 'Which helper is used to generate URLs?', 'route()', 'redirect()', 'url()', '. Both A and C', 'D', '2026-06-24 01:37:38', '2026-06-24 01:37:38'),
(10, 1, 'Which file stores database connection settings?', 'config/database.php', 'database.php', '.htaccess', 'app.php', 'A', '2026-06-24 01:38:15', '2026-06-24 04:50:25'),
(14, 2, 'What does PHP stand for?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Home Page', 'Programming Home Page', 'B', '2026-06-25 05:10:49', '2026-06-25 05:10:49'),
(15, 2, 'Which symbol is used to declare a variable in PHP?', '@', '#', '$', '&', 'C', '2026-06-25 05:11:27', '2026-06-25 05:11:27'),
(16, 2, 'Which function is used to print output in PHP?', 'echo', 'print_data', 'show', 'display', 'A', '2026-06-25 05:12:00', '2026-06-25 05:12:00'),
(17, 2, 'Which file extension is used for PHP files?', '.html', '.php', '.js', '.css', 'B', '2026-06-25 05:12:30', '2026-06-25 05:12:30'),
(18, 2, 'Which method is used to send form data securely?', 'GET', 'POST', 'PUT', 'VIEW', 'B', '2026-06-25 05:13:08', '2026-06-25 05:13:13'),
(19, 2, 'Which PHP function is used to count array elements?', 'count()', 'length()', 'size()', 'total()', 'A', '2026-06-25 05:13:56', '2026-06-25 05:13:56'),
(20, 2, 'Which operator is used for concatenation in PHP?', '+', '.', '&', '*', 'B', '2026-06-25 05:14:34', '2026-06-25 05:14:34'),
(21, 2, 'Which superglobal is used to collect form data sent by POST?', '$_GET', '$_POST', '$_SESSION', '$_COOKIE', 'B', '2026-06-25 05:15:04', '2026-06-25 05:15:04'),
(22, 2, 'Which statement is used to include another PHP file?', 'import', 'require', 'attach', 'load', 'B', '2026-06-25 05:15:31', '2026-06-25 05:15:31'),
(23, 2, 'Which function is used to start a session in PHP?', 'start_session()', 'session_start()', 'begin_session()', 'session_begin()', 'B', '2026-06-25 05:15:57', '2026-06-25 05:15:57'),
(24, 3, 'What does HTML stand for?', 'Hyper Text Markup Language', 'High Text Machine Language', 'Hyper Tool Markup Language', 'Home Text Markup Language', 'A', '2026-06-25 05:38:11', '2026-06-25 05:38:11'),
(25, 3, 'Which language is used for styling web pages?', 'HTML', 'CSS', 'PHP', 'SQL', 'B', '2026-06-25 05:39:03', '2026-06-25 05:39:03'),
(26, 3, 'Which tag is used to create a hyperlink in HTML?', '<link>', '<a>', '<href>', '<url>', 'B', '2026-06-25 05:39:32', '2026-06-25 05:39:32'),
(27, 3, 'Which CSS property is used to change text color?', 'font-color', 'text-color', 'color', 'background-color', 'C', '2026-06-25 05:40:03', '2026-06-25 05:40:03'),
(28, 3, 'Which language is used to make web pages interactive?', 'JavaScript', 'HTML', 'CSS', 'Bootstrap', 'A', '2026-06-25 05:40:29', '2026-06-25 05:40:29'),
(29, 3, 'Which HTML tag is used to display an image?', '<image>', '<img>', '<src>', '<pic>', 'B', '2026-06-25 05:40:53', '2026-06-25 05:40:53'),
(30, 3, 'Which method is commonly used to send form data securely?', 'GET', 'POST', 'VIEW', 'SEND', 'B', '2026-06-25 05:41:21', '2026-06-25 05:41:21'),
(31, 3, 'Which database query is used to get data from a table?', 'INSERT', 'UPDATE', 'SELECT', 'DELETE', 'C', '2026-06-25 05:41:48', '2026-06-25 05:41:48'),
(32, 3, 'Which framework is used for responsive design?', 'Laravel', 'Bootstrap', 'MySQL', 'PHP', 'B', '2026-06-25 05:42:16', '2026-06-25 05:42:16'),
(33, 3, 'Which file is used to write JavaScript code?', '.css', '.html', '.js', '.php', 'C', '2026-06-25 05:42:58', '2026-06-25 05:42:58'),
(35, 1, 'Which command starts the Laravel development server?', 'php artisan serve', 'php serve', 'php artisan start', 'artisan run', 'A', '2026-06-25 06:00:26', '2026-06-25 06:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `total_questions` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL,
  `wrong_answers` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `exam_id`, `total_questions`, `correct_answers`, `wrong_answers`, `score`, `created_at`, `updated_at`) VALUES
(33, 1, 2, 10, 8, 2, 8, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(34, 1, 3, 10, 8, 2, 8, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(35, 1, 1, 10, 7, 3, 7, '2026-06-25 06:02:34', '2026-06-25 06:02:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('qzA6bDJrYJsY3yVSLyh0oJftRUzWE129yQMHdO6v', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiMFJDQnRKb1V0RVN1WGoyV3ZCdEZWUWhqMFVHUFowNGpkZVRlbzJSYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czoxMDoidXNlci5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjc6InN1Y2Nlc3MiO31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjc6InVzZXJfaWQiO2k6MTtzOjk6InVzZXJfbmFtZSI7czo1OiJTYWhpbCI7czoxNDoidXNlcl9sb2dnZWRfaW4iO2I6MTtzOjc6InN1Y2Nlc3MiO3M6MzI6IkxvZ2luIHN1Y2Nlc3NmdWwuIFdlbGNvbWUgU2FoaWwhIjt9', 1782299391),
('XeW9aZG3U0GD54lYnHuNaZbB7zOLqHwoNwyRauvu', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoib2hKcWV2NEZuTVlNaWxOQjNIMnY4a0ZhdFZqeVVaTFNGOE9lZlM5YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leGFtL3N0YXJ0LzEiO3M6NToicm91dGUiO3M6MTA6ImV4YW0uc3RhcnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6ImFkbWluX2lkIjtpOjE7czoxMDoiYWRtaW5fbmFtZSI7czo1OiJTYWhpbCI7czoxNToiYWRtaW5fbG9nZ2VkX2luIjtiOjE7czo3OiJ1c2VyX2lkIjtpOjE7czo5OiJ1c2VyX25hbWUiO3M6NToiU2FoaWwiO3M6MTQ6InVzZXJfbG9nZ2VkX2luIjtiOjE7fQ==', 1782296835),
('XsUxc7Lt6tn8rntNJTOjaIy1OLP6guwPKtjYRuX6', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.0 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUpqdERuN1JtVUR5YlpENzRqdXRLM1pQZ25DM1haRHhzTGNkOWlzUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1782298670),
('zngq67CMMT8oGJRqRTmuZXgQuxcbtAGY1X4ZsiR5', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUFBiRENycVFySHdneDVPSUhZMlRkVkpuQXFSQ3ZUOG9SbFgxM29zMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9yZXN1bHRzIjtzOjU6InJvdXRlIjtzOjEzOiJhZG1pbi5yZXN1bHRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo4OiJhZG1pbl9pZCI7aToxO3M6MTA6ImFkbWluX25hbWUiO3M6NToiU2FoaWwiO3M6MTU6ImFkbWluX2xvZ2dlZF9pbiI7YjoxO30=', 1782298683);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Sahil', 'sahil@gmail.com', '6359950829', '$2y$12$LKdOKvVml/3pqjFyt/7u6OYlRL/0XSjg1lyec2T6HPteDwATvXu3a', '2026-06-24 00:25:15', '2026-06-25 04:05:51'),
(2, 'Dhruvi', 'dhruvi@gmail.com', '9465151516', '$2y$12$eyl7h8sLHZvUCINqJBas0ulcH2tD/I4MASEDiSnzoeQCTo6wxy8JC', '2026-06-24 04:11:19', '2026-06-24 04:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `result_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `user_answer` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `result_id`, `user_id`, `exam_id`, `question_id`, `user_answer`, `is_correct`, `created_at`, `updated_at`) VALUES
(199, 33, 1, 2, 14, 'B', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(200, 33, 1, 2, 15, 'C', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(201, 33, 1, 2, 16, 'A', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(202, 33, 1, 2, 17, 'B', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(203, 33, 1, 2, 18, 'B', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(204, 33, 1, 2, 19, 'A', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(205, 33, 1, 2, 20, 'B', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(206, 33, 1, 2, 21, 'B', 1, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(207, 33, 1, 2, 22, 'C', 0, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(208, 33, 1, 2, 23, 'Not Answered', 0, '2026-06-25 05:16:48', '2026-06-25 05:16:48'),
(209, 34, 1, 3, 24, 'A', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(210, 34, 1, 3, 25, 'B', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(211, 34, 1, 3, 26, 'B', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(212, 34, 1, 3, 27, 'C', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(213, 34, 1, 3, 28, 'A', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(214, 34, 1, 3, 29, 'B', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(215, 34, 1, 3, 30, 'D', 0, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(216, 34, 1, 3, 31, 'Not Answered', 0, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(217, 34, 1, 3, 32, 'B', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(218, 34, 1, 3, 33, 'C', 1, '2026-06-25 05:45:27', '2026-06-25 05:45:27'),
(219, 35, 1, 1, 2, 'B', 1, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(220, 35, 1, 1, 3, 'B', 1, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(221, 35, 1, 1, 4, 'A', 1, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(222, 35, 1, 1, 5, 'C', 1, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(223, 35, 1, 1, 6, 'Not Answered', 0, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(224, 35, 1, 1, 7, 'C', 0, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(225, 35, 1, 1, 8, 'D', 0, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(226, 35, 1, 1, 9, 'D', 1, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(227, 35, 1, 1, 10, 'A', 1, '2026-06-25 06:02:34', '2026-06-25 06:02:34'),
(228, 35, 1, 1, 35, 'A', 1, '2026-06-25 06:02:34', '2026-06-25 06:02:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_email_unique` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_number_unique` (`number`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_answers_result_id_foreign` (`result_id`),
  ADD KEY `user_answers_user_id_foreign` (`user_id`),
  ADD KEY `user_answers_exam_id_foreign` (`exam_id`),
  ADD KEY `user_answers_question_id_foreign` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_result_id_foreign` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
