function editWebzine(element) {
    let LeID = element.id;
    //Récupère le formulaire
    let LeFormulaire = document.forms[0];
    let Champs = LeFormulaire.elements[0];
    Champs.value = LeID;
    console.log(Champs.value);
    LeFormulaire.submit();
}

function DeleteFiles() {
    if (confirm('Voulez-vous supprimer les fichiers archivés ?')) {
        let formulaire = document.forms[1];
        formulaire.submit();
    }
}