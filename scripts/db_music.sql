-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_music`
--

CREATE DATABASE `db_music`;

USE `db_music`;

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `password` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id`, `name`, `password`) VALUES
(3, 'shenmian', '$2y$10$BrYbAtouq/3FAIqGEy6zdOkGFDuKP7Wk9uCNjgUT3Fm/nZ4Xxsqoi');

-- --------------------------------------------------------

--
-- Table structure for table `tb_album`
--

CREATE TABLE `tb_album` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `author` varchar(20) NOT NULL,
  `released` date DEFAULT NULL,
  `img_path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_album`
--

INSERT INTO `tb_album` (`id`, `name`, `author`, `released`, `img_path`) VALUES
(1, 'Wonderland', 'Bandari', '1998-03-20', 'img/wonderland.jpg'),
(2, 'Sunny Bay', 'Bandari', '2000-08-01', 'img/sunny_bay.jpg'),
(6, 'Forest Mist', 'Bandari', '1999-01-01', 'img/forest_mist.jpg'),
(12, 'Test', 'Nobody', '2023-11-28', 'img/Test.png');

-- --------------------------------------------------------

--
-- Table structure for table `tb_music`
--

CREATE TABLE `tb_music` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `author` varchar(25) NOT NULL,
  `duration` time NOT NULL,
  `album_id` int(11) NOT NULL,
  `path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_music`
--

INSERT INTO `tb_music` (`id`, `name`, `author`, `duration`, `album_id`, `path`) VALUES
(1, 'Adiemus', 'Bandari', '00:04:03', 1, 'music/Wonderland/Adiemus.wav'),
(2, 'Annie\'s Song', 'Bandari', '00:03:16', 1, 'music/Wonderland/Annie\'s Song.wav'),
(3, 'Annie\'s Wonderland', 'Bandari', '00:03:26', 1, 'music/Wonderland/Annie\'s Wonderland.wav'),
(4, 'La Provence', 'Bandari', '00:04:19', 1, 'music/Wonderland/La Provence.wav'),
(5, 'Laterna Magica', 'Bandari', '00:03:31', 1, 'music/Wonderland/Laterna Magica.wav'),
(6, 'Little Mermaid', 'Bandari', '00:04:05', 1, 'music/Wonderland/Little Mermaid.wav'),
(7, 'Mandy\'s Song', 'Bandari', '00:04:04', 1, 'music/Wonderland/Mandy\'s Song.wav'),
(8, 'Star Of Baghdad', 'Bandari', '00:04:27', 1, 'music/Wonderland/Star Of Baghdad.wav'),
(9, 'The Best Friends', 'Bandari', '00:00:38', 1, 'music/Wonderland/The Best Friends.wav'),
(10, 'The Daylight', 'Bandari', '00:03:50', 1, 'music/Wonderland/The Daylight.wav'),
(11, 'The Wind Of Change', 'Bandari', '00:05:45', 1, 'music/Wonderland/The Wind Of Change.wav'),
(12, 'Three Times A Lady', 'Bandari', '00:03:35', 1, 'music/Wonderland/Three Times A Lady.wav'),
(13, 'Trilogy', 'Bandari', '00:05:05', 1, 'music/Wonderland/Trilogy.wav'),
(14, 'Your Smile', 'Bandari', '00:02:52', 1, 'music/Wonderland/Your Smile.wav'),
(15, 'A Woodland Night', 'Bandari', '00:04:19', 2, 'music/Sunny Bay/A Woodland Night.wav'),
(16, 'African Sunset', 'Bandari', '00:04:27', 2, 'music/Sunny Bay/African Sunset.wav');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `password` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `name`, `password`) VALUES
(1, 'user', '$2y$10$zKHkKW8JiHKduxBttljygeTZDlRd/l9x.hqnUx1njtx6dOWol6NX6'),
(2, 'shenmian', '$2y$10$BrYbAtouq/3FAIqGEy6zdOkGFDuKP7Wk9uCNjgUT3Fm/nZ4Xxsqoi'),
(4, 'user1', '$2y$10$bHu0aogyR5fMl9e1moEXKOBAJJ9.afAuQXoGbC2G7HC3iENs2GIqa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tb_album`
--
ALTER TABLE `tb_album`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tb_music`
--
ALTER TABLE `tb_music`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_album`
--
ALTER TABLE `tb_album`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_music`
--
ALTER TABLE `tb_music`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
