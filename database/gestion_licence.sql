-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 27 avr. 2026 à 14:58
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
-- Base de données : `article`
--
CREATE DATABASE IF NOT EXISTS `article` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `article`;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `Identifiant` int NOT NULL AUTO_INCREMENT,
  `Titre` varchar(150) NOT NULL,
  `contenu` varchar(1500) NOT NULL,
  `categorie` varchar(10) NOT NULL,
  PRIMARY KEY (`Identifiant`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`Identifiant`, `Titre`, `contenu`, `categorie`) VALUES
(1, '', '', 'Développem'),
(3, 'Article sur les animaux', 'l\'article parle majoritairement d\'animaux, notamment de mammoifères', 'chepa');
--
-- Base de données : `exo`
--
CREATE DATABASE IF NOT EXISTS `exo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `exo`;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `Num_cli` int NOT NULL AUTO_INCREMENT,
  `Nom_cli` varchar(50) NOT NULL,
  `Localite_cli` varchar(500) NOT NULL,
  `dept_cli` int NOT NULL,
  `compte_cli` float NOT NULL,
  PRIMARY KEY (`Num_cli`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`Num_cli`, `Nom_cli`, `Localite_cli`, `dept_cli`, `compte_cli`) VALUES
(1, 'IDASIAK', 'SENLIS', 60, 500),
(2, 'AMMAR', 'PARIS', 75, 5000),
(3, 'DANIEL', 'MARSEILLE', 60, -500),
(4, 'KINTZLER', 'LILLE', 59, 1500),
(5, 'JULLIEN', 'APREMONT', 60, 5000),
(6, 'ROUGET', 'SENLIS', 60, 0),
(7, 'AOUDAY', 'SENLIS', 60, 10),
(8, 'SABRI', 'SENLIS', 60, 20);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `Num_com` int NOT NULL AUTO_INCREMENT,
  `Num_cli` int DEFAULT NULL,
  `Date_com` date NOT NULL,
  PRIMARY KEY (`Num_com`),
  KEY `Num_cli` (`Num_cli`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`Num_com`, `Num_cli`, `Date_com`) VALUES
(1, 1, '2018-01-01'),
(2, 1, '2018-01-02'),
(3, 1, '2018-01-03'),
(4, 2, '2018-01-04'),
(5, 3, '2018-01-06'),
(6, 2, '2021-01-05'),
(8, 2, '2018-04-23');

-- --------------------------------------------------------

--
-- Structure de la table `detail`
--

DROP TABLE IF EXISTS `detail`;
CREATE TABLE IF NOT EXISTS `detail` (
  `Num_com` int NOT NULL,
  `Num_pro` int NOT NULL,
  `Qte_pro` int NOT NULL,
  PRIMARY KEY (`Num_com`,`Num_pro`),
  KEY `FK_detail2` (`Num_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `detail`
--

INSERT INTO `detail` (`Num_com`, `Num_pro`, `Qte_pro`) VALUES
(1, 1, 1),
(1, 5, 5),
(1, 6, 4),
(1, 7, 10),
(1, 9, 2),
(2, 2, 1),
(3, 3, 1),
(4, 2, 110),
(4, 8, 10),
(5, 8, 10),
(6, 5, 50);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `Num_pro` int NOT NULL AUTO_INCREMENT,
  `Libelle_pro` varchar(500) NOT NULL,
  `prix_pro` float NOT NULL,
  `Qte_stock_pro` int NOT NULL,
  PRIMARY KEY (`Num_pro`),
  UNIQUE KEY `Libelle_pro` (`Libelle_pro`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`Num_pro`, `Libelle_pro`, `prix_pro`, `Qte_stock_pro`) VALUES
