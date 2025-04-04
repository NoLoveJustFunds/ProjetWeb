<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Quiz</title>
    <link rel="stylesheet" href="styleAdmin.css">
</head>
<body>
    <header>
    <h1 id="TITRE">Création de Quiz</h1>

    <div id="BlockDeconnexion">
        <a href="index.php"><button id="DeconnexionAdmin">Déconnexion</a></button>
    </div>
    </header>

    <div class="Container">
        <!-- Formulaire pour QCM -->
        <form method="POST" action="Admin.php" id="formQCM">
            <div class="Container1"> 
                <div class="titre">
                    <h2>QCM</h2>
                </div>

                <div class="Niveaux">
                    <label for="NiveauQCM">Difficulté du Quiz : </label>
                    <select name="ElementsQCM" class="Choix">
                        <option value="Facile">Facile</option>
                        <option value="Moyen">Moyen</option>
                        <option value="Difficile">Difficile</option>
                    </select>
                </div>

                <div id="Question">
                    <div class="IntituleQuestion">
                        <label for="ChoixQ">Choix de la question :</label> 
                    </div>
                    <input type="text" id="questionTextQCM" name="questionQCM" placeholder="Entrer votre question" required>
                </div>

                <div id="ConfigChoix">
                    <div class="IntituleQuestion">
                        <label for="Choix">Configuration des choix :</label>
                    </div>
                    <div class="ChoixContainer">
                        <input type="text" name="Choix1" placeholder="Choix 1" class="ChoixE" required><br>
                        <input type="text" name="Choix2" placeholder="Choix 2" class="ChoixE" required><br>
                        <input type="text" name="Choix3" placeholder="Choix 3" class="ChoixE" required><br>
                        <input type="text" name="Choix4" placeholder="Choix 4" class="ChoixE" required>
                    </div>
                </div>

                <div id="ReponseContainer">
                    <div class="IntituleQuestion">
                        <label for="ReponseQCM">Réponse à la question :</label>
                        <select name="ReponseQCM" id="ReponseQCM">
                            <option value="1">Choix 1</option>
                            <option value="2">Choix 2</option>
                            <option value="3">Choix 3</option>
                            <option value="4">Choix 4</option>
                        </select>
                    </div>
                </div>
                
                <div class="QuestionTerminer">
                    <!-- Champ caché pour indiquer le type de quiz -->
                    <input type="hidden" name="typeQuiz" value="QCM">
                    <button type="submit" name="question_suivante" class="Suivant">Question Suivante</button>
                
                </div>
            </div>
        </form>

        <!-- Formulaire pour Vrai/Faux -->
        <form method="POST" action="Admin.php" id="formVF">
            <div class="Container2">
                <div class="titre">
                    <h2>Vrai/Faux</h2>
                </div>
                <div class="Niveaux">
                    <label for="NiveauVF">Niveau de Difficulté : </label>
                    <select name="ElementsVF" class="Choix">
                        <option value="Facile">Facile</option>
                        <option value="Moyen">Moyen</option>
                        <option value="Difficile">Difficile</option>
                    </select>
                </div>

                <div id="Question">
                    <div class="IntituleQuestion">
                        <label for="ChoixQVF">Choix de la question :</label> 
                    </div>
                    <input type="text" id="questionTextVF" name="questionVF" placeholder="Entrer votre question" required>
                </div>

                <div id="ConfigChoix">
                    <div class="IntituleQuestion">
                        <label for="ChoixVF">Réponse à la question :</label>
                    </div>
                    <div id="ChoixContainer2">
                        <label>
                            <input type="radio" name="ReponseVF" value="Vrai" class="ChoixE2" required> Vrai
                        </label><br>
                        <label>
                            <input type="radio" name="ReponseVF" value="Faux" class="ChoixE2"> Faux
                        </label>
                    </div>
                </div>
                
                <div class="QuestionTerminer">
                    <!-- Champ caché pour indiquer le type de quiz -->
                    <input type="hidden" name="typeQuiz" value="Vrai/Faux">
                    <button type="submit" name="question_suivante" class="Suivant">Question Suivante</button>
                    
                </div>
            </div>
        </form>

        <!-- Formulaire pour Réponse Libre -->
        <form method="POST" action="Admin.php" id="formReponseLibre">
            <div class="Container3">
                <div class="titre">
                    <h2>Réponse Libre</h2>
                </div>
                <div class="Niveaux">
                    <label for="NiveauReponseLibre">Niveau de Difficulté : </label>
                    <select name="ElementsReponseLibre" class="Choix">
                        <option value="Facile">Facile</option>
                        <option value="Moyen">Moyen</option>
                        <option value="Difficile">Difficile</option>
                    </select>
                </div>

                <div id="Question">
                    <div class="IntituleQuestion">
                        <label for="ChoixQReponseLibre">Choix de la question :</label> 
                    </div>
                    <input type="text" id="questionTextReponseLibre" name="questionReponseLibre" placeholder="Entrer votre question" required>
                </div>

                <div id="ReponseContainer">
                    <div class="IntituleQuestion">
                        <div id="Espacement"> 
                            <label for="ReponseLibre">Réponse à la question :</label>
                            <span class="Espacement2"><input type="text" name="ReponseLibre" placeholder="Réponse" id="Colorier"required></span>
                        </div>
                    </div>
                </div>
                
                <div class="QuestionTerminer">
                    <!-- Champ caché pour indiquer le type de quiz -->
                    <input type="hidden" name="typeQuiz" value="Réponse Libre">
                    <button type="submit" name="question_suivante" class="Suivant">Question Suivante</button>
                    
                </div>
            </div>
        </form>
    </div>

    <script src="scriptAdmin.js"></script>   
