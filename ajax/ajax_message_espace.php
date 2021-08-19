<?php
    /*version 0.3*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    //Démarrage de la session
    session_start();
    if(isset($_SESSION['login'])) 
    {
        if (isset($_POST['auteur'])and isset($_POST['EspaceId']) and isset($_POST['texte_message']))
        {
            $Id_Usager=intval($_POST['auteur']);
            $Id_Space=intval($_POST['EspaceId']);
            $LeMessage=htmlspecialchars($_POST['texte_message'],ENT_NOQUOTES,'UTF-8');
            //On regarde si  on a un fichier
            if(isset($_FILES['fichier']['error']))
            {
               $Erreur=$_FILES['fichier']['error'];
               echo "<p>Passe 1 : $Erreur</p>";
               if(($Erreur!=0) and ($Erreur!=4))
               {
                    //On a eu un problème avec le fichier 
    
               }
               else
               {
                   //On charge le fichier
                   if($Erreur==0)
                   {
                    $NomFichier= $Id_Usager.'-'.date('d-m-Y_H-m-s').'.zip';  
                    $CheminFichier=$CheminBaseFichier.$CheminEspace.$Id_Space.'/'.$NomFichier;
                    if(move_uploaded_file($_FILES['fichier']['tmp_name'],$CheminFichier))
                    {
                        $LeFichier=$NomFichier;
                    }
                    else
                        $LeFichier='';
                   }
                   else
                        $LeFichier='';
               }
            }    
            //On connecte la base de données
            $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
            //On effectue la requete
            $SQLS=$SQL_Add_Message_Espace;
            $TabVal=array(':auteur'=>$Id_Usager,':espace'=>$Id_Space,':message'=>$LeMessage,':fichier'=>$LeFichier);
            $Resultat=$Conn->ExecProc($TabVal,$SQLS);
            $ValJSon=array();
            array_push($ValJSon,array('retour'=>$Resultat));
            echo json_encode($ValJSon);
        }
    }
?>