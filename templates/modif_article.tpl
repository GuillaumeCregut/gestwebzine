<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/modif_article.css">
    <title>Edition article</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="articles.php">Liste des articles</a></li>
                    {if isset($Admin)}
                    <li><a href="graphiste.php">Mise en page</a></li>
                    {/if}
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Edition d'un article</h2>
            <div class="commentaire">Vous pouvez modifier votre article dans cette partie. Si vous considérez l'article terminé, n'oubliez pas de télécharger les fichiers (mis au préalable dans un fichier zip IMPERATIVEMENT) et de changer l'état à terminé.<br> Si l'article est
                verrouillé pour mise en page, il est alors impossible de remettre un nouveau fichier, jusqu'au déverrouillage de celui-ci.
            </div>
            <div class="cadre1">
                <div class="cadre2">
                    <div><a href="echange_article.php" class="btn_echange">Espace d'échange équipe pour l'article</a></div>
                    {if $IsAuteur}
                    <div><a href="mep_article_auteur.php" class="btn_echange">Espace d'échange avec les graphistes</a></div>
                    {/if}
                </div>
                <div class="cadre2">
                    {if isset ($FichierMEP)}
                    <p>Télécharger la mise en page proposée :
                        <a href="{$FichierMEP}"><img src="img/dlmep.png" alt="download"></a>
                    </p> {/if}
                </div>
            </div>



        </div>
    </header>
    <main>
        <form action="modif_article.php" method="post" enctype="multipart/form-data">
            <div class="main_conteneur">
                <div class="gauche">
                    <input type="hidden" name="maj" value="1">
                    <input type="hidden" name="Uploaded" id="Fichier_OK" value="0">
                    <input type="hidden" name="id_article" value="{$idArticle}" id="Id_Article">
                    <p><img src="img/{$Verrou}.png" alt="verrou"></p>
                    <p>Nom de l'article : {$Titre_Article}</p>
                    <p>Auteur : {$Auteur}</p>
                    <p>Type d'article : {$TypeArticle}</p>
                    <p>Date de création : {$DateCreation}</p>
                    <p>Webzine : {$Webzine}</p>
                    <p>Date prévue de finalisation : {$DateFinalisation}</p>
                    <p><label for="etat">Etat d'avancement : </label><select name="etat" id="etat">
                {if isset($TabEtats)}
                {foreach from=$TabEtats item=etat}
                     <option value="{$etat.id_Etat}" {$etat.sel}>{$etat.nom}</option>
                {/foreach}
                {/if}
                </select></p>
                    {if $IsAuteur}
                    <p><input type="submit" value="Valider"></p>
                    {/if}
                </div>
                <div class="droite">
                    <p>Description </p>
                    <div class="desc">{$Description}</div>

                    <p>Niveau d'avancement : <span id="indice" class="">{$Avance} % </span><input type="hidden" name="avancement" id="avancement" value="{$Avance}"></p>
                    {if $IsAuteur} {if $Verrouillage==0}
                    <div class="fichiers">
                        <div class="UnFichier">
                            <p class="pUnfichier"><img src="img/textfile.png" alt="Fichier texte" class="imgfile"><label for="fichierTexte">Selectionner le fichier texte de l'article : </label><input type="file" name="fichier" id="fichierTexte" accept="zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed"></p>
                            <p class="pUnfichier"><progress id="progressBarTexte" value="0" max="100" style="width:300px;"></progress></p>
                            <p id="statusText" class="pUnfichier"></p>
                            <p id="loaded_n_totalText" class="pUnfichier"></p>
                            <p class="pUnfichier"><input type="button" value="Envoyer le fichier texte" onclick="uploadFile(0)"></p>
                        </div>

                    </div>
                    {/if} {/if}

                </div>

            </div>
        </form>
    </main>
    <script src="scripts/article.js"></script>
    <script src="scripts/ajax_file.js"></script>