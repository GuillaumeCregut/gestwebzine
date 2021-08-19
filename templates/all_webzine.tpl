<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/creation.css">
    <title>Webzines</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Gestion de parution</h2>
            <div class="commentaire">Vous trouverez la listes des Webzines ci dessous.<br> En " <span class="Vierge bulle">&nbsp;</span> " Les webzines non commencés,<br> en " <span class="EnCours bulle">&nbsp;</span> " ceux qui ont été commencés,<br> en " <span class="Termine bulle">&nbsp;</span>"
                ceux qui sont terminés, <br> en " <span class="Archive bulle">&nbsp;</span> " ceux qui ont été archivés<br> En cliquant sur le webzine choisi, il est possible de le modifier.<br> Il est possible de créer un webzine au système en utilisant
                la partie de droite.
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <form action="webzine.php" method="post">
                <input type="hidden" name="id_webzine" value="0">
            </form>
            <div class="all_webzine all">
                <p class="entete">Liste des webzines</p>
                <div class="web_conteneur" id="webconteneur">
                    {if isset($TabZine)} {foreach from=$TabZine item=info}
                    <div class="webzine {$info.Class_Etat}" id="{$info.id_webzine}">
                        <p class="titre">{$info.titre}</p>
                        <p>Date de parution : <span class="donnees">{$info.Date_Parution}</span></p>
                        <p>Nombre d'articles : <span class="donnees">{$info.Compte_Article}</span></p>
                        <p>Etat : <span class="donnees">{$info.Etat}</span></p>
                    </div>
                    {/foreach} {/if}
                </div>

            </div>
            <div class="new_webzine">
                <div>
                    <p>Créeer un nouveau webzine</p>
                    <form action="all_webzine.php" method="post">
                        <p><label for="nom">Nom du webzine : </label><input type="text" name="nom" id="nom"></p>
                        <p><label for="date_p">Date prévue de parution : </label><input type="date" name="date_parution" id="date_p"></p>
                        <p><input type="button" value="Valider" id="creer_btn"></p>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="scripts/creation.js"></script>