<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.4                        */
    /* Date création : 31/03/2021                  */
    /* Dernière modification : 27/04/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    $Debug=false;
    function debug_tab($Tableau)
    {
        echo "<pre>";
        print_r($Tableau);
        echo "</pre>";
    }
    //On initialise le moteur de template
    $moteur=new Smarty();
    //Connexion àla base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
        //On affiche le nom et prenom utilisateur
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        //On vérifie le niveau du membre
        if( ($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système))  //administrateur
        {
            $Id_Webzine=$_SESSION['id_webzine'];
            //Traitement admin
            //On récupère la liste des articles et les utilisateurs du webzine
            $SQLS=$SQL_Get_User_Article_Vide;
            $tabId=array(':id'=>$Id_Webzine);
            $row=$Conn->sql_fetch_all_prepared($tabId,$SQLS);
            $TabDest=array();
            foreach($row as $rec)
            {
                $mail=$rec['mail'];
                if (!(in_array($mail,$TabDest)))
                {
                    array_push($TabDest,$mail);
                }
            }
            if($Debug)
                debug_tab($TabDest);
            //Vérifie si on a bien au moins 1 destinataire
            if((sizeof($TabDest)>0)) 
            {
                //On envoie le mail
                $Destinataires='';
                foreach($TabDest as $v)
                {
                    $Destinataires.=$v.', ';
                }
                //On supprime les 2 derniers caractères, inutiles
                $Destinataires=rtrim($Destinataires,", ");
                $Sujet=$SujetRelance;
                $Message=$CorpsMessage;
                if($Debug)
                    echo nl2br($Message);
                //Récupération de l'adresse d'envoi
                $SQLS=$SQL_Get_Param_Mail;
                $row=$Conn->sql_fetch_all($SQLS);
                $adresse=$row[0]['Value_Param_S'];
                $Entete="From: $adresse \n";
                $Entete.="Reply-to: $adresse\n";
                //Envoi du mail
                if(@mail($Destinataires,$Sujet,$Message,$Entete))
                {
                    //Historisation
                    $SQLS=$SQL_Add_Histo;
                    $Id_User=$_SESSION['Utilisateur'];
                    $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_EnvoiMail, ':quoi'=>$Sujet);
                    $Conn->ExecProc($TabHisto,$SQLS);
                    $Template='mail_ok.tpl';
                }
                else
                {
                    $moteur->assign('ErreurNum','0x050');
                    $Template='erreur.tpl';
                }
            }
            else
            {
                //On envoie pas de mail car personne de concerné
                $moteur->assign('ErreurNum','0x051 Aucune personne à contacter. ');
                $Template='erreur.tpl';
            }
        }
        else
        {
           if($_SESSION['UserLevel']==Graphiste)
           {
                //Traitement graphiste
                $Template='index_graphiste.tpl';
           }
           else
           {
                //Traitement pigiste
                $Template='index_pigiste.tpl';
           }
           
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