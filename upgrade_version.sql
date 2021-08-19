--Unification des noms d'articles 
ALTER TABLE `t_articles` ADD UNIQUE( `titre_article`);

--Modification procédures stockées existantes pour la MEP
DROP PROCEDURE `P_Add_MEP`; 
CREATE  PROCEDURE `P_Add_MEP`(IN `iduser` INT, IN `idarticle` INT, IN `nbpage` SMALLINT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
 INSERT INTO t_mep(id_auteur,id_article,nb_page,date_creation,date_modif) VALUES( iduser, idarticle, nbpage, date(now()), date(now()) );
--
DROP PROCEDURE `P_Mod_MEP`;
CREATE PROCEDURE `P_Mod_MEP`(IN `userid` INT, IN `art_id` INT, IN `nbpage` SMALLINT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
 UPDATE t_mep SET id_auteur=userid, nb_page=nbpage, date_modif=date(now()) WHERE id_article=art_id;

--Modification table MEP
ALTER TABLE `t_mep` CHANGE `nb_page` `nb_page` SMALLINT NULL DEFAULT '0';

--Modification t_MEP
ALTER TABLE `t_mep` ADD UNIQUE( `id_article`);

--Ajout procédure pour MEP files
CREATE PROCEDURE `P_Add_File_MEP`(IN `iduser` INT, IN `idarticle` INT, IN `fichier` VARCHAR(200)) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
 INSERT INTO t_mep(id_auteur,id_article,fichiers,date_creation,date_modif) VALUES( iduser, idarticle, fichier, date(now()), date(now()) );

--
CREATE PROCEDURE `P_Mod_File_MEP`(IN `userid` INT, IN `art_id` INT, IN `fichier` VARCHAR(200)) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
 UPDATE t_mep SET id_auteur=userid, fichiers=fichier, date_modif=date(now()) WHERE id_article=art_id;
--
DROP PROCEDURE `P_Mod_MDP`;
 CREATE PROCEDURE `P_Mod_MDP`(IN `id_user` INT, IN `MDP` VARCHAR(200), OUT `ret` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN UPDATE t_utilisateurs SET mdp=MDP WHERE id_utilisateur=id_user; SET ret=1; END

--
ALTER TABLE `t_articles` ADD `monteur` VARCHAR(100) NULL DEFAULT NULL AFTER `description`;
ALTER TABLE `t_articles` ADD `mdp_photo` VARCHAR(10) NULL DEFAULT NULL AFTER `monteur`;
--
DROP PROCEDURE `P_Add_Article`; 
CREATE PROCEDURE `P_Add_Article`(IN `auteur` INT, IN `typeA` INT, IN `titre` VARCHAR(100) CHARSET utf8mb4, IN `Descr` VARCHAR(200) CHARSET utf8mb4, IN `Lemonteur` VARCHAR(100), IN `LeMDP` VARCHAR(10)) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER INSERT INTO t_articles(auteur_article, type_article,titre_article,description,monteur,mdp_photo,date_creation,date_modification) VALUES(auteur, typeA,titre,Descr,Lemonteur,LeMDP,date(now()),date(now()))
--
CREATE TABLE t_photos ( id_photo INT NOT NULL ,  fk_article INT NOT NULL ,  photo_valide BOOLEAN NOT NULL DEFAULT FALSE , chemin_photo VARCHAR(200) NULL DEFAULT NULL ) ENGINE = InnoDB;
ALTER TABLE t_photos CHANGE id_photo id_photo INT NOT NULL AUTO_INCREMENT, add PRIMARY KEY (id_photo);

ALTER TABLE t_photos ADD UNIQUE( fk_article);

ALTER TABLE t_photos ADD CONSTRAINT c_photos_article FOREIGN KEY (fk_article) REFERENCES t_articles(id_article) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
CREATE PROCEDURE `P_Add_Photo`(IN `id_art` INT, IN `chemin` VARCHAR(200) CHARSET utf8mb4) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER
 INSERT INTO t_photos(fk_article,chemin_photo) VALUES (id_art,chemin);

--

ALTER TABLE `t_photos` ADD `nbre_fichiers` TINYINT NOT NULL DEFAULT '0' AFTER `photo_valide`;



CREATE PROCEDURE `P_Mod_Photo`(IN `id_art` INT, IN `valide` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
 UPDATE t_photos SET photo_valide=valide WHERE fk_article=id_art;



CREATE
 ALGORITHM = UNDEFINED
 VIEW `v_mep_photos`
 AS select ta.id_article,ta.auteur_article, ta.Etat_article, ta.titre_article, ta.Avancee_Article, ta.art_locked, ta.fichiers, tu.nom, tu.prenom, tt.Nom_Type, te.nom_etat, tw.id_webzine, tw.Titre_Webzine, tm.fichiers AS fichier_mep, tm.nb_page,tp.photo_valide 
 FROM t_articles ta INNER join t_utilisateurs tu on
 ta.auteur_article = tu.id_utilisateur
 INNER join t_type_article tt on
 ta.type_article = tt.id_type 
 INNER join t_etat te on
 ta.Etat_article = te.id_etat
INNER join t_webzine tw on
ta.webzine = tw.id_webzine
INNER JOIN t_photos tp ON
tp.fk_article=ta.id_article
 left join t_mep tm on
 ta.id_article = tm.id_article
 where (ta.Etat_article <> 4);

--

CREATE
 ALGORITHM = UNDEFINED
 VIEW `v_article_resume_photo`
 AS select ta.id_article, ta.auteur_article, ta.Etat_article, ta.titre_article, ta.Avancee_Article, ta.art_locked, ta.fichiers, tu.nom, tu.prenom, tt.Nom_Type, te.nom_etat, tw.id_webzine, tw.Titre_Webzine, tm.fichiers AS fichier_mep,tm.nb_page, tp.photo_valide
 from t_articles ta inner join t_utilisateurs tu on
 ta.auteur_article = tu.id_utilisateur
inner join t_type_article tt on
ta.type_article = tt.id_type
inner join t_etat te on
ta.Etat_article = te.id_etat
inner join t_webzine tw on
ta.webzine = tw.id_webzine
left join t_mep tm on
ta.id_article = tm.id_article
left join t_photos tp ON
ta.id_article=tp.fk_article
 where (ta.Etat_article <> 4);

 --

 CREATE PROCEDURE `P_Mod_Article`(IN `auteur` INT, IN `letype` INT, IN `mdp` VARCHAR(10) CHARSET utf8mb4, IN `Lemonteur` VARCHAR(100) CHARSET utf8mb4, IN `descr` VARCHAR(200) CHARSET utf8mb4, IN `titre` VARCHAR(100) CHARSET utf8mb4, IN `id` INT) 
 NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER 
 UPDATE t_articles SET auteur_article=auteur,
  type_article=letype, mdp_photo=mdp,
   monteur=Lemonteur, description=descr,
    titre_article=titre, date_modification=DATE(NOW()) WHERE id_article=id;
 --
CREATE
 ALGORITHM = UNDEFINED
 VIEW `v_purge_article`
 AS SELECT ta.id_article,ta.fichiers, tm.fichiers as fichier_mep,tet.id_espace,tp.chemin_photo
FROM t_articles ta LEFT JOIN t_mep tm ON
ta.id_article=tm.id_article
LEFT JOIN t_espace_travail tet ON
ta.id_article=tet.fk_article
LEFT JOIN t_photos tp ON
ta.id_article=tp.fk_article;
--
CREATE PROCEDURE `P_Remove_MEP`(IN `id` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER DELETE FROM t_mep WHERE id_article=id;
--
CREATE PROCEDURE `P_Remove_Photos`(IN `id` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER DELETE FROM t_photos WHERE fk_article=id;
--
CREATE PROCEDURE `P_Remove_User_Space`(IN `id` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER DELETE FROM t_usager_espace WHERE fk_espace=id;
--
CREATE PROCEDURE `P_Remove_Espace`(IN `id` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER DELETE FROM t_espace_travail WHERE id_espace=id;
--
CREATE PROCEDURE `P_Remove_Message_Espace`(IN `id` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER DELETE FROM t_message_espace WHERE id_espace=id;
--
CREATE PROCEDURE `P_Remove_Message_Redaction`(IN `id` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER DELETE FROM t_message_redaction WHERE fk_article=id;
--
ALTER ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `v_article_webzine_archivage` AS select `ta`.`id_article` AS `id_article`,`ta`.`fichiers` AS `FichierArticle`,`ta`.`webzine` AS `webzine`,ta.titre_article,`tm`.`id_mep` AS `id_mep`,`tm`.`fichiers` AS `Fichier_MEP` from (`orga_test`.`t_articles` `ta` join `orga_test`.`t_mep` `tm` on((`tm`.`id_article` = `ta`.`id_article`))) ;