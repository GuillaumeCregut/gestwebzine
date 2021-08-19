//Fonction AJAX
var Id_Auteur = 0;

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

function add_message() {
    //Récupération du message à envoyer
    auteur = Id_Auteur;
    let MessageEnvoyer = document.getElementById('text_message').value;
    let IdArticle = document.getElementById('id_article1').value;
    if (MessageEnvoyer != '') {
        if (confirm('Voulez-vous ajouter le message ?')) {
            console.log('envoi du message, auteur : ' + auteur);
            console.log('Article : ' + IdArticle);
            console.log('Message : ' + MessageEnvoyer);
            //
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
                        Resultat = tabRetour['retour'];
                        if (Resultat == 1) {
                            //Si requete OK, alors on ajoute le nouveau message
                            console.log('ajout');
                            let ConteneurMessage = document.getElementById('contientMessages');
                            let Auteur = tabRetour['Auteur'];
                            let Date_Message = tabRetour['Date_message'];
                            let CorpsMessage = tabRetour['corps'];
                            /* architecture du message
                            div.message
                                div.auteur
                                    p
                                    p
                                span.vertical-line
                                div.corps
                                    p
                             */
                            let DivMessage = document.createElement('div');
                            DivMessage.classList.add('message');
                            let DivAuteur = document.createElement('div');
                            DivAuteur.classList.add('auteur');
                            let PAuteur = document.createElement('p');
                            let NomAuteur = document.createTextNode('Auteur : ' + Auteur);
                            PAuteur.appendChild(NomAuteur);
                            let PDate = document.createElement('p');
                            let DateMessage = document.createTextNode('Date : ' + Date_Message);
                            PDate.appendChild(DateMessage);
                            //Ajout des infos dans la div auteur
                            DivAuteur.appendChild(PAuteur);
                            DivAuteur.appendChild(PDate);
                            //Ajout dans la div message
                            DivMessage.appendChild(DivAuteur);
                            //Ajout du span
                            let LeSpan = document.createElement('span');
                            LeSpan.classList.add('vertical-line');
                            DivMessage.appendChild(LeSpan);
                            //Ajout du corps du message
                            let DivCorps = document.createElement('div');
                            DivCorps.classList.add('corps');
                            let PCorps = document.createElement('p');
                            //
                            //Suppression eventuelle des </br>
                            let replacement = '<br />';
                            let j = 0;
                            if (CorpsMessage.indexOf(replacement) > 0) {
                                let i = CorpsMessage.indexOf(replacement);
                                while (i > 0) {

                                    //On récupère la première ligne
                                    let bout = CorpsMessage.slice(0, i);
                                    console.log(i + ' : ' + bout);
                                    //On l'ajoute à P
                                    let LeCorps = document.createTextNode(bout);
                                    PCorps.appendChild(LeCorps);
                                    //On ajoute BR
                                    let BR = document.createElement("BR");
                                    PCorps.appendChild(BR);
                                    //On supprime le BR du texte de base
                                    CorpsMessage = CorpsMessage.slice(i + 7);
                                    i = CorpsMessage.indexOf(replacement);
                                    console.log('Reste : ' + CorpsMessage);
                                    j++;
                                    if (j > 100) //Evites une boucle infinie
                                        break;
                                }
                                //Ajout du dernier
                                let LeCorps = document.createTextNode(CorpsMessage);
                                PCorps.appendChild(LeCorps);
                            } else {
                                let LeCorps = document.createTextNode(CorpsMessage);
                                PCorps.appendChild(LeCorps);
                            }
                            //
                            // let LeCorps = document.createTextNode(CorpsMessage);
                            // PCorps.appendChild(LeCorps);
                            DivCorps.appendChild(PCorps);
                            //Ajout du corps du message au message
                            DivMessage.appendChild(DivCorps);
                            //Ajout du message a l'ensemble
                            ConteneurMessage.insertBefore(DivMessage, ConteneurMessage.firstChild);
                            //On efface l'espace de saisi
                            let MessageConteneur = document.getElementById('text_message');
                            MessageConteneur.value = '';
                        }
                        //fin
                    }
                }
                //On envoie la requete 
            xhr.open("POST", "ajax/ajax_message_redaction.php", true); //on appelle la page avec la méthode post en asychrone
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('auteur=' + auteur + '&texte_message=' + MessageEnvoyer); //ID post sera le $_POST[''] et ValuePost sa valeur
            //
        }
    }
}

function verrouillage() {
    //Effectuer la requete AJAX pour action verrouillage dévérouillage
    let Cadenas = document.getElementById('cadenas');
    let Verrou = document.getElementById('verrou');
    let Action = document.getElementById('verrouillage');
    let NouvelEtat;
    if (EtatEncours == 1) {
        NouvelEtat = 0;
        EtatEncours = 0;
    } else {
        NouvelEtat = 1;
        EtatEncours = 1;
    }
    //
    var xhr = getXhr();
    xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                //Fonction de traitement de l'information
                //On utilise xhr.responseText
                reponse = xhr.responseText;
                //On traduit le json
                tabRetour = JSON.parse(reponse);
                let Resultat = -1;
                //a continuer
                Resultat = tabRetour[0]['retour'];
                if (Resultat == 1) {
                    //Traitement en retour
                    RetourEtat = tabRetour[0]['nouvelEtat'];
                    if (RetourEtat == 0) //Etait verrouillé
                    {
                        Cadenas.setAttribute('src', 'img/b_unlock.png');
                        Verrou.setAttribute('src', 'img/lock.png');
                        Action.innerHTML = "Verrouiller l'article";
                    } else {
                        Cadenas.setAttribute('src', 'img/b_lock.png');
                        Verrou.setAttribute('src', 'img/unlock.png');
                        Action.innerHTML = "Déverrouiller l'article";
                    }
                }
                //fin
            }
        }
        //On envoie la requete
    xhr.open("POST", "ajax/ajax_lock_article.php", true); //on appelle la page avec la méthode post en asychrone
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('etat=' + NouvelEtat); //ID post sera le $_POST[''] et ValuePost sa valeur
}

