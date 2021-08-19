var Id_Usager = 0;
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
    Id_Usager = ev.target.id;
}

function drop(ev) {
    let Cible = 0;
    let Id_Espace = document.getElementById('EspaceId').value;
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    let TargertDiv = ev.target.id;
    if ((TargertDiv == 'concernes') | (TargertDiv == 'dispo')) {
        //ON envoie la requete de déplacement
        if ((TargertDiv == 'concernes')) {
            Cible = 1;
        } else {
            Cible = 0;
        }
        //On envoie la requete Ajax et on traite le retour
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
                    }
                }
            }
            //On envoie la requete
        xhr.open("POST", "ajax/ajax_user_espace.php", true); //on appelle la page avec la méthode post en asychrone
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send("id_Usager=" + Id_Usager + '&cible=' + Cible + '&Espace=' + Id_Espace); //ID post sera le $_POST[''] et ValuePost sa valeur
        console.log('Envoie usager #' + Id_Usager + ' Espace :' + Id_Espace + ' vers ' + Cible);

    }
}

function clicValide() {
    let caseCocher = document.getElementById('coche_relecture');
    let EtatcaseCocher = caseCocher.checked;
    let action = 1; //on considère que la case n'est pas cochée
    let OldEtat = 0;
    if (EtatcaseCocher) {
        action = 2; //si elle l'est, alors on passe à 1
        OldEtat = 1;
    }
    let xhr = getXhr();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //Fonction de traitement de l'information
            //On utilise xhr.responseText
            reponse = xhr.responseText;
            //On traduit le json et on traite le résultat
            let JSONResponse = JSON.parse(reponse);
            let Retour = JSONResponse[0]['Retour'];
            if (Retour == 0) //La manip n'a pas fonctionnée
            {
                if (OldEtat == 1) {
                    caseCocher.checked = true;
                } else {
                    caseCocher.checked = false;
                }
            } else {
                //En fonction du resultat obtenu
                //On change juste le texte, la case à cocher est déjà dans le bon état suite à la manip utilisateur
                let TexteAffiche = document.getElementById('etatTexte');
                let Liste = document.getElementById('Listechoix');
                let Indice = 0;
                if (caseCocher.checked) {
                    Indice = 1;
                }
                let TexteData = Liste.options[Indice].value;
                //Récupérer les noms d'états dans un dataset du HTML
                TexteAffiche.innerHTML = TexteData;
            }


            console.log(reponse);
        }
    }
    let donnees = new FormData();
    donnees.append('action', action);
    let id_article = document.getElementById('id_article').value;
    donnees.append('id_article', id_article);
    xhr.open("POST", "ajax/ajax_relecture.php", true); //on appelle la page avec la méthode post en asychrone
    xhr.send(donnees);
    console.log(action);
}
//Gestion des events
//Usagers
let Usagers = document.getElementsByClassName('utilisateur');
Array.prototype.forEach.call(Usagers, function(el) {
    el.addEventListener('dragstart', function(event) {
        drag(event);
    })
});
//Dépots
let Depots = document.getElementsByClassName('utilisateurs_box');
Array.prototype.forEach.call(Depots, function(el) {
    el.addEventListener('drop', function(event) {
        drop(event);
    });
    el.addEventListener('dragover', function(event) {
        allowDrop(event);
    });
});