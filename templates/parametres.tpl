<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/parametres.css">
    <title>Paramètres</title>
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
            <h2>Paramètres</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <nav>
                <ul>
                    <li><a href="gest_article.php">Gestion des articles</a></li>
                    <li><a href="gest_webzine.php">Gestion des webzines</a></li>
                </ul>
            </nav>
            <div class="form_cont">
                <form action="parametres.php" method="post">
                    <p><label for="delai">Délai de rappel des articles (en jours) avant parution : </label> <input type="text" name="delai" id="delai" value={$LeDelai}></p>
                    <p><label for="admail">Adresse Mail administrateur : </label><input type="mail" id="admail" name="admail" value="{$LeMail}"></p>
                    <p><input type="submit" value="valider"></p>
                </form>
            </div>
            <div class="form_cont">
                <form action="parametres.php" method="post">

                    <div class="conteneur_global">
                        <div class="cont_liste">
                            <p>Liste des types d'articles disponibles</p>
                            <ul>
                                {if isset($TabTypes)} {foreach from=$TabTypes item=info}
                                <li>{$info.Nom_Type}</li>
                                {/foreach} {/if}
                            </ul>
                        </div>
                        <div class="cont_param">
                            <p><label for="NouvType">Ajouter un nouvau type : </label><input type="text" name="NouvType" id="NouvType"></p>
                            <p><input type="submit" value="Ajouter"></p>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <!-- <div class="form_cont">
                <form action="parametres.php" method="post">
                    <input type="hidden" name="purge" value="1">
                    <p>Purger les archives (suppression définitives des webzines archivés): <input type="submit" value="Purger"></p>
                </form>
            </div>-->
        </div>
    </main>