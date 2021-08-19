<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/envoi_photo.css">
    <title>Gestion des photos</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <h1>Ajouter des photos pour le Webzine</h1>
            <div class="groupe">
                <form action="envoi_photo.php" method="post">
                    <input type="hidden" name="Article" value="{$id_article}">
                    <p><label for="mdp">Saisir le mot de passe communiqué : </label><input type="password" name="mdp" id="mdp"></p>
                    <p><input type="submit" value="Valider"></p>
                </form>
            </div>
        </div>
    </main>
    <script src="scripts/envoi_photo.js"></script>