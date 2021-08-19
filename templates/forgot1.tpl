<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/forgot.css">
    <title>Perte mot de passe</title>
</head>

<body>
    <h1>Récupération mot de passe</h1>
    <form action="forgot_pass.php" method="post">
        <p>Afin de vous envoyer un nouveau mot de passe, veuillez remplir le formulaire ci-dessous</p>
        <p><label for="login">Votre login : </label><input type="text" name="login" id="login"></p>
        <p><label for="mail">Votre adresse Mail : </label><input type="text" name="mail" id="mail"></p>
        <p><input type="submit" value="Valider"></p>
    </form>
    <script src="scripts/forgot.js"></script>
</body>

</html>