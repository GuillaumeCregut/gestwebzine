<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0A                       */
    /* Date création : 27/03/2021                  */
    /* Dernière modification : 29/06/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
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
    //Démarrage de la session
    session_start();
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

        /* */
        if(isset($_POST['maj']))
        {
            //On a reçù une modification d'article
            //On traite la modification de l'article
            $Id_Article=intval($_POST['id_article']);
            $Etat=intval($_POST['etat']);
            //Vérification s'il existe un fichier dans l'article
            $PresenceFichier=false;
            $SQLS=$SQL_Get_Fichier_Article;
            $TabFich=array(':id'=>$Id_Article);
            $row=$Conn->sql_fetch_all_prepared( $TabFich,$SQLS);
            $Fichiers=$row[0]['fichiers'];
            if($Fichiers!='')
            {
                $PresenceFichier=true;
            }
            $AvanceActuel=$row[0]['Avancee_Article'];
            $Avancement=$AvanceActuel;
            $FichierUpload=false;
            if(isset($_POST['Uploaded'])and ($_POST['Uploaded']==1))
                $FichierUpload=true; //Récuperer la valeur via le formulaire d'envoi

            /*
            Version 0.8
            if(isset($_FILES['fichier']['error']))
            {
                $Erreur=$_FILES['fichier']['error'];
                if($Erreur==0)
                {
                    //Récupérer nom article
                    $Nom_Article=$_SESSION['Nom_Article'];
                    $Nom_Article=str_replace(' ','_', $Nom_Article);
                    //On remplace les caractères non autorisés
                    $Nom_Article=preg_replace('#[^[:alnum:]]#u', "-", $Nom_Article);

                    //$CheminBaseFichier
                    $CheminArticle=$CheminBaseFichier.$CheminArticle.$Nom_Article.'.zip';
                    
                    if(move_uploaded_file($_FILES['fichier']['tmp_name'],$CheminArticle))
                    {
                       //Insérer le fichier dans la base de données;
                       $SQLS=$SQL_Update_Article_Fichier;
                       $Nom_fich_Base=$Nom_Article.'.zip';
                       $TabFich=array(':idart'=>$Id_Article,':chemin'=>$Nom_fich_Base);
                       $Conn->ExecProc($TabFich,$SQLS);
                     //  $moteur->assign('Gestion_Fichier','Fichier téléchargé');
                       $FichierUpload=true;
                    }
                    else
                    {
                        //Erreur de copie de fichier
                      //  $moteur->assign('Gestion_Fichier','Erreur dans le téléchargement du fichier');
                    }
                }
            }
            */
            //Calculer l'état réel
            //Modification de l'article
            //Si on a pas de fichier à stocker, alors on n'autorise pas le bon pour relecture
            if( !$PresenceFichier and (!$FichierUpload))
            {
                $Etat=Etat_Article_Vierge;
            }
            //Si on évolue dans l'état de l'article, on ajoute un step
            if(($AvanceActuel==0) and ($Etat==Etat_Article_Cours))
            {
                $Avancement=Step_Article+$AvanceActuel;
            }
            //Si on rétrograde l'état 
            if(($AvanceActuel>=Step_Article) and ($Etat==Etat_Article_Vierge))
            {
                $Avancement=Step_Article-$AvanceActuel;
            }
            $SQLS= $SQL_Mod_Article_Light;
            $tabArt=array(':id_a'=>$Id_Article,':avance'=>$Avancement,':etat'=>$Etat);
            $Conn->ExecProc($tabArt,$SQLS);
            //Historisation
            $SQLS=$SQL_Add_Histo;
            $Id_User=$_SESSION['Utilisateur'];
            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Article, ':quoi'=>$Id_Article);
            $Conn->ExecProc($TabHisto,$SQLS);
            $Template='modif_article_fait.tpl';
            $moteur->display($CheminTpl.$Template);
            $moteur->display($CheminTpl.'footer.tpl');
            die();
        }
        /*Fin du script reception de mise à jour*/ 
        if(isset($_POST['num_art']))
        {
            $Id_Article=intval($_POST['num_art']);
            $_SESSION['Id_Article_Cours']=$Id_Article;
        }
        else
        {
            $Id_Article= $_SESSION['Id_Article_Cours'];
        }
        if($Id_Article!=0)
        {
            //On récupère le délai
            $SQLS=$SQL_Get_Param_Delai;
            $row=$Conn->sql_fetch_all($SQLS);
            $LeDelai=$row[0]['Value_Param_I'];

            //On récupère les informations de l'article
            $SQLS=$SQLS_Article_Vue_Modif;
            $TabVal=array(':idarticle'=>$Id_Article);
            $Conn->ExecProc($TabVal,$SQLS);
            $row=$Conn->sql_fetchrow();
            //On affiche ce que l'on peut :D
            $Nom_Article=$row['titre_article'];
            $Id_Auteur=$row['id_utilisateur'];
            $IsAuteur=false;
            if($Id_Auteur==$_SESSION['Utilisateur'])
            {
                $IsAuteur=true;
            }
            $moteur->assign('IsAuteur',$IsAuteur);
            debug_tab($_SESSION,$Debug);
            $_SESSION['Nom_Article']=$Nom_Article;
            $Auteur=$row['prenom'].' '.$row['nom'];
            $Id_Etat_Article=$row['Etat_article'];
            $AvanceArticle=intval($row['Avancee_Article']);
            $DateCreation=$row['date_creation'];
            //Mettre en forme la date
            $date = new DateTime($DateCreation);
            $DateCreation=$date->format('d/m/Y');
            //Récupérer quand ce sera fait, la date de parution du webzine
            $Id_Webzine=$row['id_webzine'];
            $Date_Parution=$row['Date_Parution'];
            //Récupération du verrou
            $Verrou=$row['art_locked'];
            if ($Verrou==0)
            {
                $VerrouI='unlock';
            }
            else
            {
                $VerrouI='lock';
            }
            $moteur->assign('Verrou',$VerrouI);
            $moteur->assign('Verrouillage',$Verrou);
            if($Id_Webzine==1)
            {
                $DatePrevue='Non prévue';
            }
            else
            {
                $date = new DateTime($Date_Parution);
                $Modificateur='-'. $LeDelai.' day';
                $date->modify($Modificateur);
                $DatePrevue=$date->format('d/m/Y');
                
            }   
            $Description=$row['description'];
            $Webzine=$row['Titre_Webzine'];
            $EtatArticle=$row['nom_etat'];
            $Fichiers=$row['fichiers'];
            $TypeArticle=$row['Nom_Type'];
            //Affichage
            $moteur->assign('DateFinalisation',$DatePrevue);
            $moteur->assign('idArticle',$Id_Article);
            $moteur->assign('Titre_Article',$Nom_Article);
            $moteur->assign('Auteur',$Auteur);
            $moteur->assign('TypeArticle',$TypeArticle);
            $moteur->assign('DateCreation',$DateCreation);
            $moteur->assign('Webzine', $Webzine);
            //Afficher la date prévue de restitution
            $moteur->assign('Description',nl2br($Description));
            $moteur->assign('Avance',$AvanceArticle);
            if($Fichiers!='')
            {
                $Fichiers=$CheminBaseFichier.$CheminArticle.$Fichiers;
                $moteur->assign('Fichier',$Fichiers);
            }
            //On récupère les niveaux états
            $SQLS= $SQL_Get_Etat_Redacteur;
            $TabEtatArticle=array(':a'=>Etat_Article_Vierge,':b'=>Etat_Article_Cours);
            $rows=$Conn->sql_fetch_all_prepared($TabEtatArticle,$SQLS);
            //On récupère dans la base et on mets dans le tableau
            $TabEtats=array();
            foreach($rows as $v)
            {
                $NomValeur=$v['nom_etat'];
                $Valeur=$v['id_etat'];
                array_push($TabEtats,array('id_Etat'=>$Valeur,'nom'=> $NomValeur,'sel'=>''));
            }
            debug_tab($TabEtats,$Debug);
            $i=0;
            foreach($TabEtats as $v)
            {
                if($v['id_Etat']==$Id_Etat_Article)
                {
                    $TabEtats[$i]['sel']='selected';
                }
                $i++;
            }
            debug_tab($TabEtats,$Debug);
            //On les affiches
            $moteur->assign('TabEtats', $TabEtats);
            //On récupère s'il existe le fichier MEP
            $SQLS= $SQL_GET_MEP_Article_By_ID;
            $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
            if((sizeof($row)>0) and $IsAuteur)
            {
                //On l'affiche
                $LeNom=$row[0]['fichiers'];
                $CheminMEP= $CheminBaseFichier.$CheminMEP.$LeNom;
                $moteur->assign('FichierMEP', $CheminMEP);
            }
            debug_tab($row,$Debug);
           
            
            $Template='modif_article.tpl';
            /*
($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système)
            */
            //On vérifie le niveau du membre
            if( ($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système) or ($_SESSION['UserLevel']==Graphiste))  //administrateur ou graphiste
            {
                //Traitement admin
                $moteur->assign('Admin',true);
                $moteur->assign('IdArticle_MEP',$Id_Article);
            }
            else
            {
                //Traitement pigiste

            }   
        }
        else
        {
            $moteur->assign('ErreurNum','0x01A');
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
