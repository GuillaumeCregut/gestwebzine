<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 25/03/2021                  */
    /* Dernière modification : 01/07/2021          */
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
        //On vérifie le niveau du membre
        if( ($_SESSION['UserLevel']==Admin_Système))  //administrateur
            {
                //Si on a reçu un ajout de membre
                if(isset($_POST['nom']))
                {
                    //On récupère les infos
                   // print_r($_POST);
                    $leNom=htmlspecialchars($_POST['nom'],ENT_NOQUOTES,'UTF-8');
                    $LePrenom=htmlspecialchars($_POST['prenom'],ENT_NOQUOTES,'UTF-8');
                    $LeMail=htmlspecialchars($_POST['email'],ENT_NOQUOTES,'UTF-8');
                    $LeLogin=htmlspecialchars($_POST['login'],ENT_NOQUOTES,'UTF-8');
                    $LePass=htmlspecialchars($_POST['mdp'],ENT_NOQUOTES,'UTF-8');
                    $LeRole=intval($_POST['role']);
                    $LePass=hash('sha512',($LePass));
                    //On ajoute dans la base de données
                    $SQLS=$SQL_Add_User;
                    $TabVal=array(':nom'=>$leNom,':prenom'=>$LePrenom,':login'=>$LeLogin,':pass'=>$LePass,':rang'=>$LeRole,':mail'=>$LeMail);
                    $Result=$Conn->ExecProc($TabVal,$SQLS);
                    if($Result==1)
                    {
                        //Envoi mail
                        //Message de bienvenu au système
                        $LeSujet='Bienvenue dans le Webzine PlastikDream';
                        $dest=$LeMail;
                        $MessageBienvenu="Bonjour $LePrenom $leNom\nVous avez été ajouter au système du webzine, accessible à cette adresse : $AdresseSite \n
                        Vous pouvez dorénavant vous y connecter avec le login : $LeLogin et le mot de passe qui vous a été attribué.\n
                        Cependant, il vous est conseillé de changer celui-ci a votre première connexion en vous rendant dans mon compte.\n
                        Pour toute question, n'hésitez pas à nous contacter sur le discord.\n
                        Pour toute question technique ou bug du logiciel contacter moi à l'adresse suivante : $MailGestionnaire\n
                        Maquettement votre,\nL'équipe Webzine";
                        $SQLS=$SQL_Get_Param_Mail;
                        $row=$Conn->sql_fetch_all($SQLS);
                        $adresse=$row[0]['Value_Param_S'];
                        $Entete="From: $adresse \n";
                        $Entete.="Reply-to: $adresse\n";
                        if(@mail($dest,$LeSujet,$MessageBienvenu,$Entete))
                        {   
                            $SQLS=$SQL_Add_Histo;
                            $Id_User=$_SESSION['Utilisateur'];
                            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_EnvoiMail, ':quoi'=>$LeSujet.' '.$dest);
                            $Conn->ExecProc($TabHisto,$SQLS);
                        }
                        //Historisation
                        $SQLS=$SQL_Add_Histo;
                        $Id_User=$_SESSION['Utilisateur'];
                        $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_AjoutUser, ':quoi'=>$LeLogin);
                        $Conn->ExecProc($TabHisto,$SQLS);
                    }
                }
                $Template='equipe_admin.tpl';
                //Affectation des roles
                $moteur->assign('Admin',Administrateur);
                $moteur->assign('Redacteur',Pigiste);
                //Ajout 1.0A
                $moteur->assign('Relecteur',Relecteur);
                //Fin ajout
                $moteur->assign('Graphiste',Graphiste);
                $moteur->assign('AdSys',Admin_Système);
            }
            else
            {
               //Traitement pigiste
               $Template='equipe_pigiste.tpl';
            }
            //On récupère de la base de données les membres.
            $SQLS=$SQL_Equipe;
            $Result=$Conn->sql_query($SQLS);
            $TabUser=array();
            $i=0;
            while($row=$Conn-> sql_fetchrow())
            {
                $v=$row['rang'];
               switch($v)
                {
                    case Administrateur:
                        $r='redacchef';
                        break;
                    case Pigiste:
                        $r='pigiste';
                        break;
                    case Graphiste: 
                        $r='grahiste';
                        break;
                    case Admin_Système:
                        $r='administrateur';
                        break;
                }
                $v=$row['is_valable'];
                if($v=='0')
                {
                    $valable='supprime';
                    $Coche="checked";
                }
                else
                {
                    $valable='';
                    $Coche="";
                }
                $TabUser[$i]['rang']=$r;
                $TabUser[$i]['is_valable']=$valable;
                $TabUser[$i]['nom']=$row['nom'];
                $TabUser[$i]['prenom']=$row['prenom'];
                $TabUser[$i]['id_utilisateur']=$row['id_utilisateur'];
                $TabUser[$i]['Coche']=$Coche;
                $i++;
            }
            //On les affiche
            $moteur->assign('TabUser',$TabUser);
            //On envoie la page
            $moteur->display($CheminTpl.$Template);
            $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?> 