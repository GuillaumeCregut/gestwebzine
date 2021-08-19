<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 25/03/2021                  */
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
    //On vérifie si on a reçu un formulaire
    if(isset($_POST['utilisateur']) and !(isset($_SESSION['login'])))
    {
        
        //Récupération des valeurs entrées
        $LeLogin=htmlspecialchars($_POST['utilisateur'],ENT_NOQUOTES,'UTF-8');
        $LeMdp=htmlspecialchars($_POST['mdp'],ENT_NOQUOTES,'UTF-8');
        //Cryptage du mot de passe pour vérification avec la base de données
        $Password= hash('sha512',($LeMdp));
        $Ok=false;
         //Connexion à la base de données
        $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
        //On vérifie dans la base de données login, mdp, et niveau. On récupère aussi le ID
        $SQLS= $SQL_Login;
        $TabVal=array(':Lelogin'=>$LeLogin);
        $Result=$Conn->ExecProc($TabVal,$SQLS);
        //Si result vaut 1 c'est qu'on a bien un enregistrement
        if($Result==1)
        {
            //On vérifie si le login et le mot de passe sont OK
            $row=$Conn->sql_fetchrow();
            $DB_Login=$row['login'];
            $DB_MDP=$row['mdp'];
            $Autorise=$row['is_valable'];
            //echo "<p>$DB_MDP / $Password</p>";
            if(($DB_Login==$LeLogin)and($DB_MDP==$Password)and($Autorise==1))
            {
                $Ok=true;
                $IdUser=$row['id_utilisateur'];
                $Niveau=$row['rang'];
                $NomUser=$row['nom'];
                $PrenomUser=$row['prenom'];
                //Historisation
                $SQLS=$SQL_Add_Histo;
                $Id_User=$IdUser;
                $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_UserLogin, ':quoi'=>'');
                $Conn->ExecProc($TabHisto,$SQLS);
            }
        }
        //en fin :
        if($Ok){
            $_SESSION['login']=true;
            $_SESSION['Utilisateur']=$IdUser;
            $_SESSION['UserLevel']=$Niveau;
            $_SESSION['Nom_User']=$NomUser;
            $_SESSION['Prenom_User']= $PrenomUser;
        }
        else
        {
            $moteur->assign('Erreur',true);
        }

    }
    //On vérifie si on est déjà connecté
    if(isset($_SESSION['login']))
    {
        //On affiche le nom et prenom utilisateur
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        //On vérifie le niveau du membre
        $Rang=$_SESSION['UserLevel'];
        switch($Rang)
        {
            case Administrateur :
                $Template='index_admin.tpl';
                break;
            case Pigiste :
                $Template='index_pigiste.tpl';
                break;
            case Relecteur :
                $Template='index_relecteur.tpl';
            case Graphiste :
                $Template="index_graphiste.tpl";
                break;
            case Admin_Système:
                $Template='index_admin.tpl';
                $moteur->assign('SA',true);
        }
        $moteur->display($CheminTpl.$Template);
        $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?>