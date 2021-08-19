<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/gere_article.css">
    <title>gestion des articles</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="gest_article.php">Retour à la gestion des articles</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Gestion des articles</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            {if isset($Action)}
            <div class="cont_form">
                <p>{$Action}</p>
            </div>
            {/if}
            <div class="cont_form">
                <form action="gere_article.php" method="post" id="suppForm">
                    <input type="hidden" name="update" value="2">
                    <input type="hidden" name="id_article" value="{$id_article}">
                    <input type="button" value="Supprimer l'article" onclick="valide()">
                </form>
            </div>
            <div class="cont_form">
                <form action="gere_article.php" method="post">
                    <input type="hidden" name="update" value="1">
                    <input type="hidden" name="id_article" value="{$id_article}">
                    <p><label for="titre_article">Titre de l'article : </label><input type="text" name="titre" id="titre_article" value="{$Titre}"></p>
                    <p><label for="desc">Description : </label><textarea name="description" id="desc" cols="30" rows="10">{$Description}</textarea></p>
                    <p><label for="auteur">Auteur de l'article : </label><select name="auteur" id="auteur">
                        <option value="0">--</option>
                        {if (isset($TabAuteur))}
                        {foreach from=$TabAuteur item=auteurs}
                        <option value="{$auteurs.id}" {$auteurs.selec}>{$auteurs.nom}</option>
                        {/foreach}
                        {/if}
                    </select></p>
                    <p><label for="leType">Type de l'article : </label><select name="letype" id="leType">
                        <option value="0">--</option>
                        {if (isset($TabType))}
                        {foreach from=$TabType item=types}
                        <option value="{$types.id}" {$types.selec}>{$types.nom}</option>
                        {/foreach}
                        {/if}
                    </select></p>
                    <p><label for="mdp">Mot de passe photo : </label><input type="text" name="mdp" id="mdp" value="{$mdp}"></p>
                    <p><label for="monteur">Monteur : </label><input type="text" name="monteur" id="monteur" value="{$monteur}"></p>
                    <p><input type="submit" value="Valider"></p>
                </form>
            </div>

        </div>
    </main>
    <script src="scripts/gere_article.js"></script>