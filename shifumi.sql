-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 14 jan. 2026 à 08:21
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
  `IP_Joueur` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Nb_tours` int NOT NULL,
  `Timestamp_` datetime DEFAULT NULL,
  `Nb_victoire` int NOT NULL,
  `Taux_reussite` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`Id_Partie`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `partie`
--

INSERT INTO `partie` (`Id_Partie`, `Pseudo_joueur`, `IP_Joueur`, `password`, `Nb_tours`, `Timestamp_`, `Nb_victoire`, `Taux_reussite`) VALUES
(1, 'Matthyeu', '32.24.174.106', '$2y$10$IVVQiyGVtSnxdsHTEZRLauTxMIhUmQgVrXTB.nCjDCzIbJD/OAN9i', 40, '2025-11-24 09:57:27', 27, 67.50),
(2, 'Hadrien', '57.87.167.234', '$2y$10$bz93h9EH5QkS2DMDQeZwwuwWv1LbtDoZjqUfyNxS0Imlblux0iCZ.', 36, '2025-11-24 10:57:54', 23, 63.89),
(3, 'Gabriella', '34.213.209.30', '$2y$10$5ta.RKt13XcJtrB2rG4Sc.R3SYOpg7fskOv02nVaOliMvAi7q9jVm', 20, '2025-11-24 11:45:46', 20, 100.00),
(4, 'Dorian', '250.28.127.76', '$2y$10$gaRAxOgEVZsYr1Z2bh7PjeHT1N4Q..f/jxNjTUhPARC3/T8H.byg6', 10, '2025-11-24 11:47:15', 5, 50.00),
(5, 'Gireg', '135.152.74.199', '$2y$10$Mot8JYV3iWyXnl2eltqUqOGXvPJPHGoZXnGyURWNQErwjRoFp1.ca', 19, '2025-11-24 11:04:09', 13, 68.42),
(6, 'Maël', '216.91.215.148', '$2y$10$UEAdfkMJE9lfOtC6j0/7v.3w9yK1ZxtJ4mSr240PjRbIK1Fo7UG.C', 14, '2025-11-24 11:00:33', 3, 21.43),
(7, 'Erwann', '97.27.118.230', '$2y$10$R356B4wXotfm7kFln9wDjuKaUbo7jhF5CFVa/eH84Qmz4pzKW9lrG', 259, '2025-11-24 09:57:27', 0, 0.00),
(8, 'Thomas', '27.53.63.200', '$2y$10$P9iTwBWEkBoh059IIp2xZu7bw4aV7UYne0n1kOHPdQfF93IJ7X7ry', 74, '2025-11-24 12:38:14', 59, 79.73),
(9, 'Hamza', '251.178.169.242', '$2y$10$apsgFCrVe1LmBLUlfjHmoumof.Xq/PgATOCDEm5UwiUGOaZQQ7mQm', 10, '2025-11-24 12:49:54', 8, 80.00),
(12, 'silver5', '98.365.44.12', '$2y$10$pNBS6dswnE41ZK6rvnV8BewBN6wMt7pIrYYzETjkey74lYNIYlCY6', 10, '2026-01-12 14:53:06', 7, 70.00);

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

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ip` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Nb_tours` int NOT NULL,
  `Timestamp_` datetime DEFAULT NULL,
  `victoires` int NOT NULL,
  `Taux_reussite` decimal(6,2) DEFAULT NULL,
  `defaites` int NOT NULL DEFAULT '0',
  `parties` int NOT NULL DEFAULT '0',
  `IP_Joueur` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Pseudo_joueur` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`user_id`, `username`, `ip`, `password`, `Nb_tours`, `Timestamp_`, `victoires`, `Taux_reussite`, `defaites`, `parties`, `IP_Joueur`, `Pseudo_joueur`) VALUES
(1, 'Matthyeu', '32.24.174.106', '$2y$10$IVVQiyGVtSnxdsHTEZRLauTxMIhUmQgVrXTB.nCjDCzIbJD/OAN9i', 40, '2025-11-24 09:57:27', 27, 67.50, 0, 0, '', ''),
(2, 'Hadrien', '57.87.167.234', '$2y$10$bz93h9EH5QkS2DMDQeZwwuwWv1LbtDoZjqUfyNxS0Imlblux0iCZ.', 36, '2025-11-24 10:57:54', 23, 63.89, 0, 0, '', ''),
(3, 'Gabriella', '34.213.209.30', '$2y$10$5ta.RKt13XcJtrB2rG4Sc.R3SYOpg7fskOv02nVaOliMvAi7q9jVm', 20, '2025-11-24 11:45:46', 20, 100.00, 0, 0, '', ''),
(4, 'Dorian', '250.28.127.76', '$2y$10$gaRAxOgEVZsYr1Z2bh7PjeHT1N4Q..f/jxNjTUhPARC3/T8H.byg6', 10, '2025-11-24 11:47:15', 5, 50.00, 0, 0, '', ''),
(5, 'Gireg', '135.152.74.199', '$2y$10$Mot8JYV3iWyXnl2eltqUqOGXvPJPHGoZXnGyURWNQErwjRoFp1.ca', 19, '2025-11-24 11:04:09', 13, 68.42, 0, 0, '', ''),
(6, 'Maël', '216.91.215.148', '$2y$10$UEAdfkMJE9lfOtC6j0/7v.3w9yK1ZxtJ4mSr240PjRbIK1Fo7UG.C', 14, '2025-11-24 11:00:33', 3, 21.43, 0, 0, '', ''),
(7, 'Erwann', '97.27.118.230', '$2y$10$R356B4wXotfm7kFln9wDjuKaUbo7jhF5CFVa/eH84Qmz4pzKW9lrG', 259, '2025-11-24 09:57:27', 0, 0.00, 0, 0, '', ''),
(8, 'Thomas', '27.53.63.200', '$2y$10$P9iTwBWEkBoh059IIp2xZu7bw4aV7UYne0n1kOHPdQfF93IJ7X7ry', 74, '2025-11-24 12:38:14', 59, 79.73, 0, 0, '', ''),
(9, 'Hamza', '251.178.169.242', '$2y$10$apsgFCrVe1LmBLUlfjHmoumof.Xq/PgATOCDEm5UwiUGOaZQQ7mQm', 10, '2025-11-24 12:49:54', 8, 80.00, 0, 0, '', ''),
(12, 'silver5', '98.365.44.12', '$2y$10$pNBS6dswnE41ZK6rvnV8BewBN6wMt7pIrYYzETjkey74lYNIYlCY6', 10, '2026-01-12 14:53:06', 7, 70.00, 0, 0, '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
