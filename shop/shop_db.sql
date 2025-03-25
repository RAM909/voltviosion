-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 03:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL DEFAULT 1,
  `image` varchar(100) NOT NULL,
  `dimensions` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`, `dimensions`) VALUES
(3, 3, 4, 'Screwdriver Hand Tools', 150, 1, 'screwdriver.jpeg', NULL),
(4, 3, 2, '1.5 mm red Household Wires ', 1249, 1, 'images (1).jpeg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `status`, `payment_status`, `payment_id`) VALUES
(2, 4, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'cash on delivery', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', '1.5 mm red Household Wires  (1249 x 2) - Screwdriver Hand Tools (150 x 1) - admin (45 x 1) - ', 2693, '2025-01-03', 'completed', 'completed', ''),
(3, 4, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'cash on delivery', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', '1.5 mm red Household Wires  (1249 x 1) - admin (45 x 1) - pinapple (34 x 1) - ', 1328, '2025-01-03', 'completed', 'completed', ''),
(4, 5, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'cash on delivery', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', '1.5 mm red Household Wires  (1249 x 1) - Screwdriver Hand Tools (150 x 1) - apple (56 x 1) - ', 1455, '2025-01-03', 'pending', 'pending', ''),
(10, 4, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'Razorpay', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', 'Screwdriver Hand Tools (150 x 1) - 1.5 mm red Household Wires  (1249 x 1) - ', 1399, '2025-03-18', 'pending', 'completed', 'pay_Q8HTY06of0UG2E'),
(11, 4, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'Razorpay', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', 'Screwdriver Hand Tools (150 x 1) - 1.5 mm red Household Wires  (1249 x 1) - apple (56 x 1) - ', 1455, '2025-03-18', 'pending', 'completed', 'pay_Q8HVuFn8JDZegU'),
(12, 4, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'Razorpay', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', '1.5 mm red Household Wires  (1249 x 1) - Screwdriver Hand Tools (150 x 1) - apple (56 x 1) - pinapple (34 x 1) - admin (45 x 1) - ', 1534, '2025-03-18', 'pending', 'completed', 'pay_Q8HZ5tk1HFsKtQ'),
(13, 4, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'Razorpay', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', '1.5 mm red Household Wires  (1249 x 1) - Screwdriver Hand Tools (150 x 1) - ', 1399, '2025-03-18', 'pending', 'completed', 'pay_Q8HhUmLDNGHcqE'),
(14, 4, 'prathmesh', '0741852096', 'pjayp@gmail.com', 'Razorpay', 'flat no. abc d, bhy, Ekta Nagar (Kevadia), Maharashtra, India - 401105', '1.5 mm red Household Wires  (1249 x 1) - admin (45 x 1) - Screwdriver Hand Tools (150 x 1) - ', 1444, '2025-03-18', 'pending', 'completed', 'pay_Q8Hicd4BqtmMWX');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(100) NOT NULL,
  `order_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `quantity` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 10, 4, 1),
(2, 10, 2, 1),
(3, 11, 4, 1),
(4, 11, 2, 1),
(5, 11, 5, 1),
(6, 12, 2, 1),
(7, 12, 4, 1),
(8, 12, 5, 1),
(9, 12, 6, 1),
(10, 12, 7, 1),
(11, 13, 2, 1),
(12, 13, 4, 1),
(13, 14, 2, 1),
(14, 14, 7, 1),
(15, 14, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL,
  `dimensions` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `category`, `price`, `image_01`, `image_02`, `image_03`, `dimensions`) VALUES
(2, '1.5 mm red Household Wires ', '90 mtr coil polycab', 'modular', 1249, 'images (1).jpeg', 'images.jpeg', 'wire.jpeg', NULL),
(4, 'Screwdriver Hand Tools', 'tapariya', '', 150, 'screwdriver.jpeg', 'screwdriver.jpeg', 'screwdriver.jpeg', NULL),
(5, 'apple', 'organic red apples', 'solar panel', 56, 'cat-1.png', 'cat-3.png', 'pic-1.png', NULL),
(6, 'pinapple', 'melon', 'Wires', 34, 'pic-1.png', 'pic-2.png', 'pic-3.png', NULL),
(7, 'admin', 'gfh', 'Wires', 45, 'pic-3.png', 'pic-6.png', 'home-bg.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_pairs`
--

CREATE TABLE `product_pairs` (
  `id` int(11) NOT NULL,
  `product_id_1` int(11) NOT NULL,
  `product_id_2` int(11) NOT NULL,
  `count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_pairs`
--

INSERT INTO `product_pairs` (`id`, `product_id_1`, `product_id_2`, `count`) VALUES
(1, 2, 4, 2),
(2, 2, 7, 1),
(3, 4, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(2, 'Amruta', 'amruta@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b'),
(3, 'Tejal', 'tejal@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b'),
(4, 'jap', 'pjayp@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b'),
(5, 'jay', 'pjay15012002@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `dimensions` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_pairs`
--
ALTER TABLE `product_pairs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pair` (`product_id_1`,`product_id_2`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_pairs`
--
ALTER TABLE `product_pairs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
