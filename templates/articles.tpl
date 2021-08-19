<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/articles.css">
    <title>Articles</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Gestion des articles</h2>
            <div class="commentaire">Vous trouverez la listes des articles ci dessous.<br> En " <span class="Vierge bulle">&nbsp;</span> " Les articles non commencés,<br> en " <span class="EnCours bulle">&nbsp;</span> " ceux qui sont prêts pour relecture,<br> en " <span class="Termine bulle">&nbsp;</span>"
                ceux qui sont prêts pour la mise en page, <br> en " <span class="MEP bulle">&nbsp;</span> " ceux qui sont prêts pour la revue finale<br> en " <span class="Finalise bulle">&nbsp;</span> " ceux qui sont prêts pour la publication<br> En cliquant
                sur l'article choisi, il est possible de le modifier ou le mettre en page.<br> Il est possible de proposer un article au système en utilisant la partie de droite. Le nom du monteur n'est utile que si l'article concerne un montage du forum.
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <div class="paginage">
                <p>Liste des articles existants</p>
                <p><label for="coche_affiche">N'afficher que mes articles. </label><input type="checkbox" name="coche_affiche" id="coche_affiche"></p>
                <form action="modif_article.php" method="post">
                    <input type="hidden" name="num_art" value="0">
                </form>
                <div class="article_dispo">
                    {if isset($TabArticles)} {foreach from=$TabArticles item=articles}
                    <div class="article {$articles.Classe} {$articles.Classe_auteur}" id="{$articles.id_article}">
                        <div class="header_article">
                            <div class="img_header {$articles.class_lock}">&nbsp;</div>
                            <div class="titre_article"><span class="info_art">{$articles.titre}</span></div>
                            <div class="img_header {$articles.class_file}">&nbsp;</div>
                            <div class="img_header {$articles.class_fileM}">&nbsp;</div>
                            {if ($articles.photo==1)}
                            <div class="img_header img_header_photo">&nbsp;</div>
                            {/if}
                        </div>
                        <div> Auteur : <span class="info_art">{$articles.auteur}</span><br> Type : <span class="info_art">{$articles.typeA}</span><br> Webzine : <span class="info_art">{$articles.Webzine}</span><br> Etat : <span class="info_art">{$articles.etat}</span>
                        </div>
                    </div>
                    {/foreach} {/if}
                </div>
            </div>
            <div class="creerarticle paginage">
                <p>Création d'un article</p>
                <form action="articles.php" method="post">
                    <p><label for="titre">Titre de l'article : </label><input type="text" name="titre" id="titre"></p>
                    <p><label for="LeMonteur">Nom du monteur sur le forum : </label><input type="text" name="LeMonteur" id="LeMonteur"></p>
                    {if isset($Auteurs)}
                    <p><label for="auteur">Auteur de l'article : </label><select name="auteur" id="auteur">
                        {foreach from=$Auteurs item=info}
                        <option value="{$info.id_utilisateur}">{$info.prenom} {$info.nom}</option>
                        {/foreach}
                    </select></p>
                    {/if}
                    <p><label for="typearticle">Type de l'article : </label><select name="typearticle" id="typearticle">
                        {if isset($ListeType)}
                        {foreach from=$ListeType item=listetype}
                        <option value="{$listetype.id_type}">{$listetype.Nom_Type}</option>
                        {/foreach}
                        {/if}
                    </select></p>
                    <p><label for="description">Description rapide (200 lettres maximum) <span id="nbreLettre">0</span>/200 <br></label><textarea name="description" id="description" cols="30 " maxlength="200" rows="10 "></textarea></p>
                    <p><input type="button" value="créer" id="ajoutBtn"></p>
                </form>
            </div>
        </div>
    </main>
    <script src="scripts/articles.js"></script>