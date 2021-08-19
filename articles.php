<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 26/03/2021                  */
    /* Dernière modification : 31/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    //
    $Debug=false;
    function debug_tab($TableauToto, $Toggle)
    {
       if($Toggle)
       {
           echo "<pre>";
        print_r($TableauToto);
        echo "</pre>";
       }  
    }
    //On initialise le moteur de template
    $moteur=new Smarty();
     //Connexion àla base de données
     $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
        $Id_User=$_SESSION['Utilisateur']; 
        //On affiche le nom et prenom utilisateur
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        //Si on a reçu un formulaire d'ajout
         if(isset($_POST['titre']))
         {
             //Ne pas oublier l'auteur
             if(isset($_POST['auteur']))
             {
                 $Auteur=intval($_POST['auteur']);
             }
             else
             {
                $Auteur= $Id_User;
             }
             if(isset($_POST['LeMonteur']))
             {
                 $LeMonteur=htmlspecialchars($_POST['LeMonteur'],ENT_NOQUOTES,'UTF-8');
             }
             else
             {
                $LeMonteur='';
             }
             if(isset($_POST['titre']))
             {
                 $TitreArticle=htmlspecialchars($_POST['titre'],ENT_NOQUOTES,'UTF-8');
             }
             else
             {
                 $TitreArticle='';
             }
             if(isset($_POST['typearticle']))
             {
                 $LeType=intval($_POST['typearticle']);
             }
             else
                $LeType=0;
            if(isset($_POST['description']))
            {
                $LaDescription=htmlspecialchars($_POST['description'],ENT_NOQUOTES,'UTF-8');;
            }
            else
                $LaDescription='';
            /*Fonction expérimentale concernant l'ajout de photos */
            $MDP=md5($TitreArticle);
            $MDP=substr($MDP,0,10);
            /*Fin fonction expérimentale*/
            //On ajoute l'article à la base de données
            $SQLS=$SQL_Add_Article;
            $TabVal=array(':auteur'=> $Auteur, ':type_article'=>$LeType,':titre'=>$TitreArticle, ':descr'=>$LaDescription,':LeMonteur'=>$LeMonteur,':MDP'=> $MDP);
            $Conn->ExecProc($TabVal, $SQLS);
            //On historise
            $SQLS=$SQL_Add_Histo;
            $Id_User=$_SESSION['Utilisateur'];
            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_AjoutArticle, ':quoi'=>$TitreArticle);
            $Conn->ExecProc($TabHisto,$SQLS);
         }
        //On vérifie le niveau du membre
        
        if(($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système) or($_SESSION['UserLevel']==Graphiste))
            {
                //Traitement admin et graphiste
                //On se connecte à la base de données pour récupérer tous les articles
                //$SQLS=$SQL_Vu_Article_Resumee;
                $SQLS=$SQL_Vu_Article_Resumee_Photo;
                $Conn->sql_query($SQLS);
            }
            else
            {
               //On se connecte à la base de données pour récupérer les articles de l'auteur
              /* $SQLS=$SQL_Vu_Article_Resumee_User;
               $TabAuteur=array(':auteur'=> $Id_User);
               $Conn->ExecProc($TabAuteur,$SQLS);*/
               $SQLS=$SQL_Vu_Article_Resumee;
               $Conn->sql_query($SQLS);
            }
            //On récupère les articles et on les affiche
            $tabArticles=array();
            $i=0;
            while($row=$Conn->sql_fetchrow())
            {
                $tabArticles[$i]['id_article']=$row['id_article'];
                $etatArticle=$row['Etat_article'];
                //A corriger pour mettre en adéquation avec les états vrais
                switch($etatArticle)
                {
                    case Etat_Article_Vierge : //Non commencé
                        $Classe_Article='Vierge'; //non commencés
                        break;
                    case Etat_Article_Cours : //En cours  //Pret pour relecture
                        $Classe_Article='EnCours';
                        break;
                    case Etat_Article_Termine : //Bon autoriser la mise en page
                        $Classe_Article='Termine';
                        break;
                    case Etat_Article_MEP : //Pret pour revue finale
                        $Classe_Article='MEP'; 
                        break;
                    case Etat_Article_OK : //Pret pour publication
                        $Classe_Article='Finalise';
                        break;
                    default :
                         $Classe_Article='Vierge';
                }
                $Id_Auteur=$row['auteur_article'];
                $Id_User=$_SESSION['Utilisateur'];
                if($Id_Auteur==$Id_User)
                {
                    $tabArticles[$i]['Classe_auteur']='classe_auteur';
                }
                else
                    $tabArticles[$i]['Classe_auteur']='autre_auteur';
                $tabArticles[$i]['Classe']= $Classe_Article;
                $tabArticles[$i]['titre']=$row['titre_article']; 
                $Nom=$row['nom'];
                $Prenom=$row['prenom'];
                $tabArticles[$i]['auteur']=$Prenom.' '.$Nom;
                $tabArticles[$i]['typeA']=$row['Nom_Type'];
                $tabArticles[$i]['etat']=$row['nom_etat'];
                $tabArticles[$i]['Webzine']=$row['Titre_Webzine'];
                $tabArticles[$i]['photo']=$row['photo_valide'];
                $Verrou=$row['art_locked'];
                if($Verrou==0)
                {
                    $tabArticles[$i]['class_lock']='img_unlock_file';
                } 
                else
                {
                    $tabArticles[$i]['class_lock']='img_lock_file';
                }
                if($row['fichier_mep']!='')
                {
                    $tabArticles[$i]['class_fileM']='img_header_fileI';
                }
                else
                {
                    $tabArticles[$i]['class_fileM']='';
                }
                if($row['fichiers']!='')
                {
                    $tabArticles[$i]['class_file']='img_header_file';
                }
                else
                {
                    $tabArticles[$i]['class_file']='';
                }
                $i++;
            }
            //On affiche la liste des articles
            $moteur->assign('TabArticles',$tabArticles);
            //On récupère la liste des membres
            if(($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système))
            {
                //On récupère les auteurs //Via une variable, on affiche la liste des noms d'auteurs
                $SQLS=$SQL_Equipe;
                $row=$Conn->sql_fetch_all($SQLS);
                $moteur->assign('Auteurs',$row);
            }
            //On récupère la liste des types
            $SQLS= $SQL_All_Types;
            $row=$Conn->sql_fetch_all($SQLS);
            //On les affiche
            $moteur->assign('ListeType',$row);
            //On affiche la page
            $moteur->display($CheminTpl.'articles.tpl');
            $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl'); 
    }
?>