ALTER TABLE `t_articles` ADD `art_locked` BOOLEAN NOT NULL DEFAULT FALSE AFTER `type_article`;
/* */
CREATE
 VIEW `v_article_mep`
 AS SELECT ta.id_article, ta.art_locked, ta.date_creation, ta.date_modification, ta.webzine, ta.description,ta.titre_article, ta.Avancee_Article, ta.fichiers, tu.nom, tu.prenom, tt.Nom_Type, te.nom_etat  FROM t_articles ta INNER JOIN t_utilisateurs tu
on ta.auteur_article=tu.id_utilisateur
INNER JOIN t_type_article tt
on ta.type_article=tt.id_type
INNER JOIN t_etat te
ON ta.Etat_article=te.id_etat
/* */
ALTER TABLE `t_mep` ADD `nb_page` SMALLINT NOT NULL DEFAULT '0' AFTER `id_article`;
/*
*/
CREATE TABLE `orga_test`.`t_message_redaction` ( `id_message` INT NOT NULL AUTO_INCREMENT ,  `fk_article` INT NOT NULL ,  `fk_auteur` INT NOT NULL ,  `date_message` DATETIME NOT NULL ,  `corps` VARCHAR(500)  CHARACTER SET utf8mb4  NOT NULL ,    PRIMARY KEY  (`id_message`)) ENGINE = InnoDB;
/* */

