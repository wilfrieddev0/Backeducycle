-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 29 fév. 2024 à 13:04
-- Version du serveur : 8.3.0
-- Version de PHP : 8.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `educycle`
--

-- --------------------------------------------------------

--
-- Structure de la table `ed_address`
--

DROP TABLE IF EXISTS `ed_address`;
CREATE TABLE IF NOT EXISTS `ed_address` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idUser` int DEFAULT NULL,
  `idMeeting` int DEFAULT NULL,
  `road` int NOT NULL,
  `name` varchar(35) NOT NULL,
  `city` varchar(35) NOT NULL,
  `postal` int NOT NULL,
  `country` varchar(35) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`idUser`,`idMeeting`),
  KEY `idMeeting` (`idMeeting`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ed_address`
--

INSERT INTO `ed_address` (`id`, `idUser`, `idMeeting`, `road`, `name`, `city`, `postal`, `country`) VALUES
(13, 3, 3, 789, 'Maple Lane', 'Villagetown', 98765, 'Country C'),
(14, 4, 4, 101, 'Cedar Road', 'Hamletsville', 54321, 'Country D'),
(15, 5, 5, 202, 'Pine Street', 'Ruraltown', 24680, 'Country E'),
(16, 6, 6, 303, 'Birch Boulevard', 'Suburbville', 13579, 'Country F'),
(18, 8, 8, 505, 'Willow Avenue', 'Outskirts', 99887, 'Country H'),
(20, 10, 10, 707, 'Redwood Lane', 'Wilderness', 55443, 'Country J');

-- --------------------------------------------------------

--
-- Structure de la table `ed_comment`
--

