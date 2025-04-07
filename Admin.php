<?php
session_start(); // Démarre la session pour stocker les données du quiz

try {
    // Connexion à la base de données
    $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $message = ''; // Variable pour stocker les messages de confirmation ou d'erreur

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question_suivante'])) {
        $typeQuiz = $_POST['typeQuiz'] ?? '';

        // Vérifie si le type de quiz a changé
        if (!isset($_SESSION['quiz_type']) || $_SESSION['quiz_type'] !== $typeQuiz) {
            // Si le type de quiz a changé, réinitialise le quiz_id pour créer un nouveau quiz
            unset($_SESSION['quiz_id']);
            $_SESSION['quiz_type'] = $typeQuiz; // Stocke le nouveau type de quiz
        }

        if ($typeQuiz === 'QCM') {
            $niveau = $_POST['ElementsQCM'] ?? 'Facile';
            $questionText = $_POST['questionQCM'] ?? '';
            $choix1 = $_POST['Choix1'] ?? '';
            $choix2 = $_POST['Choix2'] ?? '';
            $choix3 = $_POST['Choix3'] ?? '';
            $choix4 = $_POST['Choix4'] ?? '';
            $reponseIndex = intval($_POST['ReponseQCM'] ?? '1');

            if (empty($questionText) || empty($choix1) || empty($choix2) || empty($choix3) || empty($choix4)) {
                $message = "Erreur : Tous les champs du QCM doivent être remplis.";
            } else {
                // Créer un nouveau quiz si nécessaire
                if (!isset($_SESSION['quiz_id'])) {
                    $query = "INSERT INTO Quiz (Type_Quiz, Niveau) VALUES (:typeQuiz, :niveau)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->execute([':typeQuiz' => $typeQuiz, ':niveau' => $niveau]);
                    $_SESSION['quiz_id'] = $mysqlClient->lastInsertId();
                    $_SESSION['quiz_niveau'] = $niveau;
                }

                // Insère la question
                $query = "INSERT INTO Questions (Question_Text, Id_Quiz) VALUES (:questionText, :quizId)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->execute([':questionText' => $questionText, ':quizId' => $_SESSION['quiz_id']]);
                $questionId = $mysqlClient->lastInsertId();

                $choix = [$choix1, $choix2, $choix3, $choix4];
                foreach ($choix as $choixText) {
                    $query = "INSERT INTO Choix (Id_Question, Choix_Text) VALUES (:questionId, :choixText)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->execute([':questionId' => $questionId, ':choixText' => $choixText]);
                }

                $reponseCorrecte = $choix[$reponseIndex - 1];
                $query = "INSERT INTO ReponseCorrecte (Id_Question, Reponse_Correcte, Type_Reponse) VALUES (:questionId, :reponseCorrecte, :typeReponse)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->execute([':questionId' => $questionId, ':reponseCorrecte' => $reponseCorrecte, ':typeReponse' => $typeQuiz]);

        
            }

        } elseif ($typeQuiz === 'Vrai/Faux') {
            $niveau = $_POST['ElementsVF'] ?? 'Facile';
            $questionText = $_POST['questionVF'] ?? '';
            $reponseCorrecte = $_POST['ReponseVF'] ?? '';

            if (empty($questionText) || empty($reponseCorrecte)) {
                $message = "Erreur : Tous les champs du Vrai/Faux doivent être remplis.";
            } else {
                // Créer un nouveau quiz si nécessaire
                if (!isset($_SESSION['quiz_id'])) {
                    $query = "INSERT INTO Quiz (Type_Quiz, Niveau) VALUES (:typeQuiz, :niveau)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->execute([':typeQuiz' => $typeQuiz, ':niveau' => $niveau]);
                    $_SESSION['quiz_id'] = $mysqlClient->lastInsertId();
                    $_SESSION['quiz_niveau'] = $niveau;
                }

                // Insère la question
                $query = "INSERT INTO Questions (Question_Text, Id_Quiz) VALUES (:questionText, :quizId)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->execute([':questionText' => $questionText, ':quizId' => $_SESSION['quiz_id']]);
                $questionId = $mysqlClient->lastInsertId();


                $choix = ['Vrai', 'Faux'];
                foreach ($choix as $choixText) {
                    $query = "INSERT INTO Choix (Id_Question, Choix_Text) VALUES (:questionId, :choixText)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->execute([':questionId' => $questionId, ':choixText' => $choixText]);
                }

           
                $query = "INSERT INTO ReponseCorrecte (Id_Question, Reponse_Correcte, Type_Reponse) VALUES (:questionId, :reponseCorrecte, :typeReponse)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->execute([':questionId' => $questionId, ':reponseCorrecte' => $reponseCorrecte, ':typeReponse' => $typeQuiz]);

                
            }

        } elseif ($typeQuiz === 'Réponse Libre') {
            $niveau = $_POST['ElementsReponseLibre'] ?? 'Facile';
            $questionText = $_POST['questionReponseLibre'] ?? '';
            $reponseCorrecte = $_POST['ReponseLibre'] ?? '';

            if (empty($questionText) || empty($reponseCorrecte)) {
                $message = "Erreur : Tous les champs de la Réponse Libre doivent être remplis.";
            } else {
                // Créer un nouveau quiz si nécessaire
                if (!isset($_SESSION['quiz_id'])) {
                    $query = "INSERT INTO Quiz (Type_Quiz, Niveau) VALUES (:typeQuiz, :niveau)";
                    $stmt = $mysqlClient->prepare($query);
                    $stmt->execute([':typeQuiz' => $typeQuiz, ':niveau' => $niveau]);
                    $_SESSION['quiz_id'] = $mysqlClient->lastInsertId();
                    $_SESSION['quiz_niveau'] = $niveau;
                }

                // Insère la question
                $query = "INSERT INTO Questions (Question_Text, Id_Quiz) VALUES (:questionText, :quizId)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->execute([':questionText' => $questionText, ':quizId' => $_SESSION['quiz_id']]);
                $questionId = $mysqlClient->lastInsertId();

      
                $query = "INSERT INTO ReponseCorrecte (Id_Question, Reponse_Correcte, Type_Reponse) VALUES (:questionId, :reponseCorrecte, :typeReponse)";
                $stmt = $mysqlClient->prepare($query);
                $stmt->execute([':questionId' => $questionId, ':reponseCorrecte' => $reponseCorrecte, ':typeReponse' => $typeQuiz]);

                
            }
        }
    }
} catch (Exception $e) {
    $message = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Questions</title>
    <link rel="stylesheet" href="styleAdmin.css">
</head>
<body>
    <header>
        <h1 id="TITRE">Création de Questions</h1>
        <div id="BlockDeconnexion">
            <a href="index.php"><button id="DeconnexionAdmin">Déconnexion</button></a>
        </div>
        <div id="BlockSupModif">
            <a href="ModifQuiz.php"><button id="ModifSup_Admin">Modification</button></a>
        </div>
    </header>


    <div class="Container">
        <!-- Formulaire pour QCM -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="formQCM">
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
                    <input type="text" id="questionTextQCM" name="questionQCM" placeholder="Entrer votre question">
                </div>
                <div id="ConfigChoix">
                    <div class="IntituleQuestion">
                        <label for="Choix">Configuration des choix :</label>
                    </div>
                    <div class="ChoixContainer">
                        <input type="text" name="Choix1" placeholder="Choix 1" class="ChoixE"><br>
                        <input type="text" name="Choix2" placeholder="Choix 2" class="ChoixE"><br>
                        <input type="text" name="Choix3" placeholder="Choix 3" class="ChoixE"><br>
                        <input type="text" name="Choix4" placeholder="Choix 4" class="ChoixE">
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
                    <input type="hidden" name="typeQuiz" value="QCM">
                    <button type="submit" name="question_suivante" class="Suivant">Question Suivante</button>
                </div>
            </div>
        </form>

        <!-- Formulaire pour Vrai/Faux -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="formVF">
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
                    <input type="text" id="questionTextVF" name="questionVF" placeholder="Entrer votre question">
                </div>
                <div id="ConfigChoix">
                    <div class="IntituleQuestion">
                        <label for="ChoixVF">Réponse à la question :</label>
                    </div>
                    <div id="ChoixContainer2">
                        <label>
                            <input type="radio" name="ReponseVF" value="Vrai" class="ChoixE2"> Vrai
                        </label><br>
                        <label>
                            <input type="radio" name="ReponseVF" value="Faux" class="ChoixE2"> Faux
                        </label>
                    </div>
                </div>
                <div class="QuestionTerminer">
                    <input type="hidden" name="typeQuiz" value="Vrai/Faux">
                    <button type="submit" name="question_suivante" class="Suivant">Question Suivante</button>
                </div>
            </div>
        </form>

        <!-- Formulaire pour Réponse Libre -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="formReponseLibre">
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
                    <input type="text" id="questionTextReponseLibre" name="questionReponseLibre" placeholder="Entrer votre question">
                </div>
                <div id="ReponseContainer">
                    <div class="IntituleQuestion">
                        <div id="Espacement"> 
                            <label for="ReponseLibre">Réponse à la question :</label>
                            <span class="Espacement2"><input type="text" name="ReponseLibre" placeholder="Réponse" id="Colorier"></span>
                        </div>
                    </div>
                </div>
                <div class="QuestionTerminer">
                    <input type="hidden" name="typeQuiz" value="Réponse Libre">
                    <button type="submit" name="question_suivante" class="Suivant">Question Suivante</button>
                </div>
            </div>
        </form>
    </div>

    <script src="scriptAdmin.js"></script>
</body>
</html>