/* */
ALTER TABLE `t_message_redaction` ADD CONSTRAINT `c_mradaction_auteur` FOREIGN KEY (`fk_auteur`) REFERENCES `t_utilisateurs`(`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;
/* */
ALTER TABLE `t_message_redaction` ADD CONSTRAINT `c_mredaction_article` FOREIGN KEY (`fk_article`) REFERENCES `t_articles`(`id_article`) ON DELETE RESTRICT ON UPDATE RESTRICT;
/* */
CREATE
 VIEW `v_message_redaction`
 AS SELECT tm.id_message,tm.fk_article, tm.date_message,tm.corps,tu.nom,tu.prenom FROM t_message_redaction tm INNER JOIN t_utilisateurs tu ON tm.fk_auteur=tu.id_utilisateur
 /**/
 /*Pour test */
 CREATE PROCEDURE `P_Add_Message_Redaction`(IN `auteur` INT, IN `article` INT, IN `message` VARCHAR(500) CHARSET utf8mb4, OUT `LID` INT) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN INSERT INTO t_message_redaction(fk_auteur,fk_article,date_message,corps) VALUES( auteur, article, now(), message); SET LID=LAST_INSERT_ID(); END
 /* */
 CREATE VIEW v_mep_affiche_redaction
 AS SELECT tm.id_mep,tm.id_article, tm.fichiers,tm.nb_page,tm.date_modif,tu.nom,tu.prenom FROM t_mep tm
INNER JOIN t_utilisateurs tu
ON tm.id_auteur=tu.id_utilisateur
/* */
CREATE PROCEDURE `P_Change_Verrouillage`(IN `id` INT, IN `nlEtat` BOOLEAN) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER UPDATE t_articles SET art_locked=nlEtat WHERE id_article=id
/* */
DROP PROCEDURE `P_Mod_MEP`; CREATE  PROCEDURE `P_Mod_MEP`(IN `userid` INT, IN `art_id` INT, IN `LeFichier` VARCHAR(200), IN `nbpage` SMALLINT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER UPDATE t_mep SET id_auteur=userid, fichiers=LeFichier, nb_page=nbpage, date_modif=date(now()) WHERE id_article=art_id
/* */

/* */ 
RENAME TABLE v_article_resume TO v_article_resume_old;
/* */
CREATE VIEW v_article_resume
 AS select ta.id_article,ta.auteur_article ,ta.Etat_article,ta.titre_article,ta.Avancee_Article,
ta.art_locked, ta.fichiers,tu.nom,tu.prenom,tt.Nom_Type,te.nom_etat,tw.id_webzine,tw.Titre_Webzine,
tm.fichiers as fichier_mep, tm.nb_page as nb_page from 
t_articles ta inner join t_utilisateurs tu ON
ta.auteur_article = tu.id_utilisateur
INNER JOIN t_type_article tt on
ta.type_article = tt.id_type
INNER JOIN t_etat te on
ta.Etat_article = te.id_etat
INNER join t_webzine tw on
ta.webzine = tw.id_webzine
LEFT JOIN t_mep tm
ON ta.id_article=tm.id_article
where (ta.Etat_article <> 4)
/* */
RENAME TABLE v_article_pour_modif TO v_article_pour_modif_old;
/* */
CREATE VIEW `v_article_pour_modif`
 AS select ta.id_article,ta.art_locked, ta.Etat_article, ta.Avancee_Article, ta.date_creation, ta.titre_article, ta.description, ta.fichiers, tw.id_webzine, tw.Date_Parution, tw.Titre_Webzine, tu.nom, tu.prenom, te.nom_etat, tt.Nom_Type
FROM t_articles ta INNER JOIN t_webzine tw ON
ta.webzine = tw.id_webzine
INNER JOIN t_utilisateurs tu ON
ta.auteur_article = tu.id_utilisateur
INNER JOIN t_etat te ON
ta.Etat_article = te.id_etat
INNER JOIN t_type_article tt ON 
ta.type_article = tt.id_type
/* */
CREATE VIEW v_nb_page_webzines
 AS select ta.webzine,ta.id_article,tm.nb_page,tm.id_mep FROM  t_articles ta
Left join t_mep tm
ON
ta.id_article=tm.id_article
/* */
INSERT INTO `t_etat` (`id_etat`, `nom_etat`) VALUES ('6', 'Prêt pour intégration');
/* */
CREATE PROCEDURE `P_Update_Avance`(IN `id` INT, IN `avance` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER UPDATE t_articles SET Avancee_Article=Avance WHERE id_article=id
/* */
 CREATE PROCEDURE `P_Add_MEP`(IN `iduser` INT, IN `idarticle` INT, IN `Lesfichiers` VARCHAR(200) CHARSET utf8mb4, IN `nbpage` SMALLINT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER INSERT INTO t_mep(id_auteur,id_article,fichiers,nb_page,date_creation,date_modif) VALUES( iduser, idarticle, Lesfichiers, nbpage, date(now()), date(now()) )
 /* */
 CREATE PROCEDURE `P_Mod_Etat_Article_Final`(IN `id` INT, IN `etat` INT, IN `avance` INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER UPDATE t_articles SET Etat_article=etat, Avancee_Article=avance WHERE id_article=id
 /* */
 CREATE VIEW `v_mail_auteur_article`
 AS SELECT t_utilisateurs.mail,t_articles.id_article,t_articles.titre_article FROM t_articles INNER JOIN t_utilisateurs ON 
t_articles.auteur_article=t_utilisateurs.id_utilisateur

/*V0.8 */
ALTER TABLE t_articles ADD `pec` SMALLINT NOT NULL DEFAULT '0' AFTER `id_article`;
/* */
CREATE PROCEDURE `P_Mod_PEC`(IN `id` INT, IN `graf` SMALLINT) NOT DETERMINISTIC MODIFIES SQL DATA  SQL SECURITY DEFINER UPDATE t_articles SET pec=graf WHERE id_article=id
/* */
ALTER ALGORITHM = UNDEFINED  SQL SECURITY DEFINER VIEW `v_article_mep` AS select ta.id_article, ta.art_locked, ta.date_creation, ta.date_modification, ta.webzine, ta.description, ta.titre_article, ta.Avancee_Article, ta.fichiers,ta.pec, tu.nom, tu.prenom, tt.Nom_Type, te.nom_etat FROM t_articles ta INNER join t_utilisateurs tu on ta.auteur_article = tu.id_utilisateur INNER join t_type_article tt on ta.type_article = tt.id_type INNER join t_etat te on ta.Etat_article = te.id_etat

