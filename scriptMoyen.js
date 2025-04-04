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

    displayQuestion();
}


//----------------------------------Fonction du Chrono----------------------------------------------------------//

function startTimer() {
    if (!isRunning) {
        document.getElementById("Temp").style.display = "block";
        isRunning = true;
        clearInterval(timer);
        timeLeft = 20;
        const tempElement = document.getElementById("Temp");
        if (tempElement) {
            tempElement.innerText = timeLeft;
            tempElement.style.color = "#fab45e"; 
        }

        timer = setInterval(() => {
            timeLeft--;
            if (tempElement) {
                tempElement.innerText = timeLeft;
                if (timeLeft <= 10) {
                    tempElement.style.color = "#F44336";
                } else {
                    tempElement.style.color = "#fab45e";
                }
            }

            if (timeLeft <= 0) {
                clearInterval(timer);
                if (tempElement) {
                    tempElement.style.color = "#fab45e";
                }
                isRunning = false;
                handleTimeOut();
            }
        }, 1000);
    }
}

function handleTimeOut() {
    
    const buttons = [
        document.getElementById('Bouton1'),
        document.getElementById('Bouton2')
    ];

    buttons.forEach(btn => {
        if (btn) {
            btn.disabled = true;
        }
    });

    const currentQuestion = quizData[currentQuestionIndex];
    const correctAnswer = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.trim() : null; //Supprime les espaces
    buttons.forEach(btn => {
        if (btn) {
            const btnText = btn.textContent.trim();
            if (correctAnswer && btnText === correctAnswer) {
                btn.classList.add('correct'); 
            }
        }
    });

    setTimeout(() => {
        currentQuestionIndex++;
        displayQuestion();
    }, 2000);
}


//----------------------------------Affichage des questions----------------------------------------------------------//


function displayQuestion() {
    if (currentQuestionIndex >= quizData.length) {

        document.getElementById('IntituleQuestion').textContent = "Quiz terminé !";
        document.getElementById('ElementReponse').style.display = 'none';
        document.getElementById('TexteDescriptif').style.display = 'block';
        document.getElementById('TexteDescriptif').innerHTML = `<p>Nombre de bonne réponse :  ${score}/${quizData.length}</p><br><p>Votre score sera mis à jour automatiquement ainsi votre nombre de question subies.</p>`;
        document.getElementById('Retour').style.display = 'block';
        document.getElementById('PlacementChrono').style.display = 'none'; 
        document.getElementById('returnButton').style.display = 'block'; 
        document.getElementById('returnButton2').style.display = 'none'; 

        window.quizScore = score; //Variable globale qui stocke le score du joueur
        window.quizTotal = quizData.length;
        

        document.getElementById('scoreInput').value = window.quizScore;
        document.getElementById('totalInput').value = window.quizTotal;

        document.getElementById('questions').value = quizData.length;
      

        return;
    }

    clearInterval(timer);
    timeLeft = 20;
    const tempElement = document.getElementById("Temp");
    if (tempElement) {
        tempElement.innerText = timeLeft;
        tempElement.style.color = "#fab45e"; 
    }
    isRunning = false;

    const currentQuestion = quizData[currentQuestionIndex];
    const questionContainer = document.getElementById('IntituleQuestion');
    const bouton1 = document.getElementById('Bouton1');
    const bouton2 = document.getElementById('Bouton2');

    bouton1.classList.remove('correct', 'incorrect');
    bouton2.classList.remove('correct', 'incorrect');
    bouton1.disabled = false;
    bouton2.disabled = false;

    document.getElementById('TexteDescriptif').style.display = 'none';
    document.getElementById('ElementReponse').style.display = 'block';
    document.getElementById('PlacementChrono').style.display = 'flex';
    questionContainer.textContent = currentQuestion.question;

    bouton1.textContent = currentQuestion.choices[0]?.text || 'Choix 1 indisponible';
    bouton1.value = currentQuestion.choices[0]?.id || '';
    bouton2.textContent = currentQuestion.choices[1]?.text || 'Choix 2 indisponible';
    bouton2.value = currentQuestion.choices[1]?.id || '';

    document.getElementById('questions').value = currentQuestionIndex;

    startTimer();
}

//----------------------------------Selection et verifiaction du choix----------------------------------------------------------//


function selectChoice(bouton) {

    const selectedAnswer = bouton.textContent.trim();
    const currentQuestion = quizData[currentQuestionIndex];
    const correctAnswer = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.trim() : null;


    clearInterval(timer); //Rénitialise le timer

    document.getElementById('Bouton1').disabled = true;
    document.getElementById('Bouton2').disabled = true;

    if (correctAnswer && selectedAnswer === correctAnswer) {
  
        bouton.classList.add('correct');
        score++;
    } else {
      
        bouton.classList.add('incorrect');
        const buttons = [
            document.getElementById('Bouton1'),
            document.getElementById('Bouton2')
        ];
        buttons.forEach(btn => {
            const btnText = btn.textContent.trim();
         
            if (correctAnswer && btnText === correctAnswer) {
       
                btn.classList.add('correct');
            }
        });
    }

    setTimeout(() => {
        currentQuestionIndex++;
        displayQuestion();
    }, 2000);
}

//----------------------------------Soumissions des resultats ----------------------------------------------------------//


function submitForm() {

    const scoreInput = document.getElementById('scoreInput').value;
    const totalInput = document.getElementById('totalInput').value;
  

    if (!scoreInput || !totalInput) {
        return;
    }

    document.getElementById('quizForm').submit();
}

