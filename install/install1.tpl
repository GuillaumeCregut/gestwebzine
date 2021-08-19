<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>installation plateforme</title>
</head>

<body>
    <h1>Installateur plateforme webzine plastikDream</h1>
    <div class="presentation">
        <p>Cette page permet l'installation de la plateforme de gestion du webzine sur un serveur disposant de PHP V7 et de MySQL V8 pour un fonctionnement optimal.
        </p>
        <p>L'installation et la configuration de la base de données se passe de manière automatique. Il faut simplement remplir les champs suivants pour un bon déroulé de l'opération.</p>
        <p>L'installation de la base de données peut prendre un peu de temps, en fonction de la connexion avec le serveur de données.</p>
    </div>
    <form action="install.php" method="post">
        <div class="bdd">
            <p>paramètres de connexion à la base de données :</p>
            <p>Login serveur :<input name="login_base" type="text"><br> Mot de passe serveur : <input name="pass_base" type="text"><br> Serveur de données : <input name="serveur_base" type="text"><br> Nom de la base de données (elle n'est pas crée automatiquement)
                : <input name="nom_base" type="text"><br>
            </p>
        </div>
        <div class="user">
            <p>Nom de l'administrateur : <input name="nom_admin" type="text"><br> Prénom de l'administrateur : <input name="prenom_admin" type="text"><br> Login de l'administrateur : <input name="admin_login" type="text"><br> Mot de passe de l'administrateur
                :
                <input name="admin_pass" type="text"><br> Adresse mail de l'administrateur : <input name="admin_mail" type="text"></p>

        </div>
    </form>
    <p><input name="bouton" value="Valider" type="button" id="bouton"></p>
    <script src="install.js"></script>
</body>

</html>