<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 01/07/2021                  */
    /* Dernière modification : 01/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //On initialise le moteur de template
    $moteur=new Smarty();
    if(!empty($_POST))
    {
        $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
        //On récupère le login et le mail de l'utilisateur
        $Login='';
        if(isset($_POST['login']))
        {
            $Login=htmlspecialchars($_POST['login']);
        }
        $mail='';
        if(isset($_POST['mail']))
        {
            $mail=htmlspecialchars($_POST['mail']);
        }
        $SQLS=$SQL_Login;
        $TabVal=array(':Lelogin'=>$Login);
        $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
        //Si on a une réponse
        if(!empty($row))
        {
            $v=$row[0];
            $B_Login=$v['login'];
            $B_Id=$v['id_utilisateur'];
            $B_Mail=$v['mail'];
            $Nom=$v['nom'];
            $Prenom=$v['prenom'];
            $Valide=$v['is_valable'];
            //On vérifie si le login et le mail collent et que l'utilisateur est valide
            if(($Login==$B_Login) and ($mail==$B_Mail) and ($Valide==1))
            {
                //OK
                //On reformate un nouveau mot de passe
                $NouveauPass=DefaultPassword;
                $Password= hash('sha512',($NouveauPass));
                $Corps="Bonjour $Prenom $Nom,\nUne demande de création de mot de passe pour votre compte a été effectuée sur la plateforme webzine Plastikdream.\n
                Le nouveau mot de passe est le suivant : $NouveauPass\n
                Nous vous invitons à vous connecter et changer ce mot de passe.\n
                Si vous n'êtes pas à l'initiative de cette demande, veuillez nous contacter à cette adresse $MailGestionnaire pour nous en faire part.\n
                Cordialement,\n
                L'équipe du webzine.";
                //Changement du mot de passe dans la base
                $SQLS=$SQL_Mod_MDP;
                //On connecte la base de données
                $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
                $TabVal=array(':id_user'=> $B_Id,':new_mdp'=>$Password);
                $Conn->ExecProc($TabVal,$SQLS);
                $row=$Conn->sql_fetchrow();
                $SQLS=$SQL_Get_result_MDP;
                $Conn->sql_query($SQLS);
                $row=$Conn->sql_fetchrow();
                if($row['ret']==1)
                {
                    //Mot de passe changé, on envoie le mail
                    //Récupération de l'adresse d'envoi
                    $SQLS=$SQL_Get_Param_Mail;
                    $row=$Conn->sql_fetch_all($SQLS);
                    $adresse=$row[0]['Value_Param_S'];
                    $Entete="From: $adresse \n";
                    $Entete.="Reply-to: $adresse\n";
                    $LeSujet='Réinitialisation mot de passe plateforme webzine';
                    if(@mail($B_Mail,$LeSujet,$Corps,$Entete))
                    {
                        //Mail envoyé";
                    }
                    else
                    {
                        //pas de mail envoyé';
                    }
                }
                else
                {
                    //Impossible de changer le mot de passe dans la base
                }
            }
            //Si cela ne colle pas
            else
            {
                //Login et adresse mail ne concordent pas
                
            }
        }
        //Si il n'y a pas d'utilisateur
        else
        {
            //Pas d'utilisateur
           
        }
        $Template='forgot2.tpl';
    }
    else
    {
        $Template='forgot1.tpl';
    }
    $moteur->display($CheminTpl.$Template);
    $moteur->display($CheminTpl.'footer.tpl');
?>