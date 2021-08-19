<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine V1.0B                       */
    /* Date création : 01/08/2021                  */
    /* Dernière modification : 01/08/2021          */
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
        if($_SESSION['UserLevel']==Admin_Système)  //administrateur
        {
            //As t'on reçu un formulaire ?
            if(isset($_POST['delete']))
            {
                //On récupère les fichiers archivés
                $Dir_Backup=$CheminBaseFichier.$CheminBackupWebzine;
                $TabFichier=array();
                if ($handle = opendir($Dir_Backup))
                {
                    while (false !== ($entry = readdir($handle)))
                    {
                        if ($entry != "." && $entry != "..")
                        {
                            $FileName=$Dir_Backup.$entry;
                            unlink($FileName);
                        }
                    }
                    closedir($handle);
                }
            }
            //On récupère les infos de la base et on les affiche
            $Template="gest_webzine.tpl";  
            //On se connecte à la base de données pour récupérer les webzines non archivés
            $SQLS=$SQL_Gest_All_Webzine;
            $Conn->sql_query($SQLS);
            $i=0;
            $TabWebzine=array();
            while($row=$Conn->sql_fetchrow())
            {
                $TabWebzine[$i]['id_webzine']=$row['id_webzine'];
                $TabWebzine[$i]['titre']=$row['Titre_Webzine'];
                $Etat=$row['Etat'];
                $LaClasse='';
                switch($Etat)
                {
                    case Etat_Webzine_Vierge:
                        $LaClasse='Vierge';
                        break;
                    case Etat_Webzine_En_Cours:
                        $LaClasse='EnCours';
                        break;
                    case Etat_Webzine_Termine:
                        $LaClasse='Termine';
                        break;
                    case Etat_Webzine_Archive:
                        $LaClasse='Archive';
                        break;
                }
                $TabWebzine[$i]['Class_Etat']=$LaClasse;
                $TabWebzine[$i]['Etat']=$row['nom_etat_webzine'];
                $DateParution=$row['Date_Parution'];
                $Madate = new DateTime($DateParution);
                $DateParution=$Madate->format('d/m/Y');
                $TabWebzine[$i]['Date_Parution']=$DateParution;
                $Id_Webzine=$row['id_webzine'];
                $i++;
            }
            if(sizeof($TabWebzine)>0)
            {
                $moteur->assign('TabZine', $TabWebzine);
                //On pousse les données dans la page
            }
            //On récupère les webzines archivés
            $SQLS=$SQL_Gest_Webzine_Archive;
            $Conn->sql_query($SQLS);
            $i=0;
            $TabWebzineArchive=array();
            while($row=$Conn->sql_fetchrow())
            {
                $TabWebzineArchive[$i]['id_webzine']=$row['id_webzine'];
                $TabWebzineArchive[$i]['titre']=$row['Titre_Webzine'];
                $DateParution=$row['Date_Parution'];
                $Madate = new DateTime($DateParution);
                $DateParution=$Madate->format('d/m/Y');
                $TabWebzineArchive[$i]['Date_Parution']=$DateParution;
                $i++;
            }
            if(!empty($TabWebzineArchive))
            {
                $moteur->assign('TabZineArch',$TabWebzineArchive);
            }
            //On récupère les fichiers archivés
            $Dir_Backup=$CheminBaseFichier.$CheminBackupWebzine;
            $TabFichier=array();
            if ($handle = opendir($Dir_Backup))
            {
                while (false !== ($entry = readdir($handle)))
                {
                    if ($entry != "." && $entry != "..")
                    {
                        $FileName=$Dir_Backup.$entry;
                        $tabtemp=array('lien'=>$FileName,'nom'=>$entry);
                        array_push($TabFichier,$tabtemp);
                    }
                }
                closedir($handle);
            }
            if(!empty($TabFichier))
                $moteur->assign('TabFichierArch',$TabFichier);
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
        //On affiche la page
        $moteur->display($CheminTpl.$Template);
        $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?>