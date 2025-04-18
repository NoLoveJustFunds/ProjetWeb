<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediumQuiz</title>
    <link rel="stylesheet" href="styleQuizMoyen.css">
</head>
<body>
    <header>
        <h1 id="Titre">QuizzyLand</h1>
    </header>

    <div id="PlacementBarProgress">
        <progress id="questions" value ="0" max="10" ></progress>
    </div>
    <div id="PlacementChrono">
        <span id="Temp">20</span>
        <img src="Images/FondChrono.png" id="Image">
    </div>

    <!-- Container pour les questions et réponses -->
<div class="quizContainer">
    <form id="quizForm" method="POST" action="QuiMoyen.php">
       
        <div id="IntituleQuestion"></div>

        <div id="Remercier">
            <p>Remerciement</p>
        </div>

        <div id="TexteDescriptif">
            <p>Bienvenue dans le Quiz Moyen<br>
            <p id="Dessous">Chaque question aura 2 propositions qui vous seront proposées</p><br>
            <p>Vous avez 20 secondes par question</p><br>
            <p>A vous de faire les bons choix. Bonne chance !</p>
            </p>
        </div>

        <div id="ElementReponse">
            <div id="Reponse1">
                <button type="button" id="Bouton1" onclick="selectChoice(this)"></button>
            </div>
            <div id="Reponse2">
                <button type="button" id="Bouton2" onclick="selectChoice(this)"></button>
            </div>
        </div>


        <input type="hidden" id="scoreInput" name="score">
        <input type="hidden" id="totalInput" name="total">
    </form>
</div>

<div id="ContainerBouton">
    <div id="quizIntro">
        <button id="startButton" onclick="loadQuizData()" onclick="startQuiz()">Lancer Quiz</button>
    </div>

    <div id="Retour">
    <a href="QuizPage.php"><button id="returnButton2">Retour</a></button>
            <button id="returnButton" onclick="submitForm()" >Retour</button>
    </div>
</div>

    <?php
    session_start();


    $mailUser = $_SESSION['Mail_User'];
    

    // Récupère les données actuelles de l'utilisateur (Score_User et Total_Play)
    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $fetchQuery = "SELECT Score_User, Total_Play FROM Score WHERE Mail_User = :mail";
        $fetchStmt = $mysqlClient->prepare($fetchQuery);
        $fetchStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR);
        $fetchStmt->execute();
        $userData = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $_SESSION['Score_User'] = $userData['Score_User'];
            $_SESSION['Total_Play'] = $userData['Total_Play'];
           
        } else {
            $_SESSION['Score_User'] = 0;
            $_SESSION['Total_Play'] = 0;

        }
    } catch (Exception $e) {
   
        $_SESSION['Score_User'] = 0;
        $_SESSION['Total_Play'] = 0;
    }

    // Gère la soumission du score (déclenchée manuellement par le bouton Retour)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        

        if (isset($_POST['score']) && isset($_POST['total'])) {
            $score = (int)$_POST['score'];
            $total = (int)$_POST['total'];
           

            try {
                $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // Vérifie si l'utilisateur a déjà une entrée dans la table Score
                $checkQuery = "SELECT COUNT(*) FROM Score WHERE Mail_User = :mail";
                $checkStmt = $mysqlClient->prepare($checkQuery);
                $checkStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR);
                $checkStmt->execute();
                $userExists = $checkStmt->fetchColumn();
          

                if ($userExists) {
                    // Met à jour l'entrée existante en additionnant les valeurs
                    $updateQuery = "UPDATE Score SET Score_User = Score_User + :score, Total_Play = Total_Play + :total WHERE Mail_User = :mail";
                    $updateStmt = $mysqlClient->prepare($updateQuery);
                    $updateStmt->bindParam(':score', $score, PDO::PARAM_INT);
                    $updateStmt->bindParam(':total', $total, PDO::PARAM_INT);
                    $updateStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR);
                    $updateStmt->execute();
                    
                } else {
                    // Insère une nouvelle entrée
                    $insertQuery = "INSERT INTO Score (Score_User, Total_Play, Mail_User) VALUES (:score, :total, :mail)";
                    $insertStmt = $mysqlClient->prepare($insertQuery);
                    $insertStmt->bindParam(':score', $score, PDO::PARAM_INT);
                    $insertStmt->bindParam(':total', $total, PDO::PARAM_INT);
                    $insertStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR);
                    $insertStmt->execute();
                    
                }

                
                // Récupère les nouvelles valeurs cumulées pour la session
                $fetchQuery = "SELECT Score_User, Total_Play FROM Score WHERE Mail_User = :mail";
                $fetchStmt = $mysqlClient->prepare($fetchQuery);
                $fetchStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR);
                $fetchStmt->execute();
                $userData = $fetchStmt->fetch(PDO::FETCH_ASSOC);

                if ($userData) {
                    $_SESSION['Score_User'] = $userData['Score_User'];
                    $_SESSION['Total_Play'] = $userData['Total_Play'];
                    
                }

            
                header("Location: QuizPage.php");
                exit;
            } catch (Exception $e) {
                echo "Erreur lors de la mise à jour du score : " . $e->getMessage();
            }
        } 
    }

    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $query = "
            SELECT q.Id_Question, q.Question_Text, c.Id_Choix, c.Choix_Text, rc.Reponse_Correcte
            FROM Questions q
            JOIN Choix c ON q.Id_Question = c.Id_Question
            JOIN Quiz z ON q.Id_Quiz = z.Id_Quiz
            LEFT JOIN ReponseCorrecte rc ON q.Id_Question = rc.Id_Question
            WHERE z.Type_Quiz = 'Vrai/Faux'
            ORDER BY q.Id_Question";
            
        $stmt = $mysqlClient->prepare($query);
        $stmt->execute();

        $quizData = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $questionId = $row['Id_Question'];
            if (!isset($quizData[$questionId])) {
                $quizData[$questionId] = [
                    'question' => $row['Question_Text'],
                    'choices' => [],
                    'correctAnswer' => $row['Reponse_Correcte']
                ];
            }
            $quizData[$questionId]['choices'][] = [
                'id' => $row['Id_Choix'],
                'text' => $row['Choix_Text']
            ];
        }

        $quizData = array_values($quizData);
        shuffle($quizData);
        $selectedQuestions = array_slice($quizData, 0, min(10, count($quizData)));
    } catch (Exception $e) {
        $selectedQuestions = ['error' => 'Erreur : ' . $e->getMessage()];
    }
    ?>


    <script>
        const quizDataInitial = <?php echo json_encode($selectedQuestions); ?>;
      
    </script>

   

    <script src="scriptMoyen.js"></script>
</body>
</html>