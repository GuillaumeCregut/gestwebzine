<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0                        */
    /* Date création : 03/07/2021                  */
    /* Dernière modification : 03/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "../include/smarty.class.php";
    include "../include/db.inc.php";
    include "sql.inc.php";
    //On initialise le moteur de template
    $moteur=new Smarty();
    $Erreur=false;
    //Connexion àla base de données
    
    if(!empty($_POST))
    {
        if(isset($_POST['login_base']))
        {
            $DataBaseUser=htmlspecialchars($_POST['login_base'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        if(isset($_POST['pass_base']))
        {
            $DataBasePass=htmlspecialchars($_POST['pass_base'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        if(isset($_POST['serveur_base']))
        {
            $DataBaseServeur=htmlspecialchars($_POST['serveur_base'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        if(isset($_POST['nom_base']))
        {
            $DataBaseName=htmlspecialchars($_POST['nom_base'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        //Gestion administrateur
        if(isset($_POST['nom_admin']))
        {
            $Nom_Admin=htmlspecialchars($_POST['nom_admin'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        if(isset($_POST['prenom_admin']))
        {
            $Prenom_Admin=htmlspecialchars($_POST['prenom_admin'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        if(isset($_POST['admin_login']))
        {
            $Login_Admin=htmlspecialchars($_POST['admin_login'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        if(isset($_POST['admin_pass']))
        {
            $Pass_Admin=htmlspecialchars($_POST['admin_pass'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        if(isset($_POST['admin_mail']))
        {
            $Mail_Admin=htmlspecialchars($_POST['admin_mail'],ENT_NOQUOTES,'UTF-8');
        }
        else
        {
            $Erreur=true;
        }
        //Traitement
        if(!$Erreur)
        {
            try
            {
                //$Conn=new connect_base(,,,);
                $Conn=new PDO("mysql:host=$DataBaseServeur; dbname=$DataBaseName", $DataBaseUser, $DataBasePass);
                $Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //On va lancer les requêtes de création des tables
                $SQLS=$SQL_Action;
                $Conn->exec($SQLS);
                echo "<p>Table t_action : passe</p>";
                $SQLS=$SQL_Etat;
                $Conn->exec($SQLS);
                echo "<p>Table t_etat : passe</p>";
                $SQLS=$SQL_Param;
                $Conn->exec($SQLS);
                echo "<p>Table t_Param : passe</p>";
                $SQLS=$SQL_Type;
                $Conn->exec($SQLS);
                echo "<p>Table t_type : passe</p>";
                $SQLS=$SQL_User;
                $Conn->exec($SQLS);
                echo "<p>Table t_utilisateurs : passe</p>";
                $SQLS=$SQL_Etat_Web;
                $Conn->exec($SQLS);
                echo "<p>Table t_etat_webzine : passe</p>";
                $SQLS=$SQL_Webzine;
                $Conn->exec($SQLS);
                echo "<p>Table t_webzine : passe</p>";
                $SQLS=$SQL_Article;
                $Conn->exec($SQLS);
                echo "<p>Table t_article : passe</p>";
                $SQLS=$SQL_Espace_Travail;
                $Conn->exec($SQLS);
                echo "<p>Table t_Espace_Travail : passe</p>";
                $SQLS=$SQL_Histo;
                $Conn->exec($SQLS);
                echo "<p>Table t_Histo : passe</p>";
                $SQLS=$SQL_MEP;
                $Conn->exec($SQLS);
                echo "<p>Table t_MEP : passe</p>";
                $SQLS=$SQL_Message_Espace;
                $Conn->exec($SQLS);
                echo "<p>Table t_Message_Espace : passe</p>";
                $SQLS=$SQL_MEssage_Redac;
                $Conn->exec($SQLS);
                echo "<p>Table t_Message_Redac : passe</p>";
                $SQLS=$SQL_Usager_Espace;
                $Conn->exec($SQLS);
                echo "<p>Table t_Usager_Espace : passe</p>";
                $SQLS=$SQL_Photo;
                $Conn->exec($SQLS);
                echo "<p>Table t_photos : passe</p>";
                //On modifie les tables
                $SQLS= $SQL_Alter_1;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_webzine : passe</p>";
                $SQLS= $SQL_Alter_2;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_usagers_espace : passe</p>";
                $SQLS= $SQL_Alter_3;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_photos : passe</p>";
                $SQLS= $SQL_Alter_4;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_message_redaction : passe</p>";
                $SQLS= $SQL_Alter_5;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_message_espace : passe</p>";
                $SQLS= $SQL_Alter_6;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_articles : passe</p>";
                $SQLS= $SQL_Alter_7;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_espace_travail : passe</p>";
                $SQLS= $SQL_Alter_8;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_histo : passe</p>";
                $SQLS= $SQL_Alter_9;
                $Conn->exec($SQLS);
                echo "<p>Modification Table t_mep : passe</p>";
        //On va créer les procédures stockées
                $SQLS=$SQL_Proc_P_Add_Article;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Article : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Espace_Article;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Espace_Article : passe</p>";
                $SQLS=$SQL_Proc_P_Add_File_MEP;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_File_MEP : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Histo;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Histo : passe</p>";
                $SQLS=$SQL_Proc_P_Add_MEP;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_MEP : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Message_Espace;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Message_Espace : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Message_Redaction;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Message_Redaction : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Param_D;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Param_D : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Param_I;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Param_I : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Param_S;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Param_S : passe</p>";
                $SQLS=$SQL_Proc_P_Add_Type;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_Type : passe</p>";
                $SQLS=$SQL_Proc_P_Add_User;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_User: passe</p>";
                $SQLS=$SQL_Proc_P_Add_User_Espace;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_User_Espace : passe</p>";
                $SQLS=$SQL_Proc_P_Add_WebZine;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Add_WebZine : passe</p>";
                $SQLS=$SQL_Proc_P_Affecte_Article_Webzine;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Affecte_Article_Webzine : passe</p>";
                $SQLS=$SQL_Proc_P_Archive_Article;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Archive_Article : passe</p>";
                $SQLS=$SQL_Proc_P_Change_Verrouillage;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Change_Verrouillage : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Article_Light;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Article_Light : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Date_Webzine;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Date_Webzine : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Etat_Article_Final;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Etat_Article_Final : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Etat_Webzine;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Etat_Webzine : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Fichier_Article;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Fichier_Article : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_File_MEP;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_File_MEP : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_MDP;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_MDP : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_MEP;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_MEP : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Nom_Webzine;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Nom_Webzine : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Param_D;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Param_D : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Param_I;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Param_I : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Param_S;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Param_S : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_PEC;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_PEC : passe</p>"; 
                $SQLS=$SQL_Proc_P_Mod_Rang_User;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Rang_User : passe</p>";
                $SQLS=$SQL_Proc_P_Mod_Valide_User;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Mod_Valide_User : passe</p>";
                $SQLS=$SQL_Proc_P_Remove_Usager_Espace;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Remove_Usager_Espace : passe</p>";
                $SQLS=$SQL_Proc_P_Set_Article_MEP;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Set_Article_MEP : passe</p>";
                $SQLS=$SQL_Proc_P_Update_Avance;
                $Conn->exec($SQLS);
                echo "<p>Création procédure P_Update_Avance : passe</p>"; 
         //On va créer les vues
               $SQLS=$SQL_V_Art_MEP;
                $Conn->exec($SQLS);
                echo "<p>Création vue Article_MEP : passe</p>";
                $SQLS=$SQL_V_Art_Modif;
                $Conn->exec($SQLS);
                echo "<p>Création vue Article_Modif : passe</p>";
                $SQLS=$SQL_V_Art_Res;
                $Conn->exec($SQLS);
                echo "<p>Création vue Article_Resume : passe</p>";
                $SQLS=$SQL_V_Art_Web;
                $Conn->exec($SQLS);
                echo "<p>Création vue Article_Webzine : passe</p>";
                $SQLS=$SQL_V_Histo;
                $Conn->exec($SQLS);
                echo "<p>Création vue Histo : passe</p>";
                $SQLS=$SQL_V_Mail_Art;
                $Conn->exec($SQLS);
                echo "<p>Création vue Mail_Article : passe</p>";
                $SQLS=$SQL_V_Mail_Auteur;
                $Conn->exec($SQLS);
                echo "<p>Création vue Mail_Auteur : passe</p>";
                $SQLS=$SQL_V_Aff_Redac;
                $Conn->exec($SQLS);
                echo "<p>Création vue Affiche_Redaction : passe</p>";
                $SQLS=$SQL_V_MEP_Redac;
                $Conn->exec($SQLS);
                echo "<p>Création vue MEP_Redaction : passe</p>";
                $SQLS=$SQL_V_Mess_Esp;
                $Conn->exec($SQLS);
                echo "<p>Création vue Message_Espace : passe</p>";
                $SQLS=$SQL_V_Mess_Redac;
                $Conn->exec($SQLS);
                echo "<p>Création vue Message_Redaction : passe</p>";
                $SQLS=$SQL_V_Nb_Page;
                $Conn->exec($SQLS);
                echo "<p>Création vue Nbre_pages : passe</p>";
                $SQLS=$SQL_V_Relance;
                $Conn->exec($SQLS);
                echo "<p>Création vue Relance : passe</p>";
                $SQLS=$SQL_V_User_Esp;
                $Conn->exec($SQLS);
                echo "<p>Création vue Usager_Espace : passe</p>";
                $SQLS=$SQL_V_User_Art;
                $Conn->exec($SQLS);
                echo "<p>Création vue Usager_Article : passe</p>";
                $SQLS=$SQL_V_Web;
                $Conn->exec($SQLS);
                echo "<p>Création vue Webzine : passe</p>";
                $SQLS=$SQL_V_Art_Redac;
                $Conn->exec($SQLS);
                echo "<p>Création vue Article_Redaction : passe</p>";
                //Une fois que tout est créé, on ajoute les valeurs par défaut
                foreach($Tab_Action as $v)
			    {
                    $SQLS=$v;
                    $Conn->exec($SQLS);
                    echo "Insertion action OK<br>";
			    }
                foreach($Tab_Etat as $v)
			    {
                    $SQLS=$v;
                    $Conn->exec($SQLS);
                    echo "Insertion état OK<br>";
			    }
                foreach($TabWebzine as $v)
			    {
                    $SQLS=$v;
                    $Conn->exec($SQLS);
                    echo "Insertion Etat Webzine OK<br>";
			    }
                foreach($TabParam as $v)
			    {
                    $SQLS=$v;
                    $Conn->exec($SQLS);
                    echo "Insertion paramètres OK<br>";
			    }
                foreach($TabType as $v)
			    {
                    $SQLS=$v;
                    $Conn->exec($SQLS);
                    echo "Insertion type article OK<br>";
			    }
                //On créé le fichier de connexion
                //Il faudra changer le nom par $NomFichier='../include/connecteur.inc.php';
                $NomFichier='../include/connecteur.inc.php';
                $fichier=fopen($NomFichier,'w');
                if($fichier)
                {
                    fwrite($fichier,'<?php'."\r\n");
                    fwrite($fichier,"\$DataBaseName = $DataBaseName';"."\r\n");
                    fwrite($fichier,"\$DataBaseUser = '$DataBaseUser';"."\r\n");
                    fwrite($fichier,"\$DataBasePass = '$DataBasePass';"."\r\n");
                    fwrite($fichier,"\$DataBaseServeur='$DataBaseServeur';"."\r\n");
                    fwrite($fichier,'?>');
                    fclose($fichier);
                    echo "<p>Création du fichier de connexion OK</p>";
                }
                else
                {
                    echo "<p>Création du fichier de connexion impossible</p>";
                }
                //On ajoute l'admin à la base
                //On ajoute dans la base de données
                $SQLS=$SQL_Add_User;
                $sth=$Conn->prepare($SQLS);
                $TabVal=array(':nom'=>$Nom_Admin,':prenom'=>$Prenom_Admin,':login'=>$Login_Admin,':pass'=>$Pass_Admin,':rang'=>4,':mail'=>$Mail_Admin);
                $Result=$sth->execute($TabVal);
                if($Result)
                {
                    echo "<p>Ajout administrateur OK</p>";
                }
            }
            catch (Exception $e)
            {
                $moteur->assign('Erreur',$e->getMessage());
                $moteur->display('install3.tpl');
                die();
            }
                $moteur->display('install2.tpl');
        }
        else
        {
            //Erreur, le formulaire n'est pas complet
            $moteur->assign('Erreur : Formulaire erroné');
            $moteur->display('install3.tpl');
        }
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display('install1.tpl');
    }
?>