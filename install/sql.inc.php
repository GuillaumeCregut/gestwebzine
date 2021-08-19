<?php
//Ajout de l'utlisateur admin
    $SQL_Add_User='CALL P_Add_User( :nom,:prenom,:login,:pass,:rang,:mail)';
   //From base
//Requete création table
    $SQL_Action="CREATE TABLE IF NOT EXISTS `t_action` (
                `id_action` int NOT NULL AUTO_INCREMENT,
                `nom_action` varchar(100) NOT NULL,
                PRIMARY KEY (`id_action`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Etat="CREATE TABLE IF NOT EXISTS `t_etat` (
            `id_etat` int NOT NULL AUTO_INCREMENT,
            `nom_etat` varchar(30) NOT NULL,
            PRIMARY KEY (`id_etat`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Param="CREATE TABLE IF NOT EXISTS `t_parametres` (
            `id_param` int NOT NULL AUTO_INCREMENT,
            `Nom_Param` varchar(100) NOT NULL,
            `Value_Param_I` int NOT NULL,
            `Value_Param_D` date NOT NULL,
            `Value_Param_S` varchar(200) NOT NULL,
            PRIMARY KEY (`id_param`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Type="CREATE TABLE IF NOT EXISTS `t_type_article` (
            `id_type` int NOT NULL AUTO_INCREMENT,
            `Nom_Type` varchar(50) NOT NULL,
            PRIMARY KEY (`id_type`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_User="CREATE TABLE IF NOT EXISTS `t_utilisateurs` (
            `id_utilisateur` int NOT NULL AUTO_INCREMENT,
            `is_valable` tinyint(1) NOT NULL DEFAULT '1',
            `rang` tinyint NOT NULL,
            `prenom` varchar(100) NOT NULL,
            `nom` varchar(100) NOT NULL,
            `login` varchar(50) NOT NULL,
            `mdp` varchar(200) NOT NULL,
            `mail` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`id_utilisateur`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Etat_Web="CREATE TABLE IF NOT EXISTS `t_etat_webzine` (
            `id_etat_webzine` int NOT NULL AUTO_INCREMENT,
            `nom_etat_webzine` varchar(100) NOT NULL,
            PRIMARY KEY (`id_etat_webzine`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Webzine="CREATE TABLE IF NOT EXISTS `t_webzine` (
            `id_webzine` int NOT NULL AUTO_INCREMENT,
            `Titre_Webzine` varchar(100) NOT NULL,
            `Etat` int NOT NULL,
            `Date_Creation` date NOT NULL,
            `Date_Parution` date NOT NULL,
            PRIMARY KEY (`id_webzine`),
            KEY `C_Webzine_Etat` (`Etat`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Article="CREATE TABLE IF NOT EXISTS `t_articles` (
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
            `fichiers` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
            `description` varchar(200) NOT NULL,
            `monteur` varchar(100) DEFAULT NULL,
            `mdp_photo` varchar(10) DEFAULT NULL,
            PRIMARY KEY (`id_article`),
            UNIQUE KEY `titre_article` (`titre_article`),
            KEY `C_Article_Auteur` (`auteur_article`),
            KEY `C_Article_Webzine` (`webzine`),
            KEY `C_Article_Type` (`type_article`),
            KEY `C_Article_Etat` (`Etat_article`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Espace_Travail="CREATE TABLE IF NOT EXISTS `t_espace_travail` (
        `id_espace` int NOT NULL AUTO_INCREMENT,
        `fk_article` int NOT NULL,
        PRIMARY KEY (`id_espace`),
        KEY `c_espace_article` (`fk_article`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Histo="CREATE TABLE IF NOT EXISTS `t_historisation` (
        `id_histo` int NOT NULL AUTO_INCREMENT,
        `date_histo` datetime NOT NULL,
        `id_user` int NOT NULL,
        `id_action` int NOT NULL,
        `quoi` varchar(100) DEFAULT NULL,
        PRIMARY KEY (`id_histo`),
        KEY `C_Histo_User` (`id_user`),
        KEY `C_Histo_Action` (`id_action`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_MEP="CREATE TABLE IF NOT EXISTS `t_mep` (
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
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Message_Espace="CREATE TABLE IF NOT EXISTS `t_message_espace` (
        `id_message` int NOT NULL AUTO_INCREMENT,
        `id_espace` int NOT NULL,
        `id_auteur` int NOT NULL,
        `date_message` datetime NOT NULL,
        `message` varchar(500) NOT NULL,
        `fichier` varchar(100) DEFAULT NULL,
        PRIMARY KEY (`id_message`),
        KEY `c_message_espace` (`id_espace`),
        KEY `c_message_espace_auteur` (`id_auteur`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_MEssage_Redac="CREATE TABLE IF NOT EXISTS `t_message_redaction` (
        `id_message` int NOT NULL AUTO_INCREMENT,
        `fk_article` int NOT NULL,
        `fk_auteur` int NOT NULL,
        `date_message` datetime NOT NULL,
        `corps` varchar(500) NOT NULL,
        PRIMARY KEY (`id_message`),
        KEY `c_mradaction_auteur` (`fk_auteur`),
        KEY `c_mredaction_article` (`fk_article`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Usager_Espace="CREATE TABLE IF NOT EXISTS `t_usager_espace` (
        `id` int NOT NULL AUTO_INCREMENT,
        `fk_usager` int NOT NULL,
        `fk_espace` int NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `fk_usager` (`fk_usager`,`fk_espace`),
        KEY `C_Espace_Usager` (`fk_espace`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

    $SQL_Photo="CREATE TABLE IF NOT EXISTS `t_photos` (
        `id_photo` int NOT NULL AUTO_INCREMENT,
        `fk_article` int NOT NULL,
        `photo_valide` tinyint(1) NOT NULL DEFAULT '0',
        `chemin_photo` varchar(200) DEFAULT NULL,
        PRIMARY KEY (`id_photo`),
        UNIQUE KEY `fk_article` (`fk_article`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";
//Modifications de la structures des tables
    $SQL_Alter_1="ALTER TABLE `t_webzine` ADD CONSTRAINT `C_Webzine_Etat` FOREIGN KEY (`Etat`) REFERENCES `t_etat_webzine` (`id_etat_webzine`) ON DELETE RESTRICT ON UPDATE RESTRICT;
        COMMIT;";

    $SQL_Alter_2="ALTER TABLE `t_usager_espace`
        ADD CONSTRAINT `C_Espace_Usager` FOREIGN KEY (`fk_espace`) REFERENCES `t_espace_travail` (`id_espace`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `C_usager_espace` FOREIGN KEY (`fk_usager`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_Alter_3="ALTER TABLE `t_photos`
        ADD CONSTRAINT `c_photos_article` FOREIGN KEY (`fk_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_Alter_4="ALTER TABLE `t_message_redaction`
        ADD CONSTRAINT `c_mradaction_auteur` FOREIGN KEY (`fk_auteur`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `c_mredaction_article` FOREIGN KEY (`fk_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_Alter_5="ALTER TABLE `t_message_espace`
        ADD CONSTRAINT `c_message_espace` FOREIGN KEY (`id_espace`) REFERENCES `t_espace_travail` (`id_espace`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `c_message_espace_auteur` FOREIGN KEY (`id_auteur`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_Alter_6="ALTER TABLE `t_articles`
        ADD CONSTRAINT `C_Article_Auteur` FOREIGN KEY (`auteur_article`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `C_Article_Etat` FOREIGN KEY (`Etat_article`) REFERENCES `t_etat` (`id_etat`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `C_Article_Type` FOREIGN KEY (`type_article`) REFERENCES `t_type_article` (`id_type`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `C_Article_Webzine` FOREIGN KEY (`webzine`) REFERENCES `t_webzine` (`id_webzine`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_Alter_7="ALTER TABLE `t_espace_travail`
        ADD CONSTRAINT `c_espace_article` FOREIGN KEY (`fk_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_Alter_8="ALTER TABLE `t_historisation`
        ADD CONSTRAINT `C_Histo_Action` FOREIGN KEY (`id_action`) REFERENCES `t_action` (`id_action`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `C_Histo_User` FOREIGN KEY (`id_user`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_Alter_9="ALTER TABLE `t_mep`
        ADD CONSTRAINT `C_MEP_Article` FOREIGN KEY (`id_article`) REFERENCES `t_articles` (`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        ADD CONSTRAINT `C_MEP_Auteur` FOREIGN KEY (`id_auteur`) REFERENCES `t_utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
//Création des vues
    $SQL_V_Art_MEP="CREATE ALGORITHM=UNDEFINED VIEW `v_article_mep`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`art_locked` AS `art_locked`,`ta`.`date_creation` AS `date_creation`,`ta`.`date_modification` AS `date_modification`,`ta`.`webzine` AS `webzine`,`ta`.`description` AS `description`,`ta`.`titre_article` AS `titre_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`fichiers` AS `fichiers`,`ta`.`pec` AS `pec`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat` from (((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) ;";
    $SQL_V_Art_Modif="CREATE ALGORITHM=UNDEFINED VIEW `v_article_pour_modif`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`art_locked` AS `art_locked`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`date_creation` AS `date_creation`,`ta`.`titre_article` AS `titre_article`,`ta`.`description` AS `description`,`ta`.`fichiers` AS `fichiers`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Date_Parution` AS `Date_Parution`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tu`.`id_utilisateur` AS `id_utilisateur`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`te`.`nom_etat` AS `nom_etat`,`tt`.`Nom_Type` AS `Nom_Type` from ((((`t_articles` `ta` join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) ;";
    $SQL_V_Art_Res="CREATE ALGORITHM=UNDEFINED VIEW `v_article_resume`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`Avancee_Article` AS `Avancee_Article`,`ta`.`art_locked` AS `art_locked`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tt`.`Nom_Type` AS `Nom_Type`,`te`.`nom_etat` AS `nom_etat`,`tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tm`.`fichiers` AS `fichier_mep`,`tm`.`nb_page` AS `nb_page` from (((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_webzine` `tw` on((`ta`.`webzine` = `tw`.`id_webzine`))) left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) where (`ta`.`Etat_article` <> 4) ;";
    $SQL_V_Art_Web="CREATE ALGORITHM=UNDEFINED VIEW `v_article_webzine_archivage`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`fichiers` AS `FichierArticle`,`ta`.`webzine` AS `webzine`,`tm`.`id_mep` AS `id_mep`,`tm`.`fichiers` AS `Fichier_MEP` from (`t_articles` `ta` join `t_mep` `tm` on((`tm`.`id_article` = `ta`.`id_article`))) ;";
    $SQL_V_Histo="CREATE ALGORITHM=UNDEFINED VIEW `v_histo`  AS  select `th`.`date_histo` AS `date_histo`,`th`.`quoi` AS `quoi`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`ta`.`nom_action` AS `nom_action` from ((`t_historisation` `th` join `t_utilisateurs` `tu` on((`th`.`id_user` = `tu`.`id_utilisateur`))) join `t_action` `ta` on((`th`.`id_action` = `ta`.`id_action`))) ;";
    $SQL_V_Mail_Art="CREATE ALGORITHM=UNDEFINED VIEW `v_mail_article`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`webzine` AS `webzine`,`ta`.`fichiers` AS `fichiers`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tu`.`id_utilisateur` AS `id_user` from (`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) ;";
    $SQL_V_Mail_Auteur="CREATE ALGORITHM=UNDEFINED VIEW `v_mail_auteur_article`  AS  select `t_utilisateurs`.`mail` AS `mail`,`t_articles`.`id_article` AS `id_article`,`t_articles`.`titre_article` AS `titre_article` from (`t_articles` join `t_utilisateurs` on((`t_articles`.`auteur_article` = `t_utilisateurs`.`id_utilisateur`))) ;";
    $SQL_V_Aff_Redac="CREATE ALGORITHM=UNDEFINED VIEW `v_mep_affiche_redaction`  AS  select `tm`.`id_mep` AS `id_mep`,`tm`.`id_article` AS `id_article`,`tm`.`fichiers` AS `fichiers`,`tm`.`nb_page` AS `nb_page`,`tm`.`date_modif` AS `date_modif`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom` from (`t_mep` `tm` join `t_utilisateurs` `tu` on((`tm`.`id_auteur` = `tu`.`id_utilisateur`))) ;";
    $SQL_V_MEP_Redac="CREATE ALGORITHM=UNDEFINED VIEW `v_mep_redaction`  AS  select `tm`.`id_mep` AS `id_mep`,`tm`.`id_article` AS `id_article`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tu`.`id_utilisateur` AS `id_user` from (`t_mep` `tm` join `t_utilisateurs` `tu` on((`tm`.`id_auteur` = `tu`.`id_utilisateur`))) ;";
    $SQL_V_Mess_Esp="CREATE ALGORITHM=UNDEFINED VIEW `v_message_espace`  AS  select `tm`.`id_message` AS `id_message`,`tm`.`date_message` AS `date_message`,`tm`.`message` AS `message`,`tm`.`fichier` AS `fichier`,`tm`.`id_espace` AS `id_espace`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom` from (`t_message_espace` `tm` join `t_utilisateurs` `tu` on((`tm`.`id_auteur` = `tu`.`id_utilisateur`))) ;";
    $SQL_V_Mess_Redac="CREATE ALGORITHM=UNDEFINED VIEW `v_message_redaction`  AS  select `tm`.`id_message` AS `id_message`,`tm`.`fk_article` AS `fk_article`,`tm`.`date_message` AS `date_message`,`tm`.`corps` AS `corps`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom` from (`t_message_redaction` `tm` join `t_utilisateurs` `tu` on((`tm`.`fk_auteur` = `tu`.`id_utilisateur`))) ;";
    $SQL_V_Nb_Page="CREATE ALGORITHM=UNDEFINED VIEW `v_nb_page_webzines`  AS  select `ta`.`webzine` AS `webzine`,`ta`.`id_article` AS `id_article`,`tm`.`nb_page` AS `nb_page`,`tm`.`id_mep` AS `id_mep` from (`t_articles` `ta` left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) ;";
    $SQL_V_Relance="CREATE ALGORITHM=UNDEFINED VIEW `v_relance_article`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`webzine` AS `webzine`,`ta`.`fichiers` AS `fichiers`,`tu`.`mail` AS `mail` from (`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) ;";
    $SQL_V_User_Esp="CREATE ALGORITHM=UNDEFINED VIEW `v_usager_espace`  AS  select `tue`.`fk_espace` AS `fk_espace`,`tue`.`fk_usager` AS `fk_usager`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tu`.`mail` AS `mail` from (`t_usager_espace` `tue` join `t_utilisateurs` `tu` on((`tue`.`fk_usager` = `tu`.`id_utilisateur`))) ;";
    $SQL_V_User_Art="CREATE ALGORITHM=UNDEFINED VIEW `v_utilisateur_article`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`auteur_article` AS `auteur_article`,`tm`.`id_auteur` AS `graphiste` from (`t_articles` `ta` left join `t_mep` `tm` on((`ta`.`id_article` = `tm`.`id_article`))) ;";
    $SQL_V_Web="CREATE ALGORITHM=UNDEFINED VIEW `v_webzine`  AS  select `tw`.`id_webzine` AS `id_webzine`,`tw`.`Titre_Webzine` AS `Titre_Webzine`,`tw`.`Etat` AS `Etat`,`tw`.`Date_Parution` AS `Date_Parution`,`tew`.`nom_etat_webzine` AS `nom_etat_webzine` from (`t_webzine` `tw` join `t_etat_webzine` `tew` on((`tw`.`Etat` = `tew`.`id_etat_webzine`))) where (`tw`.`id_webzine` <> 1) ;";
    $SQL_V_Art_Redac="CREATE ALGORITHM=UNDEFINED VIEW `v_article_redaction`  AS  select `ta`.`id_article` AS `id_article`,`ta`.`titre_article` AS `titre_article`,`ta`.`type_article` AS `type_article`,`ta`.`Etat_article` AS `Etat_article`,`ta`.`fichiers` AS `fichier_article`,`ta`.`webzine` AS `webzine`,`tu`.`nom` AS `nom`,`tu`.`prenom` AS `prenom`,`tm`.`fichiers` AS `fichiers`,`te`.`nom_etat` AS `nom_etat`,`tt`.`Nom_Type` AS `Nom_Type` from ((((`t_articles` `ta` join `t_utilisateurs` `tu` on((`ta`.`auteur_article` = `tu`.`id_utilisateur`))) join `t_etat` `te` on((`ta`.`Etat_article` = `te`.`id_etat`))) join `t_type_article` `tt` on((`ta`.`type_article` = `tt`.`id_type`))) left join `t_mep` `tm` on((`tm`.`id_article` = `ta`.`id_article`))) ;";
//Création procédures stockées
    $SQL_Proc_P_Add_Article="CREATE PROCEDURE `P_Add_Article` (IN `auteur` INT, IN `typeA` INT, IN `titre` VARCHAR(100) CHARSET utf8mb4, IN `Descr` VARCHAR(200) CHARSET utf8mb4, IN `Lemonteur` VARCHAR(100), IN `LeMDP` VARCHAR(10))  MODIFIES SQL DATA
        INSERT INTO t_articles(auteur_article, type_article,titre_article,description,monteur,mdp_photo,date_creation,date_modification)
        VALUES(auteur, typeA,titre,Descr,Lemonteur,LeMDP,date(now()),date(now()))";
    $SQL_Proc_P_Add_Espace_Article="CREATE PROCEDURE `P_Add_Espace_Article` (IN `idaricle` INT)  MODIFIES SQL DATA
        INSERT INTO t_espace_travail (fk_article)
        VALUES (idaricle)";
    $SQL_Proc_P_Add_File_MEP="CREATE PROCEDURE `P_Add_File_MEP` (IN `iduser` INT, IN `idarticle` INT, IN `fichier` VARCHAR(200))  MODIFIES SQL DATA
        INSERT INTO t_mep(id_auteur,id_article,fichiers,date_creation,date_modif) VALUES( iduser, idarticle, fichier, date(now()), date(now()) )";
    $SQL_Proc_P_Add_Histo="CREATE PROCEDURE `P_Add_Histo` (IN `user` INT, IN `action` INT, IN `quoi` VARCHAR(100))  MODIFIES SQL DATA
        INSERT INTO t_historisation(id_user,id_action, quoi,date_histo)
        VALUES(user,action,quoi, now())";
    $SQL_Proc_P_Add_MEP="CREATE PROCEDURE `P_Add_MEP` (IN `iduser` INT, IN `idarticle` INT, IN `nbpage` SMALLINT)  MODIFIES SQL DATA
        INSERT INTO t_mep(id_auteur,id_article,nb_page,date_creation,date_modif)
        VALUES(
            iduser,
            idarticle,
            nbpage,
            date(now()),
            date(now())
            )";
    $SQL_Proc_P_Add_Message_Espace="CREATE PROCEDURE `P_Add_Message_Espace` (IN `LEspace` INT, IN `LAuteur` INT, IN `LeMessage` VARCHAR(500), IN `LeFichier` VARCHAR(100))  MODIFIES SQL DATA
        INSERT INTO t_message_espace(id_espace,id_auteur,date_message,message,fichier)
        VALUES(
            LEspace,
            LAuteur,
            now(),
            LeMessage,
            LeFichier
            )";
    $SQL_Proc_P_Add_Message_Redaction="CREATE PROCEDURE `P_Add_Message_Redaction` (IN `auteur` INT, IN `article` INT, IN `message` VARCHAR(500) CHARSET utf8mb4, OUT `LID` INT)  MODIFIES SQL DATA
        BEGIN                                            
        INSERT INTO t_message_redaction(fk_auteur,fk_article,date_message,corps) VALUES( auteur, article, now(), message);
        SET LID=LAST_INSERT_ID();
        END";
    $SQL_Proc_P_Add_Param_D="CREATE PROCEDURE `P_Add_Param_D` (IN `nom` VARCHAR(100), IN `valeur` DATE)  
        INSERT INTO t_parametres (Nom_Param, Value_Param_I, Value_Param_D, Value_Param_S) VALUES (nom,0 , valeur, '')";
    $SQL_Proc_P_Add_Param_I="CREATE PROCEDURE `P_Add_Param_I` (IN `nom` VARCHAR(100) CHARSET utf8mb4, IN `valeur` INT)  MODIFIES SQL DATA
        INSERT INTO t_parametres (Nom_Param, Value_Param_I, Value_Param_D, Value_Param_S) VALUES (nom, valeur, '', '')";
    $SQL_Proc_P_Add_Param_S="CREATE PROCEDURE `P_Add_Param_S` (IN `nom` VARCHAR(100), IN `valeur` VARCHAR(200))  MODIFIES SQL DATA
        INSERT INTO `t_parametres` (`Nom_Param`, `Value_Param_I`, `Value_Param_D`, `Value_Param_S`) VALUES (nom, 0, '', valeur)";
    $SQL_Proc_P_Add_Type="CREATE PROCEDURE `P_Add_Type` (IN `nom` VARCHAR(50))  MODIFIES SQL DATA
        INSERT INTO t_type_article(Nom_Type) VALUES(nom)";
    $SQL_Proc_P_Add_User="CREATE PROCEDURE `P_Add_User` (IN `LeNom` VARCHAR(100), IN `LePrenom` VARCHAR(100), IN `LeLogin` VARCHAR(50), IN `LePass` VARCHAR(200), IN `LeRang` TINYINT, IN `LeMail` VARCHAR(100))  MODIFIES SQL DATA
        INSERT INTO t_utilisateurs(login, mail, mdp, nom, prenom, rang) 
        VALUES(LeLogin, LeMail, LePass,LeNom,LePrenom,LeRang)";
    $SQL_Proc_P_Add_User_Espace="CREATE PROCEDURE `P_Add_User_Espace` (IN `usager` INT, IN `Lespace` INT)  MODIFIES SQL DATA
        INSERT into t_usager_espace (fk_usager,fk_espace)
        VALUES(usager, Lespace)";
    $SQL_Proc_P_Add_WebZine="CREATE PROCEDURE `P_Add_WebZine` (IN `titre` VARCHAR(100) CHARSET utf8mb4, IN `date_p` DATE)  MODIFIES SQL DATA
        INSERT INTO t_webzine(Titre_Webzine, Date_Parution, Etat, Date_Creation)
        VALUES(
        titre,
        date_p,
        1,
        DATE(NOW())
        )";
    $SQL_Proc_P_Affecte_Article_Webzine="CREATE PROCEDURE `P_Affecte_Article_Webzine` (IN `id_art` INT, IN `id_web` INT)  MODIFIES SQL DATA
        UPDATE t_articles
        SET webzine=id_web
        WHERE id_article=id_art";
    $SQL_Proc_P_Archive_Article="CREATE PROCEDURE `P_Archive_Article` (IN `id` INT)  MODIFIES SQL DATA
        UPDATE t_articles
        SET Etat_article=4
        WHERE id_article=id";
    $SQL_Proc_P_Change_Verrouillage="CREATE PROCEDURE `P_Change_Verrouillage` (IN `id` INT, IN `nlEtat` BOOLEAN)  MODIFIES SQL DATA
        UPDATE t_articles SET
        art_locked=nlEtat
        WHERE id_article=id";
    $SQL_Proc_P_Mod_Article_Light="CREATE PROCEDURE `P_Mod_Article_Light` (IN `id_a` INT, IN `avance` INT, IN `etat` INT)  MODIFIES SQL DATA
        UPDATE t_articles SET
        Avancee_Article=avance,
        Etat_article=etat,
        date_modification=date(now())
        WHERE id_article=id_a";
    $SQL_Proc_P_Mod_Date_Webzine="CREATE PROCEDURE `P_Mod_Date_Webzine` (IN `id` INT, IN `date_p` DATE)  MODIFIES SQL DATA
        UPDATE t_webzine
        SET Date_Parution=date_p
        WHERE id_webzine=id";
    $SQL_Proc_P_Mod_Etat_Article_Final="CREATE PROCEDURE `P_Mod_Etat_Article_Final` (IN `id` INT, IN `etat` INT, IN `avance` INT)  MODIFIES SQL DATA
        UPDATE t_articles
        SET
        Etat_article=etat,
        Avancee_Article=avance
        WHERE id_article=id";
    $SQL_Proc_P_Mod_Etat_Webzine="CREATE PROCEDURE `P_Mod_Etat_Webzine` (IN `id` INT, IN `etat` INT)  MODIFIES SQL DATA
        UPDATE t_webzine
        SET
        Etat=etat
        WHERE id_webzine=id";
    $SQL_Proc_P_Mod_Fichier_Article="CREATE PROCEDURE `P_Mod_Fichier_Article` (IN `id_art` INT, IN `chemin` VARCHAR(200) CHARSET utf8mb4)  MODIFIES SQL DATA
        UPDATE t_articles
        SET fichiers=chemin
        WHERE id_article=id_art";
    $SQL_Proc_P_Mod_File_MEP="CREATE PROCEDURE `P_Mod_File_MEP` (IN `userid` INT, IN `art_id` INT, IN `fichier` VARCHAR(200))  MODIFIES SQL DATA
        UPDATE t_mep SET id_auteur=userid, fichiers=fichier, date_modif=date(now()) WHERE id_article=art_id";
    $SQL_Proc_P_Mod_MDP="CREATE PROCEDURE `P_Mod_MDP` (IN `id_user` INT, IN `MDP` VARCHAR(200), OUT `ret` INT)  MODIFIES SQL DATA
        BEGIN
        UPDATE t_utilisateurs 
        SET mdp=MDP WHERE id_utilisateur=id_user;
        SET ret=1;
        END";
    $SQL_Proc_P_Mod_MEP="CREATE PROCEDURE `P_Mod_MEP` (IN `userid` INT, IN `art_id` INT, IN `nbpage` SMALLINT)  MODIFIES SQL DATA
        UPDATE t_mep
        SET
        id_auteur=userid,
        nb_page=nbpage,
        date_modif=date(now())
        WHERE id_article=art_id";
    $SQL_Proc_P_Mod_Nom_Webzine="CREATE PROCEDURE `P_Mod_Nom_Webzine` (IN `id` INT, IN `nom` VARCHAR(100) CHARSET utf8mb4)  MODIFIES SQL DATA
        UPDATE t_webzine
        SET
        Titre_Webzine=nom
        WHERE id_webzine=id";
    $SQL_Proc_P_Mod_Param_D="CREATE PROCEDURE `P_Mod_Param_D` (IN `nom` VARCHAR(100), IN `valeur` DATE)  MODIFIES SQL DATA
        UPDATE t_parametres SET Value_Param_D=valeur WHERE Nom_Param=nom";
    $SQL_Proc_P_Mod_Param_I="CREATE PROCEDURE `P_Mod_Param_I` (IN `nom` VARCHAR(100), IN `valeur` INT)  MODIFIES SQL DATA
        UPDATE t_parametres SET Value_Param_I=valeur WHERE Nom_Param=nom";
    $SQL_Proc_P_Mod_Param_S="CREATE PROCEDURE `P_Mod_Param_S` (IN `nom` VARCHAR(100), IN `valeur` VARCHAR(200))  MODIFIES SQL DATA
        UPDATE t_parametres SET Value_Param_S=valeur WHERE Nom_Param=nom";
    $SQL_Proc_P_Mod_PEC="CREATE PROCEDURE `P_Mod_PEC` (IN `id` INT, IN `graf` SMALLINT)  MODIFIES SQL DATA
        UPDATE t_articles
        SET pec=graf WHERE
        id_article=id";
    $SQL_Proc_P_Mod_Rang_User="CREATE PROCEDURE `P_Mod_Rang_User` (IN `id_user` INT, IN `nouv_rang` INT)  MODIFIES SQL DATA
        UPDATE t_utilisateurs
        SET rang=nouv_rang
        WHERE id_utilisateur=id_user";
    $SQL_Proc_P_Mod_Valide_User="CREATE PROCEDURE `P_Mod_Valide_User` (IN `id_user` INT, IN `Laction` TINYINT)  MODIFIES SQL DATA
        BEGIN
        UPDATE t_utilisateurs
        SET is_valable=Laction
        WHERE id_utilisateur=id_user;
        SELECT ROW_COUNT() as ret;
        END";
    $SQL_Proc_P_Remove_Usager_Espace="CREATE PROCEDURE `P_Remove_Usager_Espace` (IN `usager` INT, IN `Lespace` INT)  MODIFIES SQL DATA
        delete from t_usager_espace WHERE (fk_espace=Lespace and fk_usager=usager)";
    $SQL_Proc_P_Set_Article_MEP="CREATE PROCEDURE `P_Set_Article_MEP` (IN `id_a` INT)  MODIFIES SQL DATA
        UPDATE t_articles SET
        Etat_article=5,
        date_modification=date(now())
        WHERE id_article=id_a";
    $SQL_Proc_P_Update_Avance="CREATE PROCEDURE `P_Update_Avance` (IN `id` INT, IN `avance` INT)  MODIFIES SQL DATA
        UPDATE t_articles
        SET Avancee_Article=Avance
        WHERE id_article=id";
//Ajout des valeurs par défaut dans les différentes tables.
    $Tab_Action=array();
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (1, 'Connexion')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (2, 'Modification parametre')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (3, 'Envoi mail')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (4, 'Ajout article')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (5, 'Modification Webzine')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (6, 'Ajout Webzine')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (7, 'Archivage Webzine')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (8, 'Modification article')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (9, 'Modification MEP')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (10, 'Archivage article')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (11, 'Ajout utilisateur')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (12, 'Modifie utilisateur')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (13, 'Deconnexion')";
    $Tab_Action[]="INSERT INTO `t_action` (`id_action`, `nom_action`) VALUES (14, 'Purge Webzines')";
    $Tab_Etat[]="INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES (1, 'Non commencé')";
    $Tab_Etat[]="INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES (2, 'Bon pour relecture')";
    $Tab_Etat[]="INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES (3, 'Autoriser la mise en page')";
    $Tab_Etat[]="INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES (4, 'Archivé')";
    $Tab_Etat[]="INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES (5, 'Prêt pour revue finale')";
    $Tab_Etat[]="INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES (6, 'Prêt pour publication')";
    $TabWebzine[]="INSERT INTO `t_etat_webzine` (`id_etat_webzine`, `nom_etat_webzine`) VALUES (1, 'Non Commencé')";
    $TabWebzine[]="INSERT INTO `t_etat_webzine` (`id_etat_webzine`, `nom_etat_webzine`) VALUES (2, 'En cours')";
    $TabWebzine[]="INSERT INTO `t_etat_webzine` (`id_etat_webzine`, `nom_etat_webzine`) VALUES (3, 'Terminé')";
    $TabWebzine[]="INSERT INTO `t_etat_webzine` (`id_etat_webzine`, `nom_etat_webzine`) VALUES (4, 'Archivé')";
    $TabParam[]="INSERT INTO `t_parametres` (`id_param`, `Nom_Param`, `Value_Param_I`, `Value_Param_D`, `Value_Param_S`) VALUES (1, 'mail', 0, '0000-00-00', 'redaction.webzine@plastikdream.com')";
    $TabParam[]="INSERT INTO `t_parametres` (`id_param`, `Nom_Param`, `Value_Param_I`, `Value_Param_D`, `Value_Param_S`) VALUES (2, 'delai', 30, '0000-00-00', '')";
    $TabType[]="INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES (1, 'Blindés')";
    $TabType[]="INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES (2, 'Avions')";
    $TabType[]="INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES (3, 'Figurines')";
    $TabType[]="INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES (4, 'Dioramas')";
    $TabType[]="INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES (5, 'Trucs/astuces')";
    $TabType[]="INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES (6, 'divers')";
    $TabType[]="INSERT INTO `t_type_article` (`id_type`, `Nom_Type`) VALUES (7, 'Essai')";