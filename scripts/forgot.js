document.addEventListener("submit", function(event) {
    let login = document.getElementById('login').value;
    let mail = document.getElementById('mail').value;
    if (login == '' || mail == '') {
        event.preventDefault();
    }

});