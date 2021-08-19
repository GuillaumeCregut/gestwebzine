<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/modif_webzine.css">
    <title>Modification du webzine</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="webzine.php">Retour au webzine</a></li>
                    <li><a href="all_webzine.php">Liste des webzines</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Modifications</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            {if isset($TabActions)} {foreach from=$TabActions item=info}
            <p>{$info.nom} : {$info.valeur}</p>
            {/foreach} {/if} {if isset($LeFichier)}
            <p class="download"><a href="{$LeFichier}">Télécharger l'archive</a></p>
            {/if}
        </div>
    </main>