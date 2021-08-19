var Id_Article = 0;
var NomWebzine = '';
//Fonction AJAX
function getXhr() {
    var xhr = null;
    if (window.XMLHttpRequest) // Firefox et autres
        xhr = new XMLHttpRequest();
    else if (window.ActiveXObject) { // Internet Explorer 
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
    } else { // XMLHttpRequest non supporté par le navigateur 
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
        xhr = false;
    }
    return xhr;
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text/plain", ev.target.id);
    Id_Article = ev.target.id;
}

function drop(ev) {
    let Cible = 0;
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    let TargertDiv = ev.target.id;
    if ((TargertDiv == 'article_web') | (TargertDiv == 'article_dispo')) {
        //ON envoie la requete de déplacement
        if ((TargertDiv == 'article_web')) {
            Cible = 1;
            NomWebzine = document.getElementById('Nom_Webzine').value;
        } else {
            Cible = 0;
            NomWebzine = 'Non attribué';
        }
        console.log('Envoie article #' + Id_Article + ' vers ' + Cible);
        //Envoie la requete au serveur
        var xhr = getXhr();
        xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    //Fonction de traitement de l'information
                    //On utilise xhr.responseText
                    reponse = xhr.responseText;
                    //On traduit le json
                    tabRetour = JSON.parse(reponse);
                    console.log(tabRetour);
                    let Resultat = -1;
                    //a continuer
                    for (i = 0; i < tabRetour.length; i++) {
                        Resultat = tabRetour[i].retour;
                    }
                    if (Resultat == 1) {
                        //Si requete OK, alors on autorise le déplacement
                        ev.target.appendChild(document.getElementById(data));
                        //On change le nom du webzine affecté
                        let id_span = 'web_' + Id_Article;
                        console.log(id_span);
                        let LeSpan = document.getElementById(id_span);
                        console.log(NomWebzine);
                        LeSpan.innerHTML = NomWebzine;
                    }
                    //fin
                }
            }
            //On envoie la requete
        xhr.open("POST", "ajax/ajax_article_webzine.php", true); //on appelle la page avec la méthode post en asychrone
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send("id_Article=" + Id_Article + '&cible=' + Cible); //ID post sera le $_POST[''] et ValuePost sa valeur
    }

}

function editArticle(item) {
    //Récupère le formulaire
    let LeFormulaire = document.forms[1];
    let Champs = LeFormulaire.elements[0];
    Champs.value = item;
    console.log(Champs.value);
    LeFormulaire.submit();

}

function allowNom() {
    let Champ = document.getElementById('Nom_Webzine');
    Champ.readOnly = !(Champ.readOnly);
}

function allowDate() {
    let Champ = document.getElementById('date_parution');
    Champ.readOnly = !(Champ.readOnly);
}

function allowEtat() {
    let Champ = document.getElementById('etat_webzine');
    Champ.disabled = !(Champ.disabled);
}

function modif_infos() {
    //Vérification et envoi du formulaire
    let LeFormulaire = document.forms[0];
    let CB_Archive = document.getElementById('cb_archive');
    if (CB_Archive.checked) {
        if (confirm('Voulez vous archiver ce webzine ? Cela détruira les fichiers articles stockés sur le seveur')) {
            let action = document.getElementById('action');
            console.log(action.value);
            action.value = '4';
            console.log(action.value);
            LeFormulaire.submit();
        }
    } else {
        LeFormulaire.submit();
    }

}

function archive(item) {
    if (item.checked) {
        if (confirm('Voulez vous archiver ce webzine ? Cela détruira les fichiers articles stockés sur le seveur')) {
            //Do something
            let LeFormulaire = document.forms[0];
            let ActionForm = LeFormulaire.elements[0];
            ActionForm.value = "2";
            LeFormulaire.submit();
        }
    }
}