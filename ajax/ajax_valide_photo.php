<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 27/03/2021                  */
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
        throw new Exception('Erreur de zip');
    }
    //Fonction de la page
    $TabJson=array();
    if(isset($_SESSION['login']))
    {
        //debug_tab($_POST,$Debug);
        if(!empty($_POST) and !empty($_FILES))
        {
            if(isset($_POST['id_article']))
            {
                $Id_Article=intval($_POST['id_article']);
               // $Repertoire=$_POST['repertoire'];
                //Connexion à la base de données
                $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
                //Récupère la ligne dans la table t_photo
                $SQLS=$SQL_Get_Photo_By_Article;
                $tabVal=array(':idarticle'=>$Id_Article);
                $row=$Conn->sql_fetch_all_prepared($tabVal,$SQLS);
                if(!empty($row))
                {
                    $Repertoire_photo=$row[0]['chemin_photo'];
                    //On peut traiter les photos envoyées.
                    $TabFiles=array();
                    if(isset($_FILES['file1']))
                    {
                        $CompteFile=sizeof($_FILES['file1']['name']);
                        for($i=0;$i<$CompteFile;$i++)
                        {
                            $tmpName=$_FILES['file1']['tmp_name'][$i];
                            $Error=$_FILES['file1']['error'][$i];
                            $Taille=$_FILES['file1']['size'][$i];
                            $TypeF=$_FILES['file1']['type'][$i];
                            array_push($TabFiles,array('tmp_name'=>$tmpName,'error'=>$Error,'size'=>$Taille,'typeF'=>$TypeF));
                        }
                        debug_tab($TabFiles,$Debug);
                        //Forge du chemin des photos validées
                        $CheminBaseZip='../'.$CheminBaseFichier.$CheminPhotos.$Repertoire_photo.$Rep_Photo_Valide;
                        set_error_handler("WarningHandler",E_WARNING);
                        try
                        {
                            if(!is_dir($CheminBaseZip))
                            {
                                if(!mkdir($CheminBaseZip,0666))
                                {
                                    array_push($TabJson,array('Retour'=>0,'Texte'=>"Erreur création répertoire"));
                                    echo json_encode($TabJson);
                                    die;
                                }
                            }
                        }
                        catch(Exception $e)
                        {
                            array_push($TabJson,array('Retour'=>0,'Texte'=>"Erreur création répertoire"));
                            echo json_encode($TabJson);
                            die;
                        }
                        restore_error_handler();
                        //On copie les fichiers dans le répertoire
                        $i=0;
                        foreach($TabFiles as $v)
                        {
                            if($v['error']==0)
                            {
                                $TempFichier=$v['tmp_name'];
                                $NomFichierZip=$CheminBaseZip.$Repertoire_photo.'_'.$i.'.zip';
                                if(move_uploaded_file($TempFichier,$NomFichierZip))
                                    $i++;
                            }
                            else
                            {
                                //Impossible car erreur dans le téléchargement
                                array_push($TabJson,array('Retour'=>0,'Texte'=>"Erreur de téléchargement : fichier trop volumineux ?"));
                            }            
                        }
                        //On modifie dans la base de données afin d'indiquer la validité
                        $SQLS=$SQL_Update_Photo;//='CALL P_Mod_Photo(:article,:photo_valide)'; 
                        $tabData=array(':article'=>$Id_Article,'photo_valide'=>1,':nb_photo'=>$i);
                        $res=$Conn->ExecProc($tabData,$SQLS);
                        if($res==1)
                        {
                            array_push($TabJson,array('Retour'=>$i,'Texte'=>"$i Fichiers enregistrés"));
                        }
                        else
                        {
                            array_push($TabJson,array('Retour'=>0,'Texte'=>"$i Fichiers enregistrés. Mise à jour impossible"));
                        }
                    }
                    else
                    {
                        array_push($TabJson,array('Retour'=>0,'Texte'=>"Erreur d'envoi de fichiers"));
                    }
                }
                //Si elle n'existe pas, on s'arrête
                else
                {
                    array_push($TabJson,array('Retour'=>0,'Texte'=>"Erreur dans la base"));
                }
            }
            else
            {
                //Le formulaire est incomplet
                array_push($TabJson,array('Retour'=>0,'Texte'=>"Eléments manquants"));
            }
        }
        else
        {
            //On a pas reçu de formulaire complet
            array_push($TabJson,array('Retour'=>0,'Texte'=>"Erreur d'envoi"));
        }
    }
    else
    {
        //Retour avec erreur car hors connexion
        array_push($TabJson,array('Retour'=>0,'Texte'=>"Accès refusé"));
    }
    echo json_encode($TabJson);
    
?>