<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.4                        */
    /* Date création : 31/03/2021                  */
    /* Dernière modification : 27/04/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Pour l'utilisation de Excel
    require 'include/PhpSpreadsheet/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    //Démarrage de la session
    session_start();
    $Debug=false;
    function debug_tab($Tableau, $Toggle)
    {
       if($Toggle)
       {
           echo "<pre>";
        print_r($Tableau);
        echo "</pre>";
       }  
    }
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
        if( $_SESSION['UserLevel']==Admin_Système)  //administrateur
        {
            //As t'on reç un POST ?
            if(isset($_POST['integre']))
            {
                debug_tab($_POST,$Debug);
                debug_tab($_FILES,$Debug);
                //As t'on reçu un fichier ?
                if(isset($_FILES['fichier']['error']))
                {
                    $Erreur=$_FILES['fichier']['error'];
                    if($Erreur==0)
                    {
                        //On traite.
                        $fichier='integration.xlsx';
                        $DLFile=$_FILES['fichier']['tmp_name'];
                        $CheminUsager=$CheminBaseFichier.$fichier;
                        if(move_uploaded_file($_FILES['fichier']['tmp_name'],$CheminUsager))
                        {
                            //Ouverture du fichier excel
                            try
                            {
                                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($CheminUsager);
                                $workSheet = $spreadsheet->getActiveSheet();
                                //Lecture du fichier
                                //A2->E2
                                $highestRow = $workSheet->getHighestRow();
                                $TabUser=array();
                                $User=array('Prenom'=>'','Nom'=>'','Mail'=>'','Login'=>'','Rang'=>'');
                                for ($row = StartCell; $row <= $highestRow; $row++)
                                {
                                    $User['Prenom']= $workSheet->getCell(CellPrenom.$row)->getValue();
                                    $User['Nom']=$workSheet->getCell(CellNom.$row)->getValue();
                                    $User['Login']=$workSheet->getCell(CellLogin.$row)->getValue();
                                    $User['Mail']=$workSheet->getCell(CellMail.$row)->getValue();
                                    $User['Rang']=$workSheet->getCell(CellRang.$row)->getValue();
                                    if( ($User['Prenom']!='') and ($User['Nom']!='') and ($User['Login']!='') and ($User['Mail']!=''))
                                    {
                                        array_push($TabUser,$User);    
                                    }
                                    debug_tab($User,false);
                                }
                                debug_tab($TabUser,$Debug);
                                //On intègre à la base de données
                                $TabAjout=array();
                                $i=0;
                                foreach($TabUser as $equipe) 
                                {                   
                                    $Login=$equipe['Login'];
                                    $SQLS= $SQL_Login;
                                    $TabVal=array(':Lelogin'=>$Login);
                                    $Result=$Conn->ExecProc($TabVal,$SQLS);
                                    //Vérification si le login n'est pas dans la liste
                                    if($Result==0)
                                    {    
                                       // On normalise le rang, si jamais il y a un souci
                                        $Rang=$equipe['Rang'];
                                        switch($Rang)
                                        {
                                            case Administrateur :
                                                break;
                                            case Pigiste :
                                                break;
                                            case Graphiste:
                                                break;
                                            case Admin_Système :
                                                break;
                                            default : 
                                                $Rang=Pigiste;
                                                break;

                                        }
                                        $LePrenom=$equipe['Prenom'];
                                        $LePass=hash('sha512',(DefaultPassword));
                                        $LeMail=$equipe['Mail'];
                                        $leNom=$equipe['Nom'];
                                        $SQLS=$SQL_Add_User;
                                        $TabVal=array(':nom'=>$leNom,':prenom'=>$LePrenom,':login'=>$Login,':pass'=>$LePass,':rang'=>$Rang,':mail'=>$LeMail);
                                        $Result=$Conn->ExecProc($TabVal,$SQLS);
                                        if($Result==1)
                                        {
                                            //Envoi mail
                                            //Message de bienvenu au système
                                            $LeSujet='Bienvenue dans le Webzine PlastikDream';
                                            $dest=$LeMail;
                                            $MessageBienvenu="Bonjour $LePrenom $leNom\nVous avez été ajouter au système du webzine, accessible à cette adresse : http://editiel98.net/plastik \n
                                            Vous pouvez dorénavant vous y connecter avec le login : $Login fourni et le mot de passe qui vous a été attribué.\n
                                            Cependant, il vous est conseillé de changer celui-ci a votre première connexion en vous rendant dans mon compte.\n
                                            Pour toute question, n'hésitez pas à nous contacter sur le discord.\n
                                            Pour toute question technique ou bug du logiciel contacter moi à l'adresse suivante : gcregut@free.fr\n
                                            Maquettement votre,\nL'équipe Webzine";
                                            $SQLS=$SQL_Get_Param_Mail;
                                            $row=$Conn->sql_fetch_all($SQLS);
                                            $adresse=$row[0]['Value_Param_S'];
                                            $Entete="From: $adresse \n";
                                            $Entete.="Reply-to: $adresse\n";
                                            if(@mail($dest,$LeSujet,$MessageBienvenu,$Entete))
                                            {   
                                                $SQLS=$SQL_Add_Histo;
                                                $Id_User=$_SESSION['Utilisateur'];
                                                $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_EnvoiMail, ':quoi'=>$LeSujet.' '.$dest);
                                                $Conn->ExecProc($TabHisto,$SQLS);
                                            }
                                            //Historisation
                                            $SQLS=$SQL_Add_Histo;
                                            $Id_User=$_SESSION['Utilisateur'];
                                            $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_AjoutUser, ':quoi'=>$Login);
                                            $Conn->ExecProc($TabHisto,$SQLS);
                                            //Ajouter l'utilisateur au tableau résultat
                                            $TabAjout[$i]['Nom']=$LePrenom.' '.$leNom;
                                            $i++;
                                        } //Pas besoin de template
                                    } //Pas besoin de template, on est dans le foreach
                                }//Fin boucle foreach
                                //Affectation du tableau utilisateur
                                debug_tab($TabAjout,$Debug);
                                if (sizeof($TabAjout)>0)
                                    $moteur->assign('TabResult',$TabAjout);
                                $Template='integration_ok.tpl';
                            }//fin try
                            catch(Exception $e)
                            {
                               //pas de fichier correct ou erreur excel;
                                $moteur->assign('ErreurNum','0x03E');
                                $Template='erreur.tpl';
                            }
                            //Suppression du fichier
                            unlink($CheminUsager);
                        }
                        else
                        {
                            //On a pas put copîer le fichier
                            $moteur->assign('ErreurNum','0x03C');
                            $Template='erreur.tpl';
                        }
                    }
                    else
                    {
                        //On a eu une erreur de téléchargement
                        $moteur->assign('ErreurNum','0x03D');
                        $Template='erreur.tpl';
                    }
                }
                else
                {
                     //Il n'y a pas de fichier
                     $moteur->assign('ErreurNum','0x03D');
                     $Template='erreur.tpl';
                }
                //Au final
                $Template='integration_ok.tpl';
            }
            else
            {
                //Affichage de la page simplement
                $Template='integration.tpl';
            }
        }
        else
        {
            if($_SESSION['UserLevel']==Administrateur)
            {
                $Template='index_admin.tpl';
            }
            elseif($_SESSION['UserLevel']==Graphiste)
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