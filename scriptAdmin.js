
document.addEventListener('DOMContentLoaded', function() {
    
    // Vérifie s'il y a un message de succès dans l'URL ( pour enchainer la création de question , j'ai trouvé sur un forum)
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success')) {

        // Réinitialise tous les champs de formulaire
        document.querySelectorAll('input[type="text"]').forEach(input => {
            input.value = '';
        });
        
        // Réinitialise les boutons radios Vrai/Faux
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.checked = false;
        });
        
    }
});