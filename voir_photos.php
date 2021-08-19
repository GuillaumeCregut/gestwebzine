<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : --/07/2021                  */
    /* Dernière modification : 15/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    //Fonction de débugage
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
    //Connexion à la base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
         //On affiche le nom
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        //On vérifie le niveau du membre
        if( ($_SESSION['UserLevel']==Administrateur) or($_SESSION['UserLevel']==Graphiste) or($_SESSION['UserLevel']==Admin_Système) )  //administrateur
        {
            //Traitement admin
            if(isset($_POST['id_article']))
            {
                $Id_Article=intval($_POST['id_article']);
                $TabVal=array(':idarticle'=>$Id_Article);
                //Récupérations des infos de l'article
                $SQLS=$SQLS_Article_Vue_Modif;
                $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
                $TitreArticle=$row[0]['titre_article'];
                $TitreWebzine=$row[0]['Titre_Webzine'];
                $Nom_Article=str_replace(' ','_', $TitreArticle);
                //On remplace les caractères non autorisés
                $Nom_Article=preg_replace('#[^[:alnum:]]#u', "-", $Nom_Article);
                $moteur->assign('Titre',$TitreArticle);
                $moteur->assign('Webzine',$TitreWebzine);
                $moteur->assign('id_article',$Id_Article);
                $moteur->assign('rep',$Nom_Article);
                debug_tab($row[0],$Debug);
                //Récupération des infos photos
                $SQLS=$SQL_Get_Photo_By_Article;
                $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
                $compte=count($row);
                if(!empty($row))
                {
                    $PhotoValide=$row[0]['photo_valide'];
                    if($PhotoValide==1)
                    {
                        $PhotoValide='Oui';
                    }
                    else
                    {
                        $PhotoValide='non';
                    }
                    $moteur->assign('Valide',$PhotoValide);
                    $Chemin_Article=$row[0]['chemin_photo'];
                    $Chemin_Photo=$CheminBaseFichier.$CheminPhotos.$Chemin_Article.'/';
                    $moteur->assign('chemin',$Chemin_Article);
                    $moteur->assign('classe_aff','');
                    $moteur->assign('affiche_case','cacher');
                    $CheminPhoto_mini=$Chemin_Photo.$CheminMiniatures;
                    //Récupération de la liste des photos miniatures
                    $TabFichier=scandir($CheminPhoto_mini);
                    $TabPhoto=array();
                    foreach($TabFichier as $v)
                    {
                        if(($v!='..') and ($v!='.'))
                        {
                            $CheminFichier=$Chemin_Photo.$v;
                            $CheminMiniature=$CheminPhoto_mini.$v;
                            $nom=$v;
                            array_push($TabPhoto,array('photo'=>$CheminFichier,'miniature'=>$CheminMiniature,'nom'=>$nom));
                        }
                    }
                    debug_tab($TabPhoto,$Debug);
                    $moteur->assign('TabPhoto',$TabPhoto);
                    $moteur->assign('bouton','');
                }
                else
                {
                    //Il n'y a pas de photos pour cet article
                    $moteur->assign('Valide','Pas de photo pour cet article');
                    $moteur->assign('chemin','');
                    $moteur->assign('classe_aff','cacher');
                    $moteur->assign('affiche_case','');
                }
                    //Affichage de la page
                $Template='voir_photos.tpl';
            }
            else
            {
                //Il n'y a pas eu de formulaire
                $Template='erreur.tpl';
                $moteur->assign('ErreurNum','0x5236');
            }
            
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