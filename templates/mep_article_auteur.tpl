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
            <div class="haut">
                <div class="haut1">
                    <!--Infos principales-->
                    <p>Auteur : <span class="valeur">{$Auteur}</span></p>
                    <p>Titre : <span class="valeur">{$Titre}</span></p>
                    <p>Sujet de l'article : <span class="valeur">{$TypeArticle}</span></p>
                    <input type="hidden" name="id_article1" id="id_article1" value="{$id_article}">
                    <p>Etat : <span class="valeur" id="statutArticle">{$Etat}</span></p>
                    <p>Date de création : <span class="valeur">{$Date_C}</span></p>
                    <p>Date de modification : <span class="valeur">{$Date_M}</span></p>
                    <p>Statut : <img src="img/{$Cadenas}" alt="cadenas" id="cadenas"></p>
                    <p class="finArticle">Pris en charge par : <span id="pec">{$PEC_ID}</span></p>
                    <p><span class="souligne">Intégration</span></p>
                    <p>Graphiste : <span class="valeur">{$NomGraph}</span></p>
                    <p>Dernière modification : <span class="valeur">{$DateGraph}</span></p>
                    <input type="hidden" name="id_article" id="id_article" value="{$id_article}">
                </div>
                <span class="vertical-line"></span>
                <div class="haut2">
                    <p>Description</p>
                    <div class="description">{$Desc}</div>
                </div>
            </div>
            <div class="bas">
                <div class="bas1">
                    <p>Ajouter un commentaire de suivi :
                        <p><textarea id="text_message" name="texte_message" maxlength="500" rows="10" cols="50"></textarea></p>
                        <input type="hidden" name="auteur" id="auteur" value="{$Id_Auteur}">
                        <input type="button" value="Ajouter" id="bouton">
                        <input type="hidden" name="statutArticle" id="Article_State" value="0">
                        <input type="hidden" name="toto" id="etat_verrou" value="0">
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