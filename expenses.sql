-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 16, 2022 at 07:55 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expenses`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`) VALUES
(2, 'Ropa', '#1c71d8'),
(3, 'Ocio', '#4cc5f2'),
(6, 'Perro con perro', '#c01c28'),
(7, 'Luis', '#deddda'),
(9, 'Fwegewhe', '#000000'),
(12, 'Bthrt', '#f5c211'),
(13, 'Gberher', '#deddda');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `amount`, `date`, `user_id`, `category_id`) VALUES
(68, 'Pantalon', '36', '2022-07-12', 25, 2),
(71, 'prueba5978', '45', '2022-07-12', 25, 3),
(72, 'Perro con perro', '35', '2022-07-12', 25, 3),
(73, 'hola', '15', '2022-07-12', 25, 3),
(74, 'Perrro', '10', '2022-07-15', 25, 7),
(75, 'Luis Salc', '15', '2022-07-15', 25, 6),
(76, 'Pizza familiar', '15', '2022-07-15', 25, NULL),
(77, 'perro caliente', '25', '2022-07-15', 25, NULL),
(78, 'rhthrt', '10', '2022-07-15', 25, NULL),
(79, 'hgfhg', '15', '2022-07-15', 25, 7),
(80, 'hgf', '10', '2022-07-15', 25, 7),
(81, 'T-shirt', '36', '2022-07-15', 25, 2),
(82, 'bthrt', '10', '2022-07-15', 25, 2),
(83, 'uygyu', '5', '2022-07-15', 25, 6),
(84, 'Ropa', '25', '2022-07-15', 25, 6),
(85, 'fewgwe', '15', '2022-07-15', 25, NULL),
(86, 'rhrehre', '15', '2022-07-15', 25, NULL),
(87, 'Perro con perro', '15', '2022-07-15', 25, NULL),
(88, 'gberher', '15', '2022-07-15', 25, NULL),
(89, 'Jeans', '78', '2022-07-15', 25, 2),
(90, 'f3egweh', '15', '2022-07-15', 25, NULL),
(91, 'hhj54j', '15', '2022-07-15', 25, 6),
(92, 'Luis Salc', '15', '2022-07-15', 25, 6),
(93, 'gegwegew', '456', '2022-07-15', 25, NULL),
(94, 'hguyfrtr', '15', '2022-07-16', 25, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `password` varchar(150) NOT NULL,
  `rol` varchar(45) NOT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `budget` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `photo`, `budget`) VALUES
(25, 'Pedro Gonzales', 'luis@gmail.com', '$2y$10$tQDepKH/tl2kQKfboXZkV.yaJps6yObxkRieVDrPt8SIeAsar4QB.', 'user', '2ff8e3f10bfc5123610cbd22e4c8cf41.jpg', '1500'),
(26, 'admin', 'admin@gmail.com', '$2y$10$mXMlyyrypOarP4caDtsPEejVUa/cefHTA.KckKy6ZhhfgsVLnDd6y', 'admin', 'de636b2e3071546828d19e364091869e.jpg', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
