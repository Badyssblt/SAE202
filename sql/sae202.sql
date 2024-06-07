-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 07 juin 2024 à 16:17
-- Version du serveur : 10.3.29-MariaDB-0+deb10u1
-- Version de PHP : 8.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sae202`
--

-- --------------------------------------------------------

--
-- Structure de la table `Jardin`
--

CREATE TABLE `Jardin` (
  `jardin_id` int(11) NOT NULL,
  `jardin_nom` varchar(255) DEFAULT NULL,
  `jardin_position` text DEFAULT NULL,
  `jardin_image` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Jardin`
--

INSERT INTO `Jardin` (`jardin_id`, `jardin_nom`, `jardin_position`, `jardin_image`, `user_id`, `is_public`) VALUES
(19, 'Jardin du Beffroi ', '48.2957444,3.9851243,12', '2024_06_04_14_36_19---19193090.jpg', 3, 1),
(24, 'Jardin des innocents', '48.297389,3.989107,12', '2024_06_04_17_59_39---19193203-diaporama.jpg', 3, 0),
(25, 'Jardin Juvenal des ursins', '48.2968381,3.9900704,12', '2024_06_04_18_32_23---httpsstaticapidaetourismecomfilestoreobjetstouristiquesimages25322219193597jpg.jpg', 13, 0),
(27, 'Square d’Urmitz', '48.3017906,4.0408917,17', '2024_06_06_09_07_02---19193541 (1).jpg', 19, 1),
(28, 'test', '48.3017906,4.0408917,17', '2024_06_06_09_07_48---19193541 (1).jpg', 19, 1);

-- --------------------------------------------------------

--
-- Structure de la table `parcelle`
--

CREATE TABLE `parcelle` (
  `parcelle_id` int(11) NOT NULL,
  `jardin_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parcelle_type` varchar(255) DEFAULT NULL,
  `isAccepted` tinyint(1) NOT NULL,
  `parcelle_nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `parcelle`
--

INSERT INTO `parcelle` (`parcelle_id`, `jardin_id`, `user_id`, `parcelle_type`, `isAccepted`, `parcelle_nom`) VALUES
(51, 19, 3, 'Blé', 1, 'Parcelle n°1'),
(62, 19, 3, 'test', 1, 'test'),
(63, 19, 3, 'test', 1, 'test');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_nom` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_password` text DEFAULT NULL,
  `user_picture` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `user_nom`, `user_email`, `user_password`, `user_picture`) VALUES
(3, 'test', 'test@gmail.com', '$2y$10$K/Txn38O60wg9J5Vhnmpr.HhWwGs0xPfCWnqc5Z0IzpXa59uPewPW', 'test'),
(13, 'Badyss', 'badyss@gmail.com', '$2y$10$7Ukt48kywvH585EZKYuwMu7OguoPfzxZfzdSzD552/ykhJfBQ4aKa', '2024_06_06_09_03_00---24222e52d37fdbfc2993517e5885cb26 (1).jpg'),
(14, 'Mathias', 'mathias@gmail.com', '$2y$10$actn/u.EXgYmka27PvVTKOz01MS.mjr3S7xcdv76KOvHFQFjeddDq', 'test'),
(19, 'Badyss Blilita', 'badyss.blt@gmail.com', '$2y$10$mWGFxVxycDM6JX4iObNWYe9KRvLlvXXueAno6.WdfraIeVFxpk.0S', '2024_06_06_09_03_37---24222e52d37fdbfc2993517e5885cb26 (1).jpg'),
(20, 'Alhadji', 'sidibahsal@gmail.com', '$2y$10$5IzQqXvcfX.3QmqzIgCgEOvJxUIqG9lLW6lnJa3rM2hk/4oH93i3S', 'test'),
(21, 'beb', 'a@gmail.fr', '$2y$10$aJJG8AgAs1wr7Bh8rhhhBOTYEOBsN.w6ErMaCDx4CGv8if67bKqv6', 'test');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Jardin`
--
ALTER TABLE `Jardin`
  ADD PRIMARY KEY (`jardin_id`),
  ADD KEY `Jardin_ibfk_1` (`user_id`);

--
-- Index pour la table `parcelle`
--
ALTER TABLE `parcelle`
  ADD PRIMARY KEY (`parcelle_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parcelle_ibfk_1` (`jardin_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Jardin`
--
ALTER TABLE `Jardin`
  MODIFY `jardin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `parcelle`
--
ALTER TABLE `parcelle`
  MODIFY `parcelle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Jardin`
--
ALTER TABLE `Jardin`
  ADD CONSTRAINT `Jardin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `parcelle`
--
ALTER TABLE `parcelle`
  ADD CONSTRAINT `parcelle_ibfk_1` FOREIGN KEY (`jardin_id`) REFERENCES `Jardin` (`jardin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parcelle_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
