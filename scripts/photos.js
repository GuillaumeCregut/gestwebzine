function ouvre_article(Id_Article) {
    // console.log(Id_Article);
    let formdata = document.forms[0];
    let variable = document.getElementById('id_article');
    variable.value = Id_Article;
    formdata.submit();

}
let LesArticles = document.getElementsByClassName('article');
Array.prototype.forEach.call(LesArticles, function(el) {
    // ajouteEvent(el);
    el.addEventListener('dblclick', function(e) {
        ouvre_article(this.id);
    })
});