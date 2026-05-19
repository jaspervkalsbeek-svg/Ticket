-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 19 mei 2026 om 12:44
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

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
