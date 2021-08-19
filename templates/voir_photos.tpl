<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/voir_photos.css">
    <title>Gestion des photos - Affichage et validation</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="photos.php">Retour aux photos d'articles</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Gestion des photos - Affichage et validation</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <div class="resume_article">
                <p>Titre de l'article : {$Titre}</p>
                <p>Webzine associé : {$Webzine}</p>
                <p>Photos validée par un graphiste : <span id="valide_span">{$Valide}</span></p>
            </div>
            <div class="{$affiche_case}" id="affiche_case">
                <p><input type="checkbox" name="casecoche" id="coche" onclick="creer_table(this)">&nbsp;Photos recues par un autre média, créer la structure</p>
            </div>
            <div class="envoi {$classe_aff}" id="envoi_box">
                <p>Envoyer un fichier zip contenant les photos prêtes pour l'édition</p>
                <form action="voir_photos.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="Article" value="{$id_article}" id="id_article">
                    <input type="hidden" name="repertoire" value="{$rep}" id="rep">
                    <p><label for="fichier">Selectionner le fichier photo : </label><input type="file" name="fichier" multiple accept=".zip" id="fichier"></p>
                    <p><progress id="progressBarTexte" value="0" max="100" style="width:300px;"></progress></p>
                    <p id="statusText" class="pUnfichier"></p>
                    <p id="loaded_n_totalText" class="pUnfichier"></p>
                    <p><input type="button" value="Envoyer le(s) fichier(s)" onclick="uploadFile(0)"></p>
                </form>
            </div>

            <form action="zip_photo.php" method="post">
                <input type="hidden" name="repertoire" value="{$chemin}">
                <div class="photos">
                    {if isset ($TabPhoto)} {foreach from=$TabPhoto item=info}
                    <div class="photo">
                        <a href="{$info.photo}" target="_blank"> <img src="{$info.miniature}" alt="{$info.miniature}"></a>
                        <p><input type="checkbox" name="fichiers[]" value="{$info.nom}" id="{$info.nom}"> Selectionner cette photo</p>
                    </div>
                    {/foreach} {/if}
                </div>
                {if isset($bouton)}
                <input type="submit" value="valider"> {/if}
            </form>
        </div>
    </main>
    <script src="scripts/voir_photos.js"></script>