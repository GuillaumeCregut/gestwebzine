<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/gere_webzine.css">
    <title>Administration - Webzine</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                    <li><a href="gest_webzine.php">Retour à la gestion des webzines</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Gestion des webzines</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            {if isset($Action)}
            <div class="form_res">
                <p>Résultat de l'archivage</p>
                {foreach from=$tabRetour item=liste}
                <p>{$liste}</p>
                {/foreach} {if ($zip!='') }
                <p><a href="{$zip}">Fichier de sauvegarde</a></p>
                {/if}
            </div>
            {/if}
            <div class="form_cont">
                <p>Nom du webzine : {$Titre}</p>
                <p>Date de parution prévue : {$Date_Parution}</p>
                <p>Etat : {$Etat}</p>
            </div>
            <form action="gere_webzine.php" method="post" id="form_webzine">
                <input type="hidden" name="update" value="1">
                <input type="hidden" name="id_webzine" value="{$id_webzine}">
                <input type="hidden" name="titre" value="{$Titre}">
                <input type="button" value="Archiver" onclick="valide()">
            </form>
        </div>
    </main>
    <script src="scripts/gere_webzine.js"></script>