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
            <div class="info">
                <p>Vous pouvez téléchargez vos photos pour le webzine depuis cette page.<br> Les photos doivent être contenues dans un fichier zip inférieur à 80Mo.<br> Si ce n'est possible, merci de faire autant de fichiers zip inférieurs à 80Mo et les
                    télécharger tous en même temps.<br> Il est possible d'envoyer aussi les photos sans les zipper. Attention, elles doivent toutes être envoyées en même temps.<br> Les formats autorisés sont : zip, jpg, gif et png</p>
            </div>
            <div class="groupe">
                <form action="envoi_photo.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="Article" value="{$id_article}" id="id_article">
                    <p><label for="fichier">Selectionner le fichier photo : </label><input type="file" name="fichier" multiple accept=".zip, image/*" id="fichier"></p>
                    <p><progress id="progressBarTexte" value="0" max="100" style="width:300px;"></progress></p>
                    <p id="statusText" class="pUnfichier"></p>
                    <p id="loaded_n_totalText" class="pUnfichier"></p>
                    <p><input type="button" value="Envoyer le(s) fichier(s)" onclick="uploadFile(0)"></p>
                </form>
            </div>
        </div>
    </main>
    <script src="scripts/envoi_photo.js"></script>