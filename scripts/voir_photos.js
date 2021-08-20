function _(el) {
    return document.getElementById(el);
}

function uploadFile() {
    var formdata = new FormData();
    var file = _("fichier").files;
    var nbFichiers = file.length;
    var i = 0;
    while (i < nbFichiers) {
        var fichier = file[i];
        // console.log(fichier.name);
        formdata.append("file1[]", fichier);
        i++;
    }
    let rep = _("rep").value;
    formdata.append('repertoire', rep);
    let Id_Article = _('id_article').value;
    formdata.append('id_article', Id_Article);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "ajax/ajax_valide_photo.php");
    ajax.send(formdata);
}

function progressHandler(event) {
    _("loaded_n_totalText").innerHTML = "téléchargé " + event.loaded + " octets sur " + event.total;
    var percent = (event.loaded / event.total) * 100;
    _("progressBarTexte").value = Math.round(percent);
    _("statusText").innerHTML = Math.round(percent) + "% téléchargé... veuillez patienter";
}

function completeHandler(event) {
    //Décodage du retour JSON
    let RetourJson = event.target.responseText;
    // console.log(RetourJson);
    let Tabretour = JSON.parse(RetourJson);
    // console.log(Tabretour);
    let EtatRetour = Tabretour[0]['Retour'];
    if (EtatRetour > 0) {
        _("valide_span").innerHTML = "Oui";
    }
    console.log('Etat : ' + EtatRetour);
    let TexteRetour = Tabretour[0]['Texte'];
    //console.log('Texte : ' + TexteRetour);
    _("statusText").innerHTML = TexteRetour;
    // _("progressBarTexte").value = 0;
    //console.log(event.target.responseText);
    _("loaded_n_totalText").innerHTML = '';
}

function errorHandler(event) {
    _("statusText").innerHTML = "Echec du téléchargement";
}

function abortHandler(event) {
    _("statusText").innerHTML = "Téléchargement interrompu";
}

function creer_table(coche) {
    if (coche.checked) {
        if (confirm('Voulez vous procéder à la création du système ?')) {
            //On traite
            let ajax2 = new XMLHttpRequest();
            ajax2.addEventListener("load", DoneTable, false);
            let formdata = new FormData();
            let Id_Article = _('id_article').value;
            formdata.append('id_article', Id_Article);
            ajax2.open("POST", "ajax/ajax_creer_photo.php");
            ajax2.send(formdata);

        } else {
            coche.checked = false;
        }

    }
}

function DoneTable(event) {
    let RetourJson = event.target.responseText;
    //console.log(RetourJson);
    let Tabretour = JSON.parse(RetourJson);
    let EtatRetour = Tabretour[0]['Retour'];
    if (EtatRetour > 0) {
        //Si retour OK
        _('affiche_case').classList.add('cacher'); //Passe à caché
        _('envoi_box').classList.remove('cacher'); //Passe à visible
    }
}
let LeBouton = document.getElementById('uploadFileBtn');
LeBouton.addEventListener('click', uploadFile);