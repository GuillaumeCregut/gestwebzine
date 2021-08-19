function ouvre_article(Id_Article) {
    console.log(Id_Article);
    let formdata = document.forms[0];
    let variable = document.getElementById('id_article');
    variable.value = Id_Article;
    formdata.submit();

}