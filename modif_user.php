<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0A                       */
    /* Date création : 01/04/2021                  */
    /* Dernière modification : 24/06/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
    $Debug=false;
    function debug_tab($Tableau, $Toggle)
    {
       if($Toggle)
       {
           echo "<pre>";
        print_r($Tableau);
        echo "</pre>";
       }  
    }
    //On initialise le moteur de template
    $moteur=new Smarty();
    //Connexion àla base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    if(isset($_SESSION['login']))
    {
        //On affiche le nom et prenom utilisateur
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        //On vérifie le niveau du membre
        if($_SESSION['UserLevel']==Admin_Système)  //administrateur
        {
            if(isset($_POST['id_user']))
            {
                $IdUser=intval($_POST['id_user']);
                //On récupère l'utilisateur dans la base de données et son rang et on affiche
                $SQLS=$SQL_Rang_User;
                $TabId=array(':id'=>$IdUser);
                $row=$Conn->sql_fetch_all_prepared($TabId,$SQLS);
                $Nom=$row[0]['nom'];
                $Prenom=$row[0]['prenom'];
                $Rang=$row[0]['rang'];
                $TabRangs=array();
                $TabRangs[0]['Valeur']=Administrateur;
                $TabRangs[0]['checked']='';
                $TabRangs[0]['Nom']='Rédacteur en chef';

                $TabRangs[1]['Valeur']=Pigiste;
                $TabRangs[1]['checked']='';
                $TabRangs[1]['Nom']='Rédacteur';

                //Ajout 1.0
                $TabRangs[2]['Valeur']=Relecteur;
                $TabRangs[2]['checked']='';
                $TabRangs[2]['Nom']='Relecteur';
                //fin ajout
                $TabRangs[3]['Valeur']=Graphiste;
                $TabRangs[3]['checked']='';
                $TabRangs[3]['Nom']='Graphiste';

                $TabRangs[4]['Valeur']=Admin_Système;
                $TabRangs[4]['checked']='';
                $TabRangs[4]['Nom']='Administrateur système';
                for($i=0;$i<sizeof($TabRangs);$i++)
                {
                    if ($TabRangs[$i]['Valeur']==$Rang)
                        $TabRangs[$i]['checked']='selected';
                }
                $moteur->assign('TabRang', $TabRangs);
                $moteur->assign('Nom',$Prenom.' '.$Nom);
                $moteur->assign('Id_User', $IdUser);
                $Template='modif_user.tpl';
            }
            //On a reçu les informations a modifier
            elseif(isset($_POST['modif']))
            {
                //Traitement
                debug_tab($_POST,$Debug);
                $IdUser=intval($_POST['modif']);
                $NvoRang=intval($_POST['new_rang']);
                //On modifie l'utilisateur
                $SQLS= $SQL_Mod_Rang;
                $TabVal=array(':id'=>$IdUser,':rang'=>$NvoRang);
                debug_tab($TabVal,$Debug);
                if ($Debug)
                    echo $SQLS;
                $Resultat=$Conn->ExecProc($TabVal,$SQLS);
                if($Resultat==1)
                {
                    $Texte='Utilisateur modifié';
                    //Historisation
                    $SQLS=$SQL_Add_Histo;
                    $Id_User=$_SESSION['Utilisateur'];
                    $TabHisto=array(':user'=>$Id_User, ':action'=>Histo_Modif_User, ':quoi'=>$IdUser);
                    $Conn->ExecProc($TabHisto,$SQLS);
                }
                else
                {
                    $Texte='La modification à échouée';
                }
                $moteur->assign('LeTexte',$Texte);
                $Template='modif_user_ok.tpl';
            }
            else
            {
                //On est arrivé là par erreur
                $moteur->assign('ErreurNum','0x010');
                $Template='erreur.tpl';
            }
        }
        else
        {
            if($_SESSION['UserLevel']==Graphiste)
            {
                    //Traitement graphiste
                    $Template='index_graphiste.tpl';
            }
            else
            {
                    //Traitement pigiste
                    $Template='index_pigiste.tpl';
            }
        
        }
        $moteur->display($CheminTpl.$Template);
        $moteur->display($CheminTpl.'footer.tpl');
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
?>