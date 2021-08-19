<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 01/08/2021                  */
    /* Dernière modification : 01/08/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    include "include/functions.inc.php";
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

               debug_tab($_POST,false);
                //Traitement admin
                if(!empty($_POST))
                {
                    if(isset($_POST['update']))
                    {
                        //On a demander une mise à jour
                        $Choix=intval($_POST['update']);
                        $OK=true;
                        if(isset($_POST['titre']))
                        {
                            $TitreWebzine=htmlspecialchars($_POST['titre'],ENT_NOQUOTES,'UTF-8');
                            //Création du nom du chemin de backup
                            $TitreWebzine=str_replace(' ','_', $TitreWebzine);
                            //On remplace les caractères non autorisés
                            $TitreWebzine=preg_replace('#[^[:alnum:]]#u', "-", $TitreWebzine);
                            //Création du répertoire de backup
                            $CheminBkup=$CheminBaseFichier.$CheminBackupWebzine.$TitreWebzine.'.zip';
                            debug_var($CheminBkup,$Debug);
                            $tmpDir=substr($RepTemp,1);
                            $TempDir=$CheminBaseFichier.$tmpDir.'/';
                            mkdir($TempDir);
                        }
                        else
                        {
                            $OK=false;
                        }
                        if(isset($_POST['id_webzine']) and $OK)
                        {
                            $Id_Webzine=intval($_POST['id_webzine']);
                            $Action=intval($_POST['update']);
                            switch($Action)
                            {
                                case 1 : //Archivage
                                        //Récupère la liste de tous les articles du webzine
                                        $SQLS=$SQL_Vue_Archivage;
                                        $TabId=array(':id'=>$Id_Webzine);
                                        $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
                                        debug_tab($row,false);
                                        $i=0;
                                        //Tableau d'information fichier
                                        $tabResult=array();
                                        foreach($row as $v)
                                        {
                                            $Id_Art=$v['id_article'];
                                            $titre=$v['titre_article'];
                                            if(purge_article($Id_Art,true,$TempDir)>0)
                                            {
                                                $leResult="Archivage de $titre OK";
                                                $i++;
                                                array_push($tabResult,$leResult);
                                            }
                                        }
                                        $total=count($row);
                                        if($i==$total)
                                        {
                                            //On bascule le webzine en archivé
                                            $SQLS=$SQL_Mod_Etat_Webzine;
                                            $Tab=array(':id'=>$Id_Webzine,':etat'=>Etat_Webzine_Archive);
                                            $Resultat=$Conn->ExecProc($Tab,$SQLS);
                                            if($Resultat=1)
                                            {
                                                //On a modifier
                                                //Historisation
                                                $SQLS=$SQL_Add_Histo;
                                                $Id_User=$_SESSION['Utilisateur'];
                                                $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Archivage_Webzine, ':quoi'=>$Id_Webzine);
                                                $Conn->ExecProc($TabHisto,$SQLS);
                                            }
                                        }
                                        //Archives les MEP dans un répertoire
                                        //Si tout OK
                                        //tmp->zip
                                        $MonZip=new ZipArchive;
                                        if($MonZip->open($CheminBkup,ZipArchive::CREATE)===TRUE)
                                        {
                                            if ($handle = opendir($TempDir))
                                            {
                                                while (false !== ($entry = readdir($handle)))
                                                {
                                                    if ($entry != "." && $entry != "..")
                                                    {
                                                        $FileName=$TempDir.$entry;
                                                        $MonZip->addFile($FileName,$entry);
                                                    }
                                                }
                                                closedir($handle);
                                            }
                                            $MonZip->close();
                                            if(file_exists($CheminBkup))
                                                 $DisplayZip=$CheminBkup;
                                            else
                                                $DisplayZip='';
                                        } 
                                        else
                                        {
                                            $DisplayZip='';
                                        }
                                        //on supprime le répertoire
                                        if ($handle = opendir($TempDir))
                                        {
                                            while (false !== ($entry = readdir($handle)))
                                            {
                                                if ($entry != "." && $entry != "..")
                                                {
                                                    $FileName=$TempDir.$entry;
                                                    unlink($FileName);
                                                }
                                            }
                                            closedir($handle);
                                        }
                                        rmdir($TempDir);
                                        $moteur->assign('Action','A');
                                        debug_tab($tabResult,$Debug);
                                        $moteur->assign('tabRetour',$tabResult);
                                        $moteur->assign('zip',$DisplayZip);
                                    break;
                                default : ;

                            }
                        }
                        else
                        {
                            $OK=false;
                        }
                        
                    }                 
                    if(isset($_POST['id_webzine']))
                    {
                        $Id_Webzine=intval($_POST['id_webzine']);
                        $SQLS=$SQL_Gest_Webzine_Infos;
                        $TabId=array(':id'=>$Id_Webzine);
                        $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
                        debug_tab($row,false);
                        $Nom_Webzine=$row[0]['Titre_Webzine'];
                        $Date_Parution=$row[0]['Date_Parution'];
                        $Madate = new DateTime($Date_Parution);
                        $Date_Parution=$Madate->format('d/m/Y');
                        $Etat_Webzine=$row[0]['nom_etat_webzine'];
                        $moteur->assign('Titre',$Nom_Webzine);
                        $moteur->assign('Date_Parution',$Date_Parution);
                        $moteur->assign('Etat',$Etat_Webzine);
                        $moteur->assign('id_webzine',$Id_Webzine);
                        $Template="gere_webzine.tpl"; 
                    }
                    else
                    {
                        $moteur->assign('ErreurNum','0x704');
                        $Template='erreur.tpl';
                    }
                
                }
                else
                {
                    $moteur->assign('ErreurNum','0x705');
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