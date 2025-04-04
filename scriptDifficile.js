let currentQuestionIndex = 0;
let quizData = [];
let score = 0;
let timer;
let timeLeft = 20;
let isRunning = false;

function loadQuizData() {
    console.log("loadQuizData appelé");
    console.log("Données brutes de quizDataInitial :", quizDataInitial);

    quizData = quizDataInitial;

    if (quizData.error) {
        alert(quizData.error);
        return;
    }

    if (!Array.isArray(quizData) || quizData.length === 0) {
        alert("Erreur : Aucune question n'a été récupérée pour le quiz. Veuillez vérifier la base de données.");
        return;
    }

    console.log("Nombre de questions récupérées :", quizData.length);
    console.log("Détails des questions :", quizData);

    score = 0;
    document.getElementById('questions').max = quizData.length;
    document.getElementById('quizIntro').style.display = 'none';
    document.getElementById('Retour').style.display = 'none';
    document.getElementById('PlacementChrono').style.display = 'block';

    displayQuestion();
}

function startTimer() {
    if (!isRunning) {
        document.getElementById("Temp").style.display = "block";
        isRunning = true;
        clearInterval(timer);
        timeLeft = 20;
        const tempElement = document.getElementById("Temp");
        if (tempElement) {
            tempElement.innerText = timeLeft;
            tempElement.style.color = "#8E2DE2";
        } else {
            console.error("Erreur : Élément #Temp non trouvé");
        }

        timer = setInterval(() => {
            timeLeft--;
            if (tempElement) {
                tempElement.innerText = timeLeft;
                if (timeLeft <= 10) {
                    tempElement.style.color = "#F44336";
                } else {
                    tempElement.style.color = "#8E2DE2";
                }
            }

            if (timeLeft <= 0) {
                clearInterval(timer);
                if (tempElement) {
                    tempElement.style.color = "#8E2DE2";
                }
                isRunning = false;
                handleTimeOut();
            }
        }, 1000);
    }
}

function handleTimeOut() {
    console.log("Temps écoulé !");
    const inputReponse = document.getElementById("reponseUtilisateur");
    const btnValider = document.getElementById("boutonValidation");

    // Désactiver l'input et le bouton
    if (inputReponse) {
        inputReponse.disabled = true;
        inputReponse.style.backgroundColor = '#F44336';
        const currentQuestion = quizData[currentQuestionIndex];
        inputReponse.value = currentQuestion.correctAnswer; // Afficher la bonne réponse
    }
    if (btnValider) {
        btnValider.disabled = true;
    }

    // Passer à la question suivante après un délai de 2 secondes
    setTimeout(() => {
        currentQuestionIndex++;
        displayQuestion();
    }, 2000);
}

