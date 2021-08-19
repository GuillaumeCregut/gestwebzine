function modifieArticle(id) {
    let LeFormulaire = document.forms[0];
    let IndiceArticle = LeFormulaire.elements[0];
    IndiceArticle.value = id;
    LeFormulaire.submit();
}