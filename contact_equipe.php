<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.4                        */
    /* Date création : 30/03/2021                  */
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
            if (isset($_POST['sujet']))
            {
                $Sujet=htmlspecialchars($_POST['sujet'],ENT_NOQUOTES,'UTF-8');
                $Message=htmlspecialchars($_POST['message'],ENT_NOQUOTES,'UTF-8');
                $Dest=$_POST['dest'];
                if(is_array($Dest))
                {
                    if((sizeof($Dest)==0) or ($Sujet=='') or ($Message==''))
                    {
                        //On n'envoie pas
                        $Template='mail_erreur.tpl';;
                    }
                    else
                    {
                        if($Debug)
                            debug_tab($Dest);
                        $Destinataires='';
                        //On récupère la liste des mails destinataires
                        $SQLS=$SQL_Get_Mail_User;
                        $TabVal=array();
                        foreach($Dest as $v)
                        {
                            $TabVal[':id']=$v;
                            $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
                            if ($Debug)
                                debug_tab($row);
                            foreach($row as $rec)
                            {
                                $Destinataires.=$rec['mail'].', ';
                            }
                        }
                        //On supprime les 2 derniers caractères, inutiles
                        $Destinataires=rtrim($Destinataires,", ");
                        if ($Debug)
                            echo "<p>'$Destinataires'</p>";
                        //
                        //Récupération de l'adresse d'envoi
                        $SQLS=$SQL_Get_Param_Mail;
                        $row=$Conn->sql_fetch_all($SQLS);
                        $adresse=$row[0]['Value_Param_S'];
                        $Entete="From: $adresse \n";
                        $Entete.="Reply-to: $adresse\n";
                        if(@mail($Destinataires,$Sujet,$Message,$Entete))
                        {
                            //Historisation
                            $SQLS=$SQL_Add_Histo;
                            $Id_User=$_SESSION['Utilisateur'];
                            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_EnvoiMail, ':quoi'=>$Sujet);
                            $Conn->ExecProc($TabHisto,$SQLS);
                            $moteur->display($CheminTpl.'mail_ok.tpl');
                            die();
                        }
                        else
                        {
                            $moteur->display($CheminTpl.'mail_erreur.tpl');
                            die();
                        }
                        //
                    }
                }
            }
            else
            {
                //On récupère la liste des articles et les utilisateurs du webzine
                $SQLS=$SQL_User_Art_Webzine;
                $tabId=array(':id'=>$Id_Webzine);
                $row=$Conn->sql_fetch_all_prepared($tabId,$SQLS);
                $TabDest=array();
                $TabNom=array();
                $TabArticles=array();
                foreach($row as $rec)
                {
                    $mail=$rec['id_user'];
                    if (!(in_array($mail,$TabDest)))
                    {
                        array_push($TabDest,$mail);
                    }
                    array_push($TabArticles,$rec['id_article']);
                    $Nom=$rec['nom'];
                    $Prenom=$rec['prenom'];
                    $Nom=$Prenom.' '.$Nom;
                    if(!(in_array($Nom,$TabNom)))
                    {
                        array_push($TabNom,$Nom);
                    }
                }
                //On récupère les graphistes
                $SQLS=$SQL_User_MEP_Article;
                foreach($TabArticles as $id_Article)
                {
                    $tabId[':id']=$id_Article;
                    $row=$Conn->sql_fetch_all_prepared($tabId,$SQLS);
                    if($Debug)
                    foreach($row as $rec)
                    {
                        $mail=$rec['id_user'];
                        if (!(in_array($mail,$TabDest)))
                        {
                            array_push($TabDest,$mail);
                        }
                        $Nom=$rec['nom'];
                        $Prenom=$rec['prenom'];
                        $Nom=$Prenom.' '.$Nom;
                        if(!(in_array($Nom,$TabNom)))
                        {
                            array_push($TabNom,$Nom);
                        }
                    }
                }
                if($Debug)
                {
                    debug_tab($TabDest);
                    debug_tab($TabNom);
                }
                $TabContact=array();
                for($i=0;$i<sizeof($TabDest);$i++)
                {
                    $TabContact[$i]['id']=$TabDest[$i];
                    $TabContact[$i]['nom']=$TabNom[$i];
                }
                if($Debug)
                    debug_tab($TabContact);
                //en fin
                $moteur->assign('TabContact',$TabContact);
                $Template='contact_equipe.tpl';
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
