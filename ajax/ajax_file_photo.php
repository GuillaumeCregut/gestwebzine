<?php
 /*version 1.0B*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    include "../include/functions.inc.php";
    $Resultat=0;
    $tabJson=array();
    $NbrePhoto=0;
    if(!empty($_FILES) and !empty($_POST))
    {
        //On récupère l'id de l'article
        $Id_Article=intval($_POST['Article']);
        //On récupère les fichiers
        $TabFiles=array();
        $CompteFile=sizeof($_FILES['file1']['name']);
        for($i=0;$i<$CompteFile;$i++)
        {
            $tmpName=$_FILES['file1']['tmp_name'][$i];
            $Error=$_FILES['file1']['error'][$i];
            $Taille=$_FILES['file1']['size'][$i];
            $TypeF=$_FILES['file1']['type'][$i];
            array_push($TabFiles,array('tmp_name'=>$tmpName,'error'=>$Error,'size'=>$Taille,'typeF'=>$TypeF));
        }
        //Connexion à la base de données
        $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);   
        $NoImage=true; 
        //On vérifie si on a les photos déjà déclarées.
        $SQLS=$SQL_Get_Photo_By_Article;
        $tabval=array(':idarticle'=>$Id_Article);
        $row=$Conn->sql_fetch_all_prepared($tabval,$SQLS);
        if(!empty($row))
        {
            $NoImage=false;
            $PhotoValide=$row[0]['photo_valide'];
            if($PhotoValide==1)
            {
                //Les photos sont valides, on ne télécharge pas.
                array_push($tabJson,array('Retour'=>0, 'Texte'=>'Les photos sont déjà présentes'));
                echo json_encode($tabJson);
                die();
            }
        }
        //Récupérer nom article via SQL
        $SQLS=$SQLS_Article_Vue_Modif;
        $tabval=array(':idarticle'=>$Id_Article);
        $row=$Conn->sql_fetch_all_prepared($tabval,$SQLS);
        $Nom_Article=$row[0]['titre_article'];
        //Met en forme le nom pour le fichier
        $Nom_Article=str_replace(' ','_', $Nom_Article);
        //On remplace les caractères non autorisés
        $Nom_Article=preg_replace('#[^[:alnum:]]#u', "-", $Nom_Article);
        
        //Pour chaque fichier, vérifier le type de fichier, et procéder en fonction
        $ImgJpg='image/jpeg';
        $ZipFile='application/x-zip-compressed';
        $ImgGif='image/gif';
        $ImgPng='image/png';
        $Chemin_Images= '../'.$CheminBaseFichier.$CheminPhotos.$Nom_Article;
        $CheminMini=$Chemin_Images.'/'.$CheminMiniatures;
        $RepOk=true;
        //Si le répertoire n'existe pas on le créé, 
        if(!is_dir($Chemin_Images))
        {
        
           if(mkdir($Chemin_Images))
           {
                if(!mkdir($CheminMini))
                {
                    $RepOk=false;
                }
           }
           else
           {
               $RepOk=false;
           }
           
        }
        $i=0;
        if($RepOk)
        {
            foreach($TabFiles as $v)
            {
                $Type_fichier=$v['typeF'];
                if($v['error']==0) //Si le fichier n'a pas eu d'erreur
                {
                    $NomDestination=$Chemin_Images.'/'.$i;
                    $Mini=$CheminMini.$i;
                    //$Mini= $Chemin_Images.'';
                    switch($Type_fichier)
                    {
                        case $ImgJpg :
                            //Enregistrement du fichier, création miniature
                            $NomDestination.='.jpg';
                            $Mini.='.jpg';
                            $ret=saveFile($v['tmp_name'],$NomDestination);
                            if($ret)
                            {
                                //Création miniature
                                
                                CreateMinJpg($NomDestination,$Mini);
                            }
                            break;
                        case $ZipFile :
                            //Enregistrement du zip en tmp, décompression
                            $NomDestination.='.zip';
                            $ret=saveFile($v['tmp_name'],$NomDestination);
                            if($ret)
                            {
                                //extraction fichiers
                                $Decompresse=deflate($NomDestination,$Chemin_Images.$RepTemp);
                                if($Decompresse)
                                {         
                                    //Suppression du fichier zip
                                    unlink($NomDestination);
                                    if(is_dir($Chemin_Images.$RepTemp.'/'))
                                    {
                                        $TabTemp=scandir($Chemin_Images.$RepTemp);
                                        //POur chaque fichier dans le répertoire on verifie si c'est une image, on la traite, et ensuite on supprime.
                                        foreach($TabTemp as $t)
                                        {
                                            $NomFichierExtrait=$Chemin_Images.$RepTemp.'/'.$t;
                                            if(($t!='.') and ($t!='..'))
                                            {
                                                $Fichier=new SplFileInfo($NomFichierExtrait);
                                                $ext=strtolower($Fichier->getExtension());
                                                $FichierMiniature=$CheminMini.'_'.$t;
                                                $FichierDest=$Chemin_Images.'/_'.$t;
                                                switch($ext)
                                                {
                                                    case 'jpg' :
                                                        CreateMinJpg($NomFichierExtrait,$FichierMiniature);
                                                        //Copie le fichier
                                                        copy($NomFichierExtrait,$FichierDest);
                                                        break;
                                                    case 'jpeg' :
                                                            CreateMinJpg($NomFichierExtrait,$FichierMiniature);
                                                            //Copie le fichier
                                                            copy($NomFichierExtrait,$FichierDest);
                                                            break;    
                                                    case 'png' :
                                                        CreateMinPng($NomFichierExtrait,$FichierMiniature);
                                                        //Copie le fichier
                                                        copy($NomFichierExtrait,$FichierDest);
                                                        break;
                                                    case 'gif':
                                                        CreateMinGif($NomFichierExtrait,$FichierMiniature);
                                                        //Copie le fichier
                                                        copy($NomFichierExtrait,$FichierDest);
                                                        break;
                                                    default : ;    
                                                }
                                                unlink($NomFichierExtrait);
                                            }
                                            //
                                        }

                                    }
                                    else
                                    {
                                        array_push($tabJson,array('Retour'=>0, 'Texte'=>'Erreur inconnue'));
                                    }
                                }
                                else
                                {
                                    //Erreur de décompression
                                    array_push($tabJson,array('Retour'=>0, 'Texte'=>'Fichier zip corrompu'));
                                }                   
                            }
                            break;
                        case $ImgGif :
                            $NomDestination.='.gif';
                            $Mini.='.gif';
                            $ret=saveFile($v['tmp_name'],$NomDestination);
                            if($ret)
                            {
                                //Création miniature
                                CreateMinGif($NomDestination,$Mini);
                            }
                            break;
                        case $ImgPng:
                            $NomDestination.='.png';
                            $Mini.='.png';
                            $ret=saveFile($v['tmp_name'],$NomDestination);
                            if($ret)
                            {
                                //Création miniature
                                CreateMinPng($NomDestination,$Mini);
                            }
                            break;
                        default : 
                            ;
                    }
                }
                $i++;
            }
        }
        else
        {
            array_push($tabJson,array('Retour'=>0, 'Texte'=>'Création des fichiers impossible'));
        }
        //on a fini de traiter les fichiers
        //On supprimer le répertoire temporaire
        if(is_dir($Chemin_Images.$RepTemp.'/'))
        {
            rmdir($Chemin_Images.$RepTemp.'/');
        }
        //En fonction de si on a ou non déjà dans la base
        //On vérifie si on a les photos déjà déclarées.
        $SQLS=$SQL_Get_Photo_By_Article;
        $row=$Conn->sql_fetch_all_prepared($tabval,$SQLS);
        if($NoImage)
        {
            $SQLS=$SQL_Add_Photo;
            $TabDonnees=array(':article'=>$Id_Article,':chemin'=>$Nom_Article);
            $retour=$Conn->ExecProc($TabDonnees,$SQLS);
            array_push($tabJson,array('Retour'=>$retour, 'Texte'=>'Terminé'));
        }
        else
            array_push($tabJson,array('Retour'=>1, 'Texte'=>'Terminé'));
    }
    else
    {
        array_push($tabJson,array('Retour'=>0, 'Texte'=>'Pas de fichier'));
    }
    echo json_encode($tabJson);
?>