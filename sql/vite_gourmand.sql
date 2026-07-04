-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 04 juil. 2026 à 19:32
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
-- Base de données : `vite_gourmand`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergene`
--

CREATE TABLE `allergene` (
  `idAllergene` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `allergene`
--

INSERT INTO `allergene` (`idAllergene`, `nom`) VALUES
(1, 'Gluten'),
(2, 'Crustacés'),
(3, 'Œufs'),
(4, 'Poissons'),
(5, 'Arachides'),
(6, 'Soja'),
(7, 'Lait'),
(8, 'Fruits à coque'),
(9, 'Céleri'),
(10, 'Moutarde'),
(11, 'Graines de sésame'),
(12, 'Sulfites'),
(13, 'Lupin'),
(14, 'Mollusques');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `idAvis` int(11) NOT NULL,
  `note` int(11) NOT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL,
  `valide` tinyint(1) DEFAULT 0,
  `dateAvis` datetime DEFAULT current_timestamp(),
  `idUtilisateur` int(11) NOT NULL,
  `idCommande` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`idAvis`, `note`, `commentaire`, `valide`, `dateAvis`, `idUtilisateur`, `idCommande`) VALUES
(2, 3, 'arrivé un peu tardiive', 1, '2026-06-18 22:32:51', 2, 4),
(3, 5, 'Livrée en temps et en heure, tout c\'est bien passée. le livreur était très agréables. On recommandera chez ce traiteur et on le recommande fortement pour ceux qui hésite', 1, '2026-07-04 13:09:44', 2, 5),
(4, 4, 'Très bon service , je recommande', 1, '2026-07-04 13:37:48', 2, 6);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idCommande` int(11) NOT NULL,
  `dateCommande` datetime DEFAULT current_timestamp(),
  `dateLivraison` date NOT NULL,
  `heureLivraison` time NOT NULL,
  `adresseLivraison` varchar(255) NOT NULL,
  `nbPersonnes` int(11) NOT NULL,
  `prixTotal` decimal(10,2) NOT NULL,
  `statut` enum('EN_ATTENTE','ACCEPTEE','EN_PREPARATION','EN_LIVRAISON','LIVREE','EN_ATTENTE_MATERIEL','TERMINEE','ANNULEE') DEFAULT 'EN_ATTENTE',
  `idUtilisateur` int(11) NOT NULL,
  `prixMenu` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pretMateriel` tinyint(1) NOT NULL DEFAULT 0,
  `restitutionMateriel` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idCommande`, `dateCommande`, `dateLivraison`, `heureLivraison`, `adresseLivraison`, `nbPersonnes`, `prixTotal`, `statut`, `idUtilisateur`, `prixMenu`, `pretMateriel`, `restitutionMateriel`) VALUES
