<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/photos.css">
    <title>Gestion des photos</title>
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
            <h2>Gestion des photos</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <form action="voir_photos.php" method="post">
                <input type="hidden" name="id_article" id="id_article" value="0">
            </form>
            <div class="main_conteneur">
                <div>
                    {if isset($TabArticles)} {foreach from=$TabArticles item=infos}
                    <div class="article" ondblclick="ouvre_article({$infos.id})">
                        <p><span class="head"> Titre de l'article :</span> {$infos.titre}</p>
                        <p><span class="head">Type d'article :</span> {$infos.typeA}</p>
                        <p><span class="head">Auteur de l'article :</span> {$infos.auteur}</p>
                        <p><span class="head">Monteur :</span> {$infos.monteur}</p>
                        <p><span class="head">Présence photo :</span> {$infos.presence}</p>
                        <p><span class="head">Photos valides :</span> {$infos.photo}</p>
                        <p><a href="{$infos.lien}">Lien page d'ajout des photos </a></p>
                        <p><span class="head">Mot de passe de la page : </span>{$infos.mdp}</p>
                    </div>
                    {/foreach} {/if}
                </div>
            </div>
        </div>
    </main>
    <script src="scripts/photos.js"></script>