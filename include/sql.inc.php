<?php
/*V1.0B */
require_once "config.inc.php";
//Requetes sur les utilisateurs :
    //Ajout d'un utilisateur
    $SQL_Add_User='CALL P_Add_User( :nom,:prenom,:login,:pass,:rang,:mail)';
    //Liste de tous les utilisateurs par nom
    $SQL_ALL_User='SELECT id_utilisateur, is_valable,rang,prenom, nom, login, mdp, mail FROM t_utilisateurs ORDER BY nom';
    //Récupère les infos pour le login
    $SQL_Login="SELECT id_utilisateur, login, mdp,nom, prenom, rang,is_valable,mail  FROM t_utilisateurs WHERE login=:Lelogin";
    //Requete récupération pour membres
    $SQL_Equipe="SELECT id_utilisateur, is_valable, rang, prenom, nom FROM t_utilisateurs ORDER BY nom";
    //Modifie le statut d'un membre
    $SQL_Valide="CALL P_Mod_Valide_User(:user,:action)";
    //Requête pour la messagerie
    $SQL_Mess="SELECT id_utilisateur, nom, prenom FROM `t_utilisateurs` WHERE is_valable=1";
    //Requete pour la messagerie (avec id_user en argument)
    $SQL_Usager_Mess="SELECT  nom, prenom FROM `t_utilisateurs` WHERE id_utilisateur=:idUser";
    //Requete pour l'envoi du mail
    $SQL_Envoi_Mail="SELECT mail FROM t_utilisateurs WHERE id_utilisateur=:idUser";
    //Modification du mot de passe
    $SQL_Mod_MDP="CALL P_Mod_MDP(:id_user,:new_mdp,@ret)";
    //Récupère la liste des utilisateurs participants à la rédaction articles du webzine
    $SQL_User_Art_Webzine='SELECT id_article,nom, prenom, id_user FROM v_mail_article WHERE webzine=:id';
    //Récupère la liste des utilisateurs participants à la mise en page du webzine
    $SQL_User_MEP_Article='SELECT id_user,nom, prenom FROM v_mep_redaction WHERE id_article=:id';
    //Récupère l'id user et l'ID article d'un article non documenté pour le webzine
    $SQL_Get_User_Article_Vide="SELECT mail FROM v_relance_article WHERE (webzine=:id) AND(fichiers='')";
    //Récupère le mail d'un utilisateur
    $SQL_Get_Mail_User='SELECT mail FROM t_utilisateurs WHERE id_utilisateur=:id';
    //Récupère le nom, prenom et rang d'un utilisateur
    $SQL_Rang_User="SELECT nom, prenom, rang FROM t_utilisateurs WHERE id_utilisateur=:id";
    //Modifie le rang d'un utilisateur
    $SQL_Mod_Rang='CALL P_Mod_Rang_User(:id,:rang)';
    //Récupère la liste des usagers avec id, nnom, prenom, mail
    $SQL_Get_All_User_Mail='SELECT id_utilisateur, nom, prenom, mail FROM t_utilisateurs';
    //Récupère les utilisateurs d'un m$eme rang
    $SQL_Get_Users_By_Rank="select id_utilisateur,nom, prenom, mail FROM t_utilisateurs WHERE rang=:id";
//Requetes sur les paramètres
    $SQL_Get_Param_Mail="SELECT Value_Param_S from t_parametres where Nom_Param='mail'";
    $SQL_Get_Param_Delai="SELECT Value_Param_I FROM t_parametres where Nom_Param='delai'";
    $SQL_Mod_Param_I="CALL P_Mod_Param_I(:nom,:valeur)";
    $SQL_Mod_Param_S="CALL P_Mod_Param_S(:nom,:valeur)";
    $SQL_Mod_Param_D="CALL P_Mod_Param_D(:nom,:valeur)";
