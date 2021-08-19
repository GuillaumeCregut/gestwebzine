<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/equipe.css">
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
            <form action="integration.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="integre">
                <p>Selectionner le fichier excel contenant les utilisateurs à intégrer</p>
                <p><input type="file" name="fichier" id="fichier" accept=".xlsx"></p>
                <p><input type="submit" value="Valider"></p>
            </form>
        </div>
    </main>