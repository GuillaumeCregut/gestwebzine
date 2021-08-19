<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 19/04/2021                  */
    /* Dernière modification : 01/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */ 
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    ini_set('display_errors',1);
    $Debug=false;
    function debug_tab($Tableau, $Toggle)
    {
       if($Toggle)
       {
           echo "<pre>";
        print_r($Tableau);
        echo "</pre>";
       }  
    }
    //On initialise le moteur de template
    $moteur=new Smarty();
    //Connexion àla base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
        //Si on a un article en mémoire
        if (isset($_SESSION['Id_Article_Cours']))
        {
            if( ($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Relecteur) or ($_SESSION['UserLevel']==Admin_Système))  //administrateur
            {
                $moteur->assign('RangModificateur',1);
            }
            if (isset($_POST['auteur'])and isset($_POST['EspaceId']) and isset($_POST['texte_message']))
            {
                $Id_Usager=intval($_POST['auteur']);
                $Id_Space=intval($_POST['EspaceId']);
                $TitreArticleMessage=htmlspecialchars($_POST['titre_article'],ENT_NOQUOTES,'UTF-8');
                $LeMessage=htmlspecialchars($_POST['texte_message'],ENT_NOQUOTES,'UTF-8');
                //On regarde si  on a un fichier
                if(isset($_FILES['fichier']['error']))
                {
                $Erreur=$_FILES['fichier']['error'];
                if(($Erreur!=0) and ($Erreur!=4))
                {
                        //On a eu un problème avec le fichier
        
                }
                else
                {
                    //On charge le fichier
                    if($Erreur==0)
                    {
                        $NomFichier= $Id_Usager.'-'.date('d-m-Y_H-m-s').'.zip';  
                        $CheminFichier=$CheminBaseFichier.$CheminEspace.$Id_Space.'/'.$NomFichier;
                        if(move_uploaded_file($_FILES['fichier']['tmp_name'],$CheminFichier))
                        {
                            $LeFichier=$NomFichier;
                        }
                        else
                            $LeFichier='';
                    }
                    else
                            $LeFichier='';
                }
                }    
                
                //On effectue la requete
                $SQLS=$SQL_Add_Message_Espace;
                $TabVal=array(':auteur'=>$Id_Usager,':espace'=>$Id_Space,':message'=>$LeMessage,':fichier'=>$LeFichier);
                $Resultat=$Conn->ExecProc($TabVal,$SQLS);
                //Envoi du mail à tous les destinataires :
                //Récupération des adresses mail 
                $SQLS=$SQL_Get_Usager_Space;
                $TabRech=array(':id'=>$Id_Space);
                $row=$Conn->sql_fetch_all_prepared($TabRech,$SQLS);
                debug_tab($row,$Debug);
                //Mise en place des destinataires
                $Destinataires='';
                foreach($row as $v)
                {
                    
                    //Suppression de l'auteur du message de la liste
                    if($v['fk_usager']!=$Id_Usager)
                        $Destinataires.=$v['mail'].', ';
                }
                //On supprime les 2 derniers caractères, inutiles
                $Destinataires=rtrim($Destinataires,", ");
                if($Debug)
                    echo "<p>$Destinataires</p>";
                $Sujet='Webzine : Espace Article';
                $Message="Bonjour,\nVous avez reçu un message dans l'espace d'échange article '$TitreArticleMessage' de la plateforme du webzine\n
                Vous pouvez vous connecter à la plateforme pour le consulter.\nL'équipe du webzine\n$AdresseSite";
                //Récupération de l'expéditeur
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
                    $TabHisto=array(':user'=>$Id_Usager, ':action'=>Histo_EnvoiMail, ':quoi'=>$Sujet);
                    $Conn->ExecProc($TabHisto,$SQLS);
                }
                //
            }
            //Fin récupération formulaire
            $Id_Article=$_SESSION['Id_Article_Cours'];
            $Id_User=$_SESSION['Utilisateur'];
             //On affiche le nom et prenom utilisateur
            $NomUser=$_SESSION['Nom_User'];
            $PrenomUser=$_SESSION['Prenom_User'];
            $nom_Prenom=$PrenomUser.' '.$NomUser;
            $moteur->assign('PrenomLogin',$nom_Prenom);
            //Récupération des infos de l'article
            $SQLS=$SQLS_Article_Vue_Modif;
            $TabVal=array(':idarticle'=>$Id_Article);
            $Conn->ExecProc($TabVal,$SQLS);
            $row=$Conn->sql_fetchrow();
            debug_tab($row,$Debug);
            $Auteur_art=$row['prenom'].' '.$row['nom'];
            $Titre=$row['titre_article'];
            if ($Debug)
                echo "<p>Fichier : ".$row['fichiers']."</p>";
            if($row['fichiers']!='')
            {
                $FichierArticle=$CheminBaseFichier.$CheminArticle.$row['fichiers'];

                $moteur->assign('LeFichier',$FichierArticle);
            }
            //Récupération état de l'article
            $EtatArticle=$row['Etat_article'];
            $Nom_Etat_Article=$row['nom_etat'];
            $moteur->assign('Id_Article',$Id_Article);
            $Description=$row['description'];
            $Verrouille=$row['art_locked'];
            $Description=nl2br( $Description);
            //Affichage
            $moteur->assign('Auteur_art',$Auteur_art);
            $moteur->assign('Titre',$Titre);
            $moteur->assign('Description',$Description);
            $AfficheEtat='';
            //On cache la fenêtre si l'article n'est pas bon pour relecture ou si l'article est verrouillé
            if(($EtatArticle==Etat_Article_Vierge) or $Verrouille==1)
                $AfficheEtat='cacher';
            $moteur->assign('ClasseAffiche',$AfficheEtat);
            //Affichage de l'état de l'article
            if($EtatArticle<=Etat_Article_Cours)
            {
                //case pas cochée
                $CaseCochee='';
            }
            else
            {
                //Case cochée
                $CaseCochee='checked';
            }
            $moteur->assign('EtatCase',$CaseCochee);
            //Récupération des 2 états possibles pour la mise en page
            $SQLS= $SQL_Get_Etat_Redacteur;//='SELECT id_etat, nom_etat FROM t_etat WHERE id_etat=:a OR id_etat=:b';
            $TabAlt=array(':a'=>Etat_Article_Cours,':b'=>Etat_Article_Termine);
            $rows=$Conn->sql_fetch_all_prepared($TabAlt,$SQLS);
            foreach($rows as $v)
            {
                $Id_Etat_Bdd=$v['id_etat'];
                $Nom_Etat=$v['nom_etat'];
                if($Id_Etat_Bdd==Etat_Article_Cours)
                {
                    $moteur->assign('Etat1', $Nom_Etat);
                }
                else
                    $moteur->assign('Etat2', $Nom_Etat);
            }
            //Affiche l'état en cours
            //$AfficheEtat=;
            $moteur->assign('EtatTexte',$Nom_Etat_Article);
            //Récupération de l'espace de travail
            $SQLS=$SQL_Espace_Existe;
            $Conn->ExecProc($TabVal,$SQLS);
            $row=$Conn->sql_fetchrow();
            debug_tab($row,$Debug);
            $Compte=$row['COMPTE'];
            if ($Compte==0)
            {
                //On créé l'espace de travail
                $SQLS=$SQL_Create_Espace;
                $Result=$Conn->ExecProc($TabVal,$SQLS);
                if($Result==0)
                {
                    //Il y a eu un souci, il faut traité;
                    $moteur->assign('ErreurNum','0x05E');
                    $Template='erreur.tpl';
                    $moteur->display($CheminTpl.$Template);
                    exit;
                }
            }
            //On récupère l'espace de travail 
            $SQLS= $SQL_Get_Id_Espace;
            $Conn->ExecProc($TabVal,$SQLS);
            $row=$Conn->sql_fetchrow();
            debug_tab($row,false);
            $Id_Space=$row['id_espace'];
            //Création, si il n'existe pas, du répertoire de travail
            $NomRepertoire=$CheminBaseFichier.$CheminEspace.$Id_Space;
            if(!is_dir($NomRepertoire))
            {
                mkdir($NomRepertoire);
                //Récupère fichier index et le copie dans le nouveau répertoire, cela évite les navigations furetives
                $LeBloqueur=$CheminBaseFichier.$CheminEspace.'index.html';
                if($Debug)
                echo "<p>$LeBloqueur</p>";
                copy($LeBloqueur,$NomRepertoire.'/index.html');
            }
            //Récupérations des usagers
            $SQLS=$SQL_Compte_Liste_Usagers_Espace;
            $TabRech=array(':id'=>$Id_Space);
            //On affecte la variable dans le template
            $moteur->assign('SpaceId',$Id_Space);
            $row=$Conn->sql_fetch_all_prepared($TabRech,$SQLS);
            //Si la liste est vide, alors on la créée
            debug_tab($row,$Debug);
            $CompteUsager=$row[0]['COMPTE'];
            $TabUser=array();
            if($CompteUsager==0)
            {
                //Création de la liste des usagers
                //On récupère déjà les usagers concernés.
                $SQLS= $SQL_Utilisateur_Article_by_Id;
                $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
                debug_tab($row,$Debug);
                foreach($row as $v)
                {
                    if($v['auteur_article']!='')
                    {
                        $Auteur=$v['auteur_article'];
                        array_push($TabUser,$Auteur);
                    }
                    if(($v['graphiste']!='') and ($v['graphiste']!=$Auteur))
                    {
                        $Graphiste=$v['graphiste'];
                        array_push($TabUser,$Graphiste);
                    }   
                }
                //On ajoute celui qui a ouvert l'espace
               if(!(in_array($Id_User,$TabUser)))
                {
                    array_push($TabUser,$Id_User);
                }
                //On ajoute les rédac chef
                $SQLS= $SQL_Get_Users_By_Rank;
                $TabRang=array(':id'=>Administrateur);
                $row=$Conn->sql_fetch_all_prepared($TabRang,$SQLS);
                foreach($row as $v)
                {
                    $Id_personne=$v['id_utilisateur'];
                    if(!in_array($Id_personne,$TabUser))
                    {
                        array_push($TabUser,$Id_personne);
                    }
                }
                //Ajout des utilisateurs dans la table
                foreach($TabUser as $v)
                {
                    //Insertion dans la bonne table
                    $SQLS=$SQL_Add_User_Space;
                    $TabInsert=array(':usager'=>$v,':espace'=>$Id_Space);
                    $Result=$Conn->ExecProc($TabInsert,$SQLS);
                    if($Result==0)
                    {
                        $moteur->assign('ErreurNum','0x05F');
                        $Template='erreur.tpl';
                        $moteur->display($CheminTpl.$Template);
                        exit;
                    }
                }
            }
            //On récupère les usagers de la liste
            $SQLS=$SQL_Get_Usager_Space;
            $row=$Conn->sql_fetch_all_prepared($TabRech,$SQLS);
            debug_tab($row,$Debug);
            $TabConcernes=array();
            $TabTemp=array();
            $i=0;
            foreach($row as $v)
            {
                $Nom=$v['nom'];
                $Prenom=$v['prenom'];
                $TabConcernes[$i]['Identite']=$Prenom.' '.$Nom;
                $TabConcernes[$i]['id_usager']=$v['fk_usager'];
                $TabConcernes[$i]['Mail']=$v['mail'];
                array_push($TabTemp,$v['fk_usager']);
                $i++;
            }
            //On affiche les concernés
            $moteur->assign('TabConc',$TabConcernes);
            //On valide ou non la partie rédaction/Gestion si l'utilisateur est conerné ou pas.
            if(in_array($Id_User,$TabTemp))
            {
                $moteur->assign('valide','toto');
            }
            //On récupère tous les usagers
            $SQLS=$SQL_Get_All_User_Mail;
            $TabAllUser=array();
            $TabUnique=array();
            $Conn->sql_query($SQLS);
            while($row=$Conn->sql_fetchrow())
            {
                $Id_User_tab=$row['id_utilisateur'];
                //Si l'utilisateur n'est pas concerné, on l'affiche
                if(!(in_array($Id_User_tab,$TabTemp)))
                {
                    $TabUnique['id_usager']=$Id_User_tab;
                    $Nom=$row['nom'];
                    $Prenom=$row['prenom'];
                    $TabUnique['Identite']=$Prenom.' '.$Nom;
                    $TabUnique['Mail']=$row['mail'];
                    array_push($TabAllUser,$TabUnique);
                }
            }
            //On affiche la liste des utilisateurs
            $moteur->assign('TabAllUser',$TabAllUser);
            //Récupérations des messages
            $SQLS=$SQL_Message_Espace;
            $Conn->ExecProc($TabRech,$SQLS);
            $TabMessage=array();
            $i=0;
            debug_tab($_SESSION,$Debug);
            while($row=$Conn->sql_fetchrow())
            {
                $DateMessage=$row['date_message'];
                //
                $MaDate=date_create($DateMessage);
                        
                //
                $TabMessage[$i]['DateMessage']=date_format($MaDate, 'd/m/Y H:i:s');;
                $Nom=$row['nom'];
                $Prenom=$row['prenom'];
                $TabMessage[$i]['Auteur']=$Prenom.' '.$Nom;
                $Message=$row['message'];
                $TabMessage[$i]['Message']=nl2br($Message);
                if(isset($row['fichier']) and ($row['fichier']!=''))
                {
                    //Mise en forme du lien vers le fichier
                    $Fichier=$CheminBaseFichier.$CheminEspace.$Id_Space.'/'.$row['fichier'];
                }
                else
                    $Fichier='';
                $TabMessage[$i]['Fichier']=$Fichier;
                $i++;
            }
            //Intégration des messages
            $moteur->assign('TabMessages',$TabMessage);
            $moteur->assign('Auteur',$Id_User);
            $Template="echange_article.tpl";
        }
        //Sinon, il n'y a pas d'article en cours
        else
        {
            $moteur->assign('ErreurNum','0x05B');
            $Template='erreur.tpl';
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