function telphoto(evt) {
    evt.preventDefault();
    let CheminBase = document.getElementById('rep_fichiers').value;
    console.log('télécharge les photos : ' + CheminBase);
    let page = 'getphoto.php?rep=' + CheminBase;
    window.open(page, "nom_popup", "menubar=no, status=no, location=no, scrollbars=no, menubar=no, width=200, height=100");
}

function telfichier(evt) {
    // evt.preventDefault();
    let Cadenas = document.getElementById('cadenas');
    let Verrou = document.getElementById('verrou');
    let Action = document.getElementById('verrouillage');
    EtatEncours = 1;
    //Verrouille l'article
    var xhr = getXhr();
    xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                //Fonction de traitement de l'information
                //On utilise xhr.responseText
                reponse = xhr.responseText;
                //On traduit le json
                tabRetour = JSON.parse(reponse);
                console.log('On a un retour');
                let Resultat = -1;
                //a continuer
                Resultat = tabRetour[0]['retour'];
                if (Resultat == 1) {
                    //Traitement en retour
                    RetourEtat = tabRetour[0]['nouvelEtat'];
                    if (RetourEtat == 0) //Etait verrouillé
                    {
                        Cadenas.setAttribute('src', 'img/b_unlock.png');
                        Verrou.setAttribute('src', 'img/lock.png');
                        Action.innerHTML = "Verrouiller l'article";
                    } else {
                        Cadenas.setAttribute('src', 'img/b_lock.png');
                        Verrou.setAttribute('src', 'img/unlock.png');
                        Action.innerHTML = "Déverrouiller l'article";
                    }
                    //On affecte le nom du grahiste
                    let GrafSpan = document.getElementById('pec');
                    let NomPrenom = tabRetour[1]['graf'];
                    GrafSpan.innerHTML = NomPrenom;
                }
                //fin
            }
        }
        //On envoie la requete 
    xhr.open("POST", "ajax/ajax_pec_article.php", true); //on appelle la page avec la méthode post en asychrone
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    console.log('on envoie');
    xhr.send('etat=1'); //ID post sera le $_POST[''] et ValuePost sa valeur
}

function valideArticle() {
    //On regarde l'état en cours
    console.log('Etat en cours : ' + StateArticleEnCours);
    let FuturEtat = 0;
    if (StateArticleEnCours == 6) //Si on est  au maximum
    {
        FuturEtat = LastState;
        console.log('on passe rétro');
    } else {
        FuturEtat = 6; //On passe au maximum
    }
    //On envoie la requete pour changer
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
                Resultat = tabRetour[0]['retour'];
                if (Resultat == 1) {
                    //Traitement en retour
                    RetourEtat = tabRetour[0]['nouvelEtat'];
                    NouveauNom = tabRetour[0]['nouveauNom'];
                    StateArticleEnCours = RetourEtat;
                    AfficheStatut.innerHTML = NouveauNom;
                    let Image = document.getElementById('ImgValide');
                    if (RetourEtat == 6) //Etait verrouillé
                    {
                        Image.setAttribute('src', 'img/unchecked.png');
                        // Verrou.setAttribute('src', 'img/lock.png');
                        // Action.innerHTML = "Verrouiller l'article";
                    } else {
                        Image.setAttribute('src', 'img/checked.png');
                        // Verrou.setAttribute('src', 'img/unlock.png');
                        // Action.innerHTML = "Déverrouiller l'article";
                    }
                }
                //fin
            }
        }
        //On envoie la requete
    xhr.open("POST", "ajax/ajax_UpdateStatus_article.php", true); //on appelle la page avec la méthode post en asychrone
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('statut=' + FuturEtat); //ID post sera le $_POST[''] et ValuePost sa valeur
    console.log('On envoie : ' + FuturEtat);
    //On récupère l'état réel de l'article

    //On affiche les bonnes icones

    //On change l'état
}
var StateArticle = document.getElementById('Article_State');
var StateArticleEnCours = StateArticle.value;
var LastState = StateArticleEnCours; //Mémorisation de l'état d'origine
if (LastState == 6) {
    LastState = 5;
}
var AfficheStatut = document.getElementById('statutArticle');
var StatutEnCours = AfficheStatut.innerHTML;
var Etat = document.getElementById('etat_verrou');
var EtatEncours = Etat.value;
//Gestion du pop up
/*// Get the modal
var modal = document.getElementById("myModal");
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
*/
//Initialisation
Id_Auteur = document.getElementById('auteur').value;
//gestion des events
let LeBouton = document.getElementById('bouton');
LeBouton.addEventListener('click', add_message);