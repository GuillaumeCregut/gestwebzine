<?php
 /*version 0.5*/
 include "../include/config.inc.php";
 include "../include/db.inc.php";
 include "../include/sql.inc.php";
 //Démarrage de la session
 session_start();
 if(isset($_SESSION['login'])) 
 {
    if(isset($_POST['etat']))
    {
        $Id_Article=$_SESSION['Id_Article_Cours'];
        $NouvelEtat=intval($_POST['etat']);
        //On connecte la base de données
        $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
        //On effectue la requete
        $SQLS=$SQL_Change_Verrrouillage;
        $TabVal=array(':id'=>$Id_Article, ':etat'=>$NouvelEtat);
        $Resultat=$Conn->ExecProc($TabVal,$SQLS);
        $ValJSon=array();
        if($Resultat==1)
        {
            //On controle le nouvel état
            $SQLS=$SQL_Get_Verrouillage;
            $TabVar=array(':id'=>$Id_Article);
            $row=$Conn->sql_fetch_all_prepared($TabVar,$SQLS);
            $NouvelEtat=$row[0]['art_locked'];
            array_push($ValJSon,array('retour'=>1,'nouvelEtat'=>$NouvelEtat));
        }
       else
       {
           array_push($ValJSon,array('retour'=>$Resultat));
       }
       //On envoie le résulat
       echo json_encode($ValJSon);
    }
 }
 ?>