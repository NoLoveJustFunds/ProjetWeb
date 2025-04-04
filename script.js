document.addEventListener("DOMContentLoaded", function () {
    
    const bouton = document.querySelector(".Bouton");
    const menu = document.querySelector(".ElementDeroulant");

    bouton.addEventListener("click", function (event) {
        menu.classList.toggle("visible");
    });

    // Ferme le menu si on clique en dehors
    document.addEventListener("click", function (event) {
        if (!menu.contains(event.target) && !bouton.contains(event.target)) {
            menu.classList.remove("visible");
        }
    });
});

/*------------ Fonction pour afficher le mot de passe------------------------------*/

function afficherPassword() {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.querySelector('.password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.textContent = 'Hidden';
    } else {
        passwordInput.type = 'password';
        passwordToggle.textContent = 'Display';
    }
}
