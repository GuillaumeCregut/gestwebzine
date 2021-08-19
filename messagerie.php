<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.6                        */
    /* Date création : 26/03/2021                  */
    /* Dernière modification : 03/05/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php"; 
    //Démarrage de la session
    session_start();
    //On initialise le moteur de template
    $moteur=new Smarty();
    //On connecte la base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
        //On affiche le nom et prenom utilisateur
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        //Récupération des membres pour la messagerie
        if(isset($_POST['destinataire']))
        {
           $LeId=intval($_POST['destinataire']);
            //Récupération du destinataire :
            $SQLS=$SQL_Usager_Mess;
            $TabVal=array(':idUser'=>$LeId);
            $Conn->ExecProc($TabVal,$SQLS);
            $row=$Conn->sql_fetchrow();
            if(isset($row))
            {
                $Nom=$row['nom'];
                $Prenom=$row['prenom'];
                $moteur->assign('DestOK',true);
                $moteur->assign('Prenom',$Prenom);
                $moteur->assign('Nom',$Nom);
                $moteur->assign('idUser',$LeId);
            }
        }
        //Récupération de la liste des destinataires
        $SQLS=$SQL_Mess;
        $Result=$Conn->sql_fetch_all($SQLS);
        $moteur->assign('TabDest',$Result);
        //Affichage de la page
        $moteur->display($CheminTpl.'messagerie.tpl');
        $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?>