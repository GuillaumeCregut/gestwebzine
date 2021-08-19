<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 27/04/2021                  */
    /* Dernière modification : 31/07/2021          */
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
        if( ($_SESSION['UserLevel']==Administrateur)or($_SESSION['UserLevel']==Graphiste)or($_SESSION['UserLevel']==Admin_Système))  //administrateur
            {
                //Permet d'afficher les options administrateurs dans la page
                if($_SESSION['UserLevel']==Graphiste)
                {
                    $AfficheAdmin=0;
                }
                else
                {
                    $AfficheAdmin=1;
                }
                $moteur->assign('admin',$AfficheAdmin);
                
                //As t'on recu un formulaire de mise à jour ?
                if(isset($_POST['maj']))
                {
                    $moteur->assign('Display_popup','modal_show');
                    //On modifie
                    debug_tab($_POST,$Debug);
                    /*  */
                    $Id_Article=intval($_POST['id_article']);
                    $Nb_Page=intval($_POST['nb_page']);
                    debug_tab($_SESSION,$Debug);
                    $TabFich=array(':id'=>$Id_Article);
                    //On verifie si on a un fichier MEP
                  /*  $SQLS= $SQL_Get_MEP_Fichier='SELECT fichiers FROM t_mep WHERE id_article=:id';
                    
                    $row=$Conn->sql_fetch_all_prepared( $TabFich,$SQLS);
                   $PresenceFichier='CadreCache';
                    if(sizeof($row)>0)
                    {
                        $Fichier=$row[0]['fichiers'];
                        if($Fichier!='')
                            $PresenceFichier='';
                    }*/
                    $IdUser=$_SESSION['Utilisateur'];
                    //Vérifie si une MEP pour cet article est existante
                    $SQLS=$SQL_Existe_MEP;
                    $TabVal=array(':idArticle'=>$Id_Article);
                    $Conn->ExecProc($TabVal,$SQLS);
                    while($row=$Conn->sql_fetchrow())
                    {
                        $NbreMEP=$row['compte'];
                    }
                    //Si elle est existante, il faut faire juste une mise à jour 
                    if($NbreMEP>0)
                    {
                        $SQLS=$SQL_Mod_MEP;
                        $ActionHisto=Histo_Modif_MEP;
                    }
                    //Sinon, il faut la créer
                    else
                    {
                        $SQLS= $SQL_Add_MEP;   
                        $ActionHisto=Histo_Add_MEP;            
                    }
                    //On execute la requete pour la Mise en page 
                    $TabMEP=array(':user'=>$IdUser,':article'=>$Id_Article,':nbpages'=>$Nb_Page); 
                    $Resultat=$Conn->ExecProc($TabMEP,$SQLS);
                    //Historisation
                    $SQLS=$SQL_Add_Histo;
                    $Id_User=$_SESSION['Utilisateur'];
                    $TabHisto=array(':user'=>$Id_User, ':action'=>$ActionHisto, ':quoi'=>$Id_Article);
                    $Conn->ExecProc($TabHisto,$SQLS);
                    if( $Resultat==1) //Si on a bien ajouter ou modifié la MEP
                    {
                        //On bascule l'état de l'article à MEP
                        $SQLS=$SQL_SET_MEP_Article;
                        $Tab=array(':id_article'=>$Id_Article);
                        $Result=$Conn->ExecProc($Tab,$SQLS);
                        if($Result==1)
                        {
                            //Changement d'état OK
                            //Historisation
                            $SQLS=$SQL_Add_Histo;
                            $Id_User=$_SESSION['Utilisateur'];
                            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Article, ':quoi'=>$Id_Article);
                            $Conn->ExecProc($TabHisto,$SQLS);
                            $moteur->assign('Message','Enregistrement effectué correctement');
                        }
                        else
                        {
                            //Changement d'état pas OK
                            $moteur->assign('Message',"Mise en page enregistrée, un souci est arrivé sur la modification de l'article");
                        }
                        //Si il n'y avait pas de MEP, alors on avance.
                        //On ajoute l'avancement
                        //Récupération avancement article
                        if( $NbreMEP==0)
                        {
                            $SQLS=$SQL_Get_Fichier_Article;
                            $row=$Conn->sql_fetch_all_prepared( $TabFich,$SQLS);
                            $AvanceActuel=$row[0]['Avancee_Article'];
                            $Avancement=$AvanceActuel+Step_Article;
                            //Augmente l'avance 
                            $SQLS=$SQL_Update_Avance;
                            $TabAvance=array(':id'=>$Id_Article,':avance'=>$Avancement);
                            $Conn->ExecProc($TabAvance,$SQLS);
                        }
                    }
                    else
                    {
                        //Création ou modification MEP impossible
                        $moteur->assign('Message','Un souci est survenu : Erreur 0x03B');
                    }                             

                }
                //Fin mise à jour
                else
                {
                    $moteur->assign('Message','Euh pourquoi ?');
                    $moteur->assign('Display_popup','modal_hide'); 
                }
                //As t'on reçu un formulaire indiquant un nouvel article ?
                if(isset($_POST['num_art']))
                {
                    $Id_Article=intval($_POST['num_art']);
                    $_SESSION['Id_Article_Cours']= $Id_Article;
                }
                else
                {
                    //On récupère l'id de l'article dans la session
                    $Id_Article=$_SESSION['Id_Article_Cours'];
                }
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
                $_SESSION['Nom_Article']=$Titre;
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
                $PEC_Id=$row[0]['pec'];
                $FichierArticle=$row[0]['fichiers'];
                $PresenceFichierArticle=false;
                //Mettre en forme le chemin du fichier
                if($FichierArticle!='')
                {
                    $FichierArticle=$CheminBaseFichier.$CheminArticle.$FichierArticle;
                    $PresenceFichierArticle=true;
                }
                $moteur->assign('PresenceFichierArticle',$PresenceFichierArticle);
                $moteur->assign('Auteur',$Auteur);
                $moteur->assign('Titre',$Titre);
                $moteur->assign('Etat',$Etat_Article);
                $moteur->assign('Date_C',$DateCreation);
                $moteur->assign('Date_M',$DateModification);
                $moteur->assign('Desc',$Description);
                $moteur->assign('Verrouillage',$Verrou);
                $moteur->assign('TypeArticle',$TypeArticle);
                $moteur->assign('FichierArticle',$FichierArticle);
                //Information sur le verrouillage de l'article
                if( $Verrou==0)
                {
                    $CheminVerrou='lock.png';
                    $CheminCadenas="b_unlock.png";
                    $ActionVerrou="Verrouiller l'article";
                }
                else
                {
                    $CheminVerrou='unlock.png';
                    $CheminCadenas="b_lock.png";
                    $ActionVerrou="Déverrouiller l'article"; 
                }
                $moteur->assign('Cadenas',$CheminCadenas);
                $moteur->assign('Verrou',$CheminVerrou);
                $moteur->assign('ActionVerrou',$ActionVerrou);
                $moteur->assign('EtatVerrou',$Verrou);
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
                //Récupération de l'id etat de l'article
                $SQLS=$SQL_Get_State;
                $row=$Conn->sql_fetch_all_prepared($tabVal,$SQLS);
                $State=$row[0]['Etat_article'];
                if($State==Etat_Article_OK)
                {
                    $ImageCheck='unchecked';
                }
                else
                {
                    $ImageCheck='checked';
                }
                $moteur->assign('ImageCheck',$ImageCheck);
                $moteur->assign('Article_State',$State);
                $PresenceFichier='cacheDiv';
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
                        $PresenceFichier='';
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
                $moteur->assign('PresenceFichier',$PresenceFichier);
                $moteur->assign('NomGraph',$Graphiste);
                $moteur->assign('DateGraph',$MEP_Date);
                $moteur->assign('FichierMEP',$Fichier_MEP);
                $moteur->assign('Nb_Page',$Nombre_page);
                //Ajout 1.0B
                //On récupère les informations de photos
                $SQLS="SELECT nbre_fichiers, chemin_photo,photo_valide FROM t_photos WHERE fk_article=:id";
                $row=$Conn->sql_fetch_all_prepared($tabVal,$SQLS);
                Debug_tab($row,$Debug);

                if (isset($row[0]['photo_valide']))
                {
                    $PhotoValide=$row[0]['photo_valide'];
                    if($PhotoValide==1)
                    {
                        $NbPhoto=$row[0]['nbre_fichiers'];
                        $RepertoirePhoto=$row[0]['chemin_photo'];
                        $RepPhoto=$CheminBaseFichier.$CheminPhotos.$RepertoirePhoto.$Rep_Photo_Valide;

                    }
                    else
                    {
                        $RepPhoto='';
                    }
                }
                else
                    $RepPhoto='';
                $moteur->assign('rep_photo',$RepPhoto);
                //fin ajout 1.0B
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
                        debug_tab($v,$Debug);
                        $Nom=$v['nom'];
                        $Prenom=$v['prenom'];
                        $Tabtemp['Auteur']=$Prenom.' '.$Nom;
                        $DateMessage=$v['date_message'];
                        $MaDate=date_create($DateMessage);
                        //
                        $Tabtemp['Date_message']=date_format($MaDate, 'd/m/Y H:i:s');
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
                $Template="mep_article.tpl";
            }
            else
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