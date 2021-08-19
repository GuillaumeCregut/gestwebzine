<?php
 /*version 1.0B*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    session_start();
    if(isset($_SESSION['login'])) 
    {
        if(!empty($_POST))
        {
            //On récupère et on formate le nom du fichier 
            $Nom_Article=$_SESSION['Nom_Article'];
            $Nom_Article=str_replace(' ','_', $Nom_Article);
            //On remplace les caractères non autorisés
            $Nom_Article=preg_replace('#[^[:alnum:]]#u', "-", $Nom_Article);
            //Récupération des informations du POST
            $Id_Article=intval($_POST['id_article']);
            $Action=$_POST['action'];
            //Connexion à la base de données
            $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
            switch($Action)
            {
                    case 1 : //Envoie de texte
                            // Chemin du Fichier
                            $CheminArticle='../'.$CheminBaseFichier.$CheminArticle.$Nom_Article.'.zip';
                        break;
                    case 2 : //Envoie d'image
                            //$CheminBaseFichier
                            $CheminArticle='../'.$CheminBaseFichier.$CheminPhotos.$Nom_Article.'.zip';
                        break;
            }
        }
        if(!empty($_FILES))
        {
            $tabJson=array();
            $fileName = $_FILES["file1"]["name"]; // The file name
            $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
            $fileType = $_FILES["file1"]["type"]; // The type of file it is
            $fileSize = $_FILES["file1"]["size"]; // File size in bytes
            $fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
            if ( $fileErrorMsg==0)
            { 
                if(move_uploaded_file($fileTmpLoc, $CheminArticle))
                {
                    
                    array_push($tabJson,array('Retour'=>1,'Texte'=>'Fichier téléchargé !'));
                    echo json_encode($tabJson);
                    //Mettre à jour la base de données
                     //Insérer le fichier dans la base de données;
                    $SQLS=$SQL_Update_Article_Fichier;
                    $Nom_fich_Base=$Nom_Article.'.zip';
                    $TabFich=array(':idart'=>$Id_Article,':chemin'=>$Nom_fich_Base);
                    $Conn->ExecProc($TabFich,$SQLS);
                    //Historisation
                    $SQLS=$SQL_Add_Histo;
                    $Id_User=$_SESSION['Utilisateur'];
                    $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Article, ':quoi'=>$Id_Article);
                    $Conn->ExecProc($TabHisto,$SQLS);
                }
                else
                {
                    array_push($tabJson,array('Retour'=>0,'Texte'=>'Impossible de télécharger le fichier'));
                    echo json_encode($tabJson);
                }
            }
            else
            {
                array_push($tabJson,array('Retour'=>0,'Texte'=>'Erreur dans le téléchargement du fichier.'));
                echo json_encode($tabJson);
            }
           
            
        }
        else
        {
            array_push($tabJson,array('Retour'=>0,'Texte'=>'Le fichier envoyé est trop grand !'));
        }
    }
?>