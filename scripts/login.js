function connexion() {
    let Login = document.getElementById('utilisateur');
    let Mdp = document.getElementById('mdp');
    if (Login.value == '') {
        Login.classList.add('bad');
    } else {
        Login.classList.remove('bad');
    }
    if (Mdp.value == '') {
        Mdp.classList.add('bad');
    } else {
        Mdp.classList.remove('bad');
    }
    if ((Login.value != '') & (Mdp.value != '')) {
        Login.classList.remove('bad');
        Mdp.classList.remove('bad');
        let Formulaire = document.forms[0];
        Formulaire.submit();
    }
}
document.addEventListener("keydown", function(event) {

    if (event.key == 'Enter')
        connexion();

});