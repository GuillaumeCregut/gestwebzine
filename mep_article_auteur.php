<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.8                        */
    /* Date création : 03/05/2021                  */ 
    /* Dernière modification : 19/05/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
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
            echo "</>";
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
        if( true)  //administrateur
        {
            
                //On récupère l'id de l'article dans la session
                $Id_Article=$_SESSION['Id_Article_Cours'];
            if(!isset($Id_Article) or ($Id_Article==0))
            {
                $moteur->assign('Message','Un souci est survenu : Erreur 0x03B');
                $moteur->display($CheminTpl.'erreur.tpl');
                $moteur->display($CheminTpl.'footer.tpl');
                die;
            }
            //Traitement normal
            $moteur->assign('id_article',$Id_Article);
            debug_tab($_SESSION,false);
            debug_tab($_POST,false);
            //Récupère les informations de l'article
            $SQLS=$SQL_Article_MEP;
            $tabVal=array(':id'=>$Id_Article);
            $row=$Conn->sql_fetch_all_prepared($tabVal,$SQLS);
            debug_tab($row,false);
            //On affiche les valeurs
            $Auteur=$row[0]['prenom'].' '.$row[0]['nom'];
            $Titre=$row[0]['titre_article'];
            $Etat_Article=$row[0]['nom_etat'];
            $DateCreation=$row[0]['date_creation'];
            //Mettre en forme la date
            $DateCreation = date("d/m/Y", strtotime($DateCreation));
            $DateModification=$row[0]['date_modification'];
            //Mettre en forme la date
            $DateModification= date("d/m/Y", strtotime($DateModification));
            $Description=nl2br($row[0]['description']);
            $TypeArticle=$row[0]['Nom_Type'];
            $Verrou=$row[0]['art_locked'];
            $FichierArticle=$row[0]['fichiers'];
            $PEC_Id=$row[0]['pec'];
            $PresenceFichier=false;
            //Mettre en forme le chemin du fichier
            if($FichierArticle!='')
            {
                $FichierArticle=$CheminBaseFichier.$CheminArticle.$FichierArticle;
                $PresenceFichier=true;
            }
            $moteur->assign('Auteur',$Auteur);
            $moteur->assign('Titre',$Titre);
            $moteur->assign('Etat',$Etat_Article);
            $moteur->assign('Date_C',$DateCreation);
            $moteur->assign('Date_M',$DateModification);
            $moteur->assign('Desc',$Description);
            $moteur->assign('Verrouillage',$Verrou);
            $moteur->assign('TypeArticle',$TypeArticle);
            $moteur->assign('FichierArticle',$FichierArticle);
            $moteur->assign('PresenceFichier',$PresenceFichier);
            //Récupère le nom du PEC
            if($PEC_Id!=0)
            {
                 $SQLS=$SQL_Usager_Mess;
                $TabId=array(':idUser'=>$PEC_Id);
                $row=$Conn->sql_fetch_all_prepared( $TabId,$SQLS);
                $Nom=$row[0]['nom'];
                $Prenom=$row[0]['prenom'];
                $NomPrenom="$Prenom $Nom";
            }
            else
            {
                $NomPrenom='-';
            }
            $moteur->assign('PEC_ID',$NomPrenom);
            //Information sur le verrouillage de l'article
            if( $Verrou==0)
            {
               /* $CheminVerrou='lock.png';*/
                $CheminCadenas="b_unlock.png";
                /*$ActionVerrou="Verrouiller l'article";*/
            }
            else
            {
               /* $CheminVerrou='unlock.png';*/
                $CheminCadenas="b_lock.png";
               /* $ActionVerrou="Déverrouiller l'article"; */
            }
            $moteur->assign('Cadenas',$CheminCadenas);
            $moteur->assign('EtatVerrou',$Verrou);
            //On récupère les infos de mise en page (auteur, fichier)
            $SQLS=$SQL_Get_MEP_Article;
            $TabValArt=array(':id'=>$Id_Article);
            $row1=$Conn->sql_fetch_all_prepared($TabValArt,$SQLS);
            if(sizeof($row1)>0)
            {
                $Nom=$row1[0]['nom'];
                $Prenom=$row1[0]['prenom'];
                $Graphiste=$Prenom.' '.$Nom;
                $DateMEP=$row1[0]['date_modif'];
                $MEP_Date = date("d/m/Y", strtotime( $DateMEP));
                $Fichier_MEP=$row1[0]['fichiers'];
                $Nombre_page=$row1[0]['nb_page'];
                if($Fichier_MEP!='')
                {
                    $Fichier_MEP=$CheminBaseFichier.$CheminMEP.$Fichier_MEP;
                }
            }
            else
            {
                $Graphiste="Non attribué";
                $MEP_Date='-';
                $Fichier_MEP='';
                $Nombre_page=0;
            }
            //On affiche les informations
            $moteur->assign('NomGraph',$Graphiste);
            $moteur->assign('DateGraph',$MEP_Date);
            $moteur->assign('FichierMEP',$Fichier_MEP);
            $moteur->assign('Nb_Page',$Nombre_page);
            //On récupère les messages
            $SQLS=$SQL_Get_Message_redaction;
            $row=$Conn->sql_fetch_all_prepared($TabValArt,$SQLS);
            debug_tab($row,false);
            if(sizeof($row)>0)
            {
                $TabMessages=array();
                $Tabtemp=array();
                //Affichage des messages
                foreach($row as $v)
                {
                    $Nom=$v['nom'];
                    $Prenom=$v['prenom'];
                    $Tabtemp['Auteur']=$Prenom.' '.$Nom;
                    $DateMessage=$v['date_message'];
                    $DateMessage = date("d/m/Y H:m", strtotime($DateMessage));
                    $Tabtemp['Date_message']=$DateMessage;
                    $Message=$v['corps'];
                    $Tabtemp['corps']=nl2br($Message);
                    array_push($TabMessages,$Tabtemp);
                }
            }
            if (isset($TabMessages))
            {
                $moteur->assign('TableauMessage',$TabMessages);
            }
            //Assignation de l'auteur pour les messages
            $Auteur=$_SESSION['Utilisateur'];
            $moteur->assign('Id_Auteur',$Auteur);
            //Final
            $Template="mep_article_auteur.tpl";
        }
        else //Devenu inutile
        {
            //Traitement pigiste
            $Template='inde_pigiste.tpl';
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