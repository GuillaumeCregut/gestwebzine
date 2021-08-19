<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/gestion.css">
    <title>Accueil</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <div class="user">{$PrenomLogin}</div>
            <h2>Accueil Administration globale</h2>
            <div class="main_conteneur">
                <nav>
                    <ul>
                        <li><a href="../index.php">Accueil</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <p>Historique du système</p>
            <div class="histo_tab">
                <table>
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>Utilisateur</td>
                            <td>Action</td>
                            <td>Quoi</td>
                        </tr>
                    </thead>
                    <tbody>
                        {if isset($Table_Histo)} {foreach from=$Table_Histo item=infos}
                        <tr>
                            <td>{$infos.date_histo}</td>
                            <td>{$infos.prenom} {$infos.nom}</td>
                            <td>{$infos.nom_action}</td>
                            <td>{$infos.quoi}</td>
                        </tr>
                        {/foreach} {/if}
                    </tbody>
                </table>
            </div>
            <p><a href="https://phpmyadmin.cluster003.hosting.ovh.net/index.php" target="_blank">Base de données</a></p>
        </div>
    </main>
    <footer>
        <div class="main_conteneur">
            <p>(c)2021 Editiel98 - G. Crégut Pour PlastiDream V0.2</p>
        </div>
    </footer>

</body>

</html>