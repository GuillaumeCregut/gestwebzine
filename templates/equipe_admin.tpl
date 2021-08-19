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
            <div class="commentaire">Vous trouverez la listes des membres ci dessous. <br> en cliquant sur le bouton mail, il est possible de le contacter par messagerie.<br> En double cliquant sur son nom, vous pouvez le modifier. En cochant la case désactiver, vous lui interdisez
                l'accès au système. Enfin, dans la partie de droite, vous pouvez ajouter un membre.
            </div>
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
                            <td>Désactiver</td>
                        </thead>
                        <tbody>
                            <form action="messagerie.php" method="post">
                                <input type="hidden" name="destinataire" id="destinataire">
                            </form>
                            <form action="modif_user.php" method="post">
                                <input type="hidden" name="id_user">
                            </form>
                            {if isset($TabUser)} {foreach from=$TabUser item=info}
                            <tr>
                                <td class="mail">
                                    <div class="mailbtn" onclick="messagerie('{$info.id_utilisateur}')"></div>
                                </td>
                                <td class="clic" ondblclick="modifUser('{$info.id_utilisateur}')">
                                    <span id="Nom{$info.id_utilisateur}" class="{$info.rang} {$info.is_valable}">{$info.nom}</span></td>
                                <td><span id="Prenom{$info.id_utilisateur}" class="{$info.rang} {$info.is_valable}">{$info.prenom}</span></td>
                                <td><input type="checkbox" name="cb_valide" id="cb_valide{$info.id_utilisateur}" {$info.Coche} onclick="desactive('{$info.id_utilisateur}', this)"></td>
                            </tr>
                            {/foreach} {/if}
                        </tbody>
                    </table>
                </p>
            </div>
            <div class="ajout_membre">
                <form action="equipe.php" method="post">
                    <p>Ajouter un membre : </p>
                    <p><label for="nom">Nom : </label> <input type="text" name="nom" id="nom"></p>
                    <p><label for="prenom">Prénom : </label> <input type="text" name="prenom" id="prenom"></p>
                    <p><label for="email">Adresse Mail : </label><input type="mail" name="email" id="email"></p>
                    <p><label for="login">Login : </label> <input type="text" name="login" id="login"></p>
                    <p><label for="mdp">Mot de passe : </label> <input type="password" name="mdp" id="mdp"></p>
                    <p><label for="role">Rôle : </label><select name="role" id="role">
                        <option value="{$Admin}">Rédacteur en chef</option>
                        <option value="{$Redacteur}">Rédacteur</option>
                        <option value="{$Relecteur}">Relecteur</option>
                        <option value="{$Graphiste}">Graphiste</option>
                       <option value="{$AdSys}">Administrateur système</option>
                    </select></p>
                    <p><input type="button" value="ajouter" onclick="ajouter()"></p>
                </form>
                <div class="import_usager">
                    <p><a href="integration.php">Importer une liste d'utilisateur</a></p>
                </div>
            </div>
        </div>
    </main>
    <script src="scripts/equipe.js "></script>