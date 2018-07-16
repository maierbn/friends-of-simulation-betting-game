-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 16, 2018 at 06:13 PM
-- Server version: 5.7.22-log
-- PHP Version: 5.6.36-1+0~20180505045928.13+stretch~1.gbp9b5cab

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web150_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `match`
--

CREATE TABLE `match` (
  `id` int(11) NOT NULL,
  `nation0_id` int(11) DEFAULT NULL,
  `nation1_id` int(11) DEFAULT NULL,
  `time_start` timestamp NULL DEFAULT NULL,
  `match_group_id` int(11) NOT NULL,
  `goals0` int(11) DEFAULT NULL COMMENT 'result',
  `goals1` int(11) DEFAULT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `match`
--

INSERT INTO `match` (`id`, `nation0_id`, `nation1_id`, `time_start`, `match_group_id`, `goals0`, `goals1`, `finished`) VALUES
(1, 27, 19, '2018-06-14 15:00:00', 0, 5, 0, 1),
(2, 9, 33, '2018-06-15 12:00:00', 0, 0, 1, 1),
(3, 20, 14, '2018-06-15 15:00:00', 1, 0, 1, 1),
(4, 26, 11, '2018-06-15 18:00:00', 1, 3, 3, 1),
(5, 12, 2, '2018-06-16 10:00:00', 2, 2, 1, 1),
(6, 1, 15, '2018-06-16 13:00:00', 3, 1, 1, 1),
(7, 24, 8, '2018-06-16 16:00:00', 2, 0, 1, 1),
(8, 7, 22, '2018-06-16 19:00:00', 3, 2, 0, 1),
(9, 6, 29, '2018-06-17 12:00:00', 4, 0, 1, 1),
(10, 13, 21, '2018-06-17 15:00:00', 5, 0, 1, 1),
(11, 4, 30, '2018-06-17 18:00:00', 4, 1, 1, 1),
(12, 31, 18, '2018-06-18 12:00:00', 5, 1, 0, 1),
(13, 3, 23, '2018-06-18 15:00:00', 6, 3, 0, 1),
(14, 32, 10, '2018-06-18 18:00:00', 6, 1, 2, 1),
(15, 5, 17, '2018-06-19 12:00:00', 7, 1, 2, 1),
(16, 25, 28, '2018-06-19 15:00:00', 7, 1, 2, 1),
(17, 27, 9, '2018-06-19 18:00:00', 0, 3, 1, 1),
(18, 26, 20, '2018-06-20 12:00:00', 1, 1, 0, 1),
(19, 33, 19, '2018-06-19 15:00:00', 0, 1, 0, 1),
(20, 14, 11, '2018-06-19 18:00:00', 1, 0, 1, 1),
(21, 8, 2, '2018-06-21 12:00:00', 2, 1, 1, 1),
(22, 12, 24, '2018-06-21 15:00:00', 2, 1, 0, 1),
(23, 1, 7, '2018-06-21 18:00:00', 3, 0, 3, 1),
(24, 4, 6, '2018-06-22 12:00:00', 4, 2, 0, 1),
(25, 22, 15, '2018-06-22 15:00:00', 3, 2, 0, 1),
(26, 29, 30, '2018-06-22 18:00:00', 4, 1, 2, 1),
(27, 3, 32, '2018-06-23 12:00:00', 6, 5, 2, 1),
(28, 18, 21, '2018-06-23 15:00:00', 5, 1, 2, 1),
(29, 13, 31, '2018-06-23 18:00:00', 5, 2, 1, 1),
(30, 10, 23, '2018-06-24 12:00:00', 6, 6, 1, 1),
(31, 17, 28, '2018-06-24 15:00:00', 7, 2, 2, 1),
(32, 25, 5, '2018-06-24 18:00:00', 7, 0, 3, 1),
(33, 33, 27, '2018-06-25 14:00:00', 0, 3, 0, 1),
(34, 19, 9, '2018-06-25 14:00:00', 0, 2, 1, 1),
(35, 11, 20, '2018-06-25 18:00:00', 1, 2, 2, 1),
(36, 14, 26, '2018-06-25 18:00:00', 1, 1, 1, 1),
(37, 2, 24, '2018-06-26 14:00:00', 2, 0, 2, 1),
(38, 8, 12, '2018-06-26 14:00:00', 2, 0, 0, 1),
(39, 22, 1, '2018-06-26 18:00:00', 3, 1, 2, 1),
(40, 15, 7, '2018-06-26 18:00:00', 3, 1, 2, 1),
(41, 18, 13, '2018-06-27 14:00:00', 5, 2, 0, 1),
(42, 21, 31, '2018-06-27 14:00:00', 5, 0, 3, 1),
(43, 29, 4, '2018-06-27 18:00:00', 4, 0, 2, 1),
(44, 30, 6, '2018-06-27 18:00:00', 4, 1, 1, 1),
(45, 17, 25, '2018-06-28 14:00:00', 7, 0, 1, 1),
(46, 28, 5, '2018-06-28 14:00:00', 7, 0, 1, 1),
(47, 23, 32, '2018-06-28 18:00:00', 6, 1, 2, 1),
(48, 10, 3, '2018-06-28 18:00:00', 6, 0, 1, 1),
(49, 12, 1, '2018-06-30 14:00:00', 8, 4, 3, 1),
(50, 33, 26, '2018-06-30 18:00:00', 8, 2, 1, 1),
(51, 4, 21, '2018-07-02 14:00:00', 8, 2, 0, 1),
(52, 3, 17, '2018-07-02 18:00:00', 8, 3, 2, 1),
(53, 7, 8, '2018-07-01 18:00:00', 8, 4, 3, 1),
(54, 11, 27, '2018-07-01 14:00:00', 8, 4, 5, 1),
(55, 31, 30, '2018-07-03 14:00:00', 8, 1, 0, 1),
(56, 5, 10, '2018-07-03 18:00:00', 8, 4, 5, 1),
(57, 33, 12, '2018-07-06 14:00:00', 9, 0, 2, 1),
(58, 4, 3, '2018-07-06 18:00:00', 9, 1, 2, 1),
(59, 31, 10, '2018-07-07 14:00:00', 9, 0, 2, 1),
(60, 27, 7, '2018-07-07 18:00:00', 9, 4, 5, 1),
(61, 12, 3, '2018-07-10 18:00:00', 10, 1, 0, 1),
(62, 7, 10, '2018-07-11 18:00:00', 10, 2, 1, 1),
(63, 3, 10, '2018-07-14 14:00:00', 11, 2, 0, 1),
(64, 12, 7, '2018-07-15 15:00:00', 12, 4, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `match_group`
--

CREATE TABLE `match_group` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `match_group`
--

INSERT INTO `match_group` (`id`, `name`) VALUES
(0, 'Group A'),
(1, 'Group B'),
(2, 'Group C'),
(3, 'Group D'),
(4, 'Group E'),
(5, 'Group F'),
(6, 'Group G'),
(7, 'Group H'),
(8, 'Round of 16'),
(9, 'Quarter-finals'),
(10, 'Semi-finals'),
(11, 'Play-off for third place'),
(12, 'Final');

-- --------------------------------------------------------

--
-- Table structure for table `nation`
--

CREATE TABLE `nation` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `abbreviation` varchar(4) NOT NULL,
  `image` varchar(100) NOT NULL COMMENT 'link to image file',
  `cost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nation`
--

INSERT INTO `nation` (`id`, `name`, `abbreviation`, `image`, `cost`) VALUES
(1, 'Argentina', 'ARG', 'arg.png', NULL),
(2, 'Australia', 'AUS', 'aus.png', NULL),
(3, 'Belgium', 'BEL', 'bel.png', NULL),
(4, 'Brazil', 'BRA', 'bra.png', NULL),
(5, 'Colombia', 'COL', 'col.png', NULL),
(6, 'Costa&nbsp;Rica', 'CRC', 'crc.png', NULL),
(7, 'Croatia', 'CRO', 'cro.png', NULL),
(8, 'Denmark', 'DEN', 'den.png', NULL),
(9, 'Egypt', 'EGY', 'egy.png', NULL),
(10, 'England', 'ENG', 'eng.png', NULL),
(11, 'Spain', 'ESP', 'esp.png', NULL),
(12, 'France', 'FRA', 'fra.png', NULL),
(13, 'Germany', 'GER', 'ger.png', NULL),
(14, 'Iran', 'IRN', 'irn.png', NULL),
(15, 'Iceland', 'ISL', 'isl.png', NULL),
(16, 'Italy', 'ITA', 'ita.png', NULL),
(17, 'Japan', 'JPN', 'jpn.png', NULL),
(18, 'Korea', 'KOR', 'kor.png', NULL),
(19, 'Saudi&nbsp;Arabia', 'KSA', 'ksa.png', NULL),
(20, 'Morocco', 'MAR', 'mar.png', NULL),
(21, 'Mexico', 'MEX', 'mex.png', NULL),
(22, 'Nigeria', 'NGA', 'nga.png', NULL),
(23, 'Panama', 'PAN', 'pan.png', NULL),
(24, 'Peru', 'PER', 'per.png', NULL),
(25, 'Poland', 'POL', 'pol.png', NULL),
(26, 'Portugal', 'POR', 'por.png', NULL),
(27, 'Russia', 'RUS', 'rus.png', NULL),
(28, 'Senegal', 'SEN', 'sen.png', NULL),
(29, 'Serbia', 'SRB', 'srb.png', NULL),
(30, 'Switzerland', 'SUI', 'sui.png', NULL),
(31, 'Sweden', 'SWE', 'swe.png', NULL),
(32, 'Tunisia', 'TUN', 'tun.png', NULL),
(33, 'Uruguay', 'URU', 'uru.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ranking_common`
--

CREATE TABLE `ranking_common` (
  `id` int(11) NOT NULL,
  `nation_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ranking_common`
--

INSERT INTO `ranking_common` (`id`, `nation_id`, `rank`, `value`) VALUES
(1, 13, 0, 32),
(2, 4, 1, 31),
(3, 12, 2, 30),
(4, 11, 3, 29),
(5, 10, 4, 28),
(6, 1, 5, 27),
(7, 26, 6, 26),
(8, 7, 7, 25),
(9, 3, 8, 24),
(10, 25, 9, 23),
(11, 8, 10, 22),
(12, 33, 11, 21),
(13, 21, 12, 20),
(14, 15, 13, 19),
(15, 5, 14, 18),
(16, 27, 15, 17),
(17, 30, 16, 16),
(18, 31, 17, 15),
(19, 2, 18, 14),
(20, 17, 19, 13),
(21, 22, 20, 12),
(22, 24, 21, 11),
(23, 9, 22, 10),
(24, 28, 23, 9),
(25, 6, 24, 8),
(26, 29, 25, 7),
(27, 23, 26, 6),
(28, 32, 27, 5),
(29, 18, 28, 4),
(30, 14, 29, 3),
(31, 20, 30, 2),
(32, 16, 31, 1),
(33, 19, 32, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ranking_user`
--

CREATE TABLE `ranking_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `nation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- data for table `ranking_user` is omitted
--
-- --------------------------------------------------------

--
-- Table structure for table `select_nation`
--

CREATE TABLE `select_nation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nation_add_id` int(11) NOT NULL,
  `nation_drop_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'time of operation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login_name` varchar(256) NOT NULL,
  `displayed_name` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL COMMENT 'password_hash',
  `duration_terms` double DEFAULT NULL,
  `email_address` varchar(256) NOT NULL,
  `validation_token` varchar(256) NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `excuse` text NOT NULL,
  `own_ranking` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if user specified own ranking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- data for table `user` is omitted
--
-- --------------------------------------------------------

--
-- Table structure for table `user_holds_nation`
--

CREATE TABLE `user_holds_nation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nation_id` int(11) NOT NULL,
  `time_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_to` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- data for table `user_holds_nation` is omitted
--
--
-- Indexes for dumped tables
--

--
-- Indexes for table `match`
--
ALTER TABLE `match`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `match_group`
--
ALTER TABLE `match_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nation`
--
ALTER TABLE `nation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_common`
--
ALTER TABLE `ranking_common`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_user`
--
ALTER TABLE `ranking_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `select_nation`
--
ALTER TABLE `select_nation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_holds_nation`
--
ALTER TABLE `user_holds_nation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `match`
--
ALTER TABLE `match`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `nation`
--
ALTER TABLE `nation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `ranking_common`
--
ALTER TABLE `ranking_common`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `select_nation`
--
ALTER TABLE `select_nation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
