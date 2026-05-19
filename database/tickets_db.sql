-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 19 mei 2026 om 18:58
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tickets_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `coupon_tb`
--

CREATE TABLE `coupon_tb` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `korting_euro` decimal(3,0) DEFAULT NULL,
  `korting_%` int(255) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `couponcode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `coupon_tb`
--

INSERT INTO `coupon_tb` (`id`, `name`, `korting_euro`, `korting_%`, `event_id`, `couponcode`) VALUES
(1, 'minderjarig', 30, 0, NULL, ''),
(2, 'senioren', 20, 0, NULL, ''),
(3, 'bultloze kameel', 100, 0, NULL, 'bultloze kameel'),
(4, 'tast', 5, NULL, 1, 'test');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `description_li` varchar(255) NOT NULL,
  `name_li` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `start_date`, `end_date`, `location`, `description_li`, `name_li`) VALUES
(1, 'Spik & Span XXL 2026 vrijdag', 'Spik & Span XXL Op 21 augustus 2026 feesten we samen met Spik & Span én gastartiesten in de unieke setting van Kasteel Limbricht', '2026-08-21 18:00:00', '2026-08-21 23:00:00', 'Landgoed Kasteel Limbricht, Limbricht', 'Spik & Span XXL Op 21 augustus 2026 viere we same', 'Spik & Span XXL 2026 Vriejdaag'),
(2, 'Spik & Span XXL 2026 zaterdag', 'Spik & Span XXL Op 22 augustus 2026 feesten we samen met Spik & Span én gastartiesten in de unieke setting van Kasteel Limbricht', '2026-08-22 18:00:00', '2026-08-22 23:00:00', 'Landgoed Kasteel Limbricht, Limbricht', 'Spik & Span XXL Op 22 augustus 2026 viere we same', 'Spik & Span XXL 2026 Zaterdig'),
(3, 'spik en test', 'zeg maar eens wat', '2026-05-13 15:13:00', '2026-05-14 15:13:00', 'op den kamp Landgraaf', 'zeg mer ins wat', 'spik en test'),
(4, 'test en span 2028', 'zeg nog eens wat maar ja dat ja', '2026-05-13 15:15:00', '2026-05-15 15:15:00', 'kerkrade', 'zeg nog ins wat mer ja dat ja', 'test en span 2028'),
(5, 'test en span 2028', 'zeg nog eens wat maar ja dat ja', '2026-05-13 15:15:00', '2026-05-15 15:15:00', 'kerkrade', 'zeg nog ins wat mer ja dat ja', 'test en span 2028'),
(6, 'test en span 2028', 'zeg nog eens wat maar ja dat ja', '2026-05-13 15:15:00', '2026-05-15 15:15:00', 'kerkrade', 'zeg nog ins wat mer ja dat ja', 'test en span 2028'),
(7, 'test en span 2028', 'zeg nog eens wat maar ja dat ja', '2026-05-13 15:15:00', '2026-05-15 15:15:00', 'kerkrade', 'zeg nog ins wat mer ja dat ja', 'test en span 2028');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `Fname` varchar(255) DEFAULT NULL,
  `Lname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `Aanhef` varchar(50) DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `herkomst` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `Fname`, `Lname`, `email`, `Aanhef`, `geboortedatum`, `event_id`, `total_price`, `created_at`, `herkomst`) VALUES
