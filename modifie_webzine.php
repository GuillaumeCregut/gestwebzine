<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.5                        */
    /* Date création : 29/03/2021                  */
    /* Dernière modification : 29/04/2021          */
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
    //$Conn2=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
         //On affiche le nom et prenom utilisateur
         $NomUser=$_SESSION['Nom_User'];
         $PrenomUser=$_SESSION['Prenom_User'];
         $nom_Prenom=$PrenomUser.' '.$NomUser;
         $moteur->assign('PrenomLogin',$nom_Prenom);
        //On vérifie le niveau du membre
        if( ($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système))  //administrateur
        {
            $Template='modifie_webzine.tpl';
            //Traitement admin
            //Arrive t'on par formulaire ?
            if(isset($_POST))
            {
              /*  echo "<pre>";
                print_r($_POST);
                print_r($_SESSION);
                echo "</pre>";*/
                //On va modifier le wezine par champs.
                $Id_Webzine=$_SESSION['id_webzine'];
                //Tableau contenant les résultats des modifications
                $TabRetourAction=array();
                $IndiceRetour=0;
               //Modification du nom
                if(isset($_POST['cb_nom']))
                {
                    if($_POST['cb_nom'])
                    {
                        $NouveauNom=htmlspecialchars($_POST['Nom_Webzine'],ENT_NOQUOTES,'UTF-8');
                        $SQLS=$SQL_Mod_Nom_Webzine;
                        $TabNom=array(':id'=>$Id_Webzine,':nom'=>$NouveauNom);
                        $Resultat=$Conn->ExecProc($TabNom,$SQLS);
                        $TabRetourAction[$IndiceRetour]['nom']='Modification du nom';
                        if($Resultat==1)
                        {
                            //On a modifier
                            $TabRetourAction[$IndiceRetour]['valeur']='OK';
                             //Historisation
                            $SQLS=$SQL_Add_Histo;
                            $Id_User=$_SESSION['Utilisateur'];
                            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Webzine, ':quoi'=>$Id_Webzine);
                            $Conn->ExecProc($TabHisto,$SQLS);
                        }
                        else
                        {
                            //On a pas modifier
                            $TabRetourAction[$IndiceRetour]['valeur']='Erreur';
                        }
                        $IndiceRetour++;
                    }
                }
                if(isset($_POST['cb_date']))
                {
                    if($_POST['cb_date'])
                    {
                        $NouvelleDate=htmlspecialchars($_POST['date_parution'],ENT_NOQUOTES,'UTF-8');
                        $TabRetourAction[$IndiceRetour]['nom']='Modification de la date de parution';
                        $SQLS= $SQL_Mod_Date_Webzine;
                        $Tab=array(':id'=>$Id_Webzine,':date'=>$NouvelleDate);
                        $Resultat=$Conn->ExecProc($Tab,$SQLS);
                        if($Resultat==1)
                        {
                            //On a modifier
                            $TabRetourAction[$IndiceRetour]['valeur']='OK';
                            //Historisation
                            $SQLS=$SQL_Add_Histo;
                            $Id_User=$_SESSION['Utilisateur'];
                            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Webzine, ':quoi'=>$Id_Webzine);
                            $Conn->ExecProc($TabHisto,$SQLS);
                        }
                        else
                        {
                            //On a pas modifier
                            $TabRetourAction[$IndiceRetour]['valeur']='Erreur';
                        }
                        $IndiceRetour++;
                    }
                }
                $Action=intval($_POST['action']);
                if(isset($_POST['cb_etat']) and ($Action!=4))
                {
                    if($_POST['cb_etat'])
                    {
                        $NouvelEtat=intval($_POST['etat_webzine']);
                        $TabRetourAction[$IndiceRetour]['nom']="Modification de l'état du webzine";
                        $SQLS=$SQL_Mod_Etat_Webzine;
                        $Tab=array(':id'=>$Id_Webzine,':etat'=>$NouvelEtat);
                        $Resultat=$Conn->ExecProc($Tab,$SQLS);
                        if($Resultat=1)
                        {
                            //On a modifier
                            $TabRetourAction[$IndiceRetour]['valeur']='OK';
                            //Historisation
                            $SQLS=$SQL_Add_Histo;
                            $Id_User=$_SESSION['Utilisateur'];
                            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Webzine, ':quoi'=>$Id_Webzine);
                            $Conn->ExecProc($TabHisto,$SQLS);
                        }
                        else
                        {
                            //On a pas modifier
                            $TabRetourAction[$IndiceRetour]['valeur']='Erreur';
                        }
                    }
                }
                //Si on a fait une demande d'archivage
                if($Action==Action_Archivage)
                {
                    //Récupère le nom du webzine
                    $SQLS=$SQL_Get_Webzine_Name;
                    $TabId=array(':id'=>$Id_Webzine);
                    $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
                    $NomWebzine=$row[0]['Titre_Webzine'];
                    //On supprime et remplace les espaces
                    $NomWebzine=trim($NomWebzine);
                    $NomWebzine=str_replace(' ','_',$NomWebzine).'.zip';
                    $CheminArchive=$CheminBaseFichier. $NomWebzine;
                    //Récupération de tous les fichiers appartenants au webzine
                    $SQLS=$SQL_Vue_Archivage;
                    $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
                    //Tableau d'information fichier
                    $TabFiles=array();
                    for($i=0;$i<sizeof($row);$i++)
                    {
                        $TabFiles[$i]['Nom_fichier_MEP']=$row[$i]['Fichier_MEP'];
                        $TabFiles[$i]['Chemin_MEP']=$CheminBaseFichier.$CheminMEP.$row[$i]['Fichier_MEP'];
                        $TabFiles[$i]['Nom_fichier_Article']=$row[$i]['FichierArticle'];
                        $TabFiles[$i]['Chemin_Article']=$CheminBaseFichier. $CheminArticle.$row[$i]['FichierArticle'];
                        $TabFile[$i]['Id_article']=$row[$i]['id_article'];
                    }
                    //Création du fichier zip
               //On commence le try ici    
                    try
                    {
                        $zip = new ZipArchive();
                        if ($zip->open($CheminArchive, ZipArchive::CREATE)==TRUE)
                        {
                            //Ajout des fichiers au zip
                            $ToutOk=true;
                            for($i=0;$i<sizeof($TabFiles);$i++)
                            {
                                $LeNomFichierA=$TabFiles[$i]['Nom_fichier_Article'];
                                $LeCheminA= $TabFiles[$i]['Chemin_Article'];
                                $ToutOk=$zip->addFile($LeCheminA, 'articles/'.$LeNomFichierA);
                                $LeNomFichierM=$TabFiles[$i]['Nom_fichier_MEP'];
                                $LeCheminM= $TabFiles[$i]['Chemin_MEP'];
                                $ToutOk=$zip->addFile($LeCheminM, 'pagination/'.$LeNomFichierM);
                            }
                            
                            if($ToutOk)
                            {
                                $zip->close();
                                echo "OK";
                            }
                            else
                            {
                                echo "pas OK";
                                $zip->addEmptyDir('erreur');
                                $zip->close();
                            }
                            //Bascule de tous les articles du webzine à archivé
                            $SQLS=$SQL_Set_Article_Archive;
                            $Id_User=$_SESSION['Utilisateur'];
                            for($i=0;$i<sizeof($TabFiles);$i++)
                            {
                                $Id_Article= $TabFile[$i]['Id_article'];
                                $tabId_Article[':id']=$Id_Article;
                                $Result=$Conn->ExecProc($tabId_Article,$SQLS);
                                $IndiceRetour++;
                                $TabRetourAction[$IndiceRetour]['nom']=" Article $Id_Article";
                                if($Result==1)
                                {
                                    $TabRetourAction[$IndiceRetour]['valeur']="Archivé";
                                    $LeCheminA= $TabFiles[$i]['Chemin_Article'];
                                    $LeCheminM= $TabFiles[$i]['Chemin_MEP'];
                                    //Suppression des fichiers
                                    if(file_exists($LeCheminA) and (!is_dir($LeCheminA)))
                                        unlink($LeCheminA);
                                    if(file_exists($LeCheminM) and (!is_dir($LeCheminA)))
                                        unlink($LeCheminM);
                                    //Historisation
                                    $SQLS2=$SQL_Add_Histo;
                                    $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_ArchivageArticle, ':quoi'=>$Id_Article);
                                    $Conn->ExecProc($TabHisto,$SQLS2);
                                }
                                else
                                {
                                    $TabRetourAction[$IndiceRetour]['valeur']="Non Archivé";
                                }
                            }
                            //On bascule le webzine en archivé
                            $IndiceRetour++;
                            $TabRetourAction[$IndiceRetour]['nom']='Archivage du Webzine';
                            $SQLS=$SQL_Mod_Etat_Webzine;
                            $Tab=array(':id'=>$Id_Webzine,':etat'=>Etat_Webzine_Archive);
                            $Resultat=$Conn->ExecProc($Tab,$SQLS);
                            if($Resultat=1)
                            {
                                //On a modifier
                                $TabRetourAction[$IndiceRetour]['valeur']='OK';
                                //Historisation
                                $SQLS=$SQL_Add_Histo;
                                $Id_User=$_SESSION['Utilisateur'];
                                $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Archivage_Webzine, ':quoi'=>$Id_Webzine);
                                $Conn->ExecProc($TabHisto,$SQLS);
                            }
                            else
                            {
                                //On a pas modifier
                                $TabRetourAction[$IndiceRetour]['valeur']='Erreur';
                            }
                            if(file_exists($CheminArchive))
                                $moteur->assign('LeFichier',$CheminArchive);
                        }
                        else
                        {
                            //Archivage impossible
                            $IndiceRetour++;
                            $TabRetourAction[$IndiceRetour]['nom']='Archivage';
                            $TabRetourAction[$IndiceRetour]['valeur']='impossible';
                        }
                    }
                    catch (Exception $e)
                    {
                       //Archivage impossible
                       $IndiceRetour++;
                       $TabRetourAction[$IndiceRetour]['nom']='Archivage';
                       $TabRetourAction[$IndiceRetour]['valeur']= $e->getMessage();
                    }
                } //Fin if archivage
                $moteur->assign('TabActions',$TabRetourAction);
            }//fin if post
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