(1, 'SAPIN VERT', 50, 50),
(2, 'SAPIN BLANC', 55, 10),
(3, 'SAPIN PERSONNALISE', 100, 10),
(4, 'guirlande blanche', 10, 50),
(5, 'guirlande jaune', 15, 150),
(6, 'boule bleue', 5, 200),
(7, 'boule jaune', 7, 300),
(8, 'boule noire', 15, 25),
(9, 'boule verte', 8, 50);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_comcli` FOREIGN KEY (`Num_cli`) REFERENCES `client` (`Num_cli`);

--
-- Contraintes pour la table `detail`
--
ALTER TABLE `detail`
  ADD CONSTRAINT `FK_detail1` FOREIGN KEY (`Num_com`) REFERENCES `commande` (`Num_com`),
  ADD CONSTRAINT `FK_detail2` FOREIGN KEY (`Num_pro`) REFERENCES `produit` (`Num_pro`);
--
-- Base de données : `gestion_licence`
--
CREATE DATABASE IF NOT EXISTS `gestion_licence` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `gestion_licence`;

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `intervention_type_id` int NOT NULL,
  `module_id` int NOT NULL,
  `remotely` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_course_intervention_type` (`intervention_type_id`),
  KEY `fk_course_module` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id`, `start_date`, `end_date`, `intervention_type_id`, `module_id`, `remotely`, `title`) VALUES
(1, '2025-09-01 08:00:00', '2025-09-01 17:00:00', 4, 1, 0, NULL),
(2, '2025-09-08 08:00:00', '2025-09-08 17:00:00', 4, 1, 0, NULL),
(3, '2025-09-15 08:00:00', '2025-09-15 17:00:00', 4, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `course_instructor`
--

DROP TABLE IF EXISTS `course_instructor`;
CREATE TABLE IF NOT EXISTS `course_instructor` (
  `course_id` int NOT NULL,
  `instructor_id` int NOT NULL,
  PRIMARY KEY (`course_id`,`instructor_id`),
  KEY `fk_ci_instructor` (`instructor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `course_instructor`
--

INSERT INTO `course_instructor` (`course_id`, `instructor_id`) VALUES
(1, 1),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `instructor`
--

DROP TABLE IF EXISTS `instructor`;
CREATE TABLE IF NOT EXISTS `instructor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_instructor_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `instructor`
--

INSERT INTO `instructor` (`id`, `user_id`) VALUES
(1, 2),
(5, 6),
(6, 7),
(7, 10);

-- --------------------------------------------------------

--
-- Structure de la table `instructor_module`
--

DROP TABLE IF EXISTS `instructor_module`;
CREATE TABLE IF NOT EXISTS `instructor_module` (
  `instructor_id` int NOT NULL,
  `module_id` int NOT NULL,
  PRIMARY KEY (`instructor_id`,`module_id`),
  KEY `fk_im_module` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `instructor_module`
--

INSERT INTO `instructor_module` (`instructor_id`, `module_id`) VALUES
(1, 1),
(7, 1),
(1, 2),
(7, 2);

-- --------------------------------------------------------

--
-- Structure de la table `intervention_type`
--

DROP TABLE IF EXISTS `intervention_type`;
CREATE TABLE IF NOT EXISTS `intervention_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `intervention_type`
--

