<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/index_admin.css">
    <title>Accueil</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <div class="user">{$PrenomLogin}</div>
            <h2>Accueil</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <nav class="menu_nav">
                <ul class="menu">
                    <li><a href="#" class="menulien">Articles</a>
                        <ul class="sousmenu">
                            <li> <a href="articles.php"> articles</a></li>
                            <li><a href="relecture.php">Comité de relecture</a></li>
                        </ul>
                    </li>
                    <li><a href="#" class="menulien">Webzine</a>
                        <ul class="sousmenu">
                            <li><a href="photos.php">Gestion photos</a></li>
                            <li><a href="graphiste.php">Intégration</a></li>
                            <li><a href="all_webzine.php">Comité de rédaction</a></li>
                        </ul>
                    </li>
                    <li><a href="#" class="menulien">Gestion</a>
                        <ul class="sousmenu">
                            <li><a href="equipe.php">Equipe</a></li>
                            {if isset($SA)}
                            <li><a href="parametres.php">Paramètres</a></li>{/if}
                            <li><a href="compte.php">Mon compte</a></li>
                        </ul>
                    </li>
                    <li><a href="messagerie.php">Messagerie</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        </div>
    </main>
    <script src="scripts/index.js"></script>