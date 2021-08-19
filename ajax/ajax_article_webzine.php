<?php
    /*version 0.2*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    //Démarrage de la session
    session_start();
    if(isset($_SESSION['login'])and (($_SESSION['UserLevel']==Administrateur) or ($_SESSION['UserLevel']==Admin_Système))) //A mettre après tests
    {
        if (isset($_POST['id_Article'])and isset($_POST['cible']))
        {
            $Id_Article=intval($_POST['id_Article']);
            $LaCible=intval($_POST['cible']);
            if(isset($_SESSION['id_webzine']))
            {
                $Id_Webzine=$_SESSION['id_webzine'];
                if($LaCible==0)
                {
                    $Id_Webzine=1;
                    $StepAjout=0-Step_Article;
                }
                else
                {
                    $StepAjout=Step_Article;
                }
                  //On connecte la base de données
                $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
                //On effectue la requete
                $SQLS=$SQlAffecte_Article_Webzine;
                $TabVal=array(':id_article'=>$Id_Article,':id_webzine'=>$Id_Webzine);
                $Resultat=$Conn->ExecProc($TabVal,$SQLS);
                if($Resultat==1)
                {
                    //Historisation
                    $SQLS=$SQL_Add_Histo;
                    $Id_User=$_SESSION['Utilisateur'];
                    $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_Webzine, ':quoi'=>$Id_Webzine);
                    $Conn->ExecProc($TabHisto,$SQLS);
                    //Modifie le step d'un article
                    $SQLS=$SQL_Get_Avancement;
                    $TabVar=array(':id'=>$Id_Article);
                    $row=$Conn->sql_fetch_all_prepared($TabVar,$SQLS);
                    $Avance=$row[0]['Avancee_Article'];
                    $Avance+=$StepAjout;
                    $SQLS=$SQL_Update_Avance;
                    $TabAvance=array(':id'=>$Id_Article,':avance'=>$Avance);
                    $Conn->ExecProc($TabAvance,$SQLS);
                    
                }
                $ValJSon=array();
                array_push($ValJSon,array('retour'=>$Resultat));
                echo json_encode($ValJSon);
            }
            
        }
    }
?>