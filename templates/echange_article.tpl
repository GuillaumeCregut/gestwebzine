<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/echange.css">
    <title>Espace de travail Article</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="modif_article.php">Retour à l'article</a></li>
                    <li><a href="articles.php">Liste des articles</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Espace de travail de l'article</h2>
            <div class="commentaire">Dans cet espace vous pouvez échanger sur la rédaction et la mise en page de l'article.<br> Si besoin, il est possible de rajouter les utilisateurs à la discussion<br> Si vous souhaitez échanger des fichiers, veuillez n'utiliser que des fichiers
                zip
                <br> Cet espace sera effacé à la publication du webzine</div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <div class="haut">
                <div class="info_art">
                    <p> Détails de l'article</p>
                    <p>Titre : {$Titre} <br>Auteur : {$Auteur_art}<br> {if isset($LeFichier)} Fichier :
                        <a href="{$LeFichier}" target="_blank"><img src="img/fichier.png" alt="image"></a>
                        <br>{/if} Résumé : {$Description}<br></p>
                    {if isset($RangModificateur)}
                    <div class="case_relecteur {$ClasseAffiche}">
                        <p>Reservé aux relecteurs :<br>Cocher cette case pour autoriser la mise en page de l'article si la relecture a eu lieu.</p>
                        <p class="espace"><label for="coche_relecture">Valider l'article : </label><input type="checkbox" id="coche_relecture" {$EtatCase} onclick="clicValide()"> </p>
                        <p>Etat de l'article : <span id="etatTexte">{$EtatTexte}</span></p>
                        <datalist id="Listechoix">
                            <option value="{$Etat1}">
                            <option value="{$Etat2}">
                        </datalist>
                    </div>
                    {/if}
                </div>
                {if isset($valide)}
                <div class="utilisateurs">
                    <div>
                        <table class="TabUtilisateur">
                            <tr>
                                <th class="col1">Utilisateurs concernés</th>
                                <th class="col2">Liste des utilisateurs</th>
                            </tr>
                            <tr>
                                <td class="col1">
                                    <div class="utilisateurs_box" id="concernes">
                                        {if isset($TabConc)} {foreach from=$TabConc item=user_c}
                                        <p class="utilisateur" id="{$user_c.id_usager}" draggable="true">{$user_c.Identite}</p>
                                        {/foreach} {/if}
                                    </div>
                                </td>
                                <td class="col2">
                                    <div class="utilisateurs_box" id="dispo">
                                        {if isset($TabAllUser)} {foreach from=$TabAllUser item=user_all}
                                        <p class="utilisateur" id="{$user_all.id_usager}" draggable="true">{$user_all.Identite}</p>
                                        {/foreach} {/if}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                {/if}
            </div>

            <div class="echange">
                <div class="liste_message">
                    {if isset($TabMessages)} {foreach from=$TabMessages item=infos}
                    <div class="message">
                        <div class="auteur">
                            <p>Auteur : {$infos.Auteur}</p>
                            <p>Date : {$infos.DateMessage}</p>
                            {if $infos.Fichier!=''}
                            <p>
                                <a href="{$infos.Fichier}"><img src="img/fichier.png" alt="fichier"></a>
                            </p>
                            {/if}
                        </div>
                        <div class="contenu">{$infos.Message}</div>
                    </div>
                    {/foreach} {/if}
                </div>
                {if isset($valide)}
                <div class="redaction">
                    <form action="echange_article.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="auteur" value="{$Auteur}">
                        <input type="hidden" name="titre_article" value="{$Titre}">
                        <input type="hidden" name="EspaceId" value="{$SpaceId}" id="EspaceId">
                        <input type="hidden" name="id_article" id="id_article" value="{$Id_Article}">
                        <p>Rédaction d'un message</p>
                        <p><label for="texte_message">Votre message :</label><br><textarea name="texte_message" id="texte_message" maxlength="500" rows="10" cols="100"></textarea></p>
                        <p><label for="fichier">Joindre un fichier :</label><br><input type="file" name="fichier" id="fichier" accept="zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed"></p>
                        <p><input type="submit" value="Envoyer"></p>
                    </form>
                </div>
                {/if}
            </div>
        </div>
    </main>
    <script src="scripts/echange.js"></script>