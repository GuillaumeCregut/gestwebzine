let Formulaire = document.forms[0];
Formulaire.addEventListener("submit", function(evt) {
    let MDP = document.getElementById('mdp').value;
    if (MDP == '') {
        alert('Veuillez saisir le mot de passe');
        evt.preventDefault();
        return false;
    }
});

function _(el) {
    return document.getElementById(el);
}

function uploadFile() {
    //var file = _("fichier").files[0];
    var formdata = new FormData();
    var file = _("fichier").files;
    var nbFichiers = file.length;
    var i = 0;

    while (i < nbFichiers) {
        var fichier = file[i];
        console.log(fichier.name);
        formdata.append("file1[]", fichier);
        i++;
    }
    //console.log(file.name + " | " + file.size + " | " + file.type);

    let Id_Article = _('id_article').value;
    //formdata.append("file1", file);
    // formdata.append("file1", file);
    formdata.append('Article', Id_Article);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "ajax/ajax_file_photo.php");
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
    console.log(RetourJson);
    let Tabretour = JSON.parse(RetourJson);
    console.log(Tabretour);
    let EtatRetour = Tabretour[0]['Retour'];
    console.log('Etat : ' + EtatRetour);
    let TexteRetour = Tabretour[0]['Texte'];
    console.log('Texte : ' + TexteRetour);
    _("statusText").innerHTML = TexteRetour;
    // _("progressBarTexte").value = 0;
    console.log(event.target.responseText);
    _("loaded_n_totalText").innerHTML = '';
    //let FichierOK = _('Fichier_OK'); //On récupère la variable fichier présent
    //FichierOK.value = EtatRetour;
}

function errorHandler(event) {
    _("statusText").innerHTML = "Echec du téléchargement";
}

function abortHandler(event) {
    _("statusText").innerHTML = "Téléchargement interrompu";
}