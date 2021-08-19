<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/graphiste.css">
    <title>Graphistes - mise en page</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li>
                        <a href="index.php">Accueil</a>
                    </li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Intégration</h2>
            <div class="commentaire">Vous trouverez la listes des articles ci dessous. <br> en " <span class="Termine bulle">&nbsp;</span>" ceux qui sont terminés, <br> en " <span class="MEP bulle">&nbsp;</span> " ceux qui ont été mis en page<br> En cliquant sur l'article choisi,
                il est possible le mettre en page.
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <p>Liste des articles existants prêts à être mis en page</p>
            <form action="mep_article.php" method="post">
                <input type="hidden" name="num_art" value="0">
            </form>
            <div class="paginage">

                <div class="article_dispo">
                    {if isset($TabArticles)} {foreach from=$TabArticles item=articles}
                    <div class="article {$articles.Classe}" onclick="modifieArticle('{$articles.id_article}') ">
                        <div class="header_article">
                            <div class="img_header {$articles.class_lock}">&nbsp;</div>
                            <div class="titre_article"><span class="info_art">{$articles.titre}</span></div>
                            <div class="img_header {$articles.class_file}">&nbsp;</div>
                            <div class="img_header {$articles.class_fileM}">&nbsp;</div>
                            {if ($articles.photo==1)}
                            <div class="img_header img_header_photo">&nbsp;</div>
                            {/if}
                        </div>
                        <div>
                            Auteur : <span class="info_art">{$articles.auteur}</span><br> Type : <span class="info_art">{$articles.typeA}</span><br> Webzine : <span class="info_art">{$articles.Webzine}</span><br> Etat : <span class="info_art">{$articles.etat}</span>

                        </div>
                    </div>
                    {/foreach} {/if}
                </div>
            </div>
        </div>
    </main>
    <script src="scripts/graphiste.js"></script>