</body>

<?php
session_start(); // Commencer une session pour stocker les questions

try {
    // Connexion à la base de données
    $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier si c'est une question suivante ou terminer
        $isQuestionSuivante = isset($_POST['question_suivante']);
        $isTerminer = isset($_POST['terminer']);
        $typeQuiz = $_POST['typeQuiz'] ?? ''; // Récupérer le type de quiz (QCM, Vrai/Faux, ou Réponse Libre)


        if ($typeQuiz === 'QCM') {
            // Logique pour le QCM
            $niveau = $_POST['ElementsQCM'] ?? 'Facile';
            $questionText = $_POST['questionQCM'] ?? '';
            $choix1 = $_POST['Choix1'] ?? '';
            $choix2 = $_POST['Choix2'] ?? '';
            $choix3 = $_POST['Choix3'] ?? '';
            $choix4 = $_POST['Choix4'] ?? '';
            $reponseIndex = intval($_POST['ReponseQCM'] ?? '1'); // L'index du choix sélectionné (1-4)


            // Vérifier que tous les champs sont remplis
            if (!empty($questionText) && !empty($choix1) && !empty($choix2) && !empty($choix3) && !empty($choix4) && ($isQuestionSuivante || $isTerminer)) {
                // Créer ou récupérer le quiz
                if (!isset($_SESSION['quiz_id'])) {
                    $query = "INSERT INTO Quiz (Type_Quiz, Niveau) VALUES (:typeQuiz, :niveau)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->bindValue(':typeQuiz', $typeQuiz, PDO::PARAM_STR);
                    $stmt->bindValue(':niveau', $niveau, PDO::PARAM_STR);
                    $stmt->execute();
                    $_SESSION['quiz_id'] = $mysqlClient->lastInsertId();
                    $_SESSION['quiz_niveau'] = $niveau;
                    error_log("Nouveau quiz QCM créé - Id_Quiz: " . $_SESSION['quiz_id']);
                }

                // Insérer la question
                $query = "INSERT INTO Questions (Question_Text, Id_Quiz) VALUES (:questionText, :quizId)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->bindValue(':questionText', $questionText, PDO::PARAM_STR);
                $stmt->bindValue(':quizId', $_SESSION['quiz_id'], PDO::PARAM_INT);
                $stmt->execute();
                $questionId = $mysqlClient->lastInsertId();
                

                // Tableau des choix
                $choix = [$choix1, $choix2, $choix3, $choix4];

                // Insérer les choix
                foreach ($choix as $index => $choixText) {
                    $query = "INSERT INTO Choix (Id_Question, Choix_Text) VALUES (:questionId, :choixText)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
                    $stmt->bindValue(':choixText', $choixText, PDO::PARAM_STR);
                    $stmt->execute();
                }

                // Récupérer la réponse correcte
                $reponseCorrecte = $choix[$reponseIndex - 1]; // Texte de la réponse correcte
                $typeReponse = $typeQuiz; // 'QCM'

                // Insérer la réponse correcte
                $query = "INSERT INTO ReponseCorrecte (Id_Question, Reponse_Correcte, Type_Reponse) VALUES (:questionId, :reponseCorrecte, :typeReponse)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
                $stmt->bindValue(':reponseCorrecte', $reponseCorrecte, PDO::PARAM_STR);
                $stmt->bindValue(':typeReponse', $typeReponse, PDO::PARAM_STR);
                $stmt->execute();
               

                // Si c'est terminer, réinitialiser la session
                if ($isTerminer) {
                    unset($_SESSION['quiz_id']);
                    unset($_SESSION['quiz_niveau']);
                    echo "<script>alert('Quiz QCM créé avec succès!'); window.location.href='Admin.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Question QCM ajoutée avec succès!'); window.location.href='Admin.php';</script>";
                    exit;
                }
            } else {
                error_log("QCM - Champs manquants : Question: $questionText, Choix1: $choix1, Choix2: $choix2, Choix3: $choix3, Choix4: $choix4");
                echo "<script>alert('Veuillez remplir tous les champs requis pour le QCM!');</script>";
            }
        } elseif ($typeQuiz === 'Vrai/Faux') {
            // Logique pour le Vrai/Faux
            $niveau = $_POST['ElementsVF'] ?? 'Facile';
            $questionText = $_POST['questionVF'] ?? '';
            $reponseCorrecte = $_POST['ReponseVF'] ?? ''; // "Vrai" ou "Faux"

            error_log("Vrai/Faux - Question: $questionText, Niveau: $niveau, Réponse: $reponseCorrecte");

            // Vérifier que tous les champs sont remplis
            if (!empty($questionText) && !empty($reponseCorrecte) && ($isQuestionSuivante || $isTerminer)) {
                // Créer ou récupérer le quiz
                if (!isset($_SESSION['quiz_id'])) {
                    $query = "INSERT INTO Quiz (Type_Quiz, Niveau) VALUES (:typeQuiz, :niveau)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->bindValue(':typeQuiz', $typeQuiz, PDO::PARAM_STR);
                    $stmt->bindValue(':niveau', $niveau, PDO::PARAM_STR);
                    $stmt->execute();
                    $_SESSION['quiz_id'] = $mysqlClient->lastInsertId();
                    $_SESSION['quiz_niveau'] = $niveau;
                }

                // Insérer la question
                $query = "INSERT INTO Questions (Question_Text, Id_Quiz) VALUES (:questionText, :quizId)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->bindValue(':questionText', $questionText, PDO::PARAM_STR);
                $stmt->bindValue(':quizId', $_SESSION['quiz_id'], PDO::PARAM_INT);
                $stmt->execute();
                $questionId = $mysqlClient->lastInsertId();
              

                // Insérer les choix (toujours "Vrai" et "Faux")
                $choix = ['Vrai', 'Faux'];
                foreach ($choix as $choixText) {
                    $query = "INSERT INTO Choix (Id_Question, Choix_Text) VALUES (:questionId, :choixText)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
                    $stmt->bindValue(':choixText', $choixText, PDO::PARAM_STR);
                    $stmt->execute();
                   
                }

                // Insérer la réponse correcte
                $typeReponse = $typeQuiz; // 'Vrai/Faux'
                $query = "INSERT INTO ReponseCorrecte (Id_Question, Reponse_Correcte, Type_Reponse) VALUES (:questionId, :reponseCorrecte, :typeReponse)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
                $stmt->bindValue(':reponseCorrecte', $reponseCorrecte, PDO::PARAM_STR);
                $stmt->bindValue(':typeReponse', $typeReponse, PDO::PARAM_STR);
                $stmt->execute();
               

                // Si c'est terminer, réinitialiser la session
                if ($isTerminer) {
                    unset($_SESSION['quiz_id']);
                    unset($_SESSION['quiz_niveau']);
                    echo "<script>alert('Quiz Vrai/Faux créé avec succès!'); window.location.href='Admin.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Question Vrai/Faux ajoutée avec succès!'); window.location.href='Admin.php';</script>";
                    exit;
                }
            } else {
                error_log("Vrai/Faux - Champs manquants : Question: $questionText, Réponse: $reponseCorrecte");
                echo "<script>alert('Veuillez remplir tous les champs requis pour le Vrai/Faux!');</script>";
            }
        } elseif ($typeQuiz === 'Réponse Libre') {
            // Logique pour Réponse Libre
            $niveau = $_POST['ElementsReponseLibre'] ?? 'Facile';
            $questionText = $_POST['questionReponseLibre'] ?? '';
            $reponseCorrecte = $_POST['ReponseLibre'] ?? '';



            // Vérifier que tous les champs sont remplis
            if (!empty($questionText) && !empty($reponseCorrecte) && ($isQuestionSuivante || $isTerminer)) {
                // Créer ou récupérer le quiz
                if (!isset($_SESSION['quiz_id'])) {
                    $query = "INSERT INTO Quiz (Type_Quiz, Niveau) VALUES (:typeQuiz, :niveau)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->bindValue(':typeQuiz', $typeQuiz, PDO::PARAM_STR);
                    $stmt->bindValue(':niveau', $niveau, PDO::PARAM_STR);
                    $stmt->execute();
                    $_SESSION['quiz_id'] = $mysqlClient->lastInsertId();
                    $_SESSION['quiz_niveau'] = $niveau;
           
                }

                // Insérer la question
                $query = "INSERT INTO Questions (Question_Text, Id_Quiz) VALUES (:questionText, :quizId)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->bindValue(':questionText', $questionText, PDO::PARAM_STR);
                $stmt->bindValue(':quizId', $_SESSION['quiz_id'], PDO::PARAM_INT);
                $stmt->execute();
                $questionId = $mysqlClient->lastInsertId();
                

                // Pas de choix à insérer pour Réponse Libre (table Choix reste vide pour cette question)

                // Insérer la réponse correcte
                $typeReponse = $typeQuiz; // 'Réponse Libre'
                $query = "INSERT INTO ReponseCorrecte (Id_Question, Reponse_Correcte, Type_Reponse) VALUES (:questionId, :reponseCorrecte, :typeReponse)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
                $stmt->bindValue(':reponseCorrecte', $reponseCorrecte, PDO::PARAM_STR);
                $stmt->bindValue(':typeReponse', $typeReponse, PDO::PARAM_STR);
                $stmt->execute();
               

                // Si c'est terminer, réinitialiser la session
                if ($isTerminer) {
                    unset($_SESSION['quiz_id']);
                    unset($_SESSION['quiz_niveau']);
                    echo "<script>alert('Quiz Réponse Libre créé avec succès!'); window.location.href='Admin.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Question Réponse Libre ajoutée avec succès!'); window.location.href='Admin.php';</script>";
                    exit;
                }
            } else {
                error_log("Réponse Libre - Champs manquants : Question: $questionText, Réponse: $reponseCorrecte");
                echo "<script>alert('Veuillez remplir tous les champs requis pour la Réponse Libre!');</script>";
            }
        } else {
            echo "<script>alert('Type de quiz non spécifié!');</script>";
        }
    }
} catch (Exception $e) {
    // Afficher l'erreur pour faciliter le débogage
    error_log("Erreur : " . $e->getMessage());
}
?>
</html>