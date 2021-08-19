<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Connexion</title>
</head>

<body>
    <div class="fond"></div>
    <div class="conteneur">
        <div class="login">
            <div class="logo"><img src="img/logo.png" alt="logo"></div>
            {if isset($Erreur)}
            <span class="invalide">Login ou mot de passe invalide</span> {/if}
            <form action="index.php" method="post">
                <p>Login : <br><input type="text" name="utilisateur" id="utilisateur"></p>
                <p>Mot de passe : <br><input type="password" name="mdp" id="mdp"></p>
                <p><a href="forgot_pass.php">J'ai oublié mon mot de passe</a></p>
                <p class="bouton" onclick="connexion()">Connexion</p>

            </form>
        </div>
    </div>
    <script src="scripts/login.js"></script>
</body>

</html>