(4, '2026-06-15 21:41:24', '2026-06-15', '01:40:00', '58 rues des pam', 8, 165.00, 'TERMINEE', 2, 160.00, 0, 0),
(5, '2026-07-01 16:51:09', '2026-07-10', '17:50:00', '58 rues des tomates', 10, 153.00, 'TERMINEE', 2, 0.00, 0, 0),
(6, '2026-07-01 16:52:38', '2026-07-10', '17:50:00', '58 rues des tomates', 10, 153.00, 'TERMINEE', 2, 0.00, 0, 0),
(7, '2026-07-01 18:41:49', '2026-07-11', '21:41:00', '58 rues des tomates', 8, 224.00, 'EN_ATTENTE', 2, 0.00, 0, 0),
(8, '2026-07-01 18:42:19', '2026-07-01', '19:42:00', '58 rues des tomates', 10, 162.00, 'EN_ATTENTE', 2, 0.00, 0, 0),
(9, '2026-07-01 18:42:56', '2026-07-02', '19:42:00', '58 rues des tomates', 8, 128.00, 'EN_ATTENTE', 2, 0.00, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `commande_materiel`
--

CREATE TABLE `commande_materiel` (
  `idCommandeMateriel` int(11) NOT NULL,
  `idCommande` int(11) NOT NULL,
  `idMateriel` int(11) NOT NULL,
  `quantite` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande_menu`
--

CREATE TABLE `commande_menu` (
  `idCommandeMenu` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prixUnitaire` decimal(10,2) NOT NULL,
  `idCommande` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_menu`
--

INSERT INTO `commande_menu` (`idCommandeMenu`, `quantite`, `prixUnitaire`, `idCommande`, `idMenu`) VALUES
(4, 1, 0.00, 5, 1),
(5, 1, 0.00, 6, 1),
(6, 1, 0.00, 7, 2),
(7, 1, 0.00, 8, 3),
(8, 1, 0.00, 9, 4);

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `idContact` int(11) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `email` varchar(150) NOT NULL,
  `dateContact` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`idContact`, `titre`, `message`, `email`, `dateContact`) VALUES
(1, 'bug', 'impossible de voir mes commandes', 'test@gmail.com', '2026-06-14 17:00:13'),
(15, 'bug', 'ssvsv', 'test@gmail.com', '2026-06-14 19:04:55'),
(16, 'bug', 'fsfsfsfs', 'gggg@gmail.com', '2026-06-15 14:05:14'),
(17, 'bug', 'gdgdg', 'test@gmail.com', '2026-06-15 14:10:36'),
(18, 'bug', 'bug', 'test@gmail.com', '2026-06-29 22:25:12');

-- --------------------------------------------------------

--
-- Structure de la table `historique_statut`
--

CREATE TABLE `historique_statut` (
  `idHistorique` int(11) NOT NULL,
  `statut` varchar(100) NOT NULL,
  `dateStatut` datetime DEFAULT current_timestamp(),
  `commentaire` text DEFAULT NULL,
  `idCommande` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique_statut`
--

INSERT INTO `historique_statut` (`idHistorique`, `statut`, `dateStatut`, `commentaire`, `idCommande`) VALUES
(10, 'EN_ATTENTE', '2026-06-15 21:41:24', 'Commande créée', 4),
(15, 'ACCEPTEE', '2026-06-16 19:08:47', NULL, 4),
(16, 'EN_ATTENTE', '2026-06-16 19:09:39', NULL, 4),
(17, 'EN_ATTENTE', '2026-06-16 19:10:20', NULL, 4),
(18, 'ACCEPTEE', '2026-06-16 19:14:29', NULL, 4),
(19, 'EN_ATTENTE', '2026-06-17 15:54:07', NULL, 4),
(20, 'TERMINEE', '2026-06-17 21:11:38', NULL, 4),
(21, 'EN_ATTENTE', '2026-06-23 19:48:33', NULL, 4),
(22, 'ACCEPTEE', '2026-06-24 17:23:01', NULL, 4),
(23, 'TERMINEE', '2026-06-29 17:06:45', NULL, 4),
(24, 'EN_ATTENTE', '2026-07-01 16:51:09', 'Commande créée', 5),
(25, 'EN_ATTENTE', '2026-07-01 16:52:38', 'Commande créée', 6),
(26, 'EN_ATTENTE', '2026-07-01 18:41:49', 'Commande créée', 7),
(27, 'EN_ATTENTE', '2026-07-01 18:42:19', 'Commande créée', 8),
(28, 'EN_ATTENTE', '2026-07-01 18:42:56', 'Commande créée', 9),
(29, 'EN_ATTENTE', '2026-07-04 00:12:04', NULL, 9),
(30, 'TERMINEE', '2026-07-04 13:06:47', NULL, 5),
(31, 'TERMINEE', '2026-07-04 13:36:32', NULL, 6);

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

CREATE TABLE `horaire` (
  `idHoraire` int(11) NOT NULL,
  `jour` varchar(20) NOT NULL,
  `heureOuverture` time NOT NULL,
  `heureFermeture` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`idHoraire`, `jour`, `heureOuverture`, `heureFermeture`) VALUES
(1, 'Lundi', '09:00:00', '18:00:00'),
(2, 'Mardi', '09:00:00', '18:00:00'),
(3, 'Mercredi', '09:00:00', '18:00:00'),
(4, 'Jeudi', '09:00:00', '18:00:00'),
(5, 'Vendredi', '09:00:00', '18:00:00'),
(6, 'Samedi', '09:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `materiel`
--

CREATE TABLE `materiel` (
  `idMateriel` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `materiel`
--

INSERT INTO `materiel` (`idMateriel`, `nom`) VALUES
(1, 'Table'),
(2, 'Chaise'),
(3, 'Vaisselle'),
(4, 'Verre'),
(5, 'Nappe'),
(6, 'Plateau'),
(7, 'Chauffe-plat'),
(8, 'Couverts');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `idMenu` int(11) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `theme` varchar(100) DEFAULT NULL,
  `regime` varchar(100) DEFAULT NULL,
  `nbPersonnesMin` int(11) NOT NULL,
  `prixParPersonne` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `conditions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`idMenu`, `titre`, `description`, `theme`, `regime`, `nbPersonnesMin`, `prixParPersonne`, `stock`, `conditions`) VALUES
(1, 'Menu Pizza Party', 'Buffet convivial composé de pizzas artisanales variées, salades fraîches et desserts maison.', 'Pizza', 'Classique', 5, 17.00, 10, 'Commande minimum 48h avant la prestation. Conservation au frais recommandée.'),
(2, 'Menu Noël Prestige', 'Menu festif haut de gamme avec foie gras, saumon fumé, chapon farci et dessert de Noël.', 'Noël', 'Classique', 8, 28.00, 5, 'Commande obligatoire 7 jours avant Noël. Produits frais à conserver entre 0 et 4 degrés.'),
(3, 'Menu Tradition', 'Cuisine française traditionnelle avec entrée, plat chaud et dessert artisanal.', 'Tradition', 'Classique', 5, 18.00, 7, 'Commande minimum 72h avant la livraison.'),
(4, 'Menu Green Vegan', 'Menu 100% vegan composé de produits frais, légumes de saison et desserts végétaux.', 'Vegetal', 'Vegan', 6, 16.00, 8, 'Conservation au frais recommandée après livraison.');

-- --------------------------------------------------------

--
-- Structure de la table `menu_plat`
--

CREATE TABLE `menu_plat` (
  `idMenu` int(11) NOT NULL,
  `idPlat` int(11) NOT NULL,
  `typeMenu` enum('entree','plat','dessert') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `menu_plat`
--

INSERT INTO `menu_plat` (`idMenu`, `idPlat`, `typeMenu`) VALUES
(1, 1, 'entree'),
(1, 2, 'plat'),
(1, 3, 'dessert'),
(2, 4, 'entree'),
(2, 5, 'plat'),
(2, 6, 'dessert'),
(3, 7, 'entree'),
(3, 8, 'plat'),
(3, 9, 'dessert'),
(4, 10, 'entree'),
(4, 11, 'plat'),
(4, 12, 'dessert');

-- --------------------------------------------------------

--
-- Structure de la table `plat`
--

CREATE TABLE `plat` (
  `idPlat` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `type` enum('entree','plat','dessert') NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plat`
--

INSERT INTO `plat` (`idPlat`, `nom`, `type`, `photo`) VALUES
(1, 'Bruschetta italienne', 'entree', 'bruschetta.jpg'),
(2, 'Pizza Burrata & Jambon cru', 'plat', 'pizza-burrata.jpg'),
(3, 'Tiramisu maison', 'dessert', 'tiramisu.jpg'),
(4, 'Foie gras et pain d\'épices', 'entree', 'foie-gras.jpg'),
(5, 'Suprême de chapon aux morilles', 'plat', 'chapon.jpg'),
(6, 'Bûche de Noël artisanale', 'dessert', 'buche-noel.jpg'),
(7, 'Œufs mimosa', 'entree', 'oeufs-mimosa.jpg'),
(8, 'Blanquette de veau à l\'ancienne', 'plat', 'blanquette.jpg'),
(9, 'Tarte aux pommes', 'dessert', 'tarte-pommes.jpg'),
(10, 'Houmous et légumes croquants', 'entree', 'houmous.jpg'),
(11, 'Curry de légumes au lait de coco', 'plat', 'curry-vegan.jpg'),
(12, 'Mousse au chocolat vegan', 'dessert', 'mousse-vegan.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `plat_allergene`
--

CREATE TABLE `plat_allergene` (
  `idPlat` int(11) NOT NULL,
  `idAllergene` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plat_allergene`
--

INSERT INTO `plat_allergene` (`idPlat`, `idAllergene`) VALUES
(1, 1),
(1, 7),
(1, 8),
(2, 1),
(2, 7),
(3, 1),
(3, 3),
(3, 7),
(4, 7),
(4, 12),
(5, 1),
(5, 3),
(5, 7),
(5, 9),
(5, 12),
(6, 1),
(6, 3),
(6, 7),
(7, 3),
(7, 10),
(8, 1),
(8, 3),
(8, 7),
(8, 9),
(9, 1),
(9, 3),
(9, 7),
(10, 11),
(11, 9),
(11, 10),
(12, 6),
(12, 8);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `idRole` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`idRole`, `libelle`) VALUES
(1, 'ADMIN'),
(2, 'EMPLOYE'),
(3, 'USER');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `idRole` int(11) NOT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `codePostal` varchar(20) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `resetToken` varchar(255) DEFAULT NULL,
  `resetExpire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `nom`, `prenom`, `email`, `motDePasse`, `telephone`, `adresse`, `idRole`, `ville`, `codePostal`, `pays`, `actif`, `resetToken`, `resetExpire`) VALUES
(2, 'Test', 'Test', 'test@gmail.com', '$2y$10$LWrHKCA1c5O0dcL4gKicDeCn8VmPlXI2pEVAp70OwEAanP4ucgMHe', '', '25 rue des poires', 3, 'Bordeaux', '65123', 'france', 1, NULL, NULL),
(4, 'admin', 'admin', 'admin@vitegourmand.fr', '$2y$10$9BXYwcm6yk6z/7U1Pc0eieDHBuN/vMi9NH8UkJSwCRVSXi/rJJJ2m', '05 56 45 58 57', '25 rue des poires', 1, 'nantes', '59586', 'france', 1, NULL, NULL),
(5, 'employe', 'employe', 'employe@gmail.com', '$2y$10$eXZL/K5/VsLnB5BFv96Gp.Q8fVRLZ2oxf7eJyw051iZmvtdZkgCdC', NULL, NULL, 2, NULL, NULL, NULL, 1, 'dbc23a35407dc82f8f38d9a39a8aea44ff1d8b62418793831c32d619204c86ec', '2026-07-03 01:22:08'),
(6, 'employe2', 'employe2', 'vitegourmandoff@gmail.com', '$2y$10$gRcL06kglUrh7OIM7.WrU.Co8Q.oOvBuxNgvvXZiOQnPXcjePP4LC', NULL, NULL, 2, NULL, NULL, NULL, 1, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergene`
--
ALTER TABLE `allergene`
  ADD PRIMARY KEY (`idAllergene`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`idAvis`),
  ADD KEY `idUtilisateur` (`idUtilisateur`),
  ADD KEY `idCommande` (`idCommande`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idCommande`),
  ADD KEY `idUtilisateur` (`idUtilisateur`);

--
-- Index pour la table `commande_materiel`
--
ALTER TABLE `commande_materiel`
  ADD PRIMARY KEY (`idCommandeMateriel`);

--
-- Index pour la table `commande_menu`
--
ALTER TABLE `commande_menu`
  ADD PRIMARY KEY (`idCommandeMenu`),
  ADD KEY `idCommande` (`idCommande`),
  ADD KEY `idMenu` (`idMenu`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`idContact`);

--
-- Index pour la table `historique_statut`
--
ALTER TABLE `historique_statut`
  ADD PRIMARY KEY (`idHistorique`),
  ADD KEY `idCommande` (`idCommande`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`idHoraire`);

--
-- Index pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD PRIMARY KEY (`idMateriel`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idMenu`);

--
-- Index pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD PRIMARY KEY (`idMenu`,`idPlat`),
  ADD KEY `idPlat` (`idPlat`);

--
-- Index pour la table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`idPlat`);

--
-- Index pour la table `plat_allergene`
--
ALTER TABLE `plat_allergene`
  ADD PRIMARY KEY (`idPlat`,`idAllergene`),
  ADD KEY `idAllergene` (`idAllergene`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`idRole`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idRole` (`idRole`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergene`
--
ALTER TABLE `allergene`
  MODIFY `idAllergene` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `idAvis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCommande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `commande_materiel`
--
ALTER TABLE `commande_materiel`
  MODIFY `idCommandeMateriel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande_menu`
--
ALTER TABLE `commande_menu`
  MODIFY `idCommandeMenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `idContact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `historique_statut`
--
ALTER TABLE `historique_statut`
  MODIFY `idHistorique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `idHoraire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `materiel`
--
ALTER TABLE `materiel`
  MODIFY `idMateriel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `idMenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `plat`
--
ALTER TABLE `plat`
  MODIFY `idPlat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `idRole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`idCommande`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `commande_menu`
--
ALTER TABLE `commande_menu`
  ADD CONSTRAINT `commande_menu_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`idCommande`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_menu_ibfk_2` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`idMenu`);

--
-- Contraintes pour la table `historique_statut`
--
ALTER TABLE `historique_statut`
  ADD CONSTRAINT `historique_statut_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`idCommande`) ON DELETE CASCADE;

--
-- Contraintes pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD CONSTRAINT `menu_plat_ibfk_1` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`idMenu`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_plat_ibfk_2` FOREIGN KEY (`idPlat`) REFERENCES `plat` (`idPlat`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plat_allergene`
--
ALTER TABLE `plat_allergene`
  ADD CONSTRAINT `plat_allergene_ibfk_1` FOREIGN KEY (`idPlat`) REFERENCES `plat` (`idPlat`) ON DELETE CASCADE,
  ADD CONSTRAINT `plat_allergene_ibfk_2` FOREIGN KEY (`idAllergene`) REFERENCES `allergene` (`idAllergene`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`idRole`) REFERENCES `role` (`idRole`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
