function editWebzine(element) {
    let LeID = element.id;
    //Récupère le formulaire
    let LeFormulaire = document.forms[0];
    let Champs = LeFormulaire.elements[0];
    Champs.value = LeID;
    console.log(Champs.value);
    LeFormulaire.submit();
}

function creer_webzine() {
    //Récupère le formulaire
    let LeFormulaire = document.forms[1];
    let nomWebzine = LeFormulaire.elements[0].value;
    let DateParution = LeFormulaire.elements[1].value;
    if ((nomWebzine == '') | (DateParution == '')) {
        alert('Veuillez remplir le formulaire');
        return false;
    }
    //Formatage de la date pour l'afficher
    let madate = new Date(DateParution);
    let dateAffiche = madate.toLocaleDateString();
    if (confirm('Voulez vous créer le webzine' + nomWebzine + ' à paraitre le ' + dateAffiche + '?')) {
        LeFormulaire.submit();
    }
}
//Ajout 19/08
//Ajout events sur la liste des webzines
let LesWebzine = document.getElementsByClassName('webzine');
Array.prototype.forEach.call(LesWebzine, function(el) {
    // ajouteEvent(el);
    el.addEventListener('click', function(e) {
        editWebzine(this);
    })
});
//Ajout event sur le bouton création
let LeBouton = document.getElementById('creer_btn');
LeBouton.addEventListener('click', creer_webzine);