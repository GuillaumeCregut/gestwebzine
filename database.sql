-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 22 jan. 2022 à 20:46
-- Version du serveur :  8.0.21
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `orga_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_action`
--

DROP TABLE IF EXISTS `t_action`;
CREATE TABLE IF NOT EXISTS `t_action` (
  `id_action` int NOT NULL AUTO_INCREMENT,
  `nom_action` varchar(100) NOT NULL,
  PRIMARY KEY (`id_action`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `t_action`
--

INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES
(1, 'Connexion'),
(2, 'Modification parametre'),
(3, 'Envoi mail'),
(4, 'Ajout article'),
(5, 'Modification Webzine'),
(6, 'Ajout Webzine'),
(7, 'Archivage Webzine'),
(8, 'Modification article'),
(9, 'Modification MEP'),
(10, 'Archivage article'),
(11, 'Ajout utilisateur'),
(12, 'Modifie utilisateur'),
(13, 'Deconnexion'),
(14, 'Purge Webzines');

-- --------------------------------------------------------

--
-- Structure de la table `t_articles`
--

DROP TABLE IF EXISTS `t_articles`;
CREATE TABLE IF NOT EXISTS `t_articles` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `pec` smallint NOT NULL DEFAULT '0',
  `auteur_article` int NOT NULL,
  `type_article` int NOT NULL,
  `art_locked` tinyint(1) NOT NULL DEFAULT '0',
  `webzine` int NOT NULL DEFAULT '1',
  `Etat_article` int NOT NULL DEFAULT '1',
  `Avancee_Article` int NOT NULL DEFAULT '0',
  `date_creation` date NOT NULL,
  `date_modification` date NOT NULL,
  `titre_article` varchar(100) NOT NULL,
  `fichiers` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL,
  `monteur` varchar(100) DEFAULT NULL,
  `mdp_photo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `titre_article` (`titre_article`),
  KEY `C_Article_Auteur` (`auteur_article`),
  KEY `C_Article_Webzine` (`webzine`),
  KEY `C_Article_Type` (`type_article`),
  KEY `C_Article_Etat` (`Etat_article`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_espace_travail`
--

DROP TABLE IF EXISTS `t_espace_travail`;
CREATE TABLE IF NOT EXISTS `t_espace_travail` (
  `id_espace` int NOT NULL AUTO_INCREMENT,
  `fk_article` int NOT NULL,
  PRIMARY KEY (`id_espace`),
  KEY `c_espace_article` (`fk_article`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_etat`
--

DROP TABLE IF EXISTS `t_etat`;
CREATE TABLE IF NOT EXISTS `t_etat` (
  `id_etat` int NOT NULL AUTO_INCREMENT,
  `nom_etat` varchar(30) NOT NULL,
  PRIMARY KEY (`id_etat`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `t_etat`
--

INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES
(1, 'Non commencé'),
(2, 'Bon pour relecture'),
(3, 'Autoriser la mise en page'),
(4, 'Archivé'),
(5, 'Prêt pour revue finale'),
(6, 'Prêt pour publication');

-- --------------------------------------------------------

--
-- Structure de la table `t_etat_webzine`
--

DROP TABLE IF EXISTS `t_etat_webzine`;
CREATE TABLE IF NOT EXISTS `t_etat_webzine` (
  `id_etat_webzine` int NOT NULL AUTO_INCREMENT,
  `nom_etat_webzine` varchar(100) NOT NULL,
  PRIMARY KEY (`id_etat_webzine`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `t_etat_webzine`
--

INSERT INTO `t_etat_webzine` (`id_etat_webzine`, `nom_etat_webzine`) VALUES
(1, 'Non Commencé'),
(2, 'En cours'),
(3, 'Terminé'),
(4, 'Archivé');

-- --------------------------------------------------------

--
-- Structure de la table `t_historisation`
--

DROP TABLE IF EXISTS `t_historisation`;
CREATE TABLE IF NOT EXISTS `t_historisation` (
  `id_histo` int NOT NULL AUTO_INCREMENT,
  `date_histo` datetime NOT NULL,
  `id_user` int NOT NULL,
  `id_action` int NOT NULL,
  `quoi` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_histo`),
  KEY `C_Histo_User` (`id_user`),
  KEY `C_Histo_Action` (`id_action`)
) ENGINE=InnoDB AUTO_INCREMENT=645 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_mep`
--

DROP TABLE IF EXISTS `t_mep`;
CREATE TABLE IF NOT EXISTS `t_mep` (
  `id_mep` int NOT NULL AUTO_INCREMENT,
  `id_auteur` int NOT NULL,
  `id_article` int NOT NULL,
  `nb_page` smallint DEFAULT '0',
  `date_creation` date NOT NULL,
  `date_modif` date NOT NULL,
  `fichiers` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_mep`),
  UNIQUE KEY `id_article` (`id_article`),
  KEY `C_MEP_Auteur` (`id_auteur`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_message_espace`
--

DROP TABLE IF EXISTS `t_message_espace`;
CREATE TABLE IF NOT EXISTS `t_message_espace` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_espace` int NOT NULL,
  `id_auteur` int NOT NULL,
  `date_message` datetime NOT NULL,
  `message` varchar(500) NOT NULL,
  `fichier` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `c_message_espace` (`id_espace`),
  KEY `c_message_espace_auteur` (`id_auteur`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_message_redaction`
--

DROP TABLE IF EXISTS `t_message_redaction`;
CREATE TABLE IF NOT EXISTS `t_message_redaction` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `fk_article` int NOT NULL,
  `fk_auteur` int NOT NULL,
  `date_message` datetime NOT NULL,
  `corps` varchar(500) NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `c_mradaction_auteur` (`fk_auteur`),
  KEY `c_mredaction_article` (`fk_article`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_parametres`
--

DROP TABLE IF EXISTS `t_parametres`;
CREATE TABLE IF NOT EXISTS `t_parametres` (
  `id_param` int NOT NULL AUTO_INCREMENT,
  `Nom_Param` varchar(100) NOT NULL,
  `Value_Param_I` int NOT NULL,
  `Value_Param_D` date NOT NULL,
  `Value_Param_S` varchar(200) NOT NULL,
  PRIMARY KEY (`id_param`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `t_parametres`
--

INSERT INTO `t_parametres` (`id_param`, `Nom_Param`, `Value_Param_I`, `Value_Param_D`, `Value_Param_S`) VALUES
(1, 'mail', 0, '0000-00-00', 'redaction.webzine@plastikdream.com'),
(2, 'delai', 30, '0000-00-00', ''),
(3, 'version', 0, '0000-00-00', '1.0B');

-- --------------------------------------------------------

--
-- Structure de la table `t_photos`
--

DROP TABLE IF EXISTS `t_photos`;
CREATE TABLE IF NOT EXISTS `t_photos` (
  `id_photo` int NOT NULL AUTO_INCREMENT,
  `fk_article` int NOT NULL,
  `photo_valide` tinyint(1) NOT NULL DEFAULT '0',
  `nbre_fichiers` tinyint NOT NULL DEFAULT '0',
  `chemin_photo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_photo`),
  UNIQUE KEY `fk_article` (`fk_article`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_type_article`
--

DROP TABLE IF EXISTS `t_type_article`;
CREATE TABLE IF NOT EXISTS `t_type_article` (
  `id_type` int NOT NULL AUTO_INCREMENT,
  `Nom_Type` varchar(50) NOT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `t_type_article`
--

INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES
(1, 'Blindés'),
(2, 'Avions'),
(3, 'Figurines'),
(4, 'Dioramas'),
(5, 'Trucs/astuces'),
(6, 'divers'),
(7, 'Essai'),
(8, 'BLABLA');

-- --------------------------------------------------------

--
-- Structure de la table `t_usager_espace`
--

DROP TABLE IF EXISTS `t_usager_espace`;
CREATE TABLE IF NOT EXISTS `t_usager_espace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fk_usager` int NOT NULL,
  `fk_espace` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_usager` (`fk_usager`,`fk_espace`),
  KEY `C_Espace_Usager` (`fk_espace`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_utilisateurs`
--

DROP TABLE IF EXISTS `t_utilisateurs`;
CREATE TABLE IF NOT EXISTS `t_utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `is_valable` tinyint(1) NOT NULL DEFAULT '1',
  `rang` tinyint NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `mdp` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_webzine`
--

DROP TABLE IF EXISTS `t_webzine`;
CREATE TABLE IF NOT EXISTS `t_webzine` (
  `id_webzine` int NOT NULL AUTO_INCREMENT,
  `Titre_Webzine` varchar(100) NOT NULL,
  `Etat` int NOT NULL,
  `Date_Creation` date NOT NULL,
  `Date_Parution` date NOT NULL,
  PRIMARY KEY (`id_webzine`),
  KEY `C_Webzine_Etat` (`Etat`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_mep`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_mep`;
CREATE TABLE IF NOT EXISTS `v_article_mep` (
`id_article` int
,`art_locked` tinyint(1)
,`date_creation` date
,`date_modification` date
,`webzine` int
,`description` varchar(200)
,`titre_article` varchar(100)
,`Avancee_Article` int
,`fichiers` varchar(200)
,`pec` smallint
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_pour_modif`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_pour_modif`;
CREATE TABLE IF NOT EXISTS `v_article_pour_modif` (
`id_article` int
,`art_locked` tinyint(1)
,`Etat_article` int
,`Avancee_Article` int
,`date_creation` date
,`titre_article` varchar(100)
,`description` varchar(200)
,`fichiers` varchar(200)
,`id_webzine` int
,`Date_Parution` date
,`Titre_Webzine` varchar(100)
,`id_utilisateur` int
,`nom` varchar(100)
,`prenom` varchar(100)
,`nom_etat` varchar(30)
,`Nom_Type` varchar(50)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_pour_modif_old`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_pour_modif_old`;
CREATE TABLE IF NOT EXISTS `v_article_pour_modif_old` (
`id_article` int
,`Etat_article` int
,`Avancee_Article` int
,`date_creation` date
,`titre_article` varchar(100)
,`description` varchar(200)
,`fichiers` varchar(200)
,`id_webzine` int
,`Date_Parution` date
,`Titre_Webzine` varchar(100)
,`nom` varchar(100)
,`prenom` varchar(100)
,`nom_etat` varchar(30)
,`Nom_Type` varchar(50)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_redaction`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_redaction`;
CREATE TABLE IF NOT EXISTS `v_article_redaction` (
`id_article` int
,`titre_article` varchar(100)
,`type_article` int
,`Etat_article` int
,`fichier_article` varchar(200)
,`webzine` int
,`nom` varchar(100)
,`prenom` varchar(100)
,`fichiers` varchar(200)
,`nom_etat` varchar(30)
,`Nom_Type` varchar(50)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_resume`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_resume`;
CREATE TABLE IF NOT EXISTS `v_article_resume` (
`id_article` int
,`auteur_article` int
,`Etat_article` int
,`titre_article` varchar(100)
,`Avancee_Article` int
,`art_locked` tinyint(1)
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
,`id_webzine` int
,`Titre_Webzine` varchar(100)
,`fichier_mep` varchar(200)
,`nb_page` smallint
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_resume2`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_resume2`;
CREATE TABLE IF NOT EXISTS `v_article_resume2` (
`id_article` int
,`auteur_article` int
,`Etat_article` int
,`titre_article` varchar(100)
,`art_locked` tinyint(1)
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
,`id_webzine` int
,`Titre_Webzine` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_resume3`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_resume3`;
CREATE TABLE IF NOT EXISTS `v_article_resume3` (
`id_article` int
,`auteur_article` int
,`Etat_article` int
,`titre_article` varchar(100)
,`art_locked` tinyint(1)
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
,`id_webzine` int
,`Titre_Webzine` varchar(100)
,`fichier_mep` varchar(200)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_resume4`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_resume4`;
CREATE TABLE IF NOT EXISTS `v_article_resume4` (
`id_article` int
,`auteur_article` int
,`Etat_article` int
,`titre_article` varchar(100)
,`Avancee_Article` int
,`art_locked` tinyint(1)
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
,`id_webzine` int
,`Titre_Webzine` varchar(100)
,`fichier_mep` varchar(200)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_resume_old`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_resume_old`;
CREATE TABLE IF NOT EXISTS `v_article_resume_old` (
`id_article` int
,`auteur_article` int
,`etat_article` int
,`titre_article` varchar(100)
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
,`id_webzine` int
,`titre_Webzine` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_resume_photo`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_resume_photo`;
CREATE TABLE IF NOT EXISTS `v_article_resume_photo` (
`id_article` int
,`auteur_article` int
,`Etat_article` int
,`titre_article` varchar(100)
,`Avancee_Article` int
,`art_locked` tinyint(1)
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
,`id_webzine` int
,`Titre_Webzine` varchar(100)
,`fichier_mep` varchar(200)
,`nb_page` smallint
,`photo_valide` tinyint(1)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_article_webzine_archivage`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_article_webzine_archivage`;
CREATE TABLE IF NOT EXISTS `v_article_webzine_archivage` (
`id_article` int
,`FichierArticle` varchar(200)
,`webzine` int
,`titre_article` varchar(100)
,`id_mep` int
,`Fichier_MEP` varchar(200)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_histo`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_histo`;
CREATE TABLE IF NOT EXISTS `v_histo` (
`date_histo` datetime
,`quoi` varchar(100)
,`nom` varchar(100)
,`prenom` varchar(100)
,`nom_action` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_mail_article`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_mail_article`;
CREATE TABLE IF NOT EXISTS `v_mail_article` (
`id_article` int
,`titre_article` varchar(100)
,`webzine` int
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`id_user` int
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_mail_auteur_article`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_mail_auteur_article`;
CREATE TABLE IF NOT EXISTS `v_mail_auteur_article` (
`mail` varchar(100)
,`id_article` int
,`titre_article` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_mep_affiche_redaction`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_mep_affiche_redaction`;
CREATE TABLE IF NOT EXISTS `v_mep_affiche_redaction` (
`id_mep` int
,`id_article` int
,`fichiers` varchar(200)
,`nb_page` smallint
,`date_modif` date
,`nom` varchar(100)
,`prenom` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_mep_photos`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_mep_photos`;
CREATE TABLE IF NOT EXISTS `v_mep_photos` (
`id_article` int
,`auteur_article` int
,`Etat_article` int
,`titre_article` varchar(100)
,`Avancee_Article` int
,`art_locked` tinyint(1)
,`fichiers` varchar(200)
,`nom` varchar(100)
,`prenom` varchar(100)
,`Nom_Type` varchar(50)
,`nom_etat` varchar(30)
,`id_webzine` int
,`Titre_Webzine` varchar(100)
,`fichier_mep` varchar(200)
,`nb_page` smallint
,`photo_valide` tinyint(1)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_mep_redaction`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_mep_redaction`;
CREATE TABLE IF NOT EXISTS `v_mep_redaction` (
`id_mep` int
,`id_article` int
,`nom` varchar(100)
,`prenom` varchar(100)
,`id_user` int
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_message_espace`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_message_espace`;
CREATE TABLE IF NOT EXISTS `v_message_espace` (
`id_message` int
,`date_message` datetime
,`message` varchar(500)
,`fichier` varchar(100)
,`id_espace` int
,`nom` varchar(100)
,`prenom` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_message_redaction`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_message_redaction`;
CREATE TABLE IF NOT EXISTS `v_message_redaction` (
`id_message` int
,`fk_article` int
,`date_message` datetime
,`corps` varchar(500)
,`nom` varchar(100)
,`prenom` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_nb_page_webzines`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_nb_page_webzines`;
CREATE TABLE IF NOT EXISTS `v_nb_page_webzines` (
`webzine` int
,`id_article` int
,`nb_page` smallint
,`id_mep` int
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_purge_article`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_purge_article`;
CREATE TABLE IF NOT EXISTS `v_purge_article` (
`id_article` int
,`fichiers` varchar(200)
,`fichier_mep` varchar(200)
,`id_espace` int
,`chemin_photo` varchar(200)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_relance_article`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_relance_article`;
CREATE TABLE IF NOT EXISTS `v_relance_article` (
`id_article` int
,`webzine` int
,`fichiers` varchar(200)
,`mail` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_usager_espace`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_usager_espace`;
CREATE TABLE IF NOT EXISTS `v_usager_espace` (
`fk_espace` int
,`fk_usager` int
,`nom` varchar(100)
,`prenom` varchar(100)
,`mail` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_utilisateur_article`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_utilisateur_article`;
CREATE TABLE IF NOT EXISTS `v_utilisateur_article` (
`id_article` int
,`auteur_article` int
,`graphiste` int
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_webzine`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_webzine`;
CREATE TABLE IF NOT EXISTS `v_webzine` (
`id_webzine` int
,`Titre_Webzine` varchar(100)
,`Etat` int
,`Date_Parution` date
,`nom_etat_webzine` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_mep`
--
DROP TABLE IF EXISTS `v_article_mep`;

DROP VIEW IF EXISTS `v_article_mep`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_mep`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`art_locked` AS `art_locked`,`ta`.`date_creation` AS `date_creation`,`ta`.`date_modification` AS `date_modification`,`ta`.`webzine` AS `webzine`,`ta`.`description` AS `description`,`ta`.`titre_article` AS `titre_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`fichiers` AS `fichiers`,`ta`.`pec` AS `pec`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat` from (((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_pour_modif`
--
DROP TABLE IF EXISTS `v_article_pour_modif`;

DROP VIEW IF EXISTS `v_article_pour_modif`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_pour_modif`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`art_locked` AS `art_locked`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`date_creation` AS `date_creation`,`ta`.`titre_article` AS `titre_article`,`ta`.`description` AS `description`,`ta`.`fichiers` AS `fichiers`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Date_Parution` AS `Date_Parution`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tu`.`id_utilisateur` AS `id_utilisateur`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`te`.`nom_etat` AS `nom_etat`,`tt`.`Nom_Type` AS `Nom_Type` from ((((`t_articles` `ta` join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_pour_modif_old`
--
DROP TABLE IF EXISTS `v_article_pour_modif_old`;

DROP VIEW IF EXISTS `v_article_pour_modif_old`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_pour_modif_old`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`date_creation` AS `date_creation`,`ta`.`titre_article` AS `titre_article`,`ta`.`description` AS `description`,`ta`.`fichiers` AS `fichiers`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Date_Parution` AS `Date_Parution`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`te`.`nom_etat` AS `nom_etat`,`tt`.`Nom_Type` AS `Nom_Type` from ((((`t_articles` `ta` join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_redaction`
--
DROP TABLE IF EXISTS `v_article_redaction`;

DROP VIEW IF EXISTS `v_article_redaction`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_redaction`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`type_article` AS `type_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`fichiers` AS `fichier_article`,`ta`.`webzine` AS `webzine`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tm`.`fichiers` AS `fichiers`,`te`.`nom_etat` AS `nom_etat`,`tt`.`Nom_Type` AS `Nom_Type` from ((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) left join `t_mep` `tm` on((`tm`.`id_article` = `ta`.`id_article`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_resume`
--
DROP TABLE IF EXISTS `v_article_resume`;

DROP VIEW IF EXISTS `v_article_resume`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_resume`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`art_locked` AS `art_locked`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tm`.`fichiers` AS `fichier_mep`,`tm`.`nb_page` AS `nb_page` from (((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) where (`ta`.`Etat_article` <> 4) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_resume2`
--
DROP TABLE IF EXISTS `v_article_resume2`;

DROP VIEW IF EXISTS `v_article_resume2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_resume2`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`art_locked` AS `art_locked`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine` from ((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) where (`ta`.`Etat_article` <> 4) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_resume3`
--
DROP TABLE IF EXISTS `v_article_resume3`;

DROP VIEW IF EXISTS `v_article_resume3`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_resume3`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`art_locked` AS `art_locked`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tm`.`fichiers` AS `fichier_mep` from (((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) where (`ta`.`Etat_article` <> 4) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_resume4`
--
DROP TABLE IF EXISTS `v_article_resume4`;

DROP VIEW IF EXISTS `v_article_resume4`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_resume4`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`art_locked` AS `art_locked`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tm`.`fichiers` AS `fichier_mep` from (((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) where (`ta`.`Etat_article` <> 4) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_resume_old`
--
DROP TABLE IF EXISTS `v_article_resume_old`;

DROP VIEW IF EXISTS `v_article_resume_old`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_resume_old`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `titre_Webzine` from ((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) where (`ta`.`Etat_article` <> 4) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_resume_photo`
--
DROP TABLE IF EXISTS `v_article_resume_photo`;

DROP VIEW IF EXISTS `v_article_resume_photo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_resume_photo`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`art_locked` AS `art_locked`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tm`.`fichiers` AS `fichier_mep`,`tm`.`nb_page` AS `nb_page`,`tp`.`photo_valide` AS `photo_valide` from ((((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) left join `t_photos` `tp` on((`ta`.`id_article` = `tp`.`fk_article`))) where (`ta`.`Etat_article` <> 4) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_article_webzine_archivage`
--
DROP TABLE IF EXISTS `v_article_webzine_archivage`;

DROP VIEW IF EXISTS `v_article_webzine_archivage`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_webzine_archivage`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`fichiers` AS `FichierArticle`,`ta`.`webzine` AS `webzine`,`ta`.`titre_article` AS `titre_article`,`tm`.`id_mep` AS `id_mep`,`tm`.`fichiers` AS `Fichier_MEP` from (`t_articles` `ta` join `t_mep` `tm` on((`tm`.`id_article` = `ta`.`id_article`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_histo`
--
DROP TABLE IF EXISTS `v_histo`;

DROP VIEW IF EXISTS `v_histo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_histo`  AS  select `th`.`date_histo` AS `date_histo`,`th`.`quoi` AS `quoi`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`ta`.`nom_action` AS `nom_action` from ((`t_historisation` `th` join `t_utilisateurs` `tu` on((`th`.`id_user` = `tu`.`id_utilisateur`))) join `t_action` `ta` on((`th`.`id_action` = `ta`.`id_action`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_mail_article`
--
DROP TABLE IF EXISTS `v_mail_article`;

DROP VIEW IF EXISTS `v_mail_article`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mail_article`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`webzine` AS `webzine`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tu`.`id_utilisateur` AS `id_user` from (`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_mail_auteur_article`
--
DROP TABLE IF EXISTS `v_mail_auteur_article`;

DROP VIEW IF EXISTS `v_mail_auteur_article`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mail_auteur_article`  AS  select `t_utilisateurs`.`mail` AS `mail`,`t_articles`.`id_article` AS `id_article`,`t_articles`.`titre_article` AS `titre_article` from (`t_articles` join `t_utilisateurs` on((`t_articles`.`auteur_article` = `t_utilisateurs`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_mep_affiche_redaction`
--
DROP TABLE IF EXISTS `v_mep_affiche_redaction`;

DROP VIEW IF EXISTS `v_mep_affiche_redaction`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mep_affiche_redaction`  AS  select `tm`.`id_mep` AS `id_mep`,`tm`.`id_article` AS `id_article`,`tm`.`fichiers` AS `fichiers`,`tm`.`nb_page` AS `nb_page`,`tm`.`date_modif` AS `date_modif`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom` from (`t_mep` `tm` join `t_utilisateurs` `tu` on((`tm`.`id_auteur` = `tu`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_mep_photos`
--
DROP TABLE IF EXISTS `v_mep_photos`;

DROP VIEW IF EXISTS `v_mep_photos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mep_photos`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`art_locked` AS `art_locked`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tm`.`fichiers` AS `fichier_mep`,`tm`.`nb_page` AS `nb_page`,`tp`.`photo_valide` AS `photo_valide` from ((((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) join `t_photos` `tp` on((`tp`.`fk_article` = `ta`.`id_article`))) left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) where (`ta`.`Etat_article` <> 4) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_mep_redaction`
--
DROP TABLE IF EXISTS `v_mep_redaction`;

DROP VIEW IF EXISTS `v_mep_redaction`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mep_redaction`  AS  select `tm`.`id_mep` AS `id_mep`,`tm`.`id_article` AS `id_article`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tu`.`id_utilisateur` AS `id_user` from (`t_mep` `tm` join `t_utilisateurs` `tu` on((`tm`.`id_auteur` = `tu`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_message_espace`
--
DROP TABLE IF EXISTS `v_message_espace`;

DROP VIEW IF EXISTS `v_message_espace`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_message_espace`  AS  select `tm`.`id_message` AS `id_message`,`tm`.`date_message` AS `date_message`,`tm`.`message` AS `message`,`tm`.`fichier` AS `fichier`,`tm`.`id_espace` AS `id_espace`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom` from (`t_message_espace` `tm` join `t_utilisateurs` `tu` on((`tm`.`id_auteur` = `tu`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_message_redaction`
--
DROP TABLE IF EXISTS `v_message_redaction`;

DROP VIEW IF EXISTS `v_message_redaction`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_message_redaction`  AS  select `tm`.`id_message` AS `id_message`,`tm`.`fk_article` AS `fk_article`,`tm`.`date_message` AS `date_message`,`tm`.`corps` AS `corps`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom` from (`t_message_redaction` `tm` join `t_utilisateurs` `tu` on((`tm`.`fk_auteur` = `tu`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_nb_page_webzines`
--
DROP TABLE IF EXISTS `v_nb_page_webzines`;

DROP VIEW IF EXISTS `v_nb_page_webzines`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_nb_page_webzines`  AS  select `ta`.`webzine` AS `webzine`,`ta`.`id_article` AS `id_article`,`tm`.`nb_page` AS `nb_page`,`tm`.`id_mep` AS `id_mep` from (`t_articles` `ta` left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_purge_article`
--
DROP TABLE IF EXISTS `v_purge_article`;

DROP VIEW IF EXISTS `v_purge_article`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_purge_article`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`fichiers` AS `fichiers`,`tm`.`fichiers` AS `fichier_mep`,`tet`.`id_espace` AS `id_espace`,`tp`.`chemin_photo` AS `chemin_photo` from (((`t_articles` `ta` left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) left join `t_espace_travail` `tet` on((`ta`.`id_article` = `tet`.`fk_article`))) left join `t_photos` `tp` on((`ta`.`id_article` = `tp`.`fk_article`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_relance_article`
--
DROP TABLE IF EXISTS `v_relance_article`;

DROP VIEW IF EXISTS `v_relance_article`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_relance_article`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`webzine` AS `webzine`,`ta`.`fichiers` AS `fichiers`,`tu`.`mail` AS `mail` from (`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_usager_espace`
--
DROP TABLE IF EXISTS `v_usager_espace`;

DROP VIEW IF EXISTS `v_usager_espace`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_usager_espace`  AS  select `tue`.`fk_espace` AS `fk_espace`,`tue`.`fk_usager` AS `fk_usager`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tu`.`mail` AS `mail` from (`t_usager_espace` `tue` join `t_utilisateurs` `tu` on((`tue`.`fk_usager` = `tu`.`id_utilisateur`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_utilisateur_article`
--
DROP TABLE IF EXISTS `v_utilisateur_article`;

DROP VIEW IF EXISTS `v_utilisateur_article`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_utilisateur_article`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`tm`.`id_auteur` AS `graphiste` from (`t_articles` `ta` left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_webzine`
--
DROP TABLE IF EXISTS `v_webzine`;

DROP VIEW IF EXISTS `v_webzine`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_webzine`  AS  select `tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tw`.`Etat` AS `Etat`,`tw`.`Date_Parution` AS `Date_Parution`,`tew`.`nom_etat_webzine` AS `nom_etat_webzine` from (`t_webzine` `tw` join `t_etat_webzine` `tew` on((`tw`.`Etat` = `tew`.`id_etat_webzine`))) where (`tw`.`id_webzine` <> 1) ;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_articles`
--
ALTER TABLE `t_articles`
  ADD CONSTRAINT `C_Article_Auteur` FOREIGN KEY (`auteur_article`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `C_Article_Etat` FOREIGN KEY (`Etat_article`) REFERENCES `t_etat` (`id_etat`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `C_Article_Type` FOREIGN KEY (`type_article`) REFERENCES `t_type_article` (`id_type`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `C_Article_Webzine` FOREIGN KEY (`webzine`) REFERENCES `t_webzine` (`id_webzine`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_espace_travail`
--
ALTER TABLE `t_espace_travail`
  ADD CONSTRAINT `c_espace_article` FOREIGN KEY (`fk_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_historisation`
--
ALTER TABLE `t_historisation`
  ADD CONSTRAINT `C_Histo_Action` FOREIGN KEY (`id_action`) REFERENCES `t_action` (`id_action`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `C_Histo_User` FOREIGN KEY (`id_user`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_mep`
--
ALTER TABLE `t_mep`
  ADD CONSTRAINT `C_MEP_Article` FOREIGN KEY (`id_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `C_MEP_Auteur` FOREIGN KEY (`id_auteur`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_message_espace`
--
ALTER TABLE `t_message_espace`
  ADD CONSTRAINT `c_message_espace` FOREIGN KEY (`id_espace`) REFERENCES `t_espace_travail` (`id_espace`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `c_message_espace_auteur` FOREIGN KEY (`id_auteur`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_message_redaction`
--
ALTER TABLE `t_message_redaction`
  ADD CONSTRAINT `c_mradaction_auteur` FOREIGN KEY (`fk_auteur`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `c_mredaction_article` FOREIGN KEY (`fk_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_photos`
--
ALTER TABLE `t_photos`
  ADD CONSTRAINT `c_photos_article` FOREIGN KEY (`fk_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_usager_espace`
--
ALTER TABLE `t_usager_espace`
  ADD CONSTRAINT `C_Espace_Usager` FOREIGN KEY (`fk_espace`) REFERENCES `t_espace_travail` (`id_espace`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `C_usager_espace` FOREIGN KEY (`fk_usager`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `t_webzine`
--
ALTER TABLE `t_webzine`
  ADD CONSTRAINT `C_Webzine_Etat` FOREIGN KEY (`Etat`) REFERENCES `t_etat_webzine` (`id_etat_webzine`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
