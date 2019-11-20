-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2018 at 09:07 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobishop`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `name`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'View', 'View', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Create', 'Create', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Update', 'Update', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Delete', 'Delete', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Lock', 'Lock', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Download', 'Download', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Change password', 'Change password', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Password reset', 'Password reset', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Print', 'Print', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Commit', 'Commit', 0, 0, '0000-00-00 00:00:00', '2017-11-13 08:34:12'),
(11, 'Activate', 'Activate', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Approve', 'Approve', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Decline', 'Decline', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Amend', 'Amend', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Details', 'Details', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Role Management', 'Role Management', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Role Access Control', 'Role Access Control', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'User Management', 'User Management', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'User Access Control', 'User Access Control', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Module Management', 'Module Management', 0, 0, '2017-11-13 07:30:40', '2017-11-13 07:30:40'),
(8, 'Activity Management', 'Activity Management', 0, 0, '2017-11-13 07:32:33', '2017-11-13 07:32:33'),
(9, 'Settings', 'Settings', 0, 0, '2017-11-13 09:37:10', '2017-11-13 09:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `module_to_activity`
--

CREATE TABLE `module_to_activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `module_id` int(11) UNSIGNED NOT NULL,
  `activity_id` int(11) UNSIGNED NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `module_to_activity`
--

INSERT INTO `module_to_activity` (`id`, `module_id`, `activity_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 55, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 2, 55, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 1, 3, 55, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 4, 55, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 3, 1, 55, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 3, 2, 55, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 3, 3, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 3, 4, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 4, 1, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 5, 1, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 7, 1, 0, 0, '2017-11-13 07:30:40', '2017-11-13 07:30:40'),
(18, 7, 2, 0, 0, '2017-11-13 07:30:40', '2017-11-13 07:30:40'),
(19, 7, 3, 0, 0, '2017-11-13 07:30:40', '2017-11-13 07:30:40'),
(20, 7, 4, 0, 0, '2017-11-13 07:30:40', '2017-11-13 07:30:40'),
(21, 7, 5, 0, 0, '2017-11-13 07:30:40', '2017-11-13 07:30:40'),
(89, 2, 1, 0, 0, '2017-11-13 08:38:47', '2017-11-13 08:38:47'),
(90, 2, 2, 0, 0, '2017-11-13 08:38:47', '2017-11-13 08:38:47'),
(91, 2, 3, 0, 0, '2017-11-13 08:38:47', '2017-11-13 08:38:47'),
(92, 2, 4, 0, 0, '2017-11-13 08:38:47', '2017-11-13 08:38:47'),
(107, 8, 1, 0, 0, '2017-11-13 09:15:30', '2017-11-13 09:15:30'),
(108, 8, 2, 0, 0, '2017-11-13 09:15:30', '2017-11-13 09:15:30'),
(109, 8, 3, 0, 0, '2017-11-13 09:15:30', '2017-11-13 09:15:30'),
(110, 8, 4, 0, 0, '2017-11-13 09:15:30', '2017-11-13 09:15:30'),
(115, 9, 3, 0, 0, '2017-11-13 10:12:51', '2017-11-13 10:12:51'),
(193, 25, 1, 0, 0, '2018-01-04 11:01:53', '2018-01-04 11:01:53'),
(258, 50, 1, 0, 0, '2018-10-01 06:55:40', '2018-10-01 06:55:40'),
(259, 50, 3, 0, 0, '2018-10-01 06:55:40', '2018-10-01 06:55:40'),
(260, 6, 1, NULL, NULL, '2018-10-14 06:27:41', '2018-10-14 06:27:41'),
(261, 6, 2, NULL, NULL, '2018-10-14 06:27:41', '2018-10-14 06:27:41'),
(262, 6, 3, NULL, NULL, '2018-10-14 06:27:41', '2018-10-14 06:27:41'),
(263, 6, 4, NULL, NULL, '2018-10-14 06:27:41', '2018-10-14 06:27:41'),
(264, 6, 7, NULL, NULL, '2018-10-14 06:27:41', '2018-10-14 06:27:41'),
(265, 6, 8, NULL, NULL, '2018-10-14 06:27:41', '2018-10-14 06:27:41'),
(266, 6, 11, NULL, NULL, '2018-10-14 06:27:41', '2018-10-14 06:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `module_to_role`
--

CREATE TABLE `module_to_role` (
  `id` int(11) UNSIGNED NOT NULL,
  `module_id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) UNSIGNED NOT NULL,
  `activity_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `module_to_role`
--

INSERT INTO `module_to_role` (`id`, `module_id`, `role_id`, `activity_id`) VALUES
(3888, 1, 1, 1),
(3889, 1, 1, 2),
(3890, 1, 1, 3),
(3891, 1, 1, 4),
(3892, 2, 1, 1),
(3893, 3, 1, 1),
(3894, 3, 1, 2),
(3895, 3, 1, 3),
(3896, 3, 1, 4),
(3897, 6, 1, 1),
(3898, 6, 1, 7),
(3899, 6, 1, 8),
(3900, 6, 1, 11),
(3901, 7, 1, 1),
(3902, 7, 1, 2),
(3903, 7, 1, 3),
(3904, 7, 1, 4),
(3905, 8, 1, 1),
(3906, 8, 1, 2),
(3907, 8, 1, 3),
(3908, 8, 1, 4),
(3909, 9, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `module_to_user`
--

CREATE TABLE `module_to_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `module_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `activity_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `module_to_user`
--