//Requetes sur les types d'articles
    //Récupères tous les types d'articles
    $SQL_All_Types="SELECT id_type, Nom_Type FROM t_type_article ORDER BY Nom_Type";
    $SQL_Add_Type="CALL P_Add_Type(:nom)";
//Requetes sur les articles
    //Ajoute un article
    $SQL_Add_Article='CALL P_Add_Article(:auteur, :type_article, :titre, :descr,:LeMonteur,:MDP)';
    //Récupère une vue résumée des articles
    $SQL_Vu_Article_Resumee="SELECT id_article,art_locked, fichier_mep, auteur_article, etat_article,titre_article,nom, prenom, nom_type, nom_etat,titre_webzine,fichiers FROM v_article_resume";
    $SQL_Vu_Article_Resumee_User="SELECT id_article,art_locked, fichier_mep, etat_article,titre_article,nom, prenom, nom_type, nom_etat,titre_webzine,fichiers FROM v_article_resume WHERE auteur_article=:auteur";
    //Requete a modifier pour récupérer les informations d'un article : Il faut rajouter la date du webzine quand la table sera faite
    $SQLS_Article_Vue_Modif="SELECT Etat_article, Avancee_Article, date_creation,titre_article,description, art_locked,fichiers,Titre_Webzine,id_utilisateur,nom, prenom, nom_etat, Nom_Type,id_Webzine, Date_Parution FROM v_article_pour_modif WHERE id_article=:idarticle";
    //Modifie un article simplement
    $SQL_Mod_Article_Light="CALL P_Mod_Article_Light(:id_a, :avance, :etat)";
    //Met le champs fichiers a jour de la table article
    $SQL_Update_Article_Fichier="CALL P_Mod_Fichier_Article(:idart,:chemin)";
    //Bascule l'article à Mis en page
    $SQL_SET_MEP_Article='CALL P_Set_Article_MEP(:id_article)';
    //Récupère la liste des articles prêts à être mis en page
    $SQL_Vu_Article_Resumee_MEP="SELECT id_article,art_locked, fichier_mep, Etat_article,titre_article,nom, prenom, nom_type, nom_etat,titre_webzine,fichiers FROM v_article_resume WHERE ((etat_article=3) or (etat_article=5)) and (fichiers !='')";
    //Récupère la liste des articles pour la MEP avec Photos OK
    $SQL_Vu_Article_MEP_Photos="SELECT id_article,art_locked, fichier_mep, Etat_article,titre_article,nom, prenom, nom_type, nom_etat,titre_webzine,fichiers,photo_valide FROM v_mep_photos WHERE ((etat_article=3) or (etat_article=5)) and (fichiers !='') and (photo_valide=1)";
    //Requete de comptage des article par Webzine
    $SQL_Count_Article_Webzine="SELECT count(*) as Compte FROM t_articles WHERE webzine=:id_webzine";
    //Requete articles d'un webzine
    $SQL_Get_Article_Webzine='SELECT id_article,art_locked,Avancee_Article,etat_article,titre_article,nom, prenom, nom_type, fichier_mep, nom_etat,titre_webzine,fichiers,nb_page FROM v_article_resume WHERE id_webzine=:id';
    //Requete d'affectation article à un webzine
    $SQlAffecte_Article_Webzine='CALL P_Affecte_Article_Webzine(:id_article,:id_webzine)';
    //Récupère les nom des auteurs article et graphiste de l'article
    $SQL_Utilisateur_Article_by_Id='SELECT auteur_article,graphiste FROM v_utilisateur_article WHERE id_article=:idarticle';
    //Récupère les infos d'un article pour la page MEP
    $SQL_Article_MEP='SELECT art_locked,date_creation, date_modification, webzine, description,titre_article,Avancee_Article, fichiers, nom, prenom, Nom_Type, nom_etat,pec FROM `v_article_mep` WHERE id_article=:id';
    //Change l'état de verrouillage d'un article
    $SQL_Change_Verrrouillage='CALL P_Change_Verrouillage(:id,:etat)';
    //Récupère l'état de verrouillage d'un article
    $SQL_Get_Verrouillage='SELECT art_locked FROM t_articles WHERE id_article=:id';
    //Récupère l'avancement d'un article
    $SQL_Get_Avancement='SELECT Avancee_Article FROM t_articles WHERE id_article=:id';
    //Mets à jour l'avancement d'un article
    $SQL_Update_Avance='CALL P_Update_Avance(:id,:avance)';
    $SQL_Get_Fichier_Article='SELECT fichiers,Avancee_Article FROM t_articles WHERE id_article=:id';
    //Récupère Etat d'un article
    $SQL_Get_State='SELECT Etat_article FROM t_articles WHERE id_article=:id';
    //Change l'état et l'avancement d'un article
    $SQL_Change_Etat_Article_Final='CALL P_Mod_Etat_Article_Final(:id,:etat,:avance)';
    //Récupère l'état et le nom de l'état d'un article
    $SQL_Get_State_NameState='SELECT Etat_article, nom_etat FROM v_article_resume WHERE id_article=:id';
