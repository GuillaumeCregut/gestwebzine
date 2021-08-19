<?php
 /*version 1.0B*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    session_start();
    if(isset($_SESSION['login'])) 
    {
        $Resultat=0;
        $tabJson=array();
        if(!empty($_FILES))
        {
            //Connexion à la base de données
            $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
             //Debut concernant le tlc fichier
            $IdUser=$_SESSION['Utilisateur'];
            $Id_Article=$_SESSION['Id_Article_Cours'];
            //Récupérer nom article
            $Nom_Article=$_SESSION['Nom_Article'];
            //Met en forme le nom pour le fichier
            $Nom_Article=str_replace(' ','_', $Nom_Article);
            //On remplace les caractères non autorisés
            $Nom_Article=preg_replace('#[^[:alnum:]]#u', "-", $Nom_Article);
            $fichier=$Nom_Article.'.zip';
            // Chemin Base Fichier
            $CheminArticle='../'.$CheminBaseFichier.$CheminMEP.$fichier;
                //Si on a bien reçu le fichier
            if($_FILES['fichier']["error"]==0)   
            {
                if(move_uploaded_file($_FILES['fichier']['tmp_name'],$CheminArticle))
                {
                    //Vérifie si une MEP pour cet article est existante                            
                    $SQLS=$SQL_Existe_MEP;
                    $TabVal=array(':idArticle'=>$Id_Article);
                    $Conn->ExecProc($TabVal,$SQLS);
                    while($row=$Conn->sql_fetchrow())
                    {
                        $NbreMEP=$row['compte'];
                    }
                    $IdUser=$_SESSION['Utilisateur'];
                    //Si elle est existante, il faut faire juste une mise à jour 
                    if($NbreMEP>0)
                    {                              
                        $SQLS=$SQL_Mod_File_MEP;
                        $ActionHisto=Histo_Modif_MEP;
                    }
                    //Sinon, il faut la créer
                    else
                    {                               
                        $SQLS= $SQL_Add_File_MEP;   
                        $ActionHisto=Histo_Add_MEP;            
                    }
                    //On execute la requete pour intégrant le fichier dans la base de données
                    $TabMEP=array(':id_user'=>$IdUser,':id_article'=>$Id_Article,':fichier'=>$fichier); 
                    $Resultat=$Conn->ExecProc($TabMEP,$SQLS);
                    //Historisation
                    $SQLS=$SQL_Add_Histo;
                    $TabHisto=array(':user'=>$IdUser, ':action'=>$ActionHisto, ':quoi'=>$Id_Article);
                    $Conn->ExecProc($TabHisto,$SQLS);
                    if( $Resultat==1) //Si on a bien ajouter ou modifié la MEP
                    {
                        //Si OK pour changement fichier
                        array_push($tabJson,array('Retour'=>1, 'Texte'=>'Fichier ajouté'));
                    }
                    else
                    {
                        //Création ou modification MEP impossible
                        array_push($tabJson,array('Retour'=>0, 'Texte'=>'Fichier ajouté, mais pas mis à jour dans la base'));
                    }
                }
                else
                {
                    //On a pas put copîer le fichier
                    array_push($tabJson,array('Retour'=>0, 'Texte'=>'Fichier non copié'));
                }
            }
        }
        else
        {
            array_push($tabJson,array('Retour'=>0, 'Texte'=>'Pas de fichier'));
        }
        echo json_encode($tabJson);
    }
    //On retourne un json avec aucune raison, car on aurait pas du l'appeler
    array_push($tabJson,array('Retour'=>0, 'Texte'=>''));
    echo json_encode($tabJson);