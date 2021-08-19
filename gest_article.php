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
    $Debug=true;
    function debug_tab($Tableau,$Toggle)
    {
       if($Toggle)
       {
            echo "<pre>";
            print_r($Tableau);
            echo "</pre>";
       }
    }
    function debug_var($var,$Toggle)
    {
       if($Toggle)
       {
            echo "<p>";
            echo "variable : $var";
            echo "</>";
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
        //On vérifie le niveau du membre
        if( $_SESSION['UserLevel']==Admin_Système)  //administrateur
        {
            
            //Traitement admin
            //On récupère la liste des articles
            $SQLS=$SQL_Vu_Article_Resumee;
            $Conn->sql_query($SQLS);
            $tabArticles=array();
            $i=0;
            while($row=$Conn->sql_fetchrow())
            {
                $tabArticles[$i]['id_article']=$row['id_article'];
                $Id_Auteur=$row['auteur_article'];
                $tabArticles[$i]['titre']=$row['titre_article']; 
                $Nom=$row['nom'];
                $Prenom=$row['prenom'];
                $tabArticles[$i]['auteur']=$Prenom.' '.$Nom;
                $tabArticles[$i]['typeA']=$row['Nom_Type'];
                $tabArticles[$i]['etat']=$row['nom_etat'];
                $tabArticles[$i]['Webzine']=$row['Titre_Webzine'];
                $i++;
            }
            $Template='gest_article.tpl';
            //On affiche la liste des articles
            $moteur->assign('TabArticles',$tabArticles);
        }
        else
        {
            //Traitement pigiste
            switch($_SESSION['UserLevel'])
            {
                case Administrateur :
                    $Template='index_admin.tpl';
                    break;
                case Pigiste :
                    $Template='index_pigiste.tpl';
                    break;
                case Graphiste :
                    $Template='index_graphiste.tpl';
                    break;
                case Relecteur :
                    $Template='index_relecteur.tpl';
                    break;
                default :
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