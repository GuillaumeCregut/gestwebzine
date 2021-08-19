<?php
    include "include/config.inc.php";
    include "include/smarty.class.php";
     //Démarrage de la session
     session_start();
     //On initialise le moteur de template
     $moteur=new Smarty();
    if(isset($_SESSION['login']))
    {
        //On vérifie le niveau du membre
        if(!empty($_GET))
        {
            if(isset($_GET['rep']))
            {
                $Chemin=$_GET['rep'];
                //POur chaque fichier de chemin, si c'est un zip, on l'affiche
                $tabFiles=array();
                if(is_dir($Chemin))
                {
                    if ($handle = opendir($Chemin)) {
                        while (false !== ($entry = readdir($handle))) {
                            if ($entry != "." && $entry != "..") {
                                echo '<p><a href="'.$Chemin.$entry.'">'.$entry."</a></p>";
                            }
                        }
                        closedir($handle);
                    }
                }
                else
                {
                    //Erreur, ce n'est pas un répertoire
                    echo "Erreur de répertoire";
                }
               
            }
        }
    }
    else
    {
        //On affiche la page par défaut de connexion
        $moteur->display($CheminTpl.'login.tpl');
    }
    
?>