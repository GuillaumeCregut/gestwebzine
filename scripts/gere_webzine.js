function valide() {
    let formulaire = document.getElementById('form_webzine');
    if (confirm('Voulez-vous archiver ce webzine et supprimer toutes les sources ?')) {
        formulaire.submit();
    }
}