<?php
    /*version 0.3*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    //Démarrage de la session
    session_start();
    if(isset($_SESSION['login'])) 
    {
        if (isset($_POST['id_Usager'])and isset($_POST['cible']) and isset($_POST['Espace']))
        {
            $Id_Usager=intval($_POST['id_Usager']);
            $LaCible=intval($_POST['cible']);
            $Lespace=intval($_POST['Espace']);
                //On connecte la base de données
            $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
            //On effectue la requete
            if($LaCible==0)
            {
                //On supprime le membre de la liste
                $SQLS=$SQL_Remove_User_Space;
            }
            else
            {
                //On ajoute le membre à la liste
                $SQLS=$SQL_Add_User_Space;
            }
            $TabVal=array(':usager'=>$Id_Usager,':espace'=>$Lespace);
            $Resultat=$Conn->ExecProc($TabVal,$SQLS);
            $ValJSon=array();
            array_push($ValJSon,array('retour'=>$Resultat));
            echo json_encode($ValJSon);
        }
    }
?>