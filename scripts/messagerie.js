function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text/plain", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    let LeID = ev.target.id;
    if (LeID == 'DestList' | LeID == 'liste') {
        ev.target.appendChild(document.getElementById(data));
    }
}

function envoyer() {
    let ContDest = document.getElementById('DestList');
    let EnsembleDest = ContDest.childNodes;
    let MiseEnForme = '';
    // console.log(EnsembleDest.length);
    for (let i = 0; i < EnsembleDest.length; i++) {
        if (EnsembleDest[i].id !== undefined)
            MiseEnForme += EnsembleDest[i].id + ';';
    }
    //Vérification
    if (MiseEnForme == '') {
        alert('Veuillez saisir au moins 1 destinataire');
        return false;
    }
    let LeSujet = document.getElementById('sujet').value;
    if (LeSujet == '') {
        alert('Le sujet est vide !');
        return false;
    }
    let LeMessage = document.getElementById('taMessage').value;
    if (LeMessage == '') {
        alert('Le message est vide !');
        return false;
    }
    let LesDestinataires = document.getElementById('champDestinataires');
    LesDestinataires.value = MiseEnForme;

    let LeFormulaire = document.getElementById('form_envoi');
    LeFormulaire.submit();
}