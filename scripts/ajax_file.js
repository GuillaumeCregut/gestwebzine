function _(el) {
    return document.getElementById(el);
}

function uploadFile() {
    var file = _("fichierTexte").files[0];
    console.log(file.name + " | " + file.size + " | " + file.type);
    var formdata = new FormData();
    let Id_Article = _('Id_Article').value;
    formdata.append("file1", file);
    formdata.append('action', 1);
    formdata.append('id_article', Id_Article);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "ajax/ajax_file_article.php");
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
    let Tabretour = JSON.parse(RetourJson);
    console.log(Tabretour);
    let EtatRetour = Tabretour[0]['Retour'];
    console.log('Etat : ' + EtatRetour);
    let TexteRetour = Tabretour[0]['Texte'];
    console.log('Texte : ' + TexteRetour);
    _("statusText").innerHTML = TexteRetour;
    // _("progressBarTexte").value = 0;
    console.log(event.target.responseText);
    let FichierOK = _('Fichier_OK'); //On récupère la variable fichier présent
    FichierOK.value = EtatRetour;
}

function errorHandler(event) {
    _("statusText").innerHTML = "Echec du téléchargement";
}

function abortHandler(event) {
    _("statusText").innerHTML = "Téléchargement interrompu";
}