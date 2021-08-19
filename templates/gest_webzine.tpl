<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/gest_webzine.css">
    <title>Administration - Webzine</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                    <li><a href="parametres.php">Retour aux paramètres</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Gestion des webzines</h2>
            <div class="commentaire">Vous trouverez la listes des Webzines ci dessous.<br> En " <span class="Vierge bulle">&nbsp;</span> " Les webzines non commencés,<br> en " <span class="EnCours bulle">&nbsp;</span> " ceux qui ont été commencés,<br> en " <span class="Termine bulle">&nbsp;</span>"
                ceux qui sont terminés, <br> en " <span class="Archive bulle">&nbsp;</span> " ceux qui ont été archivés<br> En cliquant sur le webzine choisi, il est possible de le modifier.<br> Il est possible de créer un webzine au système en utilisant
                la partie de droite.
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <form action="gere_webzine.php" method="post">
                <input type="hidden" name="id_webzine" value="0">
            </form>
            <div class="conteneur">
                <div class="liste_webzine">
                    <p class="entete">Liste des webzines</p>
                    <div class="web_conteneur" id="webconteneur">
                        {if isset($TabZine)} {foreach from=$TabZine item=info}
                        <div class="webzine {$info.Class_Etat}" id="{$info.id_webzine}" onclick="editWebzine(this)">
                            <p class="titre">{$info.titre}</p>
                            <p>Date de parution : <span class="donnees">{$info.Date_Parution}</span></p>
                            <p>Etat : <span class="donnees">{$info.Etat}</span></p>
                        </div>
                        {/foreach} {/if}
                    </div>
                </div>
                <div class="archive">
                    <p>Liste des webzines archivés<br>
                    </p>
                    {if isset($TabZineArch)} {foreach from=$TabZineArch item=archive}
                    <div class="webzine Archive">
                        <p class="titre">{$archive.titre}</p>
                        <p>Date de parution : <span class="donnees">{$archive.Date_Parution}</span></p>
                    </div>
                    {/foreach} {/if}
                    <p>Liste des fichiers archivés</p>
                    {if isset($TabFichierArch)}
                    <div class="fichier">
                        <ul>
                            {foreach from=$TabFichierArch item=fichier}
                            <li>
                                <a href="{$fichier.lien}">{$fichier.nom}</a></li>
                            {/foreach}
                        </ul>
                        <form action="gest_webzine.php" method="post">
                            <input type="hidden" name="delete">
                            <input type="button" value="Supprimer les fichiers" onclick="DeleteFiles()">
                        </form>
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </main>
    <script src="scripts/gest_webzine.js"></script>