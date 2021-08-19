<?php
    /*version 0.2*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    //Démarrage de la session
    session_start();
    if(isset($_SESSION['login'])and (($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système))) //A mettre après tests
    {
        if (isset($_POST['id_user']))
        {
            //On récupère les infos du post
            $Id_User=intval($_POST['id_user']);
            $Action=intval($_POST['action']);
             //On connecte la base de données
            $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
            //On effectue la requete
            $SQLS=$SQL_Valide;
            $TabVal=array(':user'=>$Id_User,':action'=>$Action);
            $Conn->ExecProc($TabVal,$SQLS);
            $row=$Conn->sql_fetchrow();
            $ValJSon=array();
            if($row['ret']==1)
            {
                //OK, on peut renvoyer pour traitement
                $Valretour=1;
                //Historisation
                $SQLS=$SQL_Add_Histo;
                $Id_User=$_SESSION['Utilisateur'];
                $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_User, ':quoi'=>$Id_User);
                $Conn->ExecProc($TabHisto,$SQLS);
            }
            else
            {
                //Erreur
                $Valretour=0;
            }
            array_push($ValJSon,array('retour'=>$Valretour));
            echo json_encode($ValJSon);
        }
    } // A mettre après tests
?>