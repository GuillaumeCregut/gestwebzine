<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * *
 * 																                 *
 * Nom de la page :	config.inc.php								 *
 * Date création :	23/03/2021										 *
 * Date Modification : 03/07/2021									 *
 * Créateur : Guillaume Crégut										 *													
 * Version :	1.0B 												         *
 * Objet et notes :												      	 *
 *	                        										   *
 *	                              								 *
 * * * * * * * * * * * * * * * * * * * * * * * * * */
    require('connecteur.inc.php');
    $CheminTpl="templates/";
    $CheminBaseFichier='fichiers/';
    $CheminArticle='articles/';
    $CheminMEP='mep/';
    $CheminPhoto='photos/';
    $CheminPhotos='articles_images/';
    $Rep_Photo_Valide='/valide/';
    $CheminMiniatures='mini/';
    $RepTemp='/tmp';
    $CheminEspace='espaces/';
    $MailGestionnaire='gcregut@free.fr';
    $CheminBackupWebzine='backup/';
    //Variables globales
    $Etat_Termine=3;
    $Etat_Encours=2;
    $AdresseSite='http://editiel98.net/plastik/';
    $PagePhotoExt='envoi_photo.php?Article=';
    define('Action_Archivage',4);
    //Role utilisateur
    define('Administrateur',1); //Rédac chefs
    define('Pigiste',2);
    define('Graphiste',3);
    define('Admin_Système',4);
    //Ajout v1.0A
    define('Relecteur',5);
    define('DateAlerteWebzine',15);
    define('DateWarningWebzine',30);
    //Fin ajout
    define('MaxRang',6); //Utilisé dans modification utilisateur Définie le nombre de rang+1
    //Etat des articles
    define('Etat_Article_Vierge',1); //Non commencé
    define('Etat_Article_Cours',2); //Bon pour relecture
    define('Etat_Article_Termine',3); //Bon autoriser la mise en page
    define('Etat_Article_Archive',4); //Archivé
    define('Etat_Article_MEP',5); //Pret pour revue finale
    define('Etat_Article_OK',6); //Pret pour publication
    //Etat des webzines
    define('Etat_Webzine_Archive',4);
    define('Etat_Webzine_Vierge',1);
    define('Etat_Webzine_En_Cours',2);
    define('Etat_Webzine_Termine',3);
   //Historisation
    define('Histo_UserLogin',1);
    define('Histo_Modif_Param',2);
    define('Histo_EnvoiMail',3);
    define('Histo_AjoutArticle',4);
    define('Histo_Modif_Webzine',5);
    define('Histo_Ajout_Webzine',6);
    define('Histo_Archivage_Webzine',7);
    define('Histo_Modif_Article',8);
    define('Histo_Modif_MEP',9);
    define('Histo_Add_MEP',9); //Si jamais on change la valeur dans la BDD
    define('Histo_ArchivageArticle',10);
    define('Histo_AjoutUser',11);
    define('Histo_Modif_User',12);
    define('Histo_UserLogout',13);
    define('Histo_Purge',14);
    //Message de relance des pigistes :
    $SujetRelance="Relance Webzine PlastikDream";
    $CorpsMessage="Bonjour, vous avez proposer un article à paraitre dans le prochain numéro du webzine PlastikDream.\n
    A ce jour, nous n'avons toujours pas reçu cet article, et ne pouvons donc le mettre en page et l'intégrer.\n
    Pourriez vous faire le nécessaire au plus vite ?\n
    Nous vous remercions par avance.\n
    L'équipe Webzine PlastikDream\n".$AdresseSite;
    //Fichier Excel
    define('StartCell',2);
    define('CellPrenom','A');
    define('CellNom','B');
    define('CellLogin','C');
    define('CellMail','D');
    define('CellRang','E');
    define('DefaultPassword','Plastik1234');
    //Etape Article
    define('Step_Article',25);
?>