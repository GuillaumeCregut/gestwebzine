function verif_form() {
    let formulaire = document.forms[0];
    let erreur = false;
    let taille = formulaire.elements.length;
    console.log(taille);
    for (i = 0; i < taille; i++) {
        console.log(formulaire.elements[i].value);
        let j = formulaire.elements[i].value;
        if (j == '') {
            erreur = true;
            console.log(j);
            break;
        }
    }
    if (erreur) {
        alert('Veuillez remplir tous les champs !');
    } else {
        formulaire.submit();
    }
}
let bouton = document.getElementById('bouton');
bouton.addEventListener('click', verif_form);