(1, 'test', 'test', 'jasper.v.kalsbeek@gmail.com', 'Man', '0000-00-00', 1, 80.00, '2026-05-12 16:25:42', 'Groningen'),
(2, 'test', 'test', 'jasper.v.kalsbeek@gmail.com', 'Man', '0000-00-00', 2, 55.00, '2026-05-12 18:04:06', 'Friesland'),
(3, 'jasper', 'van kalsbeek', 'jasper.v.kalsbeek@gmail.com', 'Man', '0000-00-00', 2, 40.00, '2026-05-13 08:24:03', 'Utrecht'),
(4, 'jasper', 'asdf', 'jasper.v.kalsbeek@gmail.com', 'Man', NULL, 3, 10.00, '2026-05-19 11:00:37', 'Zuid-Holland'),
(5, 'a;kdjf', 'asdfjk', 'jasper.v.kalsbeek@gmail.com', 'Man', NULL, 3, 10.00, '2026-05-19 11:07:05', 'Zeeland'),
(6, 'je', 'moeder', 'jasper.v.kalsbeek@gmail.com', 'None', NULL, 4, 50.00, '2026-05-19 11:54:52', 'Utrecht'),
(7, 'je', 'moeder', 'jasper.v.kalsbeek@gmail.com', 'None', NULL, 4, 50.00, '2026-05-19 11:54:54', 'Noord-Holland'),
(8, 'je', 'moeder', 'jasper.v.kalsbeek@gmail.com', 'None', NULL, 4, 50.00, '2026-05-19 11:55:28', 'Gelderland'),
(9, 'jasper', 'van kalsbeek', 'jasper.v.kalsbeek@gmail.com', 'Man', NULL, 3, 10.00, '2026-05-19 16:13:05', 'Limburg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tickets_tb`
--

CREATE TABLE `tickets_tb` (
  `id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ticket_id` varchar(255) NOT NULL,
  `Fname` varchar(255) NOT NULL,
  `Lname` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `dateofattendance` int(11) NOT NULL,
  `scanned` tinyint(1) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `ticket_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `tickets_tb`
--

INSERT INTO `tickets_tb` (`id`, `email`, `ticket_id`, `Fname`, `Lname`, `date`, `dateofattendance`, `scanned`, `order_id`, `ticket_type_id`) VALUES
(1, 'jasper.v.kalsbeek@gmail.com', 'Example1234-5678-9123', 'jasper', 'van kalsbeek', '0000-00-00', 0, 1, NULL, NULL),
(2, 'jasper.v.kalsbeek@gmail.com', 'D624F818CF32', 'test', 'test', '2026-05-12', 0, 0, 1, 3),
(3, 'jasper.v.kalsbeek@gmail.com', '1D77C3A13C62', 'test', 'test', '2026-05-12', 0, 0, 1, 1),
(4, 'jasper.v.kalsbeek@gmail.com', '0AA0D529F6A9', 'test', 'test', '2026-05-12', 0, 0, 1, 2),
(5, 'jasper.v.kalsbeek@gmail.com', '4F8EE6EB9236', 'test', 'test', '2026-05-12', 0, 0, 2, 4),
(6, 'jasper.v.kalsbeek@gmail.com', '6E3AAD3B6E0B', 'test', 'test', '2026-05-12', 0, 0, 2, 5),
(7, 'jasper.v.kalsbeek@gmail.com', '110E76DAE30A', 'jasper', 'van kalsbeek', '2026-05-13', 0, 0, 3, 5),
(8, 'jasper.v.kalsbeek@gmail.com', '97B450703817', 'jasper', 'van kalsbeek', '2026-05-13', 0, 0, 3, 5),
(9, 'jasper.v.kalsbeek@gmail.com', '0EA9BE6B9700', 'jasper', 'asdf', '2026-05-19', 0, 0, 4, 13),
(10, 'jasper.v.kalsbeek@gmail.com', '6AD69F46DB3E', 'a;kdjf', 'asdfjk', '2026-05-19', 0, 0, 5, 13),
(11, 'jasper.v.kalsbeek@gmail.com', '5CA9C99517BF', 'je', 'moeder', '2026-05-19', 0, 0, 6, 8),
(12, 'jasper.v.kalsbeek@gmail.com', '6B4CFC556F8F', 'je', 'moeder', '2026-05-19', 0, 0, 6, 9),
(13, 'jasper.v.kalsbeek@gmail.com', '8E2E63B96039', 'je', 'moeder', '2026-05-19', 0, 0, 7, 8),
(14, 'jasper.v.kalsbeek@gmail.com', '2A4A4629C9AC', 'je', 'moeder', '2026-05-19', 0, 0, 7, 9),
(15, 'jasper.v.kalsbeek@gmail.com', '48D61ED66A33', 'je', 'moeder', '2026-05-19', 0, 0, 8, 8),
(16, 'jasper.v.kalsbeek@gmail.com', '20DC71763D03', 'je', 'moeder', '2026-05-19', 0, 0, 8, 9),
(17, 'jasper.v.kalsbeek@gmail.com', '819A08061DD7', 'jasper', 'van kalsbeek', '2026-05-19', 0, 0, 9, 13);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ticket_type_tb`
--

CREATE TABLE `ticket_type_tb` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `max_per_order` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `max_available` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `ticket_type_tb`
--

INSERT INTO `ticket_type_tb` (`id`, `name`, `price`, `max_per_order`, `created_at`, `deleted_at`, `max_available`, `event_id`) VALUES
(1, 'Normaal', 35, 8, NULL, NULL, 500, 1),
(2, 'Junior', 20, 8, NULL, NULL, 100, 1),
(3, 'Senior', 25, 8, NULL, NULL, 100, 1),
(4, 'Normaal', 35, 8, NULL, NULL, 500, 2),
(5, 'Junior', 20, 8, NULL, NULL, 100, 2),
(6, 'Senior', 25, 8, NULL, NULL, 100, 2),
(7, 'normaal', 35, 8, '2026-05-13 15:16:44', NULL, 500, 4),
(8, 'klientjes', 20, 8, '2026-05-13 15:16:44', NULL, 500, 4),
(9, 'senior', 30, 8, '2026-05-13 15:16:44', NULL, 250, 4),
(10, 'normaal', 35, 8, '2026-05-13 15:17:37', NULL, 500, 5),
(11, 'klientjes', 20, 8, '2026-05-13 15:17:37', NULL, 500, 5),
(12, 'senior', 30, 8, '2026-05-13 15:17:37', NULL, 250, 5),
(13, 'aanbieding 2020', 10, 8, '2026-05-13 15:19:09', NULL, 5, 3),
(14, 'aanbieding 2020', 10, 8, '2026-05-13 15:19:32', NULL, 5, 1);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `coupon_tb`
--
ALTER TABLE `coupon_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexen voor tabel `tickets_tb`
--
ALTER TABLE `tickets_tb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexen voor tabel `ticket_type_tb`
--
ALTER TABLE `ticket_type_tb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `coupon_tb`
--
ALTER TABLE `coupon_tb`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `tickets_tb`
--
ALTER TABLE `tickets_tb`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT voor een tabel `ticket_type_tb`
--
ALTER TABLE `ticket_type_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Beperkingen voor tabel `tickets_tb`
--
ALTER TABLE `tickets_tb`
  ADD CONSTRAINT `tickets_tb_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Beperkingen voor tabel `ticket_type_tb`
--
ALTER TABLE `ticket_type_tb`
  ADD CONSTRAINT `ticket_type_tb_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
