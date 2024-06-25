-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Gegenereerd op: 22 sep 2023 om 08:49
-- Serverversie: 10.4.27-MariaDB
-- PHP-versie: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softwareshop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bestellingen`
--

CREATE TABLE `bestellingen` (
  `bestellingen_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `datum` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `bestellingen`
--

INSERT INTO `bestellingen` (`bestellingen_id`, `email`, `datum`, `total_amount`) VALUES
(1, 'juliekraanen@gmail.com', '2023-09-11', 154.48),
(2, 'juliekraanen@gmail.com', '2023-09-11', 38.49),
(3, 'juliekraanen@gmail.com', '2023-09-11', 18.50),
(4, 'juliekraanen@gmail.com', '2023-09-11', 7.00),
(5, 'juliekraanen@gmail.com', '2023-09-11', 11.99),
(6, 'juliekraanen@gmail.com', '2023-09-11', 17.00),
(7, 'juliekraanen@gmail.com', '2023-09-11', 17.00),
(8, 'juliekraanen@gmail.com', '2023-09-11', 20.00),
(9, '2165220@talnet.nl', '2023-09-11', 54.00),
(10, '2165220@talnet.nl', '2023-09-11', 75.97),
(11, 'r.kraanen@chello.nl', '2023-09-11', 25.50),
(12, '2165220@talnet.nl', '2023-09-11', 11.99),
(13, 'juliekraanen@gmail.com', '2023-09-11', 42.49),
(14, '2165220@talnet.nl', '2023-09-11', 49.98),
(15, '2165220@talnet.nl', '2023-09-13', 17.00),
(16, 'juliekraanen@gmail.com', '2023-09-13', 10.00),
(17, 'juliekraanen@gmail.com', '2023-09-15', 11.99),
(18, '2165220@talnet.nl', '2023-09-15', 27.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bestellingen_producten`
--

CREATE TABLE `bestellingen_producten` (
  `id` int(11) NOT NULL,
  `bestellingen_id` int(11) DEFAULT NULL,
  `producten_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `bestellingen_producten`
--

INSERT INTO `bestellingen_producten` (`id`, `bestellingen_id`, `producten_id`, `quantity`) VALUES
(1, 1, 1, 4),
(2, 1, 4, 6),
(3, 1, 5, 2),
(4, 1, 6, 2),
(5, 1, 7, 3),
(6, 2, 7, 1),
(7, 2, 8, 1),
(8, 3, 1, 1),
(9, 3, 4, 1),
(10, 4, 6, 1),
(11, 5, 5, 1),
(12, 6, 4, 1),
(13, 6, 7, 1),
(14, 7, 4, 1),
(15, 7, 7, 1),
(16, 8, 1, 2),
(17, 9, 1, 2),
(18, 9, 4, 2),
(19, 9, 7, 2),
(20, 10, 5, 1),
(21, 10, 8, 1),
(22, 10, 11, 1),
(23, 11, 1, 1),
(24, 11, 4, 1),
(25, 11, 6, 1),
(26, 12, 5, 1),
(27, 13, 4, 1),
(28, 13, 11, 1),
(29, 14, 10, 2),
(30, 15, 4, 1),
(31, 15, 7, 1),
(32, 16, 1, 1),
(33, 17, 5, 1),
(34, 18, 1, 1),
(35, 18, 4, 1),
(36, 18, 7, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klanten`
--

CREATE TABLE `klanten` (
  `voornaam` varchar(255) NOT NULL,
  `achternaam` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `telefoonnummer` varchar(255) DEFAULT NULL,
  `wachtwoord` varchar(64) NOT NULL,
  `role` varchar(45) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `klanten`
--

INSERT INTO `klanten` (`voornaam`, `achternaam`, `email`, `adres`, `telefoonnummer`, `wachtwoord`, `role`) VALUES
('Julie', 'Kraanen', '2165220@talnet.nl', 'Egoli 28 Amsterdam', '', 'Koekje10', 'admin'),
('julie2', 'kra2', 'juliekraanen@gmail.com', 'Egoli 28 Amsterdam', '', 'Koekje10', 'user'),
('judith', 'kraanen-giling', 'r.kraanen@chello.nl', 'Egoli 25 Amsterdam', '', 'Koekje10', 'user'),
('richard', 'kraanen', 'wo@gmail.com', 'eguue 34 amsterdam', '', 'Koekje10', 'user');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `producten`
--

CREATE TABLE `producten` (
  `producten_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `prijs` decimal(10,2) NOT NULL,
  `beschrijving` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `producten`
--

INSERT INTO `producten` (`producten_id`, `naam`, `prijs`, `beschrijving`) VALUES
(1, 'Adobe Creative Cloud', 10.00, 'Geef je studenten, faculteit en medewerkers vooraanstaande creatieve desktopapps én verbonden mobiele apps en services. Creative Cloud biedt alles wat ze nodig hebben om bijvoorbeeld afbeeldingen, video’s, webcontent en online portfolio’s te maken.'),
(4, 'Microsoft Powerpoint', 8.50, 'PowerPoint is Microsoft\'s presentation software that enables users to create engaging presentations that consist of individual pages, or slides, which may contain text, graphics, sound, movies, hyperlinks, and other objects. PowerPoint enables users to ad'),
(5, 'disney+', 11.99, 'Disney+ offers an ever-growing collection of exclusive originals, including feature-length films, documentaries, live-action and animated series, and short-form content. Disney is known for its exceptional storytelling. They create immersive experiences f'),
(6, 'Spotify', 7.00, 'Spotify is a digital music, podcast, and video service that gives you access to millions of songs and other content from creators all over the world.\r\nSpotify only gives access to music and podcasts through our apps. Our licensing means there\'s no way to '),
(7, 'Microsoft Word', 8.50, 'MS Word is a word processor developed by Microsoft. It has advanced features which allow you to format and edit your files and documents in the best possible way.'),
(8, 'Bit Academy', 29.99, 'Bit Academy - developing the next generation of tech talent. Amsterdam-founded Bit Academy is dedicated to tackling a shortage in tech talent by transforming tech education - first in the Netherlands, then on to the rest of the world.'),
(9, 'Xbox', 11.99, 'Xbox Game Pass members enjoy access to high-quality games within the PC or console libraries, until either the membership is canceled/expires, or a game leaves the Xbox Game Pass library. Game Pass game titles, number, features, and availability vary over'),
(10, 'Playstation plus 3 months', 24.99, 'Enhance your PlayStation experience with core features, including online multiplayer access, monthly games, exclusive discounts, and more:\r\n• Access to online multiplayer  \r\n• Hand-picked games to download every month at no extra cost \r\n• Exclusive discou'),
(11, 'Playstation plus 6 months', 33.99, 'Enhance your PlayStation experience with core features, including online multiplayer access, monthly games, exclusive discounts, and more:\r\n• Access to online multiplayer  \r\n• Hand-picked games to download every month at no extra cost \r\n• Exclusive discou'),
(12, 'Playstation plus 12 months', 59.99, 'Enhance your PlayStation experience with core features, including online multiplayer access, monthly games, exclusive discounts, and more:\r\n• Access to online multiplayer  \r\n• Hand-picked games to download every month at no extra cost \r\n• Exclusive discou');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_cart`
--

CREATE TABLE `user_cart` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user_cart`
--

INSERT INTO `user_cart` (`id`, `user_email`, `product_id`) VALUES
(25, '2165220@talnet.nl', 4),
(26, '2165220@talnet.nl', 4),
(27, '2165220@talnet.nl', 1),
(28, '2165220@talnet.nl', 5),
(29, '2165220@talnet.nl', 5);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `bestellingen`
--
ALTER TABLE `bestellingen`
  ADD PRIMARY KEY (`bestellingen_id`);

--
-- Indexen voor tabel `bestellingen_producten`
--
ALTER TABLE `bestellingen_producten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bestellingen_id` (`bestellingen_id`),
  ADD KEY `producten_id` (`producten_id`);

--
-- Indexen voor tabel `klanten`
--
ALTER TABLE `klanten`
  ADD PRIMARY KEY (`email`);

--
-- Indexen voor tabel `producten`
--
ALTER TABLE `producten`
  ADD PRIMARY KEY (`producten_id`);

--
-- Indexen voor tabel `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `bestellingen`
--
ALTER TABLE `bestellingen`
  MODIFY `bestellingen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `bestellingen_producten`
--
ALTER TABLE `bestellingen_producten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT voor een tabel `producten`
--
ALTER TABLE `producten`
  MODIFY `producten_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT voor een tabel `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `bestellingen_producten`
--
ALTER TABLE `bestellingen_producten`
  ADD CONSTRAINT `bestellingen_producten_ibfk_1` FOREIGN KEY (`bestellingen_id`) REFERENCES `bestellingen` (`bestellingen_id`),
  ADD CONSTRAINT `bestellingen_producten_ibfk_2` FOREIGN KEY (`producten_id`) REFERENCES `producten` (`producten_id`);

--
-- Beperkingen voor tabel `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `klanten` (`email`),
  ADD CONSTRAINT `user_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `producten` (`producten_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
