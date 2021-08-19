function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text/plain", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    console.log('hello');
    console.log(data);
    let LeID = ev.target.id;
    if ((LeID == 'a_faire') | (LeID == 'en_cours') | (LeID == 'fait')) {
        ev.target.appendChild(document.getElementById(data));
        let larticle = document.getElementById(data);

        console.log(LeID);
        if (LeID == 'fait') {
            larticle.classList.add('fait');
        } else {
            larticle.classList.remove('fait');
        }
    }
}

function modifieArticle(element) {
    //Récupère le formulaire
    let LeFormulaire = document.forms[0];
    let Champs = LeFormulaire.elements[0];
    Champs.value = element;
    console.log(Champs.value);
    LeFormulaire.submit();

}