function displayQuestion() {
    if (currentQuestionIndex >= quizData.length) {
        document.getElementById('IntituleQuestion').textContent = "Quiz terminé !";
        document.getElementById('ElementReponse2').style.display = 'none';
        document.getElementById('TexteDescriptif').style.display = 'block';
        document.getElementById('TexteDescriptif').innerHTML = `<p>Nombre de bonne réponse :  ${score}/${quizData.length}</p><br><p>Votre score sera mis à jour automatiquement ainsi votre nombre de question subies.</p>`;

        document.getElementById('Retour').style.display = 'flex';
        document.getElementById('PlacementChrono').style.display = 'none';
        document.getElementById('returnButton').style.display = 'block';
        document.getElementById('returnButton2').style.display = 'none';

        window.quizScore = score;
        window.quizTotal = quizData.length;
        console.log("Fin du quiz - Score:", window.quizScore, "Total:", window.quizTotal);

        const scoreInput = document.getElementById('scoreInput');
        const totalInput = document.getElementById('totalInput');
        if (scoreInput && totalInput) {
            scoreInput.value = window.quizScore;
            totalInput.value = window.quizTotal;
            console.log("Champs cachés remplis - Score:", scoreInput.value, "Total:", totalInput.value);
        } else {
            console.error("Erreur : Les éléments scoreInput ou totalInput sont introuvables");
        }

        return;
    }

    // Ajouter un log pour vérifier la question actuelle
    console.log("Affichage de la question", currentQuestionIndex + 1, ":", quizData[currentQuestionIndex]);

    clearInterval(timer);
    timeLeft = 20;
    const tempElement = document.getElementById("Temp");
    if (tempElement) {
        tempElement.innerText = timeLeft;
        tempElement.style.color = "#8E2DE2";
    }
    isRunning = false;

    const currentQuestion = quizData[currentQuestionIndex];
    const questionContainer = document.getElementById('IntituleQuestion');
    const intituleQuestion2 = document.getElementById('IntituleQuestion2');

    document.getElementById('TexteDescriptif').style.display = 'none';
    document.getElementById('ElementReponse2').style.display = 'block';
    document.getElementById('PlacementChrono').style.display = 'block';
    questionContainer.textContent = "Complétez la phrase";

    // Afficher la question telle quelle, et ajouter un champ de saisie en dessous
    intituleQuestion2.innerHTML = `
        <div class="question-text" style="font-size: 18px;">${currentQuestion.question}</div>
        <input 
            type="text" 
            id="reponseUtilisateur" 
            placeholder="Réponse"
            class="dynamic-input"
        >
    `;

    // Récupérer l'input
    const inputReponse = document.getElementById("reponseUtilisateur");
    if (!inputReponse) {
        console.error("Erreur : Le champ de saisie reponseUtilisateur n'a pas été créé.");
        return;
    }

    // Réinitialiser l'input
    inputReponse.disabled = false;
    inputReponse.style.backgroundColor = 'white';
    inputReponse.value = '';
    inputReponse.style.margin= "20px";
    inputReponse.style.fontSize ="15px"

    // Ajouter un écouteur d'événement pour redimensionner dynamiquement
    inputReponse.addEventListener('input', ajusterTailleInput);

    // Initialiser le dimensionnement
    ajusterTailleInput();

    // Créer un conteneur pour le bouton
    const containerBouton = document.createElement('div');
    containerBouton.id = 'containerBoutonValidation';

    // Créer le bouton de validation
    const btnValider = document.createElement('button');
    btnValider.textContent = 'Valider';
    btnValider.id = 'boutonValidation';
    btnValider.style.margin = "20px";
    btnValider.style.fontSize = "15px"; // Augmente la taille du texte
    btnValider.style.padding = "3px";


    // Ajouter le bouton au conteneur
    containerBouton.appendChild(btnValider);

    // Ajouter le conteneur du bouton après l'élément IntituleQuestion2
    intituleQuestion2.parentNode.appendChild(containerBouton);

    // Vérifier que le bouton a été ajouté
    if (!document.getElementById('boutonValidation')) {
        console.error("Erreur : Le bouton de validation n'a pas été créé.");
    }

    // Fonction de validation
    function validerReponse() {
        const reponseUtilisateur = inputReponse.value.trim();
        const reponseCorrecte = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.toString().trim() : '';

        if (!reponseCorrecte) {
            console.error("Erreur : Aucune réponse correcte définie pour cette question");
            inputReponse.style.backgroundColor = '#F44336';
            inputReponse.value = "Réponse manquante";
            return;
        }

        // Arrêter le chronomètre
        clearInterval(timer);

        // Désactiver l'input et le bouton après validation
        inputReponse.disabled = true;
        btnValider.disabled = true;

        if (reponseUtilisateur.toLowerCase() === reponseCorrecte.toLowerCase()) {
            // Réponse correcte
            inputReponse.style.backgroundColor = '#4CAF50';
            score++; // Incrémente le score
        } else {
            // Réponse incorrecte
            inputReponse.style.backgroundColor = '#F44336';
            inputReponse.value = reponseCorrecte; // Montrer la bonne réponse
        }

        // Passer à la prochaine question après un délai
        setTimeout(() => {
            currentQuestionIndex++;
            displayQuestion();
        }, 2000);
    }

    // Validation par clic
    btnValider.addEventListener('click', validerReponse);

    // Validation par touche Entrée
    inputReponse.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            validerReponse();
        }
    });

    document.getElementById('questions').value = currentQuestionIndex + 1;

    startTimer();
}

// Fonction pour redimensionner l'input
function ajusterTailleInput() {
    const inputReponse = document.getElementById("reponseUtilisateur");
    if (!inputReponse) return;

    // Créer un span temporaire pour mesurer la taille du texte
    const mesureSpan = document.createElement('span');
    mesureSpan.style.visibility = 'hidden';
    mesureSpan.style.position = 'absolute';
    mesureSpan.style.whiteSpace = 'pre';
    mesureSpan.style.font = window.getComputedStyle(document.body).font;
    document.body.appendChild(mesureSpan);

    // Mesurer la longueur du texte
    mesureSpan.textContent = inputReponse.value || '_____';

    // Ajuster la largeur de l'input
    const largeur = Math.max(
        mesureSpan.offsetWidth + 20, // Ajouter un peu de padding
        100 // Largeur minimale
    );

    // Définir les limites de largeur
    inputReponse.style.minWidth = '125px'; // Largeur minimale
    inputReponse.style.maxWidth = '250px'; // Largeur maximale
    inputReponse.style.width = `${largeur}px`;

    // Nettoyer
    document.body.removeChild(mesureSpan);
}

function submitForm() {
    console.log("Soumission manuelle du formulaire");
    const scoreInput = document.getElementById('scoreInput');
    const totalInput = document.getElementById('totalInput');

    if (!scoreInput || !totalInput) {
        console.error("Erreur : Les éléments scoreInput ou totalInput sont introuvables");
        return;
    }

    const scoreValue = scoreInput.value;
    const totalValue = totalInput.value;
    console.log("Valeurs avant soumission - Score:", scoreValue, "Total:", totalValue);

    if (!scoreValue || !totalValue) {
        console.error("Erreur : Les champs score ou total sont vides");
        return;
    }

    document.getElementById('quizForm').submit();
}