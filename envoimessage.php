<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v0.4                        */
    /* Date création : 25/03/2021                  */
    /* Dernière modification : 27/04/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    $Debug = false;
    //On initialise le moteur de template
    $moteur=new Smarty();
    //Connexion àla base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        if(isset($_POST['sujet']))
        {
            $LeSujet=$_POST['sujet'];
            $LeMessage=$_POST['message'];
            $ChaineDestinataires=$_POST['destinataires'];
            $TabDestinataires=explode(';',$ChaineDestinataires); //attention la derniere est vide
            $dest='';
            //On parcourt la liste des destinataires
            $SQLS=$SQL_Envoi_Mail;
            $TabVal=array();
            foreach( $TabDestinataires as $v)
            {
                if($v!='')
                {
                    $TabVal[':idUser']=$v;
                    $Conn->ExecProc($TabVal,$SQLS);
                    $row=$Conn->sql_fetchrow();
                    $LeMail=$row['mail'];
                    $dest.=$LeMail.', ';
                }
            }
            //Inscription de l'emetteur dans le sujet du mail
            $LeSujet="Webzine Plastikdream. Message de : $nom_Prenom : ". $LeSujet;
            //On supprime les 2 derniers caractères, inutiles
            $dest=rtrim($dest,", "); 
            //Récupération de l'adresse d'envoi
            $SQLS=$SQL_Get_Param_Mail;
            $row=$Conn->sql_fetch_all($SQLS);
            $adresse=$row[0]['Value_Param_S'];
            if(!$Debug)
            {
                $Entete="MIME-Version: 1.0\r\n";
                $Entete.="Content-type: text/html; charset=iso-8859-1\r\n";
                $Entete.="TO: $dest\r\n";
                $Entete.="From: $adresse\r\n";
                $Entete.="Reply-to: $adresse\r\n";
            }
           /* 
            $Entete.="MIME-Version: 1.0\n";
            $Entete.="Content-type: text/html; charset=iso-8859-1";*/
            //Essai temporaire
            else
            {
                $TabTest[] = 'MIME-Version: 1.0';
                $TabTest[] = 'Content-type: text/html; charset=iso-8859-1';
                $TabTest[] ="TO: $dest";
                $TabTest[]="From: $adresse";
                $TabTest[].="Reply-to: $adresse";
                $Entete=implode("\r\n",$TabTest);
            }
            if(@mail($dest,$LeSujet,$LeMessage,$Entete))
           // if(@mail($dest,$LeSujet,$LeMessage,$Entete))
            {
                //Historisation
                $SQLS=$SQL_Add_Histo;
                $Id_User=$_SESSION['Utilisateur'];
                $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_EnvoiMail, ':quoi'=>$LeSujet);
                $Conn->ExecProc($TabHisto,$SQLS);
                $moteur->display($CheminTpl.'mail_ok.tpl');
                $moteur->display($CheminTpl.'footer.tpl');
            }
            else
            {
               $moteur->display($CheminTpl.'mail_erreur.tpl');
               $moteur->display($CheminTpl.'footer.tpl');
            }
        }
           
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?>