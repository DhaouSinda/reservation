-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 12 sep. 2026 à 02:19
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reservation_salles`
--

-- --------------------------------------------------------

--
-- Structure de la table `batiments`
--

CREATE TABLE `batiments` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `batiments`
--

INSERT INTO `batiments` (`id`, `nom`, `adresse`) VALUES
(2, 'Batiment 2', '456'),
(4, 'Batiment 1', '123'),
(5, 'Batiment 3', '789');

-- --------------------------------------------------------

--
-- Structure de la table `etages`
--

CREATE TABLE `etages` (
  `id` int(11) NOT NULL,
  `batiment_id` int(11) NOT NULL,
  `numero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etages`
--

INSERT INTO `etages` (`id`, `batiment_id`, `numero`) VALUES
(3, 2, 5),
(4, 4, 0),
(5, 4, 1),
(6, 4, 2),
(7, 4, 3),
(8, 4, 4),
(9, 4, 5),
(10, 4, 6),
(11, 5, 1),
(12, 5, 2),
(13, 5, 3),
(14, 5, 4),
(15, 5, 5);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `salle_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `motif` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','validee','refusee','annulee') DEFAULT 'en_attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `salle_id`, `utilisateur_id`, `date_debut`, `date_fin`, `motif`, `statut`, `created_at`) VALUES
(1, 1, 4, '2026-12-04 17:00:00', '2026-12-05 17:00:00', 'nuit', 'validee', '2026-08-28 01:57:00'),
(2, 1, 2, '2027-06-05 10:00:00', '2027-06-05 11:00:00', 'reu', 'annulee', '2026-08-29 15:57:25'),
(3, 1, 2, '2026-09-04 08:00:00', '2026-09-04 08:30:00', '', 'validee', '2026-09-03 21:01:22'),
(4, 2, 2, '2026-09-04 08:30:00', '2026-09-04 10:00:00', 'reunion', 'validee', '2026-09-03 21:06:44'),
(5, 2, 3, '2026-09-04 10:00:00', '2026-09-04 12:30:00', 'reunion', 'validee', '2026-09-03 21:07:52'),
(6, 2, 3, '2026-09-12 10:00:00', '2026-09-12 22:09:00', 'reunion', 'validee', '2026-09-03 21:10:01'),
(7, 3, 5, '2026-09-09 07:30:00', '2026-09-09 10:00:00', 'reunion', 'validee', '2026-09-06 16:28:25'),
(8, 3, 6, '2026-09-12 08:00:00', '2026-09-12 10:30:00', 'reunion', 'validee', '2026-09-09 14:02:09'),
(9, 2, 7, '2026-09-11 08:00:00', '2026-09-11 10:45:00', 'reunion', 'refusee', '2026-09-09 23:19:05'),
(10, 3, 6, '2026-09-12 00:28:00', '2026-09-12 03:29:00', 'reunion', 'validee', '2026-09-09 23:29:13'),
(11, 3, 7, '2026-09-11 12:00:00', '2026-09-11 14:00:00', 'reunion', 'validee', '2026-09-10 10:31:37'),
(12, 1, 7, '2026-09-11 08:00:00', '2026-09-11 09:00:00', 'reunion', 'validee', '2026-09-10 12:01:44'),
(13, 4, 8, '2026-09-11 07:00:00', '2026-09-12 09:00:00', 'reunion', 'annulee', '2026-09-10 12:29:58'),
(14, 5, 7, '2026-09-12 12:30:00', '2026-09-12 15:00:00', 'reunion', 'en_attente', '2026-09-12 00:08:03');

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `etage_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `capacite` int(11) NOT NULL,
  `equipements` varchar(255) DEFAULT NULL,
  `localisation` varchar(150) DEFAULT NULL,
  `statut` enum('disponible','maintenance','indisponible') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `etage_id`, `nom`, `capacite`, `equipements`, `localisation`, `statut`) VALUES
(1, 3, '2A', 4, 'Wifi , Climatiseur', 'ze', 'disponible'),
(2, 7, '3D', 8, 'Wifi , Climatiseur', 'rrr', 'maintenance'),
(3, 9, '1D', 10, 'Wifi , Climatiseur', 'z', 'disponible'),
(4, 11, '5C', 2, 'Wifi , TV', 'c', 'indisponible'),
(5, 8, '2D', 8, 'Wifi', 'b', 'disponible');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin_batiments','gestionnaire','utilisateur') NOT NULL DEFAULT 'utilisateur',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'elaa', 'dkhil', 'elaa@gmail.com', '$2y$10$LDy.hmpj67Z.QxWWO1v75ebWcHQ0ILl3grGTR9MgC4cqz7qe1vK0C', 'utilisateur', '2026-08-27 10:49:16'),
(2, 'ons', 'fares', 'ons@gmail.com', '$2y$10$JnTXj9Hp0dWIgkTkWWgGXu/r3iGahXd6g2MrbkW.YmcSAI98M/mSW', 'utilisateur', '2026-08-27 11:07:17'),
(3, 'mariem', 'akaichi', 'mariem@gmail.com', '$2y$10$i9LPOpnEbiwAOHkRD.04v.foIMTOaE/D8rSxnvNRF03AhfpB3VwVC', 'utilisateur', '2026-08-27 11:08:20'),
(4, 'kmar', 'ben mansour', 'kmar@gmail.com', '$2y$10$lrGZZZPgcyggqsuzvlrI3ee7WaYJkC8GiXUQEu9/1XAq0I3kH5i.a', 'gestionnaire', '2026-08-28 01:55:46'),
(5, 'sindaa', 'dh', 'dhaousinda2004@gmail.com', '$2y$10$O1.LQGb.ifYvF9N1BUM6VO29jZxN/HoFGlVWV7O/zoeiHgFBgOHoC', 'admin_batiments', '2026-09-06 16:27:06'),
(6, 'eya', 'barkia', 'eya@gmail.com', '$2y$10$Q3w2Zbg8yomSWmxsuLrNK.MlUEdxPffFB7wWp4/xktZDua8bjLY0G', 'utilisateur', '2026-09-09 14:01:27'),
(7, 'Dhaou', 'Sinda', 'sinda.dhaou@esprit.tn', '$2y$10$xUPkWMXzWrmWbpeIEAuoBeiQVP3i4Lf9tl9/7Y6AKAWL80/HlVXsq', 'utilisateur', '2026-09-09 23:04:32'),
(8, 'guest', 'guest', 'donia.riahi@esprit.tn', '$2y$10$X.fb7EYyVKJtuZlMR4eqIuQy4L4VLtSjLdUBEAu0bOTczOYFwI5Wu', 'utilisateur', '2026-09-10 12:26:46'),
(9, 'hend', 'dridi', 'hend@gmail.com', '$2y$10$QDtzazFuzCwiQKd3ishoYuC.CKavcZ6CNGXLWaI07YuHEnuyRnvv6', 'utilisateur', '2026-09-12 00:02:14');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `batiments`
--
ALTER TABLE `batiments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etages`
--
ALTER TABLE `etages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batiment_id` (`batiment_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salle_id` (`salle_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etage_id` (`etage_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `batiments`
--
ALTER TABLE `batiments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `etages`
--
ALTER TABLE `etages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etages`
--
ALTER TABLE `etages`
  ADD CONSTRAINT `etages_ibfk_1` FOREIGN KEY (`batiment_id`) REFERENCES `batiments` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `salles`
--
ALTER TABLE `salles`
  ADD CONSTRAINT `salles_ibfk_1` FOREIGN KEY (`etage_id`) REFERENCES `etages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