INSERT INTO `intervention_type` (`id`, `name`, `description`, `color`) VALUES
(4, 'CM', 'Cours magistral', '#0277BD'),
(5, 'Soutenance', 'Soutenance de fin de projet', '#2E7D32'),
(7, 'TD', 'Travaux dirigés en petit groupe', '#6750A4'),
(8, 'TP', 'Travaux pratiques en laboratoire ou salle informatique', '#AD1457'),
(9, 'Projet', 'Séance dédiée au travail sur projet', '#4527A0'),
(10, 'Réunion', 'Réunion pédagogique ou administrative', '#558B2F'),
(11, 'Visite', 'Visite d\'entreprise ou de site', '#4E342E');

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hours_count` tinyint UNSIGNED NOT NULL,
  `capstone_project` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`),
  KEY `fk_module_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `code`, `parent_id`, `name`, `description`, `hours_count`, `capstone_project`) VALUES
(1, 'GIT', NULL, 'Git', 'Gestion de version avec Git', 20, 0),
(2, 'ENV-TRAV', NULL, 'Environnement de travail', 'Configuration de l\'environnement de dev', 15, 0),
(3, 'PYTHON', NULL, 'Python', 'Programmation Python', 30, 0),
(4, 'JS', NULL, 'JavaScript', 'Développement JavaScript', 25, 0),
(5, 'SQL', NULL, 'SQL', 'Bases de données SQL', 20, 0),
(6, 'REACT', NULL, 'React', 'Framework React', 20, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` enum('admin','instructor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `role`, `email`, `last_name`, `first_name`, `password`) VALUES
(1, 'admin', 'ribas@gmail.com', '', '', '$2a$12$Z9KHw3HikeG10BI0mqvsHO0gDqFl5AbAijuieIP6opcNe9lOgISnS'),
(2, 'instructor', 'j.martins@mentalworks.fr', 'Martins-Jacquelot', 'Jeff', '$2a$12$placeholder'),
(6, 'instructor', 'j.martinss@mentalworks.fr', 'Evian', 'Fethi', '$2a$12$placeholder'),
(7, 'instructor', 'j.martinsss@mentalworks.fr', 'Test', 'Mika', '$2a$12$placeholder'),
(8, 'instructor', 'j.martinssss@mentalworks.fr', 'Evian', 'Fethi', '$2a$12$placeholder'),
(9, 'instructor', 'j.martinsssss@mentalworks.fr', 'Evian', 'Fethi', '$2a$12$placeholder'),
(10, 'instructor', 'test@aol.com', 'IDASIAK', 'Mikaelllll', '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `fk_course_intervention_type` FOREIGN KEY (`intervention_type_id`) REFERENCES `intervention_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_course_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `course_instructor`
--
ALTER TABLE `course_instructor`
  ADD CONSTRAINT `fk_ci_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ci_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `fk_instructor_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `instructor_module`
--
ALTER TABLE `instructor_module`
  ADD CONSTRAINT `fk_im_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_im_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `fk_module_parent` FOREIGN KEY (`parent_id`) REFERENCES `module` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
--
-- Base de données : `sessions`
--
CREATE DATABASE IF NOT EXISTS `sessions` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `sessions`;

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `date_connexion` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
--
-- Base de données : `tierlist_db`
--
CREATE DATABASE IF NOT EXISTS `tierlist_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `tierlist_db`;

-- --------------------------------------------------------

--
-- Structure de la table `tierlists`
--

DROP TABLE IF EXISTS `tierlists`;
CREATE TABLE IF NOT EXISTS `tierlists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `elements` json NOT NULL,
  `tiers` json NOT NULL,
  `positions` json NOT NULL,
  `share_url` varchar(225) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `share_url` (`share_url`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tierlists`
--

INSERT INTO `tierlists` (`id`, `title`, `elements`, `tiers`, `positions`, `share_url`, `created_at`) VALUES
(1, 'Tier list', '[{\"id\": \"1\", \"name\": \"BMW\"}, {\"id\": \"2\", \"name\": \"Citroen\"}, {\"id\": \"3\", \"name\": \"Chevrolet\"}, {\"id\": \"4\", \"name\": \"Ferrari\"}, {\"id\": \"5\", \"name\": \"McLaren\"}, {\"id\": \"6\", \"name\": \"Mercedes\"}]', '[\"S\", \"A\", \"B\", \"C\", \"F\"]', '[]', '74e46ea005a8062f', '2026-03-05 12:56:00'),
(2, 'Tier list', '[{\"id\": \"1\", \"name\": \"BMW\"}, {\"id\": \"2\", \"name\": \"Citroen\"}, {\"id\": \"3\", \"name\": \"Chevrolet\"}, {\"id\": \"4\", \"name\": \"Ferrari\"}, {\"id\": \"5\", \"name\": \"McLaren\"}, {\"id\": \"6\", \"name\": \"Mercedes\"}]', '[\"S\", \"A\", \"B\", \"C\", \"F\"]', '[]', '73e6be9fb2d05c1b', '2026-03-05 12:56:08'),
(3, 'Tier list', '[{\"id\": \"1\", \"name\": \"BMW\"}, {\"id\": \"2\", \"name\": \"Citroen\"}, {\"id\": \"3\", \"name\": \"Chevrolet\"}, {\"id\": \"4\", \"name\": \"Ferrari\"}, {\"id\": \"5\", \"name\": \"McLaren\"}, {\"id\": \"6\", \"name\": \"Mercedes\"}]', '[\"S\", \"A\", \"B\", \"C\", \"F\"]', '{\"1\": \"F\"}', '9bd54540e662a342', '2026-03-05 12:57:36'),
(4, 'Tier list', '[{\"id\": \"4\", \"name\": \"Ferrari\"}, {\"id\": \"5\", \"name\": \"McLaren\"}, {\"id\": \"1\", \"name\": \"BMW\"}, {\"id\": \"6\", \"name\": \"Mercedes\"}, {\"id\": \"3\", \"name\": \"Chevrolet\"}, {\"id\": \"2\", \"name\": \"Citroen\"}]', '[\"S\", \"A\", \"B\", \"C\", \"F\"]', '{\"1\": \"S\", \"2\": \"B\", \"3\": \"A\", \"4\": \"S\", \"5\": \"S\", \"6\": \"A\"}', 'e0a927ff01ae6723', '2026-03-05 12:57:53');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
