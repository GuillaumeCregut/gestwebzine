<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.4                        */
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
    //On initialise le moteur de template
    $moteur=new Smarty();
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
                
                //Connexion à la base de données
                $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
                //Traitement admin
                $Template='parametres.tpl';
                //As t'on recu un formulaire ?
                if(isset($_POST['delai']))
                {
                    //On ajoute les paramètres
                    $LeDelai=intval($_POST['delai']);
                    $LeMail=htmlspecialchars($_POST['admail'],ENT_NOQUOTES,'UTF-8');
                    $SQLS=$SQL_Mod_Param_I;
                    $TabVal=array(':nom'=>'delai',':valeur'=>$LeDelai);
                    $Conn->ExecProc($TabVal,$SQLS);
                    $SQLS=$SQL_Mod_Param_S;
                    $TabVal['nom']='mail';
                    $TabVal['valeur']=$LeMail;
                    $Conn->ExecProc($TabVal,$SQLS);
                    //Historisation
                    $SQLS=$SQL_Add_Histo;
                    $Id_User=$_SESSION['Utilisateur'];
                    $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Param, ':quoi'=>'Delai et mail');
                    $Conn->ExecProc($TabHisto,$SQLS);

                }
                //As t'on recu un autre formulaire
                if(isset($_POST['NouvType']))
                {
                    $NouveauType=htmlspecialchars($_POST['NouvType'],ENT_NOQUOTES,'UTF-8');
                    if($NouveauType!='')
                    {
                        //On ajoute le type
                        $SQLS=$SQL_Add_Type;
                        $TabType=array(':nom'=>$NouveauType); 
                        $Conn->ExecProc($TabType,$SQLS);
                        //Historisation
                        $SQLS=$SQL_Add_Histo;
                        $Id_User=$_SESSION['Utilisateur'];
                        $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Param, ':quoi'=>$NouveauType);
                        $Conn->ExecProc($TabHisto,$SQLS);
                        }  
                }
                //Demande de purge
                /*if(isset($_POST['purge']))
                {
                    $Purge=intval($_POST['purge']);
                    if($Purge==1)
                    {
                        //Récupère l'ensemble des fichiers zip du répertoire fichiers et les supprime
                        $FileListe=scandir($CheminBaseFichier);
                        for ($i=0;$i<sizeof($FileListe);$i++)
                        {
                            $LeFichier=$CheminBaseFichier.$FileListe[$i];
                            if(is_file($LeFichier))
                            {
                                $Test=new SplFileInfo($LeFichier);
                                $Ext=$Test->getExtension();
                                if($Ext=='zip')
                                {
                                    unlink($LeFichier);
                                }
                            }
                        }
                        //Historisation
                        $SQLS=$SQL_Add_Histo;
                        $Id_User=$_SESSION['Utilisateur'];
                        $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Purge, ':quoi'=>'');
                        $Conn->ExecProc($TabHisto,$SQLS);

                    }
                }*/
//Traitement de l'affichage
                //récupération des paramètres
                //Délai
                $SQLS=$SQL_Get_Param_Delai;
                $row=$Conn->sql_fetch_all($SQLS);
                $Delai=$row[0]['Value_Param_I'];
                $moteur->assign('LeDelai',$Delai);
                //Adresse mail administrateur
                $SQLS=$SQL_Get_Param_Mail;
                $row=$Conn->sql_fetch_all($SQLS);
                $adresse=$row[0]['Value_Param_S'];
                $moteur->assign('LeMail',$adresse);
                //Récupération des types d'articles
                $SQLS=$SQL_All_Types;
                $row=$Conn->sql_fetch_all($SQLS);
                $moteur->assign('TabTypes',$row);
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