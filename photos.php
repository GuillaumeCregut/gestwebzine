<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 02/07/2021                  */
    /* Dernière modification : 15/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
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
    //Démarrage de la session
    session_start();
    //On initialise le moteur de template
    $moteur=new Smarty();
    //Connexion àla base de données
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
                //Récupération de la liste des articles et infos photos
                $SQLS="SELECT ta.id_article,ta.titre_article, ta.monteur,ta.mdp_photo,tu.nom, tu.prenom, tta.Nom_Type,tp.id_photo,tp.chemin_photo,tp.photo_valide FROM t_articles ta
                INNER JOIN t_utilisateurs tu ON
                ta.auteur_article=tu.id_utilisateur
                INNER JOIN t_type_article tta ON
                ta.type_article=tta.id_type
                LEFT JOIN t_photos tp ON
                ta.id_article=tp.fk_article
                WHERE ta.Etat_article!=4";
                $Conn->sql_query($SQLS);
                $TabArticles=array();
                while ($row=$Conn->sql_fetchrow())
                {
                    Debug_tab($row,$Debug);
                    $NomAuteur=$row['prenom'].' '.$row['nom'];
                    $NomMonteur=$row['monteur'];
                    $Id_Article=$row['id_article'];
                    $PhotoValide=$row['photo_valide'];
                    $titreArticle=$row['titre_article'];
                    $TypeArticle=$row['Nom_Type'];
                    $IdPhoto=$row['id_photo'];
                    $Mdp=$row['mdp_photo'];
                    if($IdPhoto=='')
                    {
                        //Il n'y a pas de photos
                        $PresencePhoto='Aucune photo';
                    }
                    else
                    {
                        $PresencePhoto='Photos présentes';
                    }
                    if(($PhotoValide==0) or ($PhotoValide==''))
                    {
                        $PhotoValide='non';
                    }
                    else
                    {
                        $PhotoValide='oui';
                    }
                    if($NomMonteur=='')
                    {
                        $NomMonteur='-';
                    }
                    $Lien=$AdresseSite.$PagePhotoExt.$Id_Article;
                    if($Mdp=='')
                    {
                        $MDP='-';
                    }
                    //Mise en place dans le tableau
                    array_push($TabArticles,array('id'=>$Id_Article,'monteur'=>$NomMonteur,'titre'=>$titreArticle,'typeA'=>$TypeArticle,'auteur'=> $NomAuteur,'photo'=>$PhotoValide,'lien'=>$Lien,'presence'=>$PresencePhoto,'mdp'=>$Mdp));

                }
                debug_tab($TabArticles,$Debug);
                //Affectation du tableau
                $moteur->assign('TabArticles',$TabArticles);
                //Traitement admin
                $Template='photos.tpl';
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