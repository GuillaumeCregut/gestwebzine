<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <title>Erreur</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <div class="user">{$PrenomLogin}</div>
            <h2>Erreur</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <nav>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                </ul>
            </nav>
            <p>Une erreur ({$ErreurNum}) s'est produite, nous en sommes désolés.</p>
        </div>
    </main>