<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 27/03/2021                  */
    /* Dernière modification : 31/07/2021          */
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
    //Connexion à la base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
         //On affiche le nom et prenom utilisateur
         $NomUser=$_SESSION['Nom_User'];
         $PrenomUser=$_SESSION['Prenom_User'];
         $nom_Prenom=$PrenomUser.' '.$NomUser;
         $moteur->assign('PrenomLogin',$nom_Prenom);
        //On vérifie le niveau du membre
        if( ($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système) or ($_SESSION['UserLevel']==Graphiste))  //administrateur
        {
           //On récupère la liste des articles
           // $SQLS=$SQL_Vu_Article_Resumee_MEP; 
            $SQLS=$SQL_Vu_Article_MEP_Photos;
            $Conn->sql_query($SQLS);
            //On récupère les articles et on les affiche
            $tabArticles=array();
            $i=0;
            while($row=$Conn->sql_fetchrow())
            {
                $tabArticles[$i]['id_article']=$row['id_article'];
                $etatArticle=$row['Etat_article'];
                switch($etatArticle)
                {
                    case 1: //Non commencé
                        $Classe_Article='Vierge';
                        break;
                    case 2: //En cours
                        $Classe_Article='EnCours';
                        break;
                    case 3: //terminé
                        $Classe_Article='Termine';
                        break;
                    case 5: //Mis en page
                        $Classe_Article='MEP';
                        break;
                }
                $tabArticles[$i]['Classe']= $Classe_Article;
                $tabArticles[$i]['titre']=$row['titre_article'];
                $Nom=$row['nom'];
                $Prenom=$row['prenom'];
                $tabArticles[$i]['auteur']=$Prenom.' '.$Nom;
                $tabArticles[$i]['typeA']=$row['Nom_Type'];
                $tabArticles[$i]['etat']=$row['nom_etat'];
                $tabArticles[$i]['Webzine']=$row['Titre_Webzine'];
                $tabArticles[$i]['photo']=$row['photo_valide'];
                //Rajout V0.5
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
                    $tabArticles[$i]['class_fileM']='img_header_file';
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
            $Template='graphiste.tpl';
        }
        else
        {
            //Traitement pigiste
            $Template='index_pigiste.tpl';
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