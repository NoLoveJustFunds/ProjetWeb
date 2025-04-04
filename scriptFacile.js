let currentQuestionIndex = 0;
let quizData = [];
let score = 0;
let timer; // Variable pour stocker l'intervalle du chronomètre
let timeLeft = 20; // Temps initial en secondes
let isRunning = false; // Variable pour suivre l'état du chronomètre

function loadQuizData() {
    
    quizData = quizDataInitial;


    score = 0;
    document.getElementById('questions').max = quizData.length; //Taille du quiz pour le nombre de question
    document.getElementById('quizIntro').style.display = 'none';
    document.getElementById('Retour').style.display = 'none';
    document.getElementById('PlacementChrono').style.display = 'block';

    displayQuestion(); //Appel de la fonction pour l'affichage des questionss
}


//----------------------------------Fonction du Chrono----------------------------------------------------------//

function startTimer() {
    if (!isRunning) {
        document.getElementById("Temp").style.display="block";
        isRunning = true; //Démarre le chrono
        clearInterval(timer);
        timeLeft = 20; // Réinitialisation du chrono
        const tempElement = document.getElementById("Temp");
        if (tempElement) {
            tempElement.innerText = timeLeft; 
        }

        timer = setInterval(() => {
            timeLeft--; //Décrémentation du chrono
            if (tempElement) {
                tempElement.innerText = timeLeft;
            }
            if(timeLeft<=10){
                document.getElementById('Temp').style.color="#F44336"; //Met en rouge quand < 10 sec
            }

            if (timeLeft <= 0) {
                clearInterval(timer);
                document.getElementById('Temp').style.color="#fab45e";
                isRunning = false;
                TimeOut();
            }
        }, 1000);
    }
}

function TimeOut() {

    const buttons = [
        document.getElementById('Bouton1'),
        document.getElementById('Bouton2'),
        document.getElementById('Bouton3'),
        document.getElementById('Bouton4')
    ];

    // Désactive les boutons
    buttons.forEach(btn => {
        if (btn) {
            btn.disabled = true;
        }
    });

    // Affiche la bonne réponse en vert
    const currentQuestion = quizData[currentQuestionIndex];
    const correctAnswer = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.trim() : null; //Supprime les espaces
    buttons.forEach(btn => {
        if (btn) {
            const btnText = btn.textContent.trim();
            if (correctAnswer && btnText === correctAnswer) {
                btn.classList.add('incorrect'); //Couleur defini dans le styleFacile.css
            }
        }
    });

    // Passer à la question suivante après un délai de 2 secondes
    setTimeout(() => {
        currentQuestionIndex++;
        displayQuestion();
    }, 2000);
}

//----------------------------------Affichage des questions----------------------------------------------------------//


