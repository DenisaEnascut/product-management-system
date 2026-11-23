-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: nov. 23, 2026 la 09:24 AM
-- Versiune server: 10.11.16-MariaDB
-- Versiune PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `product_management`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `availability_date` date DEFAULT NULL,
  `stock_status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `availability_date`, `stock_status`) VALUES
(2, 'Tricou Bumbac Organic', 'Tricou confortabil din bumbac 100%, culoare albastră, mărimea L.', 89.99, '1794392114_tricou-blue.jpg', '2027-04-06', 1),
(4, 'Mouse Wireless', 'Mouse ergonomic cu conectivitate duală (Bluetooth și Wireless 2.4GHz).', 125.00, '', '2027-02-16', 1),
(5, 'Ochelari de Soare', 'Ochelari cu protecție UV400 și ramă ușoară din titan', 210.00, '1794392310_Ochelari de Soare.jpg', '2027-09-14', 1),
(6, 'Tastatură Mecanică RGB', 'Tastatură Mecanică RGB cu iluminare dinamică personalizabilă, taste confortabile cu răspuns rapid și un design ergonomic.', 420.00, '', '2026-12-03', 0),
(7, 'Rucsac pentru Laptop', 'Rucsac impermeabil cu compartiment dedicat pentru laptop de 15.6 inch și port USB.', 180.00, '1794392565_Rucsac pentru Laptop.jpg', '2027-03-11', 1),
(8, 'Ceas Smartwatch Sport', 'Ceas inteligent cu monitorizare a ritmului cardiac, GPS și rezistență la apă.', 650.00, '', '2026-11-21', 1),
(9, 'Căni Ceramice pentru Cafea (Set)', 'Set de 4 căni moderne din ceramică rezistentă la temperaturi ridicate.', 75.00, '1794392799_Căni Ceramice.png', '2026-11-26', 1),
(10, 'Suport Reglabil pentru Laptop', 'Suport din aluminiu pliabil și reglabil pe înălțime pentru o poziție corectă la birou.', 95.00, '', '2027-04-28', 0),
(11, 'Căști Wireless Studio', 'Căști over-ear cu anulare activă a zgomotului și autonomie 30h', 349.99, '1795421438_casti.jfif', '2027-03-31', 1),
(12, 'Cameră Web Full HD', 'Cameră web 1080p cu microfon dual și autofocus pentru ședințe video', 189.00, '', '2027-02-26', 1),
(13, 'Boxă Portabilă Bluetooth', 'Boxă compactă rezistentă la stropi cu sunet clar și autonomie de 12 ore.', 230.00, '1795421563_boxa.jfif', '2027-01-08', 1),
(14, 'Lampă Birou LED Smart', 'Lampă de birou cu temperatură de culoare reglabilă și control tactil', 129.90, '1795421629_lampa.jfif', '2027-07-15', 1),
(15, 'Pad Încărcare Wireless', 'Încărcător wireless Qi cu protecție la suprasarcină și design subțire', 85.00, '1795421690_pad.jfif', '2027-10-14', 1),
(16, 'Microfon USB Podcast', 'Microfon condensator plug-and-play ideal pentru streaming și înregistrări vocale', 275.00, '1795421743_microfon.jfif', '2027-06-17', 1),
(17, 'Cablu HDMI 2.1 (2m)', 'Cablu de înaltă viteză cu mufe placate cu aur și înveliș textil durabil.', 55.00, '1795421810_HDMI.jfif', '2027-05-12', 1);

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
