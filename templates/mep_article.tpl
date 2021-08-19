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
                    <li><a href="index.php">Retour à l'accueil</a></li>
                    <li><a href="articles.php">Liste des articles</a></li>
                    <li><a href="graphiste.php">Intégration</a></li>
                    {if $admin==1}
                    <li><a href="webzine.php">Retour au webzine</a></li>
                    <li><a href="all_webzine.php">Comité de rédaction</a></li>{/if}
                </ul>
                <div class="user">{$PrenomLogin}</div>
            </nav>
            <h2>Mise en page d'un article</h2>
            <div class="commentaire">Ci dessous les informations de l'article.<br> Vous pouvez télécharger, s'ils sont présents, les fichiers bruts ou mis en page de l'article.<br> L'article passera en mode verrrouillé (aucun upload possible pour le rédacteur) si vous téléchargez
                les fichiers brut.<br> Il est possible d'ajouter un commentaire de suivi sur la mise en page de l'article.
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <!-- The Modal -->
            <div id="myModal" class="modal {$Display_popup}">

                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>{$Message}</p>
                </div>

            </div>
            <div class="haut">
                <div class="haut1">
                    <!--Infos principales-->
                    <p>Auteur : <span class="valeur">{$Auteur}</span></p>
                    <input type="hidden" name="id_article1" id="id_article1" value="{$id_article}">
                    <p>Titre : <span class="valeur">{$Titre}</span></p>
                    <p>Sujet de l'article : <span class="valeur">{$TypeArticle}</span></p>
                    <p>Etat : <span class="valeur" id="statutArticle">{$Etat}</span></p>
                    <p>Date de création : <span class="valeur">{$Date_C}</span></p>
                    <p>Date de modification : <span class="valeur">{$Date_M}</span></p>
                    <p>Statut : <img src="img/{$Cadenas}" alt="cadenas" id="cadenas"></p>
                    <p class="finArticle">Pris en charge par : <span id="pec">{$PEC_ID}</span></p>
                    <p><span class="souligne">Intégration</span></p>
                    <!-- <p>Graphiste : <span class="valeur">{$NomGraph}</span></p> -->
                    <p>Dernière modification : <span class="valeur">{$DateGraph}</span></p>
                </div>
                <span class="vertical-line"></span>
                <div class="haut2">
                    <p>Description</p>
                    <div class="description">{$Desc}</div>
                    {if $PresenceFichierArticle}
                    <div class="uplFile">
                        <p><span> Téléchargement de l'article mis en page</span></p>
                        <p><input type="file" name="fichier" accept=".zip" id="fichier"></p>
                        <p class="pUnfichier"><progress id="progressBarTexte" value="0" max="100" style="width:300px;"></progress></p>
                        <p id="statusText" class="pUnfichier"></p>
                        <p id="loaded_n_totalText" class="pUnfichier"></p>
                        <p class="pUnfichier"><input type="button" value="Envoyer le fichier" onclick="uploadFile(0)"></p>
                    </div>
                    {/if}
                </div>

                <div class="haut3">
                    {if $FichierArticle!=''}
                    <p>
                        <a href="{$FichierArticle}" target="_blank" onclick="telfichier(event)"><img src="img/dl.png" alt="download"> Fichier article</a>
                    </p>
                    {/if} {if $FichierMEP!=''}
                    <p>
                        <a href="{$FichierMEP}"><img src="img/dl.png" alt="download" target="_blank"> Fichier Intégré</a>
                    </p>
                    {/if} {if $rep_photo!=''}
                    <p>
                        <a href="{$rep_photo}" target="_blank" onclick="telphoto(event)"><img src="img/dl.png" alt="download"> photos article</a>
                        <input type="hidden" name="rep" id="rep_fichiers" value="{$rep_photo}">

                    </p>
                    {/if}
                    <form action="mep_article.php" method="post" enctype="multipart/form-data">
                        <div class="nbform {$PresenceFichier}" id="DivPages">
                            <p><label for="NbPage">Nombre de page de l'article :</label> <input type="number" name="nb_page" id="NbPage" value="{$Nb_Page}"></p>

                            <input type="hidden" name="id_article" id="id_article" value="{$id_article}">
                            <input type="hidden" name="maj" value="1">
                            <p><input type="submit" value="Envoyer"></p>
                        </div>
                    </form>

                    <!--Fichiers-->
                </div>
            </div>
            <div class="bas">
                <div class="bas1">
                    <input type="hidden" name="State" id="Article_State" value="{$Article_State}">
                    <input type="hidden" name="ihverrou" id="etat_verrou" value="{$EtatVerrou}"> {if $admin==1}
                    <p><img src="img/{$Verrou}" alt="cadenas" onclick="verrouillage()" id="verrou" class="click"> <span id="verrouillage">{$ActionVerrou}</span></p>
                    <p><img src="img/{$ImageCheck}.png" alt="Validation" onclick="valideArticle()" id="ImgValide" class="click"> Valider l'article pour l'intégration</p>
                    {/if}
                    <p>Ajouter un commentaire de suivi :
                        <p><textarea id="text_message" name="texte_message" maxlength="500" rows="10" cols="50"></textarea></p>
                        <input type="hidden" name="auteur" id="auteur" value="{$Id_Auteur}">
                        <input type="button" value="Ajouter" id="bouton" onclick="add_message({$Id_Auteur})">
                    </p>
                </div>
                <div class="bas2">
                    <p>Messages de suivi :</p>
                    <div class="cont_message" id="contientMessages">
                        {if isset($TableauMessage)} {foreach from= $TableauMessage item=info}
                        <div class="message">
                            <div class="auteur">
                                <p>Auteur : {$info.Auteur}</p>
                                <p>Date : {$info.Date_message}</p>
                            </div>
                            <span class="vertical-line"></span>
                            <div class="corps">
                                <p>{$info.corps}</p>
                            </div>
                        </div>
                        {/foreach} {/if}
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="scripts/mep_article.js"></script>
    <script src="scripts/ajax_file_MEP.js"></script>