function displayQuestion() {
    if (currentQuestionIndex >= quizData.length) { //Si le nombre de question max est atteind , on arrete + score final

        document.getElementById('IntituleQuestion').textContent = "Quiz terminé !";
        document.getElementById('ElementReponse').style.display = 'none';
        document.getElementById('TexteDescriptif').style.display = 'block';
        document.getElementById('TexteDescriptif').innerHTML = `<p>Nombre de bonne réponse :  ${score}/${quizData.length}</p><br><p>Votre score sera mis à jour automatiquement ainsi que votre nombre de question subies.</p>`;
        
        document.getElementById('Retour').style.display = 'block';
        document.getElementById('PlacementChrono').style.display = 'none'; 
        document.getElementById('returnButton').style.display = 'block'; 
        document.getElementById('returnButton2').style.display = 'none'; 


        window.quizScore = score; //Variable globale pour le score du joueur
        window.quizTotal = quizData.length;

        document.getElementById('questions').value = quizData.length;
       

        document.getElementById('scoreInput').value = window.quizScore; //Modification de scoreInput pour y placer quizScore
        document.getElementById('totalInput').value = window.quizTotal;
     

        return;
    }

    clearInterval(timer); // Arrête le chrono précédent
    timeLeft = 20; // Réinitialisation du chrono

    const tempElement = document.getElementById("Temp");
    if (tempElement) {
        tempElement.innerText = timeLeft;
    }
    isRunning = false; 

    //Une boucle aurait plus etre envisageable 

    const currentQuestion = quizData[currentQuestionIndex];
    const questionContainer = document.getElementById('IntituleQuestion');
    const bouton1 = document.getElementById('Bouton1');
    const bouton2 = document.getElementById('Bouton2');
    const bouton3 = document.getElementById('Bouton3');
    const bouton4 = document.getElementById('Bouton4');

    bouton1.classList.remove('correct', 'incorrect');
    bouton2.classList.remove('correct', 'incorrect');
    bouton3.classList.remove('correct', 'incorrect');
    bouton4.classList.remove('correct', 'incorrect');
    
    bouton1.disabled = false;
    bouton2.disabled = false;
    bouton3.disabled = false;
    bouton4.disabled = false;

    document.getElementById('TexteDescriptif').style.display = 'none';
    document.getElementById('ElementReponse').style.display = 'block';
    document.getElementById('PlacementChrono').style.display = 'flex';

    questionContainer.textContent = currentQuestion.question;

    bouton1.textContent = currentQuestion.choices[0]?.text || ''; //Choix 1
    bouton1.value = currentQuestion.choices[0]?.id || '';
    bouton2.textContent = currentQuestion.choices[1]?.text ;
    bouton2.value = currentQuestion.choices[1]?.id || '';
    bouton3.textContent = currentQuestion.choices[2]?.text;
    bouton3.value = currentQuestion.choices[2]?.id || '';
    bouton4.textContent = currentQuestion.choices[3]?.text;
    bouton4.value = currentQuestion.choices[3]?.id || '';

    document.getElementById('questions').value = currentQuestionIndex;

    startTimer(); //Nouveau chrono pour la question précédente
}

//----------------------------------Selection et verifiaction du choix----------------------------------------------------------//

function selectChoice(bouton) {
    
    const selectedAnswer = bouton.textContent.trim(); //Supprime les espaces
    const currentQuestion = quizData[currentQuestionIndex];
    const correctAnswer = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.trim() : null;

  
    clearInterval(timer);

    // Désactiver les boutons pour éviter plusieurs clics
    document.getElementById('Bouton1').disabled = true;
    document.getElementById('Bouton2').disabled = true;
    document.getElementById('Bouton3').disabled = true;
    document.getElementById('Bouton4').disabled = true;

    // Vérifier la réponse sélectionnée
    if (correctAnswer && selectedAnswer === correctAnswer) {
       
        bouton.classList.add('correct'); //Bonne réponse en vert
        score++;

    } else {
        
        bouton.classList.add('incorrect'); //Mauvaise réponse en rouge

        const buttons = [
            document.getElementById('Bouton1'),
            document.getElementById('Bouton2'),
            document.getElementById('Bouton3'),
            document.getElementById('Bouton4')
        ];

        buttons.forEach(btn => {
            const btnText = btn.textContent.trim();
            if (correctAnswer && btnText === correctAnswer) {//Comparaison de la bonne réponse avec celui de l'User
                btn.classList.add('correct'); 
            }
        });
    }

    // Passe à la question suivante après un délai de 2 secondes
    setTimeout(() => {
        currentQuestionIndex++; 
        displayQuestion();
    }, 2000);
}


//----------------------------------Soumissions des resultats ----------------------------------------------------------//

function submitForm() {
 
    const scoreInput = document.getElementById('scoreInput').value; //Récupère le score
    const totalInput = document.getElementById('totalInput').value; //Récupère le nombre de question


    if (!scoreInput || !totalInput) {
        return;
    }

    document.getElementById('quizForm').submit();
}
