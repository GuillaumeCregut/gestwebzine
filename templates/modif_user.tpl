<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/integration.css">
    <title>Webzine - Equipe</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="equipe.php">Retour à l'équipe</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Modification de l'utilisateur</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <p>Modification de l'utilisateur : {$Nom}</p>
            <form action="modif_user.php" method="post">
                <input type="hidden" name="modif" value="{$Id_User}">
                <p><label for="rang">Nouvelle fonction : </label><select name="new_rang" id="rang">
                    {foreach from=$TabRang item=info}
                    <option value="{$info.Valeur}" {$info.checked}>{$info.Nom}</option>
                    {/foreach}
                </select></p>
                <p><input type="submit" value="Modifier"></p>
            </form>
        </div>
    </main>