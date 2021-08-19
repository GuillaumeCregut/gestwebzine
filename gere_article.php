<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 31/07/2021                  */
    /* Dernière modification : 31/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    include "include/functions.inc.php";
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
            echo "</p>";
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
                if(!empty($_POST))
                {
                    if(isset($_POST['update']))
                    {
                        //On a demander une mise à jour
                        $Choix=intval($_POST['update']);
                        $OK=true;
                        if(isset($_POST['id_article']))
                        {
                            $IdArticle=intval($_POST['id_article']);
                        }
                        else
                        {
                            $OK=false;
                        }
                        switch($Choix)
                        {
                            case 1 : //Modification
                                //On récupère les infos
                                
                                if(isset($_POST['titre']))
                                {
                                $NewTitre=htmlspecialchars($_POST['titre'],ENT_NOQUOTES,'UTF-8');
                                }
                                else
                                {
                                    $OK=false;
                                }
                                if(isset($_POST['description']))
                                {
                                    $NewDesc=htmlspecialchars($_POST['description'],ENT_NOQUOTES,'UTF-8');
                                }
                                else
                                {
                                    $OK=false;
                                }
                                if(isset($_POST['auteur']))
                                {
                                    $NewAuteur=intval($_POST['auteur']);
                                }
                                else
                                {
                                    $OK=false;
                                }
                                if(isset($_POST['letype']))
                                {
                                    $Newtype=intval($_POST['letype']);
                                }
                                else
                                {
                                    $OK=false;
                                }
                                if(isset($_POST['mdp']))
                                {
                                    $newMPD=htmlspecialchars($_POST['mdp'],ENT_NOQUOTES,'UTF-8');
                                }
                                else
                                {
                                    $OK=false;
                                }
                                if(isset($_POST['monteur']))
                                {
                                    $NewMonteur=htmlspecialchars($_POST['monteur'],ENT_NOQUOTES,'UTF-8');
                                }
                                else
                                {
                                    $OK=false;
                                }
                                
                                if($OK)
                                {
                                    //Tout est OK niveau 
                                    $TabVar=array(':auteur'=>$NewAuteur,':type'=>$Newtype,':mdp'=>$newMPD,':monteur'=>$NewMonteur,':desc'=>$NewDesc,':titre'=>$NewTitre,':id_article'=>$IdArticle);
                                    $SQLS=$SQL_Update_Article;
                                    $Res=$Conn->ExecProc($TabVar,$SQLS);
                                    if($Res>=0)
                                    {
                                        $Message="Modification effecuée";
                                    }
                                    else
                                    {
                                        $Message="Erreur dans la modification";
                                    }
                                }
                                else
                                {
                                    $Message="Erreur dans la modification : Champs incomplets";
                                }
                                break;
                            case 2 : // Archivage (suppression)
                                $Resultat=purge_article($IdArticle);
                                if($Resultat>0)
                                {
                                    $Message="Suppression effectuée";
                                }
                                else
                                {
                                    $Message="Erreur lors de la suppression : $resultat";
                                }
                                break;
                            default: //On fait rien
                                ;
                        }
                        $moteur->assign('Action',$Message);
                    }                 
                    if(isset($_POST['id_article']))
                    {
                        $IdArticle=intval($_POST['id_article']);
                        $SQLS=$SQL_SEL_Art_Id;
                        $TabVar=array(':id_article'=>$IdArticle);
                        $row=$Conn->sql_fetch_all_prepared($TabVar,$SQLS);
                        debug_tab($row,false);
                        //Récupération des valeurs
                        if(!empty($row))
                        {
                            $Id_Auteur=$row['0']['auteur_article'];
                            $Id_Type=$row['0']['type_article'];
                            $Titre_Article=$row['0']['titre_article'];
                            $Desc=$row['0']['description'];
                            $Mdp=$row['0']['mdp_photo'];
                            $Monteur=$row['0']['monteur'];
                            $Template="gere_article.tpl";
                            //Récupération de la liste des auteurs
                            $SQLS=$SQL_Equipe;
                            $row=$Conn->sql_fetch_all($SQLS);
                            $Tabuser=array();
                            foreach($row as $v)
                            {
                                $Nom=$v['nom'];
                                $Prenom=$v['prenom'];
                                $Id=$v['id_utilisateur'];
                                $sel='';
                                if($Id==$Id_Auteur)
                                {
                                    $sel='selected';
                                }
                                $Auteur=$Prenom.' '.$Nom;
                                $TabTemp=array('id'=>$Id,'nom'=>$Auteur,'selec'=>$sel);
                                array_push($Tabuser,$TabTemp);
                            }
                            $moteur->assign('TabAuteur',$Tabuser);       
                            //Récupération de la liste des styles            
                            $SQLS= $SQL_All_Types;
                            $row=$Conn->sql_fetch_all($SQLS);
                            debug_tab($row,false);
                            $TabType=array();
                            foreach($row as $v)
                            {
                                $Nom=$v['Nom_Type'];
                                
                                $Id=$v['id_type'];
                                $sel='';
                                if($Id==$Id_Type)
                                {
                                    $sel='selected';
                                }
                                $TabTemp=array('id'=>$Id,'nom'=>$Nom,'selec'=>$sel);
                                array_push($TabType,$TabTemp);
                            }
                            $moteur->assign('TabType',$TabType); 
                            //On les affiche
                            $moteur->assign('id_article',$IdArticle);
                            $moteur->assign('Titre',$Titre_Article);
                            $moteur->assign('Description',$Desc);
                            $moteur->assign('mdp',$Mdp);
                            $moteur->assign('monteur',$Monteur);
                        }
                        else
                        {
                            $moteur->assign('ErreurNum','0x803');
                            $Template='erreur.tpl';
                        }
                        
                    }
                    else
                    {
                        $moteur->assign('ErreurNum','0x804');
                        $Template='erreur.tpl';
                    }
                
                }
                else
                {
                    $moteur->assign('ErreurNum','0x805');
                    $Template='erreur.tpl';
                }
            }
            else
            {
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