<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/equipe.css">
    <title>Webzine - Equipe</title>
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
            <h2>L'équipe</h2>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <div class="liste_equipe">
                <p>
                    <table>
                        <thead>
                            <td></td>
                            <td>Nom</td>
                            <td>Prénom</td>
                        </thead>
                        <tbody>
                            <form action="messagerie.php" method="post">
                                <input type="hidden" name="destinataire" id="destinataire">
                            </form>
                            {if isset($TabUser)} {foreach from=$TabUser item=info}
                            <tr>
                                <td class="mail">
                                    <div class="mailbtn" onclick="messagerie('{$info.id_utilisateur}')"></div>
                                </td>
                                <td>
                                    <span id="Nom{$info.id_utilisateur}" class="{$info.is_valable} {$info.rang}">{$info.nom}</span></td>
                                <td><span id="Prenom{$info.id_utilisateur}" class="{$info.is_valable} {$info.rang}">{$info.prenom}</span></td>
                            </tr>
                            {/foreach} {/if}
                        </tbody>
                    </table>
                </p>
            </div>
        </div>
    </main>
    <script src="scripts/equipe.js"></script>