//Requetes sur les Etats 
    $SQL_Etat_Sans_Archive="SELECT id_etat,nom_etat FROM t_etat WHERE (id_etat!=4)";
    //Requete etat Webzine
    $SQL_Etat_Webzine='SELECT id_etat_webzine, nom_etat_webzine FROM t_etat_webzine';
//Requetes sur les mise en pages
    $SQL_Add_MEP="CALL P_Add_MEP(:user,:article,:nbpages)";
    $SQL_Existe_MEP="SELECT COUNT(*) AS compte FROM t_mep WHERE id_article=:idArticle";
    $SQL_Mod_MEP="CALL P_Mod_MEP(:user,:article,:nbpages)";
    $SQL_Get_MEP_Article='SELECT fichiers,date_modif,nom, prenom,nb_page FROM v_mep_affiche_redaction WHERE id_article=:id';
    $SQL_Get_MEP_Fichier='SELECT fichiers FROM t_mep WHERE id_article=:id';
    //Affecte une prise en compte MEP de l'article
    $SQL_SET_MEP='CALL P_Mod_PEC(:id, :graf)';
    //Récupère l'ID' du graphiste qui a fait la PEC 
    $SQL_Get_PEC_Id='SELECT pec FROM t_articles WHERE id_article=:id';
//Requetes Webzine
    //Requete affichage de tous les webzines sauf le non attribué
    $SQL_All_Webzine='SELECT id_webzine,Titre_Webzine,Etat,Date_Parution, nom_etat_webzine FROM v_webzine';
    //Ajoute un webzine
    $SQL_Add_Webzine='CALL P_Add_Webzine(:titre,:date_p)';
    //Regarde si Webzine existe
    $SQl_Count_Webine_Id='SELECT count(*) as Compte FROM t_webzine WHERE id_webzine=:id';
    //Récupère les infos d'un webzine
    $SQL_Get_Webzine_Infos='SELECT Titre_Webzine,Etat,Date_Creation,Date_Parution FROM t_webzine WHERE id_webzine=:id';
    //Modification du webzine
    $SQL_Mod_Nom_Webzine='CALL P_Mod_Nom_Webzine(:id,:nom)';
    $SQL_Mod_Etat_Webzine='CALL P_Mod_Etat_Webzine(:id,:etat)';
    $SQL_Mod_Date_Webzine='CALL P_Mod_Date_Webzine(:id,:date)';
    //Selectionne les artilse du webzine pour le tableau rédaction
    $SQL_Get_Article_Redac_1='SELECT id_article, titre_article, Etat_Article,fichier_article, nom, prenom, fichiers, Nom_type, nom_etat FROM v_article_redaction WHERE (webzine=:id) AND (Etat_Article=:etat)';
    $SQL_Get_Article_Redac_2='SELECT id_article, titre_article, Etat_Article,fichier_article, nom, prenom, fichiers, Nom_type, nom_etat FROM v_article_redaction WHERE (webzine=:id) AND (Etat_Article=:etat1) OR (Etat_Article=:etat2)';
    //Requete pour le nombre de page du webzine
    $SQL_Get_NbPage_Webzine="SELECT nb_page FROM v_nb_page_webzines WHERE webzine=:id";
