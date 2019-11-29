-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 29, 2019 at 08:03 AM
-- Server version: 5.7.27
-- PHP Version: 7.0.33-0+deb9u6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdeskdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `mbox`
--

CREATE TABLE `mbox` (
  `id` int(10) NOT NULL,
  `ticket_no` int(10) NOT NULL,
  `sent_by` int(10) NOT NULL,
  `sent_to` int(10) NOT NULL,
  `message` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `reset_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE `site` (
  `meta_id` int(11) NOT NULL,
  `meta_key` varchar(50) NOT NULL,
  `meta_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`meta_id`, `meta_key`, `meta_value`) VALUES
(1, 'site_title', 'Helpdesk A'),
(2, 'site_name', 'Helpdesk A'),
(3, 'site_logo', 'site_logo.png'),
(4, 'admin_email', 'sellusedbooksph@gmail.com'),
(5, 'theme', 'default'),
(6, 'admin_user_id', '2'),
(7, 'site_url', 'http://localhost/helpdesk/'),
(8, 'site_desc', ''),
(9, 'shortcode', '21588418'),
(10, 'app_id', 'Mq75Fqo97jF5bibg5gc9yAFzMqE5Fbee'),
(11, 'app_secret', 'b4ff5de4c1a7d40db00ae51c3a8ee331479de4d91c4869a4c51e86968dcacba2');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `subscriber_no` varchar(10) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `date_subscribed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subscribed` tinyint(1) NOT NULL DEFAULT '1',
  `date_unsubscribed` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`subscriber_no`, `access_token`, `date_subscribed`, `subscribed`, `date_unsubscribed`) VALUES
('9062038475', 'ZIMq_RzqiKfjFoa4dEGGXWQfL2QqVxFEkP_hDaiar8g', '2019-11-28 18:43:01', 1, NULL),
('9778171882', '97sSUiaWcvNrKyUg--tZA_esdRi_90TNeo0mWBaS1eY', '2019-11-29 00:36:18', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_no` int(10) NOT NULL,
  `subscriber_no` varchar(10) NOT NULL,
  `message` varchar(500) NOT NULL,
  `date_sent` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `agent_assigned` varchar(255) DEFAULT NULL,
  `response` varchar(500) DEFAULT NULL,
  `date_responded` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_no`, `subscriber_no`, `message`, `date_sent`, `agent_assigned`, `response`, `date_responded`) VALUES
(1, '9062038475', 'Hi, need your help.', '2019-11-28 20:43:45', 'agent', 'Hi, what\'s your concern?', '2019-11-28 23:35:20'),
(2, '9062038475', 'Hi, I have a concern. ', '2019-11-29 00:16:08', 'agent', 'How may I help you?', '2019-11-29 00:17:00'),
(3, '9778171882', 'Kahit ano', '2019-11-29 00:36:48', 'agent', 'Ano pong concern nyo?', '2019-11-29 00:37:42'),
(4, '9778171882', 'Pabili pong suka. ', '2019-11-29 00:39:11', 'agent', 'Wala po kaming suka. Refer ko na lang po kayo sa kabilang tindahan.', '2019-11-29 02:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isDeactivated` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `user_type`, `password`, `email`, `first_name`, `last_name`, `date_registered`, `isDeactivated`) VALUES
(1, 'superadmin', 'admin', 'ef238ea00a26528de40ff231e5a97f50', 'sbvizcara@up.edu.ph', 'Sheryl', 'Vizcara', '2019-04-18 05:55:10', 0),
(2, 'agent', 'agent', 'ef238ea00a26528de40ff231e5a97f50', 'user2@test.email', 'Agent', 'Agent', '2019-04-18 09:55:02', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mbox`
--
ALTER TABLE `mbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`subscriber_no`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mbox`
--
ALTER TABLE `mbox`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `site`
--
ALTER TABLE `site`
  MODIFY `meta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
