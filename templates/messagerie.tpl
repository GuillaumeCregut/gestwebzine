<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/messagerie.css">
    <title>Messagerie</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <nav>
                <ul>
                    <li><a href="equipe.php"> Equipe</a></li>
                    <li><a href="index.php">Retour à l'accueil</a></li>
                </ul>
            </nav>
            <div class="user">{$PrenomLogin}</div>
            <h2>Messagerie</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <div class="gauche">
                <p>Destinataires</p>
                <div class="listedestinataires" ondrop="drop(event)" ondragover="allowDrop(event)" id='liste'>
                    {if isset($TabDest)} {foreach from=$TabDest item=info}
                    <p class="equipe" draggable="true" ondragstart="drag(event)" id="{$info.id_utilisateur}">{$info.nom} {$info.prenom}</p>
                    {/foreach} {/if}
                </div>
            </div>
            <div class="interface">
                <form action="envoimessage.php" method="post" id="form_envoi">
                    <input type="hidden" name="destinataires" id="champDestinataires">
                    <p>Destinataires :</p>
                    <div class="destinataires" ondrop="drop(event)" ondragover="allowDrop(event)" id="DestList">
                        {if isset($DestOK)}
                        <p class="equipe" draggable="true" ondragstart="drag(event)" id="{$idUser}">{$Prenom} {$Nom}</p>
                        {/if}
                    </div>
                    <p><label for="sujet">Sujet : </label><input type="text" name="sujet" id="sujet"></p>
                    <div class="message">
                        <p>Message :</p>
                        <textarea name="message" id="taMessage" cols="30" rows="10"></textarea>
                    </div>
                    <div class="boutons"><input type="button" value="Envoyer" onclick="envoyer()"></div>
                </form>
            </div>

        </div>
    </main>
    <script src="scripts/messagerie.js"></script>