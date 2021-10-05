<?php
    /* * * * * * * * * * * * * * * * * * * * * * * */
    /* Gestion Webzine v1.0B                       */
    /* Date création : 02/07/2021                  */
    /* Dernière modification : 02/07/2021          */
    /* * * * * * * * * * * * * * * * * * * * * * * */
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    $Debug=false;
    function debug_tab($TableauToto, $Toggle)
    {
       if($Toggle)
       {
           echo "<pre>";
        print_r($TableauToto);
        echo "</pre>";
       }  
    }
   
    //On initialise le moteur de template
    $moteur=new Smarty();
    //Connexion àla base de données
    $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
    //Utilisation methode GET
    if (!empty($_GET))
    {
        if(isset($_GET['Article']))
        {
            //OK
            $Id_Article=intval($_GET['Article']);
            $moteur->assign('id_article',$Id_Article);
            $Template='envoi_photo1.tpl';
        }
        else
        {
            //Erreur
            $Template='envoi_photo3.tpl';
        }
    }
    else
    { 
        if(!empty($_POST))
        {
            //On a tout
            $Id_Article=intval($_POST['Article']);
            $MDP=htmlspecialchars($_POST['mdp'],ENT_NOQUOTES,'UTF-8');
            debug_tab($_POST,$Debug);
            $SQLS="SELECT mdp_photo FROM t_articles WHERE id_article=:id";
            $TabVal=array(':id'=> $Id_Article);
            $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
            debug_tab($row,$Debug);
            $MDP=ltrim($MDP);
            $B_MDP=$row[0]['mdp_photo'];
            if($MDP==$B_MDP)
            {
                //OK, envoie des photos par ajax
                $moteur->assign('id_article',$Id_Article);
                $Template='envoi_photo2.tpl';
                //
            }
            else
            {
                //Pas OK
                $Template='envoi_photo3.tpl';
            }
        }
        else
        {
            //Erreur d'arrivée sur la page
            $Template='envoi_photo3.tpl';
        }
       
    }
    $moteur->display($CheminTpl.$Template);
    $moteur->display($CheminTpl.'footer.tpl');
?>