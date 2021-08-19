function desactiveLien() {
    let tabLienMenu = document.getElementsByClassName('menulien');
    console.log('On passe');
    let Taille = tabLienMenu.length;
    console.log('Taille :' + Taille);
    for (let i = 0; i < tabLienMenu.length; i++) {
        tabLienMenu[i].addEventListener('click', function(event) {
            event.preventDefault();
            return false;
        });
        console.log(i);
    }
}
desactiveLien();