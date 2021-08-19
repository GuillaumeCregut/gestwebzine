<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/mep_article.css">
    <title>Mise en page article</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="articles.php">Liste des articles</a></li>
                    <li><a href="graphiste.php">Mise en page</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
                <div class="user">{$PrenomLogin}</div>
            </nav>
            <h2>Mise en page d'un article</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <p>{$Message}</p>
        </div>
    </main>