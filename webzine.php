<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 29/03/2021                  */
    /* Dernière modification : 31/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    $Debug=false;
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
         //Affiches les dates pour warning et alert
         $moteur->assign('DateAlert',DateAlerteWebzine);
         $moteur->assign('DateWarning',DateWarningWebzine);
        //On vérifie le niveau du membre
        if(($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système))  //administrateur
            {
                //Traitement admin
                //Arrive t'on par formulaire ?
                if(isset($_POST['id_webzine']))
                {
                    $Id_Webzine=intval($_POST['id_webzine']);
                    //On vérifie si le Webzine existe
                    $SQLS=$SQl_Count_Webine_Id;
                    $TabVal=array(':id'=>$Id_Webzine);
                    $Conn->ExecProc($TabVal,$SQLS);
                    $Compte=0;
                    while($row=$Conn->sql_fetchrow())
                    {
                        $Compte=$row['Compte'];
                    }
                    if($Compte==1)
                    {
                        $_SESSION['id_webzine']=$Id_Webzine;
                    }
                }
                //On vérifie si on a bien un ID valide
                if(isset($_SESSION['id_webzine']))
                {
                    $Id_Webzine=$_SESSION['id_webzine'];
                    //On récupère les informations du webzine
                    $SQLS=$SQL_Get_Webzine_Infos;
                    $TabId=array(':id'=>$Id_Webzine);
                    $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
                    $Nom_Webzine=$row[0]['Titre_Webzine'];
                    $Date_Creation=$row[0]['Date_Creation'];
                    $Date_Parution=$row[0]['Date_Parution'];
                    $Etat_Webzine=$row[0]['Etat'];
                    if($Etat_Webzine==Etat_Webzine_Archive)
                    {
                        $Blocage='disabled'; 
                    }
                    else
                    {
                        $Blocage='';
                    }
                    $moteur->assign('Archiver',$Blocage); 
                    //Mise ne forme de la date création
                    $Madate = new DateTime($Date_Creation);
                    $Date_Creation=$Madate->format('d/m/Y');
                    //Affichage des informations
                    $moteur->assign('Id_Webzine',$Id_Webzine);
                    $moteur->assign('Titre',$Nom_Webzine);
                    $moteur->assign('Date_Parution',$Date_Parution);
                    $moteur->assign('Date_Creation', $Date_Creation);
                    //On récupère les états
                    $SQLS=$SQL_Etat_Webzine;
                    $Conn->sql_query($SQLS);
                    $i=0;
                    $TabEtat=array();
                    while ($row=$Conn->sql_fetchrow())
                    {
                        $TabEtat[$i]['id']=$row['id_etat_webzine'];
                        $Etat_Webzine_BDD=$row['id_etat_webzine'];
                        $TabEtat[$i]['nom']=$row['nom_etat_webzine'];
                        if($Etat_Webzine_BDD==$Etat_Webzine)
                        {
                            $TabEtat[$i]['checked']='selected';
                        }
                        else
                        {
                            $TabEtat[$i]['checked']='';
                        }
                        $i++;
                    }
                    $moteur->assign('TabEtat',$TabEtat);
                    //Nombre de pages du webzine 
                    $SQLS=$SQL_Get_NbPage_Webzine;
                    $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
                    $NbPages=0;
                    foreach($row as $v)
                    {
                       $NbPages+=$v['nb_page'];
                    }
                    $moteur->assign('Nbre_Pages',$NbPages);
                    //On récupère la liste des articles du webzine
                    $WebzineOK=true;
                    //$SQLS=$SQL_Get_Article_Webzine;
                    $SQLS=$SQL_Get_Article_Webzine_Photo;
                    $TabIdWeb=array(':id'=>$Id_Webzine);
                    $row=$Conn->sql_fetch_all_prepared($TabIdWeb,$SQLS);
                    debug_tab($row,$Debug);
                    $tabArticles_Webzine=array();
                    for($i=0;$i<sizeof($row);$i++)
                    {
                        $tabArticles_Webzine[$i]['id_article']=$row[$i]['id_article'];
                        $Verrou=$row[$i]['art_locked'];
                        if($Verrou==0)
                        {
                            $tabArticles_Webzine[$i]['class_fileL']='img_header_unlock';
                        }
                        else
                        {
                            $tabArticles_Webzine[$i]['class_fileL']='img_header_lock';
                        }
                        $Avancee=$row[$i]['Avancee_Article'];
                        $tabArticles_Webzine[$i]['Avancee']=$Avancee;
                        $PagesArticle=$row[$i]['nb_page'];
                        if($PagesArticle=='')
                        {
                            $PagesArticle='-';
                        }
                        $tabArticles_Webzine[$i]['nbPageArticle']=$PagesArticle;
                        //On affiche l'avancée sous forme de bargraph
                       switch($Avancee)
                       {
                            case Step_Article*0: 
                                $tabArticles_Webzine[$i]['Avancee_Class']='bar_red';
                                break;
                            case Step_Article:
                                $tabArticles_Webzine[$i]['Avancee_Class']='bar_orange';
                                break;
                            case Step_Article*2:
                                    $tabArticles_Webzine[$i]['Avancee_Class']='bar_jaune';
                                    break;
                            case Step_Article*3:
                                    $tabArticles_Webzine[$i]['Avancee_Class']='bar_jaune';
                                    break;
                            case 100:
                                    $tabArticles_Webzine[$i]['Avancee_Class']='bar_green';
                                    break;
                            default :  $tabArticles_Webzine[$i]['Avancee_Class']='bar_green';
                        }
                        $etatArticle=$row[$i]['Etat_article'];
                        /*
                        prendre la date du jour, voir combien de temps il reste, et 
                        mettre en couleurs ceux qui sont prêt et ceux qui ne le sont pas
                         vert - l’article est en page
        jaune - on est à une mois de la publication et l’article n’est pas en page
        rouge - on est à quinze jour de la publication et l’article n’est pas en page */
                        $Aujourdhui=date_create();
                        $Parution=date_create($Date_Parution);
                        $interval = date_diff($Aujourdhui,$Parution);
                        $EcartDate=$interval->days;
                        $Inverse=$interval->invert;
                        if($Inverse==1)
                            $EcartDate=0-$EcartDate;
                        if($EcartDate>DateWarningWebzine)
                        {
                            //On ne fait rien
                            switch($etatArticle)
                            {
                                case Etat_Article_Vierge: //Non commencé
                                    $Classe_Article='Vierge';
                                    $WebzineOK=false;
                                    break;
                                case Etat_Article_Cours: //En cours
                                    $Classe_Article='EnCours';
                                    $WebzineOK=false;
                                    break;
                                case Etat_Article_Termine: //terminé
                                    $Classe_Article='Termine';
                                    $WebzineOK=false;
                                    break;
                                case Etat_Article_MEP: //Mis en page
                                    $Classe_Article='MEP';
                                    $WebzineOK=false;
                                    break;
                                case Etat_Article_OK:
                                    $Classe_Article='etatOK';
                                    break;
                            }
                        }
                        if(($EcartDate<=DateWarningWebzine) and ($EcartDate>DateAlerteWebzine))
                        {
                            //On met en jaune les non terminés
                            if($etatArticle!=Etat_Article_OK)
                            {
                                $Classe_Article='etat_warning';
                                $WebzineOK=false;
                            }
                            else
                            {
                                $Classe_Article='etatOK';
                            }
                        }
                        if($EcartDate<=DateAlerteWebzine)
                        {
                            if($etatArticle!=Etat_Article_OK)
                            {
                                $Classe_Article='etat_alert';
                                $WebzineOK=false;
                            }
                            else
                            {
                                $Classe_Article='etatOK'; 
                            }
                        }
                        /*fin de refonte */ 
                        $tabArticles_Webzine[$i]['Classe']= $Classe_Article;
                        $tabArticles_Webzine[$i]['titre']=$row[$i]['titre_article']; 
                        $Nom=$row[$i]['nom'];
                        $Prenom=$row[$i]['prenom'];
                        $tabArticles_Webzine[$i]['auteur']=$Prenom.' '.$Nom;
                        $tabArticles_Webzine[$i]['typeA']=$row[$i]['Nom_Type'];
                        $tabArticles_Webzine[$i]['etat']=$row[$i]['nom_etat'];
                        $tabArticles_Webzine[$i]['Webzine']=$row[$i]['Titre_Webzine'];
                        $tabArticles_Webzine[$i]['photo']=$row[$i]['photo_valide'];
                        if($row[$i]['fichiers']!='')
                        {
                            $tabArticles_Webzine[$i]['class_file']='img_header_file';
                        }
                        else
                        {
                            $tabArticles_Webzine[$i]['class_file']='';
                        }
                        if($row[$i]['fichier_mep']!='')
                        {
                            $tabArticles_Webzine[$i]['class_fileI']='img_header_fileI';
                        }
                        else
                        {
                            $tabArticles_Webzine[$i]['class_fileI']='';
                        }
                    }
                    $moteur->assign('TabArticlesWebzine',$tabArticles_Webzine);
                    if( $WebzineOK)
                    {
                        $ImageWebzine='greenled';
                    }
                    else
                    {
                        $ImageWebzine='redled';
                    }
                    $moteur->assign('WebinePret',$ImageWebzine);
                    //On récupère la liste des articles disponibles (webzine=1)
                    //$SQLS=$SQL_Get_Article_Webzine;
                    $SQLS=$SQL_Get_Article_Webzine_Photo;
                    $TabIdWeb=array(':id'=>1);
                    $row=$Conn->sql_fetch_all_prepared($TabIdWeb,$SQLS);
                    debug_tab($row,$Debug);
                    //////////////////////////////
                    $tabArticles_Dispo=array();
                    for($i=0;$i<sizeof($row);$i++)
                    {
                        $tabArticles_Dispo[$i]['id_article']=$row[$i]['id_article'];
                        $etatArticle=$row[$i]['Etat_article'];
                        switch($etatArticle)
                        {
                            case Etat_Article_Vierge: //Non commencé
                                $Classe_Article='Vierge';
                                break;
                            case Etat_Article_Cours: //En cours
                                $Classe_Article='EnCours';
                                break;
                            case Etat_Article_Termine: //terminé
                                $Classe_Article='Termine';
                                break;
                            case Etat_Article_MEP: //Mis en page
                                $Classe_Article='MEP';
                                break;
                            case Etat_Article_OK: //Pret pour publication
                                $Classe_Article='etatOK';
                                break;
                        }
                        //Verrou
                        $Verrou=$row[$i]['art_locked'];
                        if($Verrou==0)
                        {
                            $tabArticles_Dispo[$i]['class_fileL']='img_header_unlock';
                        }
                        else
                        {
                            $tabArticles_Dispo[$i]['class_fileL']='img_header_lock';
                        }
                        $tabArticles_Dispo[$i]['Classe']= $Classe_Article;
                        $tabArticles_Dispo[$i]['titre']=$row[$i]['titre_article']; 
                        $Nom=$row[$i]['nom'];
                        $Prenom=$row[$i]['prenom'];
                        $tabArticles_Dispo[$i]['auteur']=$Prenom.' '.$Nom;
                        $tabArticles_Dispo[$i]['typeA']=$row[$i]['Nom_Type'];
                        $tabArticles_Dispo[$i]['etat']=$row[$i]['nom_etat'];
                        $tabArticles_Dispo[$i]['Webzine']=$row[$i]['Titre_Webzine'];
                        $tabArticles_Dispo[$i]['photo']=$row[$i]['photo_valide'];
                        $PagesArticle_Dispo=$row[$i]['nb_page'];
                        if($PagesArticle_Dispo=='')
                        {
                            $PagesArticle_Dispo='-';
                        }
                        $tabArticles_Dispo[$i]['nbPageArticle']=$PagesArticle_Dispo;
                        if($row[$i]['fichiers']!='')
                        {
                            $tabArticles_Dispo[$i]['class_file']='img_header_file';
                        }
                        else
                        {
                            $tabArticles_Dispo[$i]['class_file']='';
                        }
                        if($row[$i]['fichier_mep']!='')
                        {
                            $tabArticles_Dispo[$i]['class_fileI']='img_header_fileI'; 
                        }
                        else
                        {
                            $tabArticles_Dispo[$i]['class_fileI']='';
                        }
                    }
                    $moteur->assign('TabArticlesDispo',$tabArticles_Dispo);
                    debug_tab($tabArticles_Dispo,$Debug);



                    ////////////
                    $Template="webzine.tpl";
                }
                else
                {
                    //Id webzine n'est pas défini.
                    $moteur->assign('ErreurNum','0x04A');
                    $Template='erreur.tpl';
                }
                //On pousse les données dans la page
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