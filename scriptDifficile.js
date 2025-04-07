let currentQuestionIndex = 0;
let quizData = [];
let score = 0;
let timer;
let timeLeft = 20;
let isRunning = false;

function loadQuizData() {
   

    quizData = quizDataInitial;

    if (quizData.error) {
        return;
    }

    score = 0;
    document.getElementById('questions').max = quizData.length;
    document.getElementById('quizIntro').style.display = 'none';
    document.getElementById('Retour').style.display = 'none';
    document.getElementById('PlacementChrono').style.display = 'block';

    displayQuestion(); //Appel de la fonction pour afficher les questions
}

/*-----------------------------------------Fonction du chrono------------------------------------------*/ 

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
        }

        timer = setInterval(() => {
            timeLeft--;
            if (tempElement) {
                tempElement.innerText = timeLeft;
                if (timeLeft <= 10) { //Met en rouge le chrono si < 10 secondes
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
                TimeOut();
            }
        }, 1000);
    }
}



function TimeOut() {
   
    const inputReponse = document.getElementById("reponseUtilisateur");
    const btnValider = document.getElementById("boutonValidation");

    // Désactive l'input et le bouton des que le temps est écoulé
    if (inputReponse) {
        inputReponse.disabled = true;
        inputReponse.style.backgroundColor = '#F44336';
        const currentQuestion = quizData[currentQuestionIndex];
        inputReponse.value = currentQuestion.correctAnswer; // Permet d'afficher la bonne réponse
    }
    if (btnValider) {
        btnValider.disabled = true;
    }

    // Passe à la question suivante après un délai de 2 secondes( le temps que l'user voit la réponse)
    setTimeout(() => {
        currentQuestionIndex++;
        displayQuestion();
    }, 2000);
}


/*-----------------------------------------Fonction affichage des questions------------------------------------------*/ 

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

        document.getElementById('questions').value = quizData.length;

        const scoreInput = document.getElementById('scoreInput');
        const totalInput = document.getElementById('totalInput');
        if (scoreInput && totalInput) {
            scoreInput.value = window.quizScore;
            totalInput.value = window.quizTotal;
        }

        return;
    }

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

    // Affiche la question telle quelle, et ajouter un champ de saisie en dessous(j'ai utiliser la documenet pour injecter dans html)
    intituleQuestion2.innerHTML = `
        <div class="question-text" style="font-size: 18px;">${currentQuestion.question}</div>
        <input 
            type="text" 
            id="reponseUtilisateur" 
            placeholder="Réponse"
            class="dynamic-input"
        >
    `;

    // Récupération de l'input
    const inputReponse = document.getElementById("reponseUtilisateur");

    
    // Réinitialise l'input pour la prochaine question et personalisation de l'input avec .style
    inputReponse.disabled = false;
    inputReponse.style.backgroundColor = 'white';
    inputReponse.value = '';
    inputReponse.style.margin = "20px";
    inputReponse.style.fontSize = "15px";

    // Ajoute un écouteur d'événement pour redimensionner la taille du "blanc"(J'ai mis pas mal de temps à comprendre le fait qu'il faut une autre focntion)
    inputReponse.addEventListener('input', ajusterTailleInput);

    ajusterTailleInput();

    // Supprime l'ancien conteneur du bouton s'il existe (J'ai eu un probleme avec l'accumulation de bouton valider)
    const ancienContainerBouton = document.getElementById('containerBoutonValidation');
    if (ancienContainerBouton) {
        ancienContainerBouton.remove();
    }

    // Crée le container pour le bouton
    const containerBouton = document.createElement('div');
    containerBouton.id = 'containerBoutonValidation';

    // Crée le bouton de validation
    const btnValider = document.createElement('button');
    btnValider.textContent = 'Valider';
    btnValider.id = 'boutonValidation';
    btnValider.style.margin = "20px";
    btnValider.style.fontSize = "15px";
    btnValider.style.padding = "3px";

   
    containerBouton.appendChild(btnValider);

    // Ajoute le conteneur du bouton après l'élément IntituleQuestion2
    const parentElement = intituleQuestion2.parentNode;
    if (parentElement) {
        const existingContainer = parentElement.querySelector('#containerBoutonValidation');
        if (existingContainer) {
            existingContainer.remove(); // Supprime tout conteneur existant
        }
        parentElement.appendChild(containerBouton);
    }

    // Fonction de validation
    function validerReponse() {
        const reponseUtilisateur = inputReponse.value.trim();
        const reponseCorrecte = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.toString().trim() : '';

        if (!reponseCorrecte) {
            inputReponse.style.backgroundColor = '#F44336';
            inputReponse.value = "Réponse manquante";
            return;
        }

        clearInterval(timer);

        inputReponse.disabled = true;
        btnValider.disabled = true;

        if (reponseUtilisateur.toLowerCase() === reponseCorrecte.toLowerCase()) {
            inputReponse.style.backgroundColor = '#4CAF50'; // Réponse correcte
            score++;
        } else {
            inputReponse.style.backgroundColor = '#F44336'; // Réponse incorrecte
            inputReponse.value = reponseCorrecte; // Montre la bonne réponse
        }

        setTimeout(() => {
            currentQuestionIndex++;
            displayQuestion();
        }, 2000);
    }

    btnValider.addEventListener('click', validerReponse); // Validation avec le clic

    // ou une validation avec la touche entrée
    inputReponse.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            validerReponse();
        }
    });

    document.getElementById('questions').value = currentQuestionIndex;

    startTimer();
}

/*-----------------------------------------Fonction pour redimmensionenr l'input -----------------------------------------*/ 

function ajusterTailleInput() {
    const inputReponse = document.getElementById("reponseUtilisateur");
    if (!inputReponse) return;

   
    const mesureSpan = document.createElement('span');
    mesureSpan.style.visibility = 'hidden';
    mesureSpan.style.position = 'absolute';
    mesureSpan.style.whiteSpace = 'pre';
    mesureSpan.style.font = window.getComputedStyle(document.body).font;
    document.body.appendChild(mesureSpan);

    // Mesure la longueur du texte pour ajuster la taille de l'input
    mesureSpan.textContent = inputReponse.value || '_____';

    // Ajuste la largeur de l'input
    const largeur = Math.max(
        mesureSpan.offsetWidth + 20,
        100
    );


    inputReponse.style.minWidth = '125px'; // min de la hauteur
    inputReponse.style.maxWidth = '250px'; //max de la hauteur
    inputReponse.style.width = `${largeur}px`;


    document.body.removeChild(mesureSpan);
}


/*-----------------------------------------Fonction de soumissions des données-----------------------------------------*/ 

function submitForm() {

    const scoreInput = document.getElementById('scoreInput');
    const totalInput = document.getElementById('totalInput');

   
    const scoreValue = scoreInput.value;
    const totalValue = totalInput.value;
    

    document.getElementById('quizForm').submit();
}