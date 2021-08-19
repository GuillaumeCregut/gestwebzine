function modifieArticle(id) {
    let LeFormulaire = document.forms[0];
    let IndiceArticle = LeFormulaire.elements[0];
    IndiceArticle.value = id;
    LeFormulaire.submit();
}

function calcul() {
    let TArea = document.getElementById('description').value;
    let Texte = document.getElementById('nbreLettre');
    console.log(TArea.length);
    let Nbrerestant = 200 - TArea.length;
    Texte.innerHTML = Nbrerestant;
}

function ajoutArticle() {
    //Vérifications des champs
    let TitreArticle = document.getElementById('titre').value;
    let DescArticle = document.getElementById('description').value;
    if ((TitreArticle == '') | (DescArticle == '')) {
        alert('Veuillez remplir les informations');
        return false;
    }
    let LeFormulaire = document.forms[1];
    LeFormulaire.submit();
}

function masquage() {
    let ListeArticlesAutre = document.getElementsByClassName('autre_auteur');
    let MaCoche = document.getElementById('coche_affiche');
    if (MaCoche.checked) {
        for (i = 0; i < ListeArticlesAutre.length; i++) {
            ListeArticlesAutre[i].classList.add('autre_auteur_cache');
        }
    } else {
        for (i = 0; i < ListeArticlesAutre.length; i++) {
            ListeArticlesAutre[i].classList.remove('autre_auteur_cache');
        }
    }

}
//Gestion des events articles
let LesArticles = document.getElementsByClassName('article');
Array.prototype.forEach.call(LesArticles, function(el) {
        // ajouteEvent(el);
        el.addEventListener('click', function(e) {
            modifieArticle(this.id);
        })
    })
    //ajoutBtn
let LeBouton = document.getElementById('ajoutBtn');
LeBouton.addEventListener('click', ajoutArticle);
//Cacher les menus
let CocheCache = document.getElementById('coche_affiche');
CocheCache.addEventListener('click', masquage);
//Calculer le nombre de caractères
let LeTextArea = document.getElementById('description');
LeTextArea.addEventListener('input', calcul);