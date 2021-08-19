<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="styles/general.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webzine - Contacter l'équipe</title>
    <link rel="stylesheet" href="styles/messagerie.css">
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="webzine.php">Retour au webzine</a></li>
                    <li><a href="all_webzine.php">Liste des webzines</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Contact</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <div class="interface">
                <form action="contact_equipe.php" method="post" id="form_envoi">
                    <p>Destinataires :</p>
                    <div class="destinataires">
                        {if isset($TabContact)} {foreach from=$TabContact item=info}
                        <p class="equipe">{$info.nom}</p>
                        <input type="hidden" name="dest[]" value="{$info.id}">{/foreach} {/if}
                    </div>
                    <p><label for="sujet">Sujet : </label><input type="text" name="sujet" id="sujet"></p>
                    <div class="message">
                        <p>Message :</p>
                        <textarea name="message" id="taMessage" cols="30" rows="10"></textarea>
                    </div>
                    <div class="boutons"><input type="button" value="Envoyer" onclick="envoi_message()"></div>
                </form>
            </div>
        </div>
    </main>
    <script src="scripts/contact_equipe.js"></script>