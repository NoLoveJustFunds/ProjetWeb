
document.addEventListener('DOMContentLoaded', function() {
    // Vérifie s'il y a un message de succès dans l'URL (pourrait être ajouté après redirection)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        // Réinitialiser tous les champs de formulaire
        document.querySelectorAll('input[type="text"]').forEach(input => {
            input.value = '';
        });
        
        // Réinitialiser les radios Vrai/Faux
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.checked = false;
        });
        
        // Réinitialiser les sélecteurs (optionnel, généralement on garde la difficulté)
        // document.querySelectorAll('select').forEach(select => {
        //     select.selectedIndex = 0;
        // });
    }
});