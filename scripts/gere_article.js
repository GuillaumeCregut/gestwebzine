function valide() {
    if (confirm('Voulez vous supprimer tout ce qui concerne cet article ?')) {
        let formulaire = document.getElementById('suppForm');
        formulaire.submit();
    }
}