-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Jan 2024 um 20:09
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `berlisahotel`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `userID` int(11) NOT NULL,
  `userTyp` enum('Anonym','Gast','Admin') NOT NULL DEFAULT 'Anonym',
  `userANREDE` enum('Herr','Frau','Divers') NOT NULL,
  `userVN` varchar(128) NOT NULL,
  `userNN` varchar(128) NOT NULL,
  `userEMAIL` varchar(128) NOT NULL,
  `userUID` varchar(128) NOT NULL,
  `userPWD` varchar(128) NOT NULL,
  `userStatus` varchar(12) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`userID`, `userTyp`, `userANREDE`, `userVN`, `userNN`, `userEMAIL`, `userUID`, `userPWD`, `userStatus`) VALUES
(19, 'Admin', 'Divers', 'Null', 'Null', 'null@null.com', 'Admin', '$2y$10$A0IgbgloczUaysJ9nPwbkuDiUktFiVip8pvD0pNi2JN0tivgqHURq', 'active'),
(25, 'Gast', 'Herr', 'Thomas', 'Max', 'test@test.com', 'Thoma', '$2y$10$GutedDUztsZMe03QiyD6b.V2NFRgwAx1a7eSVILVQsxP1n9yYY7Fe', 'inactive'),
(26, 'Gast', 'Frau', 'Sarah', 'Niche', 'idk@test.com', 'Saraah', '$2y$10$OFfg7Ke6WdfL3AsSsHcmfOPKYcADbKWtDIRme5K9So96yttD1eS/G', 'active');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `newsID` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `text` text NOT NULL,
  `newsdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `original_file` varchar(128) NOT NULL,
  `thumbnail` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `news`
--

INSERT INTO `news` (`newsID`, `title`, `text`, `newsdate`, `original_file`, `thumbnail`) VALUES
(20, 'Webseite aktualisiert', 'Entdecken Sie unsere frisch aktualisierte Webseite! Tauchen Sie ein in ein verbessertes Nutzererlebnis und finden Sie die neuesten Informationen zu unseren Angeboten und Services. Willkommen auf der modernisierten Plattform Ihres Vertrauens!', '2023-12-31 15:08:12', '../uploads/news/Webseite_1704035292.jpg', '../uploads/thumbnails/Webseite_1704035292_thumb.jpg'),
(21, 'Neuer Mitarbeiter eingestellt', 'Mit großer Freude geben wir bekannt, dass wir einen neuen Mitarbeiter, Max, in unserem Team begrüßen dürfen. Mit seiner/ihrer Expertise bereichern wir unser Hotel, um Ihnen einen noch angenehmeren Aufenthalt zu bieten.', '2024-01-01 12:23:06', '../uploads/news/Mitarbeiter_1704111785.jpg', '../uploads/thumbnails/Mitarbeiter_1704111785_thumb.jpg'),
(22, 'Neues Restaurant im Hotel eröffnet', 'Wir freuen uns, Ihnen die Eröffnung unseres neuen Restaurants im Hotel bekannt zu geben. Unser Küchenchef hat eine köstliche Speisekarte mit regionalen Spezialitäten und internationalen Gerichten kreiert. Genießen Sie ein unvergessliches kulinarisches Erlebnis in unserem eleganten Ambiente.', '2024-01-01 12:23:23', '../uploads/news/Restaurant_1704111802.jpg', '../uploads/thumbnails/Restaurant_1704111802_thumb.jpg'),
(23, 'Neuer Artikel veröffentlicht', 'Wir freuen uns, einen neuen Artikel veröffentlicht zu haben! Erfahren Sie mehr über spannende Themen und bleiben Sie stets informiert. Lesen Sie den Artikel jetzt auf unserer Website!', '2024-01-01 12:23:44', '../uploads/news/Artikel_1704111823.jpg', '../uploads/thumbnails/Artikel_1704111823_thumb.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `reservations`
--

CREATE TABLE `reservations` (
  `reservationID` int(11) NOT NULL,
  `FK_UserID` int(11) NOT NULL,
  `Anreise` date NOT NULL,
  `Abreise` date NOT NULL,
  `Zimmer` varchar(255) NOT NULL,
  `Personen` int(5) NOT NULL,
  `FH` tinyint(1) NOT NULL DEFAULT 0,
  `PK` tinyint(1) NOT NULL DEFAULT 0,
  `HT` tinyint(1) NOT NULL DEFAULT 0,
  `Kosten` decimal(10,0) NOT NULL,
  `RES_Status` enum('Neu','Bestätigt','Storniert') NOT NULL,
  `Submitted` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `reservations`
--

INSERT INTO `reservations` (`reservationID`, `FK_UserID`, `Anreise`, `Abreise`, `Zimmer`, `Personen`, `FH`, `PK`, `HT`, `Kosten`, `RES_Status`, `Submitted`) VALUES
(27, 25, '2023-12-29', '2023-12-30', 'Familienzimmer', 2, 1, 0, 0, 210, 'Bestätigt', '2023-12-28');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userUID` (`userUID`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`newsID`);

--
-- Indizes für die Tabelle `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservationID`),
  ADD KEY `FK_UserID` (`FK_UserID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `newsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT für Tabelle `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `FK_UserID` FOREIGN KEY (`FK_UserID`) REFERENCES `benutzer` (`userID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
