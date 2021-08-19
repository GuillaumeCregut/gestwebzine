<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.2                        */
    /* Date création : 26/03/2021                  */
    /* Dernière modification : 27/03/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "../include/config.inc.php";
    include "../include/smarty.class.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    //Démarrage de la session
    session_start();
    //On initialise le moteur de template
   
    if(isset($_SESSION['login']))
    {
        //On vérifie le niveau du membre
        if( $_SESSION['UserLevel']==Admin_Système)  //administrateur
        {
            $moteur=new Smarty();
            //On affiche le nom et prenom utilisateur
            $NomUser=$_SESSION['Nom_User'];
            $PrenomUser=$_SESSION['Prenom_User'];
            $nom_Prenom=$PrenomUser.' '.$NomUser;
            $moteur->assign('PrenomLogin',$nom_Prenom);
             //Connexion àla base de données
            $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
            $SQLS=$SQL_Voir_Histo;
            $row=$Conn->sql_fetch_all($SQLS);
            $moteur->assign('Table_Histo',$row);
            $moteur->display($CheminTpl.'index.tpl');
        }
        else
        {
            header("HTTP/1.0 404 Not Found");
        }
            
    }
    else
    {
        //On affiche la page par défaut de connexion
       // $moteur->display($CheminTpl.'login.tpl');
       header("HTTP/1.0 404 Not Found");
    }
?>