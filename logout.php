<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 25/03/2021                  */
    /* Dernière modification : 02/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    //Historisation
    if(!empty($_SESSION))
    {
        $Id_User=$_SESSION['Utilisateur'];
        //Connexion à la base de données
        $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
        $SQLS=$SQL_Add_Histo;
        $Id_User=$_SESSION['Utilisateur'];
        $TabHisto=array(
            ':user'=>$Id_User,
        ':action'=>Histo_UserLogout,
        ':quoi'=>'');
        $Conn->ExecProc($TabHisto,$SQLS);
        unset($_SESSION['login']);
    }
    session_destroy();
    $moteur=new Smarty();
    $moteur->display($CheminTpl.'login.tpl');
?>
