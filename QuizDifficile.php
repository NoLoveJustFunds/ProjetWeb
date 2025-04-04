<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleQuizDifficile.css">
    <title>HardQuiz</title>
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
        <img src="FondChrono.png" id="Image">
    </div>

    <!-- Container pour les questions et réponses -->
    <div class="quizContainer">
    <form id="quizForm" method="POST" action="QuizDifficile.php">
        <div id="IntituleQuestion"></div>

        

        <div id="TexteDescriptif">
            <p>Bienvenue dans le Quiz Difficile</p>
            <p id="Dessous">Chaque question sera uniquement à compléter</p><br>
            <p>Vous avez 20 secondes par question</p><br>
            <p>J'espère que vous avez bien révisé. Bonne chance !</p>
        </div>

        <div id="ElementReponse2">
            <div id="Reponse1">
                <div id="IntituleQuestion2" style="color: white; text-align: center;"></div>
            </div>
        </div>
        <input type="hidden" name="score" id="scoreInput">
        <input type="hidden" name="total" id="totalInput">
    </form>
</div>



    <!-- Section de démarrage du quiz -->

   
    <div id="ContainerBouton">
        <div id="quizIntro">
            <button id="startButton" onclick="loadQuizData()" onclick="startQuiz()">Lancer Quiz</button>
        </div>
        <div id="Retour">
            <!-- Le bouton Retour soumet le formulaire manuellement -->
           <a href="QuizPage.php"><button id="returnButton2">Retour</a></button>
            <button id="returnButton" onclick="submitForm()" >Retour</button>
        </div>
    </div>
    
    <?php
    session_start();

    $mailUser = $_SESSION['Mail_User'];
  

    // Récupèreles données actuelles de l'utilisateur (Score_User et Total_Play)
    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $fetchQuery = "SELECT Score_User, Total_Play FROM Score WHERE Mail_User = :mail";
        $fetchStmt = $mysqlClient->prepare($fetchQuery);
        $fetchStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR); // Associe l'email de l'utilisateur à la variable email
        $fetchStmt->execute();
        $userData = $fetchStmt->fetch(PDO::FETCH_ASSOC); //Récupère la première ligne de la requete SQL

        if ($userData) {
            $_SESSION['Score_User'] = $userData['Score_User']; //Associe a la variable de la session , le score du joueru
            $_SESSION['Total_Play'] = $userData['Total_Play'];
            
        } else {
            $_SESSION['Score_User'] = 0; //Sinon l'initialise à 0
            $_SESSION['Total_Play'] = 0;
          
        }
    } catch (Exception $e) {

        $_SESSION['Score_User'] = 0;
        $_SESSION['Total_Play'] = 0;
    }

    // Gère la soumission du score (déclenchée manuellement par le bouton Retour)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      

        if (isset($_POST['score']) && isset($_POST['total'])) { //Verifie su les données existe déjà
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
                $userExists = $checkStmt->fetchColumn(); //Récupère la premiere colonne de la première ligne 
             

                if ($userExists) {

                    // Met à jour l'entrée existante en additionnant les valeurs
                    $updateQuery = "UPDATE Score SET Score_User = Score_User + :score, Total_Play = Total_Play + :total WHERE Mail_User = :mail";
                    //Pour la requet j'ai mis pas mal de temsp  à trouver le fait qu'il faut ajouter et non remplacer
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

                header("Location: QuizPage.php"); //Header permet de se rediriger vers la page ciblée 
                exit;
            } catch (Exception $e) {
                echo "Erreur lors de la mise à jour du score : " . $e->getMessage(); //En java , j'aurais utilisé getStackTrace()
            }
        } 
    }

    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    
        $query = "
            SELECT q.Id_Question, q.Question_Text, rc.Reponse_Correcte
            FROM Questions q
            JOIN Quiz z ON q.Id_Quiz = z.Id_Quiz
            LEFT JOIN ReponseCorrecte rc ON q.Id_Question = rc.Id_Question
            LEFT JOIN Choix c ON q.Id_Question = c.Id_Question
            WHERE z.Type_Quiz = 'Réponse Libre'
            ORDER BY q.Id_Question";
            
        $stmt = $mysqlClient->prepare($query);
        $stmt->execute();
    
        $quizData = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { //tableau associatif sous forme de clé : valeur
            $questionId = $row['Id_Question']; //Récupère l'id de la questions
            if (!isset($quizData[$questionId])) {
                $quizData[$questionId] = [
                    'question' => $row['Question_Text'],
                    'choices' => [], 
                    'correctAnswer' => $row['Reponse_Correcte']
                ];
            }
          
        }
    
        $quizData = array_values($quizData);
        shuffle($quizData);//Mélange le tableau de manière aléatorie
        $selectedQuestions = array_slice($quizData, 0, min(10, count($quizData))); //Si nombre de question < 10 alors on prends tous sinon 10 questions max
        //arrat_slice permet de prendre des "morceaux" du tableau ( 10 valeurs max seront prises)
    
    } catch (Exception $e) {
        $selectedQuestions = ['error' => 'Erreur : ' . $e->getMessage()]; 
        
    }
    ?>


    <!-- Conteneur pour afficher les questions dynamiquement -->
    <script>
        const quizDataInitial = <?php echo json_encode($selectedQuestions); ?>;
    
    </script>

    <script src="scriptDifficile.js"></script>
    
</body>
</html>