//Special Archivage
    $SQL_Vue_Archivage='SELECT id_article, FichierArticle, id_mep,Fichier_MEP,titre_article FROM v_article_webzine_archivage WHERE webzine=:id';
    $SQL_Get_Webzine_Name='SELECT Titre_Webzine FROM t_webzine WHERE id_webzine=:id';
    $SQL_Set_Article_Archive='CALL P_Archive_Article(:id)';
 //Requete historisation
    //Ajoute un historique
    $SQL_Add_Histo='CALL P_Add_Histo(:user, :action, :quoi)';  
    $SQL_Voir_Histo="SELECT date_histo, quoi, nom, prenom, nom_action FROM `v_histo` ORDER BY date_histo Desc"; 
//Requetes sur l'espace de travail article
    $SQL_Get_Id_Espace='SELECT id_espace FROM t_espace_travail WHERE fk_article=:idarticle';
    $SQL_Espace_Existe='SELECT count(*) AS COMPTE FROM t_espace_travail WHERE fk_article=:idarticle';
    $SQL_Create_Espace='CALL P_Add_Espace_Article(:idarticle)';
    //Requete récupperant les messages d'un espace
    $SQL_Message_Espace='SELECT id_message, date_message, message, fichier, nom, prenom FROM v_message_espace WHERE id_espace=:id ORDER BY date_message DESC';
    //Requete ajoutant un message à l'espace
    $SQL_Add_Message_Espace='CALL P_Add_Message_Espace(:espace,:auteur,:message,:fichier)';
//Requetes sur les usagers d'un espace de travail
    //Compte le nombre d'usager d'un espace
    $SQL_Compte_Liste_Usagers_Espace='SELECT count(*) AS COMPTE FROM t_usager_espace WHERE fk_espace=:id';
    //Ajoute un usager à l'espace de travail
    $SQL_Add_User_Space='CALL P_Add_User_Espace(:usager,:espace)';
    //Supprime un usager de l'espace
    $SQL_Remove_User_Space='CALL P_Remove_Usager_Espace(:usager,:espace)';
    //Récupère toutes les informations des usagers de l'espace
    $SQL_Get_Usager_Space='SELECT fk_usager, nom, prenom, mail FROM v_usager_espace WHERE fk_espace=:id';
//Messagerie de la rédaction
    //Ajoute un message
    $SQL_Add_Message_Redaction='CALL P_Add_Message_Redaction(:auteur,:article,:message,@LID)';
   // $SQL_Add_Message_Redaction='CALL P_Add_Message_Redaction(:auteur,:article,:message)'; //originale
   //Récupère L'ID inséré
   $SQL_Get_Id_Last_Message='SELECT @LID as id';
    //Récupère les messages d'un article
    $SQL_Get_Message_redaction='SELECT date_message, corps, nom, prenom FROM v_message_redaction WHERE fk_article=:id ORDER BY date_message DESC';
    //Récupère le message par ID
    $SQL_Get_Message_redaction_By_Id='SELECT date_message, corps, nom, prenom FROM v_message_redaction WHERE id_message=:id';
    //Récupère l'adresse mail de l'auteur
    $SQL_Get_Mail_Article_Author='SELECT mail,titre_article FROM v_mail_auteur_article WHERE id_article=:id';
    $SQL_Get_Mail_Admin_Graphiste='SELECT id_utilisateur, mail FROM t_utilisateurs WHERE (rang=:rang1 or rang=:rang2)';
