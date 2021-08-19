<?php
 /*version 1.0B*/
    include "../include/config.inc.php";
    include "../include/db.inc.php";
    include "../include/sql.inc.php";
    session_start();
    if(isset($_SESSION['login'])) 
    {
        $tabJson=array();
        if (isset($_POST['id_article']))
        {
            //Connexion à la base de données
            $Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
            $Id_Article=intval($_POST['id_article']);
            $Action=intval($_POST['action']);
            //On récupère l'avance de l'article
            $SQLS= $SQL_Get_Avancement;//='SELECT Avancee_Article FROM t_articles WHERE id_article=:id';
            $TabVal=array(':id'=>$Id_Article);
            $row=$Conn->sql_fetch_all_prepared($TabVal,$SQLS);
            $AvanceActuelle=$row[0]['Avancee_Article'];
            switch($Action)
            {
                
                case 1 : //On supprime l'autorisation de mise en page et on diminue de step %
                        $AvanceFuture=$AvanceActuelle-Step_Article;
                        $NouvelEtat=Etat_Article_Cours;
                        break;
                case 2 : //On passe l'autorisation de mise en page et on augmente de step %
                        $AvanceFuture=$AvanceActuelle+Step_Article;
                        $NouvelEtat=Etat_Article_Termine;
                        break;
            }
            //On excute la requete 
            $SQLS=$SQL_Mod_Article_Light;//="CALL P_Mod_Article_Light(:id_a, :avance, :etat)";
            $TabId=array(':id_a'=>$Id_Article,':avance'=>$AvanceFuture,':etat'=>$NouvelEtat);
            $Result=$Conn->ExecProc($TabId,$SQLS);
            //On retourne le résultat
            array_push($tabJson,array('Retour'=>$Result)); //Changer la valeur du résultat
        }
        else
        {
            //On a rien alors on retourne une erreur
            array_push($tabJson,array('Retour'=>0));
        }
        echo json_encode($tabJson);
    }
?>