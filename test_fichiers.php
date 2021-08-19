<?php
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    echo "<p>lecture des fichiers<p>";
    $MesFichiers=$_FILES['file1'];
    $compte=sizeof($MesFichiers['name']);
    echo "<p>Compte : $compte</p>";
    for($i=0;$i<$compte;$i++)
    {
        $Nom=$MesFichiers['name'][$i];
        $Taille=$MesFichiers['size'][$i];
        $Erreur=$MesFichiers['error'][$i];
        $tempName=$MesFichiers['tmp_name'][$i];
        echo "<p>Fichier $Nom, Taille : $Taille, Erreur $Erreur, Temporaire=$tempName</p>";
    }
?>