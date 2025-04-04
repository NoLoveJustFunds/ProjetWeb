let currentQuestionIndex = 0;
let quizData = [];
let score = 0;
let timer;
let timeLeft = 20;
let isRunning = false;

function loadQuizData() {
    console.log("loadQuizData appelé");
    quizData = quizDataInitial;

    if (quizData.error) {
        alert(quizData.error);
        return;
    }

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
            tempElement.style.color = "#fab45e"; // Réinitialiser la couleur
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
    console.log("Temps écoulé !");
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
    const correctAnswer = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.trim() : null;
    buttons.forEach(btn => {
        if (btn) {
            const btnText = btn.textContent.trim();
            if (correctAnswer && btnText === correctAnswer) {
                console.log("Mise en vert de la bonne réponse : ", btnText);
                btn.classList.add('correct'); // Correction : utiliser 'correct' au lieu de 'incorrect'
            }
        }
    });

    setTimeout(() => {
        currentQuestionIndex++;
        displayQuestion();
    }, 2000);
}

function displayQuestion() {
    if (currentQuestionIndex >= quizData.length) {
        document.getElementById('IntituleQuestion').textContent = "Quiz terminé !";
        document.getElementById('ElementReponse').style.display = 'none';
        document.getElementById('TexteDescriptif').style.display = 'block';
        document.getElementById('TexteDescriptif').innerHTML = `<p>Nombre de bonne réponse :  ${score}/${quizData.length}</p><br><p>Votre score sera mis à jour automatiquement ainsi votre nombre de question subies.</p>`;
        document.getElementById('Retour').style.display = 'block';
        document.getElementById('PlacementChrono').style.display = 'none'; // Cacher le chronomètre à la fin
        document.getElementById('returnButton').style.display = 'block'; // Cacher le chronomètre à la fin
        document.getElementById('returnButton2').style.display = 'none'; // Cacher le chronomètre à la fin

        window.quizScore = score;
        window.quizTotal = quizData.length;
        console.log("Fin du quiz - Score:", window.quizScore, "Total:", window.quizTotal);

        document.getElementById('scoreInput').value = window.quizScore;
        document.getElementById('totalInput').value = window.quizTotal;
        console.log("Champs cachés remplis - Score:", window.quizScore, "Total:", window.quizTotal);

        return;
    }

    clearInterval(timer);
    timeLeft = 20;
    const tempElement = document.getElementById("Temp");
    if (tempElement) {
        tempElement.innerText = timeLeft;
        tempElement.style.color = "#fab45e"; // Réinitialiser la couleur
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

    document.getElementById('questions').value = currentQuestionIndex + 1;

    startTimer();
}

function selectChoice(bouton) {
    console.log("selectChoice appelé pour : ", bouton.textContent);
    const selectedAnswer = bouton.textContent.trim();
    const currentQuestion = quizData[currentQuestionIndex];
    const correctAnswer = currentQuestion.correctAnswer ? currentQuestion.correctAnswer.trim() : null;

    console.log("Réponse sélectionnée : ", selectedAnswer);
    console.log("Réponse correcte : ", correctAnswer);

    clearInterval(timer);

    document.getElementById('Bouton1').disabled = true;
    document.getElementById('Bouton2').disabled = true;

    if (correctAnswer && selectedAnswer === correctAnswer) {
        console.log("Réponse correcte !");
        bouton.classList.add('correct');
        score++;
    } else {
        console.log("Réponse incorrecte !");
        bouton.classList.add('incorrect');
        const buttons = [
            document.getElementById('Bouton1'),
            document.getElementById('Bouton2')
        ];
        buttons.forEach(btn => {
            const btnText = btn.textContent.trim();
            console.log("Comparaison avec : ", btnText);
            if (correctAnswer && btnText === correctAnswer) {
                console.log("Mise en vert de la bonne réponse : ", btnText);
                btn.classList.add('correct');
            }
        });
    }

    setTimeout(() => {
        currentQuestionIndex++;
        displayQuestion();
    }, 2000);
}

function submitForm() {
    console.log("Soumission manuelle du formulaire");
    const scoreInput = document.getElementById('scoreInput').value;
    const totalInput = document.getElementById('totalInput').value;
    console.log("Valeurs avant soumission - Score:", scoreInput, "Total:", totalInput);

    if (!scoreInput || !totalInput) {
        console.error("Erreur : Les champs score ou total sont vides");
        return;
    }

    document.getElementById('quizForm').submit();
}

