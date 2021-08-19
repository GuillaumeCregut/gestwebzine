<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.4                        */
    /* Date création : 26/03/2021                  */
    /* Dernière modification : 27/04/2021          */
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
     //Connexion àla base de données
     $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
     //Connexion secondaire pour les sous requêtes
     $Conn2=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
         //On affiche le nom et prenom utilisateur
         $NomUser=$_SESSION['Nom_User'];
         $PrenomUser=$_SESSION['Prenom_User'];
         $nom_Prenom=$PrenomUser.' '.$NomUser;
         $moteur->assign('PrenomLogin',$nom_Prenom);
        //On vérifie le niveau du membre
        if( ($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système))  //administrateur
        {
            //As t'on reçu un formulaire ?
            if(isset($_POST['nom']))
            {
                $LeNom=htmlspecialchars($_POST['nom'],ENT_NOQUOTES,'UTF-8');
                $LaDate=htmlspecialchars($_POST['date_parution'],ENT_NOQUOTES,'UTF-8');
                //On ajoute le webzine
                $SQLS=$SQL_Add_Webzine;
                $TabAdd=array(':titre'=>$LeNom,':date_p'=>$LaDate);
                $Conn->ExecProc($TabAdd,$SQLS);
                //On historise
                    $SQLS=$SQL_Add_Histo;
                    $Id_User=$_SESSION['Utilisateur'];
                    $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Ajout_Webzine, ':quoi'=>$LeNom);
                    $Conn->ExecProc($TabHisto,$SQLS);
            }
            //On récupère les infos de la base et on les affiche
            $Template="all_webzine.tpl";
            //Si on a reçu un formulaire d'ajout
            if(isset($_POST['nom']))
            {
                $LeNom=htmlspecialchars($_POST['nom'],ENT_NOQUOTES,'UTF-8');
                $LaDate=htmlspecialchars($_POST['date_parution'],ENT_NOQUOTES,'UTF-8');
            }
            //On se connecte à la base de données pour récupérer les webzines non archivés
            $SQLS=$SQL_All_Webzine;
            $Conn->sql_query($SQLS);
            $i=0;
            $TabWebzine=array();
            while($row=$Conn->sql_fetchrow())
            {
                $TabWebzine[$i]['id_webzine']=$row['id_webzine'];
                $TabWebzine[$i]['titre']=$row['Titre_Webzine'];
                $Etat=$row['Etat'];
                $LaClasse='';
                switch($Etat)
                {
                    case Etat_Webzine_Vierge:
                        $LaClasse='Vierge';
                        break;
                    case Etat_Webzine_En_Cours:
                        $LaClasse='EnCours';
                        break;
                    case Etat_Webzine_Termine:
                        $LaClasse='Termine';
                        break;
                    case Etat_Webzine_Archive:
                        $LaClasse='Archive';
                        break;
                }
                $TabWebzine[$i]['Class_Etat']=$LaClasse;
                $TabWebzine[$i]['Etat']=$row['nom_etat_webzine'];
                $DateParution=$row['Date_Parution'];
                $Madate = new DateTime($DateParution);
                $DateParution=$Madate->format('d/m/Y');
                $TabWebzine[$i]['Date_Parution']=$DateParution;
                $Id_Webzine=$row['id_webzine'];
                $SQLS2=$SQL_Count_Article_Webzine;
                $TabVal=array(':id_webzine'=>$Id_Webzine);
                $Conn2->ExecProc($TabVal, $SQLS2);
                while($row2=$Conn2->sql_fetchrow())
                {
                    $Compte=$row2['Compte'];
                }
                $TabWebzine[$i]['Compte_Article']=$Compte;
                $i++;
            }
            if(sizeof($TabWebzine)>0)
            {
                $moteur->assign('TabZine', $TabWebzine);
                //On pousse les données dans la page
            }
            
        }
        else
        {
            if($_SESSION['UserLevel']==Pigiste)
                $Template='index_pigiste.tpl';
            else
                $Template='index_graphiste.tpl';
        }
        //On affiche la page
        $moteur->display($CheminTpl.$Template);
        $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?>