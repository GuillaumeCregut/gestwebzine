<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/gest_article.css">
    <title>gestion des articles</title>
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
            <h2>Paramètres</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <form action="gere_article.php" method="post">
                <input type="hidden" name="id_article">
            </form>
            <div class="article_dispo">
                {if isset($TabArticles)} {foreach from=$TabArticles item=articles}
                <div class="article" onclick="modifieArticle('{$articles.id_article}') ">
                    <div class="header_article">
                        <div class="titre_article"><span class="info_art">{$articles.titre}</span></div>
                    </div>
                    <div> Auteur : <span class="info_art">{$articles.auteur}</span><br> Type : <span class="info_art">{$articles.typeA}</span><br> Webzine : <span class="info_art">{$articles.Webzine}</span><br> Etat : <span class="info_art">{$articles.etat}</span>
                    </div>
                </div>
                {/foreach} {/if}
            </div>
        </div>
    </main>
    <script src="scripts/gest_article.js"></script>