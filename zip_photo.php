
<?php
    //Inclusion des fichiers nécessaires
    include "include/config.inc.php";
    include "include/smarty.class.php";
    include "include/db.inc.php";
    include "include/sql.inc.php";
    //Démarrage de la session
    session_start();
     //On initialise le moteur de template
    $moteur=new Smarty();
    if(isset($_SESSION['login']))
    {
        //On affiche le nom
        $NomUser=$_SESSION['Nom_User'];
        $PrenomUser=$_SESSION['Prenom_User'];
        $nom_Prenom=$PrenomUser.' '.$NomUser;
        $moteur->assign('PrenomLogin',$nom_Prenom);
        if(isset($_POST['fichiers']) and isset($_POST['repertoire']))
        {
            $TabFichier=$_POST['fichiers'];
            //Création du fichier zip
            $MonZip=new ZipArchive;

            //Création des variables répertoires
            $Repertoire_Photo=$_POST['repertoire'];
            $Repertoire=$CheminBaseFichier.$CheminPhotos.$Repertoire_Photo.'/';
            $NomZip=$Repertoire.$Repertoire_Photo.'.zip';
            if($MonZip->open($NomZip,ZipArchive::CREATE)===TRUE)
            {
                foreach($TabFichier as $v)
                {
                    $Chemin=$Repertoire.$v;
                    $MonZip->addFile($Chemin,$v);
                }
                $MonZip->close();
                $Template='zip_photo.tpl';
                $moteur->assign('lien',$NomZip);
            }
            else
            {
                //Impossible de créer le zip
                $Template='erreur.tpl';
                $moteur->assign('ErreurNum','0x5237');
            }
            $moteur->display($CheminTpl.$Template);
            $moteur->display($CheminTpl.'footer.tpl');
        }
        else
        {
            //Pas de formulaire correct
            $Template='erreur.tpl';
            $moteur->assign('ErreurNum','0x5237');
            $moteur->display($CheminTpl.$Template);
        }
    }
    else
    {
        //On affiche la page par défaut de connexion
       
        $moteur->display($CheminTpl.'login.tpl');
    }
?>
