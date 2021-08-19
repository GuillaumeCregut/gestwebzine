function verif_pass() {
    let MotPasse = document.getElementById('new_MDP').value;
    if (MotPasse.length == 0) {
        alert('Veuillez saisir un mot de passe');
        return false;
    }
    let LeFormulaire = document.forms[0];
    LeFormulaire.submit();
}