<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 16/07/2021                  */
    /* Dernière modification : 16/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
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
    function WarningHandler($errno,$errstr)
    {
        throw new Exception('Erreur dans la gestion fichier');
    }
    //Début code
    $TabJson=array();
    if(isset($_SESSION['login']))
    {
        if(!empty($_POST))
        {
            if(isset($_POST['id_article']))
            {
                //Connexion à la base de données
                $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
                $id_article=intval($_POST['id_article']);
                $SQLS=$SQL_Get_Photo_By_Article;
                $tabval=array(':idarticle'=>$id_article);
                $row=$Conn->sql_fetch_all_prepared($tabval,$SQLS);
                if(!empty($row))
                {
                    array_push($TabJson,array('Retour'=>0,'Texte'=>"Table existe déjà"));
                }
                else
                {
                    //Récupérer nom article via SQL
                    $SQLS=$SQLS_Article_Vue_Modif;
                    $row=$Conn->sql_fetch_all_prepared($tabval,$SQLS);
                    $Nom_Article=$row[0]['titre_article'];
                    //Met en forme le nom pour le fichier
                    $Nom_Article=str_replace(' ','_', $Nom_Article);
                    //On remplace les caractères non autorisés
                    $Nom_Article=preg_replace('#[^[:alnum:]]#u', "-", $Nom_Article);
                    $Chemin_Images= '../'.$CheminBaseFichier.$CheminPhotos.$Nom_Article;
                    $CheminMini=$Chemin_Images.'/'.$CheminMiniatures;
                    set_error_handler("WarningHandler",E_WARNING);
                    $repOK=true;
                    try
                    {
                        if(!is_dir($Chemin_Images))
                        {
                        
                           if(mkdir($Chemin_Images))
                           {
                                if(!mkdir($CheminMini))
                                {
                                    array_push($TabJson,array('Retour'=>0,'Texte'=>"Création répertoire mini impossible"));
                                    $repOK=false;
                                }
                           }
                           else
                           {
                                array_push($TabJson,array('Retour'=>0,'Texte'=>"Création répertoire de base impossible"));
                                $repOK=false;
                           }
                           
                        }
                        //On a (ou avait) la structure répertoire de créée
                    }
                    catch(Exception $e)
                    {
                        $Erreur=$e->getMessage();
                        array_push($TabJson,array('Retour'=>0,'Texte'=>$Erreur));
                        echo json_encode($TabJson);
                        die;
                    }
                    restore_error_handler();
                    if($repOK)
                    {
                        //On peut créer l'entrée dans la base
                        $SQLS=$SQL_Add_Photo;
                        $TabDonnees=array(':article'=>$id_article,':chemin'=>$Nom_Article);
                        $retour=$Conn->ExecProc($TabDonnees,$SQLS);
                        array_push($TabJson,array('Retour'=>$retour,'Texte'=>"OK"));
                    }
                }
            }
            else
            {
                //On a pas recu le bon formulaire
                array_push($TabJson,array('Retour'=>0,'Texte'=>"Mauvais formulaire"));
            }
        }
        else
        {
            //On a pas recu de POST
            array_push($TabJson,array('Retour'=>0,'Texte'=>"POST vide"));
        }
    }
    else
    {
        //La session n'est pas bonne
        array_push($TabJson,array('Retour'=>0,'Texte'=>"Accès refusé"));
    }
    echo json_encode($TabJson);
?>