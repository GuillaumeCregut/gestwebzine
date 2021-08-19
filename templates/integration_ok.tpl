<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/integration.css">
    <title>Webzine - Equipe</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="equipe.php">Retour à l'équipe</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>L'équipe</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <p>Résulat de l'intégration des utilisateurs</p>
            {if isset($TabResult)} {foreach from=$TabResult item=info}
            <p>Intégration de {$info.Nom} réussie</p>
            {/foreach} {/if}
        </div>
    </main>