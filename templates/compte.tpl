<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/compte.css">
    <title>Mon compte</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li>
                        <a href="index.php">Accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Mon compte</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            {if isset($Ok_Msg)}
            <p>{$Ok_Msg}</p>
            {/if}
            <form action="compte.php" method="post">
                <p><label for="new_MDP">Nouveau mot de passe : </label><input type="password" name="new_MDP" id="new_MDP"></p>
                <p><input type="button" value="Changer le mot de passe" onclick="verif_pass()"></p>
            </form>
        </div>
    </main>
    <script src="scripts/compte.js"></script>