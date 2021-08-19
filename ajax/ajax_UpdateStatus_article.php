<?php
 /*version 0.5*/
 include "../include/config.inc.php";
 include "../include/db.inc.php";
 include "../include/sql.inc.php";
 //Démarrage de la session
 session_start();
 if(isset($_SESSION['login'])) 
 {
    if(isset($_POST['statut']))
    {
        $Id_Article=$_SESSION['Id_Article_Cours'];
        $NouvelEtat=intval($_POST['statut']);
        //On connecte la base de données
        $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
        //On récupère l'avancement de l'article
        $SQLS= $SQL_Get_Fichier_Article;
        $TabVar=array(':id'=>$Id_Article);
        $row=$Conn->sql_fetch_all_prepared($TabVar,$SQLS);
        $AvanceArticle=$row[0]['Avancee_Article']; 
        //On le calcule
        if($NouvelEtat==Etat_Article_OK)
        {
            $AvanceArticle+=Step_Article;
        }
        else
        {
            $AvanceArticle-=Step_Article;
        }
        //On effectue la requete
        $SQLS=$SQL_Change_Etat_Article_Final='CALL P_Mod_Etat_Article_Final(:id,:etat,:avance)';
        $TabVal=array(':id'=>$Id_Article, ':etat'=>$NouvelEtat,':avance'=>$AvanceArticle);
        $Resultat=$Conn->ExecProc($TabVal,$SQLS);
        $ValJSon=array();
        if($Resultat==1)
        {
            //On controle le nouvel état
            $SQLS=$SQL_Get_State_NameState='SELECT Etat_article, nom_etat FROM v_article_resume WHERE id_article=:id';
            $row=$Conn->sql_fetch_all_prepared($TabVar,$SQLS);
            $NouvelEtat=$row[0]['Etat_article'];
            $NouveauNom=$row[0]['nom_etat'];
            array_push($ValJSon,array('retour'=>1,'nouvelEtat'=>$NouvelEtat,'nouveauNom'=>$NouveauNom));
        }
       else
       {
           array_push($ValJSon,array('retour'=>$Resultat));
       }
       echo json_encode($ValJSon);
    }
 }
 ?>