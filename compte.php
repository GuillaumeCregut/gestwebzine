<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.4                        */
    /* Date création : 26/03/2021                  */
    /* Dernière modification : 27/04/2021          */
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
    if(isset($_SESSION['login']))
    {
        //On affiche le nom et prenom utilisateur
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        $Id_User=$_SESSION['Utilisateur'];
        //Si on reçoit un formulaire
        if(isset($_POST['new_MDP']))
        {
            $Nouveau_Pass=htmlspecialchars($_POST['new_MDP'],ENT_NOQUOTES,'UTF-8');
            if($Nouveau_Pass!='') //Si le mot de passe n'est pas vide
            {
                $Password= hash('sha512',($Nouveau_Pass));
                $SQLS=$SQL_Mod_MDP;
                //On connecte la base de données
                $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
                $TabVal=array(':id_user'=> $Id_User,':new_mdp'=>$Password);
                $Conn->ExecProc($TabVal,$SQLS);
                $SQLS=$SQL_Get_result_MDP;
                $Conn->sql_query($SQLS);
                $row=$Conn->sql_fetchrow();
                if($row['ret']==1)
                {
                   $moteur->assign('Ok_Msg','Mot de passe changé.');
                }
            }
        }
        $moteur->display($CheminTpl.'compte.tpl');
        $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?>