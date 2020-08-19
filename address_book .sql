-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 18, 2020 at 11:52 PM
-- Server version: 10.5.3-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `address_book`
--

-- --------------------------------------------------------

--
-- Table structure for table `ab_admins`
--

CREATE TABLE `ab_admins` (
  `id` int(11) NOT NULL COMMENT 'Administrator ID',
  `role_id` int(11) NOT NULL COMMENT 'Role ID',
  `username` varchar(255) NOT NULL COMMENT 'Log In name',
  `name_first` varchar(255) NOT NULL COMMENT 'First Name',
  `name_last` varchar(255) NOT NULL COMMENT 'Last Name',
  `hashed_password` varchar(255) NOT NULL COMMENT 'Hashed Password',
  `email` varchar(255) NOT NULL COMMENT 'Email Address',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 active 0 inactive',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date Created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Administrative Users';

--
-- Dumping data for table `ab_admins`
--

INSERT INTO `ab_admins` (`id`, `role_id`, `username`, `name_first`, `name_last`, `hashed_password`, `email`, `status`, `date_created`) VALUES
(1, 1, 'Demo', 'Demo', 'User', '$2y$10$x340rsYpLz3uWyNdytqK1OtInLT.lFML.k.beWUy132dbawe2JpQa', 'demo@address.book', 1, '2020-07-01 19:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `ab_contacts`
--

CREATE TABLE `ab_contacts` (
  `id` int(11) NOT NULL COMMENT 'Contact ID',
  `name_first` varchar(255) NOT NULL COMMENT 'First Name',
  `name_last` varchar(255) NOT NULL COMMENT 'Last Name',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email Address',
  `address` varchar(255) DEFAULT NULL COMMENT 'Address',
  `address2` varchar(255) DEFAULT NULL COMMENT 'Address2',
  `city` varchar(255) DEFAULT NULL COMMENT 'City',
  `state` tinytext DEFAULT NULL COMMENT 'State Abbreviation',
  `zip` tinytext DEFAULT NULL COMMENT 'postal code',
  `phone_home` varchar(20) DEFAULT NULL COMMENT 'Home Phone Number',
  `phone_mobile` varchar(20) DEFAULT NULL COMMENT 'Mobile Phone Number',
  `date_of_birth` date DEFAULT NULL COMMENT 'Birthdate',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date Created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Address Book Contact Details';

--
-- Dumping data for table `ab_contacts`
--

INSERT INTO `ab_contacts` (`id`, `name_first`, `name_last`, `email`, `address`, `address2`, `city`, `state`, `zip`, `phone_home`, `phone_mobile`, `date_of_birth`, `date_created`) VALUES
(78, 'Alex P.', 'Keaton', 'a.keaton@tv.starr', '1234 Main St', 'Unit 7', 'Columbus', 'OH', '43016', '614-222-2222', '614-333-3333', '1961-06-09', '2020-07-21 16:30:13'),
(97, 'Mallory', 'Keaton', 'justinebateman@tvstarr.info', '1234 Main St', '', 'Columbus', 'OH', '43016', '614-222-2222', '614-333-3333', '1966-02-19', '2020-08-18 22:22:45'),
(98, 'Elyse', 'Keaton', 'meredithbaxter@tvstarr.info', '1234 Main St', '', 'Columbus', 'OH', '43016', '614-222-2222', '614-333-3333', '1947-06-21', '2020-08-18 22:24:58'),
(99, 'Jennifer', 'Keaton', 'tinayothers@tvstarr.info', '1234 Main St', '', 'Columbus', 'OH', '43016', '614-222-2222', '614-333-3333', '1973-05-05', '2020-08-18 22:26:20'),
(100, 'Steven', 'Keaton', 'michaelgross@tvstarr.info', '1234 Main St', '', 'Columbus', 'OH', '43016', '614-222-2222', '614-333-3333', '1947-06-21', '2020-08-18 22:27:29'),
(101, 'Andy', 'Keaton', 'brianbonsall@tvstarr.info', '1234 Main St', '', 'Columbus', 'OH', '43016', '614-222-2221', '614-333-3333', '1981-12-03', '2020-08-18 22:28:46');

-- --------------------------------------------------------

--
-- Table structure for table `ab_images`
--

CREATE TABLE `ab_images` (
  `id` int(11) NOT NULL COMMENT 'Image ID',
  `filename` varchar(255) NOT NULL COMMENT 'File Name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Address Book Images';

--
-- Dumping data for table `ab_images`
--

INSERT INTO `ab_images` (`id`, `filename`) VALUES
(121, 'alex.keaton_ehaEOw.png'),
(179, 'john.usb_x0Da8C.jpg'),
(184, 'mallory.keaton_YtdKIQ.png'),
(185, 'elyse.keaton_uD28sK.png'),
(186, 'jennifer.keaton_bn1Yex.png'),
(187, 'steven.keaton_pQQxH_.png'),
(188, 'andy.keaton_AUMRht.png'),
(194, 'profile-3.png');

-- --------------------------------------------------------

--
-- Table structure for table `ab_image_xref`
--

CREATE TABLE `ab_image_xref` (
  `contact_id` int(11) NOT NULL COMMENT 'Contact ID',
  `image_id` int(11) NOT NULL COMMENT 'Image ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Address Book Images Cross Reference Table';

--
-- Dumping data for table `ab_image_xref`
--

INSERT INTO `ab_image_xref` (`contact_id`, `image_id`) VALUES
(78, 121),
(97, 184),
(98, 185),
(99, 186),
(100, 187),
(101, 188);

-- --------------------------------------------------------

--
-- Table structure for table `ab_permissions`
--

CREATE TABLE `ab_permissions` (
  `perm_id` int(11) NOT NULL,
  `perm_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ab_permissions`
--

INSERT INTO `ab_permissions` (`perm_id`, `perm_desc`) VALUES
(1, 'View Contacts'),
(2, 'Create Contacts'),
(3, 'Update Contacts'),
(4, 'Delete Contacts'),
(5, 'View Admins'),
(6, 'Create Admins'),
(7, 'Update Admins'),
(8, 'Delete Admins'),
(9, 'View Roles'),
(10, 'Create Roles'),
(11, 'Update Roles'),
(12, 'Delete Roles');

-- --------------------------------------------------------

--
-- Table structure for table `ab_roles`
--

CREATE TABLE `ab_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ab_roles`
--

INSERT INTO `ab_roles` (`role_id`, `role_name`) VALUES
(2, 'Manager'),
(1, 'Owner'),
(3, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `ab_roles_permissions`
--

CREATE TABLE `ab_roles_permissions` (
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ab_roles_permissions`
--

INSERT INTO `ab_roles_permissions` (`role_id`, `perm_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ab_user_image_xref`
--

CREATE TABLE `ab_user_image_xref` (
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User ID',
  `image_id` int(11) UNSIGNED NOT NULL COMMENT 'Image ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Address Book User Images Cross Reference Table';

--
-- Dumping data for table `ab_user_image_xref`
--

INSERT INTO `ab_user_image_xref` (`user_id`, `image_id`) VALUES
(1, 194),
(10, 179);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ab_admins`
--
ALTER TABLE `ab_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `ab_contacts`
--
ALTER TABLE `ab_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ab_images`
--
ALTER TABLE `ab_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ab_image_xref`
--
ALTER TABLE `ab_image_xref`
  ADD PRIMARY KEY (`contact_id`,`image_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `ab_permissions`
--
ALTER TABLE `ab_permissions`
  ADD PRIMARY KEY (`perm_id`);

--
-- Indexes for table `ab_roles`
--
ALTER TABLE `ab_roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `ab_roles_permissions`
--
ALTER TABLE `ab_roles_permissions`
  ADD PRIMARY KEY (`role_id`,`perm_id`);

--
-- Indexes for table `ab_user_image_xref`
--
ALTER TABLE `ab_user_image_xref`
  ADD PRIMARY KEY (`user_id`,`image_id`),
  ADD KEY `image_id` (`image_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ab_admins`
--
ALTER TABLE `ab_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Administrator ID', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ab_contacts`
--
ALTER TABLE `ab_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Contact ID', AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `ab_images`
--
ALTER TABLE `ab_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Image ID', AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `ab_roles`
--
ALTER TABLE `ab_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
