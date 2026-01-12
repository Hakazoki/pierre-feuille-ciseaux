-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 12 jan. 2026 à 10:07
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `shifumi`
--

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

DROP TABLE IF EXISTS `partie`;
CREATE TABLE IF NOT EXISTS `partie` (
  `Id_Partie` int NOT NULL AUTO_INCREMENT,
  `Pseudo_joueur` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `IP_Joueur` varchar(200) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Nb_tours` int NOT NULL,
  `Timestamp_` datetime DEFAULT NULL,
  `Nb_victoire` int NOT NULL,
  `Taux_reussite` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`Id_Partie`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `partie`
--

INSERT INTO `partie` (`Id_Partie`, `Pseudo_joueur`, `IP_Joueur`, `password`, `Nb_tours`, `Timestamp_`, `Nb_victoire`, `Taux_reussite`) VALUES
(1, 'Matthyeu', '32.24.174.106', 'M4tthy3U', 40, '2025-11-24 09:57:27', 27, 67.50),
(2, 'Hadrien', '57.87.167.234', 'H4dr1En', 36, '2025-11-24 10:57:54', 23, 63.89),
(3, 'Gabriella', '34.213.209.30', 'G4br1eLL4', 20, '2025-11-24 11:45:46', 20, 100.00),
(4, 'Dorian', '250.28.127.76', 'D0r1aN', 10, '2025-11-24 11:47:15', 5, 50.00),
(5, 'Gireg', '135.152.74.199', 'G1r3G', 19, '2025-11-24 11:04:09', 13, 68.42),
(6, 'Maël', '216.91.215.148', 'M4eL', 14, '2025-11-24 11:00:33', 3, 21.43),
(7, 'Erwann', '97.27.118.230', '3rW4nn', 259, '2025-11-24 09:57:27', 0, 0.00),
(8, 'Thomas', '27.53.63.200', 'Th0m4S', 74, '2025-11-24 12:38:14', 59, 79.73),
(9, 'Hamza', '251.178.169.242', 'H4mz4', 10, '2025-11-24 12:49:54', 8, 80.00);

--
-- Déclencheurs `partie`
--
DROP TRIGGER IF EXISTS `insert_taux`;
DELIMITER $$
CREATE TRIGGER `insert_taux` BEFORE INSERT ON `partie` FOR EACH ROW IF NEW.Taux_reussite IS NULL THEN
SET NEW.Taux_reussite = (NEW.Nb_victoire/NEW.Nb_tours)*100;
END IF
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `update_taux`;
DELIMITER $$
CREATE TRIGGER `update_taux` BEFORE UPDATE ON `partie` FOR EACH ROW IF OLD.Nb_tours != NEW.Nb_tours THEN
SET NEW.Taux_reussite = (NEW.Nb_victoire/NEW.Nb_tours)*100;
END IF
$$
DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
