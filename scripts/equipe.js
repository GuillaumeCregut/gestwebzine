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
//Fonctions propres
//Désactive ou active un utilisateur
function desactive(id, item) {
    //Faire le traitement en ajax
    let LeNom = document.getElementById('Nom' + id);
    let Prenom = document.getElementById('Prenom' + id);
    let action = -1;
    if (item.checked) {
        LeNom.classList.add('supprime');
        Prenom.classList.add('supprime');
        action = 0;
    } else {
        LeNom.classList.remove('supprime');
        Prenom.classList.remove('supprime');
        action = 1;
    }
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
                    if (item.checked) {
                        LeNom.classList.add('supprime');
                        Prenom.classList.add('supprime');
                    } else {
                        LeNom.classList.remove('supprime');
                        Prenom.classList.remove('supprime');
                    }
                }
                //fin
            }
        }
        //On envoie la requete
    xhr.open("POST", "ajax/ajax_active.php", true); //on appelle la page avec la méthode post en asychrone
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("id_user=" + id + '&action=' + action); //ID post sera le $_POST[''] et ValuePost sa valeur

}
//Dirige vers la messagerie
function messagerie(id) {
    let LeFormulaire = document.forms[0];
    let IdDest = LeFormulaire.elements[0];
    IdDest.value = id;
    LeFormulaire.submit();
}

function ajouter() {
    let LeFormulaire = document.forms[2];
    //Vérification des informations du formulaire
    let Champs = LeFormulaire.elements;
    let OK = true;
    for (let i = 0; i < Champs.length; i++) {
        if (Champs[i].value == '') {
            alert('Veuillez remplir tous les champs');
            OK = false;
            break;
        }
    }
    //Si tout OK, on valide
    if (OK) {
        LeFormulaire.submit();
    }

}

function modifUser(id) {
    let LeFormulaire = document.forms[1];
    let Champs = LeFormulaire.elements[0];
    Champs.value = id;
    LeFormulaire.submit();
}