DROP TABLE IF EXISTS `ed_comment`;
CREATE TABLE IF NOT EXISTS `ed_comment` (
  `id` int NOT NULL,
  `idUser` int NOT NULL,
  `idItem` int NOT NULL,
  `rate` int NOT NULL,
  `message` text NOT NULL,
  `datePublished` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `_like` int DEFAULT NULL,
  `report` int DEFAULT NULL,
  KEY `idUser` (`idUser`,`idItem`),
  KEY `idItem` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ed_comment`
--

INSERT INTO `ed_comment` (`id`, `idUser`, `idItem`, `rate`, `message`, `datePublished`, `_like`, `report`) VALUES
(0, 4, 4, 4, 'Highly recommended smartphone.', '2024-02-01 18:28:32', 8, 3),
(0, 5, 5, 2, 'Average gaming console.', '2024-02-01 18:28:32', 3, 1),
(0, 6, 6, 5, 'Best coffee maker ever!', '2024-02-01 18:28:32', 12, 0),
(0, 7, 7, 3, 'Decent fitness tracker.', '2024-02-01 18:28:32', 6, 2),
(0, 8, 8, 4, 'Great Bluetooth speaker!', '2024-02-01 18:28:32', 10, 1),
(0, 10, 10, 3, 'Good noise-canceling headphones.', '2024-02-01 18:28:32', 8, 2);

-- --------------------------------------------------------

--
-- Structure de la table `ed_donation`
--

DROP TABLE IF EXISTS `ed_donation`;
CREATE TABLE IF NOT EXISTS `ed_donation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idDonator` int NOT NULL,
  `idUser` int NOT NULL,
  `date` datetime DEFAULT NULL,
  `idAddress` int DEFAULT NULL,
  `dateReception` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idDonator` (`idDonator`,`idUser`),
  KEY `idDonatee` (`idUser`),
  KEY `idAddress` (`idAddress`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ed_donation`
--

INSERT INTO `ed_donation` (`id`, `idDonator`, `idUser`, `date`, `idAddress`, `dateReception`) VALUES
(3, 3, 4, '2024-02-15 00:00:00', NULL, NULL),
(4, 4, 5, '2024-02-18 00:00:00', NULL, NULL),
(5, 5, 3, '2024-02-20 00:00:00', NULL, NULL),
(6, 6, 8, '2024-02-22 00:00:00', NULL, NULL),
(8, 8, 7, '2024-02-28 00:00:00', NULL, NULL),
(10, 10, 6, '2024-03-05 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ed_item`
--

DROP TABLE IF EXISTS `ed_item`;
CREATE TABLE IF NOT EXISTS `ed_item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` int NOT NULL,
  `residence` varchar(50) DEFAULT NULL,
  `category` int NOT NULL,
  `description` text NOT NULL,
  `worth` int NOT NULL,
  `state` int NOT NULL,
  `period` int NOT NULL,
  `available` date NOT NULL,
  `publishedDate` date DEFAULT NULL,
  `idUser` int DEFAULT NULL,
  `statut` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ed_item`
--

INSERT INTO `ed_item` (`id`, `name`, `residence`, `category`, `description`, `worth`, `state`, `period`, `available`, `publishedDate`, `idUser`, `statut`) VALUES
(4, 0, NULL, 1, 'Latest smartphone model', 1000, 0, 0, '0000-00-00', NULL, 33, 'En attente de validation'),
(5, 0, NULL, 4, 'Next-gen gaming console', 500, 0, 0, '0000-00-00', NULL, 33, 'En attente de validation'),
(6, 0, NULL, 5, 'Espresso coffee maker', 200, 0, 0, '0000-00-00', NULL, 33, 'En attente de validation'),
(7, 0, NULL, 6, 'Health and fitness tracker', 80, 0, 0, '0000-00-00', NULL, 33, 'En attente de validation'),
(8, 0, NULL, 7, 'Wireless Bluetooth speaker', 70, 0, 0, '0000-00-00', NULL, 33, 'En attente de validation'),
(9, 0, NULL, 8, 'Fast-charging portable charger', 40, 0, 0, '0000-00-00', NULL, 33, 'En attente de validation'),
(10, 0, NULL, 9, 'Noise-canceling headphones', 150, 0, 0, '0000-00-00', NULL, 33, 'Validé'),
(11, 0, 'Residences Jean Medecin', 0, 'd gfserdf zerbzererv zerv', 0, 3, 0, '2024-02-07', NULL, 33, 'En attente de Récupération');

-- --------------------------------------------------------

--
-- Structure de la table `ed_media`
--

DROP TABLE IF EXISTS `ed_media`;
CREATE TABLE IF NOT EXISTS `ed_media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idUser` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `name` varchar(35) NOT NULL,
  `category` varchar(30) NOT NULL,
  `location` varchar(200) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`idUser`),
  KEY `idUser_2` (`idUser`,`idItem`),
  KEY `idItem` (`idItem`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ed_media`
--

INSERT INTO `ed_media` (`id`, `idUser`, `idItem`, `name`, `category`, `location`, `description`) VALUES
(2, 2, 2, 'Media 2', 'Video', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 2'),
(3, 33, 3, 'Media 3', 'Audio', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 3'),
(4, 4, 4, 'Media 4', 'Image', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 4'),
(5, 5, 5, 'Media 5', 'Video', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 5'),
(6, 6, 6, 'Media 6', 'Audio', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 6'),
(7, 7, 7, 'Media 7', 'Image', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 7'),
(8, 8, 8, 'Media 8', 'Video', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 8'),
(10, 10, 10, 'Media 10', 'Image', 'https://images.pexels.com/photos/697509/pexels-photo-697509.jpeg', 'Description for Media 10'),
(11, NULL, 11, '404.gif', 'descriptive', 'ressources\\images/404.gif', ''),
(12, NULL, 11, '503 - Copy.gif', 'descriptive', 'ressources\\images/503 - Copy.gif', ''),
(13, NULL, 11, 'deathNote.jpg', 'descriptive', 'ressources\\images/deathNote.jpg', ''),
(14, NULL, 11, 'deathNote1 - Copy.jpg', 'descriptive', 'ressources\\images/deathNote1 - Copy.jpg', ''),
(15, NULL, 11, 'deathNote1.jpg', 'descriptive', 'ressources\\images/deathNote1.jpg', ''),
(16, NULL, 11, 'OIP.jpg', 'descriptive', 'ressources\\images/OIP.jpg', ''),
(17, NULL, 11, 'pexels-rodolfo-clix-1615776.jpg', 'descriptive', 'ressources\\images/pexels-rodolfo-clix-1615776.jpg', ''),
(25, 33, 0, '404.gif', 'profile', 'ressources\\images/404.gif', ''),
(26, 33, 0, '404.gif', 'profile', 'ressources/images/404.gif', '');

-- --------------------------------------------------------

--
-- Structure de la table `ed_user`
--

DROP TABLE IF EXISTS `ed_user`;
CREATE TABLE IF NOT EXISTS `ed_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstName` varchar(35) NOT NULL,
  `lastName` varchar(35) NOT NULL,
  `email` varchar(35) NOT NULL,
  `birthday` date NOT NULL,
  `role` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phone` int DEFAULT NULL,
  `emailVerified` tinyint(1) DEFAULT '0',
  `dateCreation` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ed_user`
--

INSERT INTO `ed_user` (`id`, `firstName`, `lastName`, `email`, `birthday`, `role`, `password`, `phone`, `emailVerified`, `dateCreation`) VALUES
(2, 'John', 'Doe', 'john@example.com', '1990-05-15', 'USER', 'password123', 743235334, 1, '2024-02-25'),
(3, 'Jane', 'Doe', 'jane@example.com', '1988-08-22', 'USER', 'securepass', 743235334, 1, '2024-02-25'),
(4, 'Alice', 'Smith', 'alice@example.com', '1995-02-28', 'USER', 'alicepass', 743235334, 1, '2024-02-25'),
(5, 'Bob', 'Johnson', 'bob@example.com', '1992-11-10', 'USER', 'bobpass', 743235334, 0, '2024-02-25'),
(6, 'Eva', 'White', 'eva@example.com', '1987-07-05', 'USER', 'evapass', 743235334, 0, '2024-02-25'),
(7, 'Michael', 'Brown', 'michael@example.com', '1993-09-20', 'USER', 'michaelpass', 743235334, 0, '2024-02-25'),
(8, 'Sophia', 'Miller', 'sophia@example.com', '1998-04-15', 'USER', 'sophiapass', 743235334, 0, '2024-02-25'),
(10, 'Emma', 'Clark', 'emma@example.com', '1991-06-18', 'USER', 'emmapass', 743235334, 0, '2024-02-25'),
(11, 'Matthew', 'Taylor', 'matthew@example.com', '1996-01-25', 'USER', 'matthewpass', 743235334, 0, '2024-02-25'),
(18, 'aquillas', 'djidjou', 'aquillas@icloud.com', '2013-09-22', 'USER', '$2y$10$XSTOFXLd4Jv.zwOxg.O90.m/28tEp1j8sV47V1MfEZwNTbVF85bza', 743235334, 0, '2024-02-25'),
(19, 'ivana', 'lele', 'lele@icloud.com', '2029-09-22', 'USER', '$2y$10$CUa.d3ZpxF.c/nkatacWIe8XqN5Lyq3f4QOJsTPohg6on10AoqEh6', 743235334, 0, '2024-02-25'),
(20, 'divan', 'fotso', 'fotso@icloud.com', '2013-09-22', 'USER', '$2y$10$SDEHBg2.fgK5woiRmIHqdub8VdbPRfGCtX7GyOrDjzSGbRHIRMS0K', 743235334, 0, '2024-02-25'),
(22, 'laeticia', 'fokou', 'laeticia@icloud.com', '2001-09-22', 'USER', '$2y$10$2fYiJEIIIgX956XfJN0FsudhG.QArGCooU4pevD5SsilnTYoZN5xm', 743235334, 0, '2024-02-25'),
(33, 'Aaron', 'djibi', 'a%40a.a', '2024-02-23', 'USER', '$2y$10$eTyN9hlJCbW.17FtXx3Pu.HHj9.3.eH3PBkd5HGzlBisqHp0E8.3O', 743235334, 0, '2024-02-25');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ed_address`
--
ALTER TABLE `ed_address`
  ADD CONSTRAINT `ed_address_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `ed_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ed_address_ibfk_2` FOREIGN KEY (`idMeeting`) REFERENCES `ed_donation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ed_comment`
--
ALTER TABLE `ed_comment`
  ADD CONSTRAINT `ed_comment_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `ed_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ed_comment_ibfk_2` FOREIGN KEY (`idItem`) REFERENCES `ed_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ed_donation`
--
ALTER TABLE `ed_donation`
  ADD CONSTRAINT `ed_donation_ibfk_1` FOREIGN KEY (`idDonator`) REFERENCES `ed_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ed_donation_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `ed_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ed_donation_ibfk_3` FOREIGN KEY (`idAddress`) REFERENCES `ed_address` (`id`);

--
-- Contraintes pour la table `ed_item`
--
ALTER TABLE `ed_item`
  ADD CONSTRAINT `ed_item_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `ed_user` (`id`);

--
-- Contraintes pour la table `ed_media`
--
ALTER TABLE `ed_media`
  ADD CONSTRAINT `ed_media_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `ed_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
