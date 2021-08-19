<?php
 /*version 0.7*/
 include "../include/config.inc.php";
 include "../include/db.inc.php";
 include "../include/sql.inc.php";
 //Démarrage de la session
 session_start();
 if(isset($_SESSION['login'])) 
 {
     if (isset($_POST['auteur']) and isset($_POST['texte_message']))
     {
         $Id_Usager=intval($_POST['auteur']);
         $Id_Article=$_SESSION['Id_Article_Cours'];
         $LeMessage=htmlspecialchars($_POST['texte_message'],ENT_NOQUOTES,'UTF-8');
         //On connecte la base de données
         $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
         //On effectue la requete
         $SQLS=$SQL_Add_Message_Redaction;
         $TabVal=array(':auteur'=>$Id_Usager,':article'=>$Id_Article,':message'=>$LeMessage);
         $Resultat=$Conn->ExecProc($TabVal,$SQLS);
         $ValJSon=array();
         if($Resultat==1)
         {
            $SQLS=$SQL_Get_Id_Last_Message;
            $Conn->sql_query($SQLS);
           while ($row=$Conn->sql_fetchrow())
           {
               $Id_Message=$row['id'];
           }
           //Récupération des informations du message
           $SQLS= $SQL_Get_Message_redaction_By_Id;
           $Tabvar=array(':id'=>$Id_Message);
           $row=$Conn->sql_fetch_all_prepared($Tabvar,$SQLS);
           foreach ($row as $v)
           {
            $ValJSon['retour']=1;
                $Nom=$v['nom'];
                $Prenom=$v['prenom'];
                $ValJSon['Auteur']=$Prenom.' '.$Nom;
                $DateMessage=$v['date_message'];
                $DateMessage = date("d/m/Y H:m", strtotime($DateMessage));
                $ValJSon['Date_message']=$DateMessage;
                $Message=$v['corps'];
                $ValJSon['corps']=nl2br($Message);
           }
           //Envoi un mail aux admin, auteur et les graphistes
           //Récupération de l'adresse mail de l'auteur de l'article :
           $SQLS=$SQL_Get_Mail_Article_Author;
           $TabId=array(':id'=>$Id_Article);
           $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
           $Destinataires='';
           $TitreArticle='';
           $Destinataires.=$row[0]['mail'].', ';
           $TitreArticle=$row[0]['titre_article'];
           //Récupération des mails graphistes et admin
           $SQLS=$SQL_Get_Mail_Admin_Graphiste;
           $TabRang=array(':rang1'=>Administrateur,':rang2'=>Graphiste);
           $row=$Conn->sql_fetch_all_prepared($TabRang,$SQLS);
           foreach($row as $v)
           {
                //Suppression de l'auteur dans la liste
                if($v['id_utilisateur'] !=$Id_Usager)
                    $Destinataires.=$v['mail'].', ';
               
           }
           //On supprime les 2 derniers caractères, inutiles
           $Destinataires=rtrim($Destinataires,", ");
           $Sujet='Webzine : Espace Mise en page';
           $Message="Bonjour,\nVous avez reçu un message dans l'espace mise en page de l'article '$TitreArticle' de la plateforme du webzine\n
           Vous pouvez vous connecter à la plateforme pour le consulter.\nL'équipe du webzine\n$AdresseSite";
           //Récupération de l'expéditeur
           $SQLS=$SQL_Get_Param_Mail;
           $row=$Conn->sql_fetch_all($SQLS);
           $adresse=$row[0]['Value_Param_S'];
           $Entete="From: $adresse \n";
           $Entete.="Reply-to: $adresse\n";
           //Envoi du mail
           if(@mail($Destinataires,$Sujet,$Message,$Entete))
           {
               //Historisation
               $SQLS=$SQL_Add_Histo;
               $Id_User=$_SESSION['Utilisateur'];
               $TabHisto=array(':user'=>$Id_Usager, ':action'=>Histo_EnvoiMail, ':quoi'=>$Sujet);
               $Conn->ExecProc($TabHisto,$SQLS);
           }
           //
          
        }
        else
        {
            array_push($ValJSon,array('retour'=>0));
        } 
        echo json_encode($ValJSon); 
     }
 }
?>