INSERT INTO `module_to_user` (`id`, `module_id`, `user_id`, `activity_id`) VALUES
(5590, 1, 1, 1),
(5591, 1, 1, 2),
(5592, 1, 1, 3),
(5593, 1, 1, 4),
(5594, 2, 1, 1),
(5595, 3, 1, 1),
(5596, 3, 1, 2),
(5597, 3, 1, 3),
(5598, 3, 1, 4),
(5599, 6, 1, 1),
(5600, 6, 1, 7),
(5601, 6, 1, 8),
(5602, 6, 1, 11),
(5603, 7, 1, 1),
(5604, 7, 1, 2),
(5605, 7, 1, 3),
(5606, 7, 1, 4),
(5607, 8, 1, 1),
(5608, 8, 1, 2),
(5609, 8, 1, 3),
(5610, 8, 1, 4),
(5611, 9, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `info` varchar(200) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status_id` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `status_id`) VALUES
(1, 'Super Admin', 'Super User of this application who can manage all kind of operation', 55, 55, '2017-04-12 17:30:56', '2018-10-01 05:27:32', 1),
(2, 'Administrator', 'Limited access with almost all the features', 55, 55, '2017-11-15 06:58:49', '2018-10-14 08:12:26', 1),
(3, 'Manager', 'Manager', 55, 55, '2018-10-14 08:12:56', '2018-10-14 08:12:56', 1),
(4, 'Content Manager', 'Content Manager', 1, 1, '2018-10-14 08:41:20', '2018-10-14 08:41:20', 1),
(5, 'Content Writer', 'Content Writer', 1, 1, '2018-10-14 08:45:25', '2018-10-14 08:45:25', 1),
(6, 'Tele Operator', 'Tele Operator', 1, 1, '2018-10-14 08:45:40', '2018-10-14 08:45:40', 1),
(7, 'Promoter', 'Promoter', 1, 1, '2018-10-14 08:45:50', '2018-10-14 08:45:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(255) CHARACTER SET utf16 NOT NULL,
  `tag_line` varchar(255) CHARACTER SET utf16 DEFAULT NULL,
  `site_description` varchar(255) CHARACTER SET utf16 NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `location` text,
  `logo` varchar(255) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `copyRight` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `tag_line`, `site_description`, `email`, `phone`, `location`, `logo`, `favicon`, `copyRight`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Mobishop WAP Portal', 'Mobishop', 'Mobishop', 'mobishop@gmail.com', '09614151617', 'House 71, Road 7, Sector 4, Uttara Dhaka, Bangladesh 1230', 'public/upload/systemSettings/7Q6TW0cmMJgAjUkTtxlN.png', 'public/upload/systemSettings/fy2vBT64LzWODLKvTlpH.ico', 'Mobishop. All Rights Reserved', 55, 55, '2018-02-08 00:00:00', '2018-10-14 09:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `contact_no` varchar(14) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `designation` varchar(200) DEFAULT NULL,
  `photo` varchar(6550) DEFAULT NULL,
  `operator_id` varchar(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `recover_attempt` datetime DEFAULT NULL,
  `recover_link` varchar(255) DEFAULT NULL,
  `status_id` tinyint(1) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `lastLoginTime` datetime DEFAULT NULL,
  `regMetaServer` varchar(255) DEFAULT NULL,
  `loginMetaServer` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `first_name`, `last_name`, `email`, `contact_no`, `username`, `password`, `designation`, `photo`, `operator_id`, `group_id`, `recover_attempt`, `recover_link`, `status_id`, `remember_token`, `token`, `lastLoginTime`, `regMetaServer`, `loginMetaServer`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'system', 'Administrator', 'system@info.com', '019999999999', 'admin', '$2y$10$pspzvjtiRwhonDZ0JgFRqOCTbyL4zknA1Nl8k9.4RmO7BcmV8WkmC', NULL, '5bbc9f59e09b5510222832-612x612.jpg', NULL, NULL, NULL, NULL, 1, 'LePIAooxHe615R4XNdGNel9lAHfHnrbatLWIcFHFIL7X0x5GYHQgwZCxlu5y', NULL, '2018-04-04 12:12:42', NULL, '{\"REDIRECT_STATUS\":\"200\",\"HTTP_HOST\":\"quizbook.com.bd\",\"HTTP_CONNECTION\":\"keep-alive\",\"CONTENT_LENGTH\":\"88\",\"HTTP_CACHE_CONTROL\":\"max-age=0\",\"HTTP_ORIGIN\":\"http:\\/\\/quizbook.com.bd\",\"HTTP_UPGRADE_INSECURE_REQUESTS\":\"1\",\"CONTENT_TYPE\":\"application\\/x-www-f', 55, 55, '2015-10-15 04:21:06', '2018-10-14 06:22:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_to_activity`
--
ALTER TABLE `module_to_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_to_role`
--
ALTER TABLE `module_to_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_to_user`
--
ALTER TABLE `module_to_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `module_to_activity`
--
ALTER TABLE `module_to_activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT for table `module_to_role`
--
ALTER TABLE `module_to_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3910;

--
-- AUTO_INCREMENT for table `module_to_user`
--
ALTER TABLE `module_to_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5612;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