//Ajout version 1.0A
//Recherche les états articles pour rédacteurs
    $SQL_Get_Etat_Redacteur='SELECT id_etat, nom_etat FROM t_etat WHERE id_etat=:a OR id_etat=:b ORDER BY id_etat';
//Recherche une MEP article par ID article
    $SQL_GET_MEP_Article_By_ID='SELECT fichiers FROM t_mep WHERE id_article=:idarticle';
    //Récupère les articles en attente de relecture
    $SQL_Sel_Art_relecteur="SELECT id_article,art_locked, fichier_mep, auteur_article, etat_article,titre_article,nom, prenom, nom_type, nom_etat,titre_webzine,fichiers FROM v_article_resume WHERE etat_article=".Etat_Article_Cours;
    $SQL_Add_File_MEP="CALL P_Add_File_MEP(:id_user,:id_article,:fichier)";
    $SQL_Mod_File_MEP="CALL P_Mod_File_MEP(:id_user,:id_article,:fichier)";
    $SQL_Get_result_MDP="SELECT @ret as ret";
//Concerne les photos
    $SQL_Get_Photo_By_Article='SELECT id_photo, fk_article,chemin_photo, photo_valide FROM t_photos WHERE fk_article=:idarticle';   
    $SQL_Add_Photo='CALL P_Add_Photo(:article,:chemin)';
    $SQL_Update_Photo='CALL P_Mod_Photo(:article,:photo_valide,:nb_photo)'; 
    $SQL_Count_Photo_Id="SELECT count(*) as compte FROM t_photos WHERE fk_article=:idarticle";
    $SQL_Vu_Article_Resumee_Photo="SELECT id_article,art_locked,photo_valide, fichier_mep, auteur_article, etat_article,titre_article,nom, prenom, nom_type, nom_etat,titre_webzine,fichiers FROM v_article_resume_photo";
    $SQL_Get_Article_Webzine_Photo='SELECT id_article,art_locked,Avancee_Article,etat_article,titre_article,nom, prenom, nom_type, fichier_mep, nom_etat,titre_webzine,fichiers,nb_page,photo_valide FROM v_article_resume_photo WHERE id_webzine=:id';
//Gestion des articles
    $SQL_SEL_Art_Id='SELECT auteur_article, type_article, titre_article, description, monteur, mdp_photo FROM t_articles WHERE id_article=:id_article';
    $SQL_Update_Article='call P_Mod_Article(:auteur,:type,:mdp,:monteur,:desc,:titre,:id_article)';
//Purge des articles
    $SQL_Purge_Article='SELECT fichiers, fichier_mep,id_espace,chemin_photo FROM v_purge_article WHERE id_article=:id_article';
    $SQLS_Supp_Espace='CALL P_Remove_Espace(:id_espace)';
    $SQL_Supp_Espace_User='CALL P_Remove_User_Space(:id_espace)';
    $SQL_Supp_MEP='CALL P_Remove_MEP(:id_article)';
    $SQL_Supp_Photo='CALL P_Remove_Photos(:id_article)';
    $SQL_Supp_MEP='CALL P_Remove_MEP(:id_article)';
    $SQL_Supp_Message_Espace='CALL P_Remove_Message_Espace(:id_espace)';
    $SQL_Supp_Message_Redaction='CALL P_Remove_Message_Redaction(:id_article)';
//Gestion des webzines V1.0
    $SQL_Gest_All_Webzine='SELECT id_webzine,Titre_Webzine,Etat,Date_Parution, nom_etat_webzine FROM v_webzine WHERE Etat!='.Etat_Webzine_Archive;
    $SQL_Gest_Webzine_Infos='SELECT Titre_Webzine,nom_etat_webzine,Date_Parution FROM v_webzine WHERE id_webzine=:id';
    $SQL_Gest_Webzine_Archive='SELECT id_webzine,Titre_Webzine,Date_Parution FROM v_webzine WHERE Etat='.Etat_Webzine_Archive;
?>