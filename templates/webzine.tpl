<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/webzine.css">
    <link rel="stylesheet" href="styles/progress.css">
    <title>Détail Webzine</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="all_webzine.php">Liste des webzines</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Affichage des détails du webzine</h2>
            <div class="commentaire">Vous trouverez la listes des articles ci dessous.<br> En " <span class="Vierge bulle">&nbsp;</span> " Les articles non commencés,<br> en " <span class="EnCours bulle">&nbsp;</span> " Les articles en attente de relecture,<br> en " <span class="Termine bulle">&nbsp;</span>"
                Les articles en attente de mise en page, <br> en " <span class="MEP bulle">&nbsp;</span> " Les articles prêts pour revue finale<br> en " <span class="etat_warning bulle">&nbsp;</span> " Les articles non prêt {$DateWarning} jours avant
                la sortie,<br> en "
                <span class="etat_alert bulle">&nbsp;</span> " Les articles non prêts pour le webzine {$DateAlert} jours avant,<br> en " <span class="etatOK bulle">&nbsp;</span> " Les articles prêts pour la publication<br> Il est possible de proposer
                un article au système en utilisant la partie de droite.<br> Pour affecter un article au webzine, faites le glisser de la colonne article diponible (droite) vers la colonne article du webzine (gauche).<br> En double cliquant sur l'article
                choisi, il est possible de le modifier ou le mettre en page.<br> Pour supprimer un article du webzine, faites le glisser de la colonne de gauche vers la colonne de droite. <br>
                <img src="img/fichier.png" alt="icone"> : Fichier rédacteur présent<br>
                <img src="img/fichiertl.png" alt="icone"> : Fichier graphiste présent<br>
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <div class="detail_webzine">

                <div class="detail">
                    <form action="modifie_webzine.php" method="post">
                        <input type="hidden" name="action" value="0" id="action">
                        <input type="hidden" name="id_webzine" value="{$Id_Webzine}">
                        <p><label for="Nom_Webzine">Nom du webzine : </label><input type="text" name="Nom_Webzine" id="Nom_Webzine" value="{$Titre}" readonly><label for="cb_nom">Modifier</label><input type="checkbox" name="cb_nom" id="cb_nom" onclick="allowNom()"
                                {$Archiver}></p>
                        <p>Date de création : {$Date_Creation}</p>
                        <p><label for="date_parution">Date de parution prévue : </label><input type="date" name="date_parution" id="date_parution" value="{$Date_Parution}" readonly><label for="cb_date">Modifier</label><input type="checkbox" name="cb_date"
                                id="cb_date" onclick="allowDate()" {$Archiver}></p>
                        <p><label for="etat_webzine">Etat : </label> <select name="etat_webzine" id="etat_webzine" disabled="disabled">
                                {if isset($TabEtat)}
                                {foreach from=$TabEtat item=etat}
                                <option value="{$etat.id}" {$etat.checked}>{$etat.nom}</option>
                                {/foreach}
                                {/if}
                            </select> <label for="cb_etat">Modifier</label><input type="checkbox" name="cb_etat" id="cb_etat" onclick="allowEtat()" {$Archiver}></p>
                        <p>Nombre de page pour ce webzine à ce stade (cela dépend de la finition des mises en pages) : {$Nbre_Pages}</p>
                        <p>Webzine prêt à être produit : <img src="img/{$WebinePret}.png" alt="info état webzine" class="imageEtat"></p>
                    </form>
                </div>
                <div class="detail">
                    <p><span {if $Archiver=='' } onclick="modif_infos()" {/if} class="boutons">valider les
                            changements</span></p>
                    <p>
                        <!-- <div class="tooltip"><label for="cb_archive">Archiver le webzine </label><input type="checkbox" name="cb_archive" id="cb_archive" {$Archiver}><span class="tooltiptext">En archivant le
                            webzine, vous téléchargerez l'ensemble des articles,<br> ils seront supprimés du
                            serveur</span></div>-->
                    </p>
                    <p class="tdb"><a href="relance_equipe.php">Effectuer une relance des rédacteurs</a></p>
                    <p class="tdb"><a href="contact_equipe.php">Contacter les rédacteurs de ce webzine</a></p>
                </div>
            </div>
            {if $Archiver==''}
            <div class="articles">
                <form action="mep_article.php" method="post" name="formulaire">
                    <input type="hidden" name="num_art" value="0">
                </form>
                <div class="appartient articles_item">
                    <p>Listes des articles du Webzine</p>
                    <div class="conteneur_article" id="article_web">
                        {if isset($TabArticlesWebzine)} {foreach from=$TabArticlesWebzine item=articles}
                        <div class="article {$articles.Classe}" draggable="true" id="{$articles.id_article}">
                            <div class="header_article">
                                <div class="titre_article"><span class="info_art">{$articles.titre}</span></div>
                                <div class="img_header {$articles.class_file}">&nbsp;</div>
                                <div class="img_header {$articles.class_fileI}">&nbsp;</div>
                                {if ($articles.photo==1)}
                                <div class="img_header img_header_photo">&nbsp;</div>
                                {/if}
                                <div class="img_header {$articles.class_fileL}">&nbsp;</div>
                            </div>
                            <div> Auteur : <span class="info_art">{$articles.auteur}</span><br> Type : <span class="info_art">{$articles.typeA}</span><br> Nombre de page : <span class="info_art">{$articles.nbPageArticle}</span><br> Webzine : <span class="info_art"
                                    id="web_{$articles.id_article}">{$articles.Webzine}</span><br> Etat :
                                <span class="info_art">{$articles.etat}</span>
                                <p>
                                    <div class="BarCont">
                                        <div class="{$articles.Avancee_Class} bar" style="width:{$articles.Avancee}%"></div>
                                    </div>
                                </p>
                            </div>
                        </div>
                        {/foreach} {/if}
                    </div>
                </div>
                <div class="dispo articles_item">
                    <p>Les des articles disponibles</p>
                    <div class="conteneur_article" id="article_dispo">
                        {if isset($TabArticlesDispo)} {foreach from=$TabArticlesDispo item=articlesd}
                        <div class="article {$articlesd.Classe}" draggable="true" id="{$articlesd.id_article}">
                            <div class="header_article">
                                <div class="titre_article"><span class="info_art">{$articlesd.titre}</span></div>
                                <div class="img_header {$articlesd.class_file}">&nbsp;</div>
                                <div class="img_header {$articlesd.class_fileI}">&nbsp;</div>
                                {if $articlesd.photo==1}
                                <div class="img_header img_header_photo">&nbsp;</div>
                                {/if}
                                <div class="img_header {$articlesd.class_fileL}">&nbsp;</div>

                            </div>
                            <div> Auteur : <span class="info_art">{$articlesd.auteur}</span><br> Type : <span class="info_art">{$articlesd.typeA}</span><br> Nombre de page : <span class="info_art">{$articlesd.nbPageArticle}</span><br> Webzine : <span class="info_art"
                                    id="web_{$articlesd.id_article}">{$articlesd.Webzine}</span><br> Etat : <span class="
                                    info_art ">{$articlesd.etat}</span>
                            </div>
                        </div>
                        {/foreach} {/if}
                    </div>
                </div>
            </div>
            {/if}
        </div>
    </main>
    <script src="scripts/webzine.js "></script>