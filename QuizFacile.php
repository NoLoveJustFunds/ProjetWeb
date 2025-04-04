<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyQuiz</title>
    <link rel="stylesheet" href="styleQuizFacile.css">
</head>
<body>
    <header>
        <h1 id="Titre">QuizzyLand</h1>
    </header>

    <div id="PlacementBarProgress">
        <progress id="questions" value="0" max="10"></progress>
    </div>
    <div id="PlacementChrono">
        <span id="Temp">20</span>
        <img src="FondChrono.png" id="Image">
    </div>

    <div class="quizContainer">
        <form id="quizForm" method="POST" action="QuizFacile.php">
            <div id="IntituleQuestion"></div>

            <div id="TexteDescriptif">
                <p>Bienvenue dans le Quiz Facile</p>
                <p id="Dessous">Chaque question aura 4 propositions qui vous seront proposées</p><br>
                <p>Vous aurez 20 secondes par question</p><br>
                <p>À vous de faire les bons choix. Bonne chance !</p><br>
            </div>

            <div id="ElementReponse"> <!-- Appel de la fonction selectChoice pour avoir le fond coloré (Si bonne / mauvaise réposne)-->
                <!-- J'ai utilisé this car le bouton "this" fait référence au bouton lui meme ( appel de la fonction sur le bouton cliqué)-->
                <div id="Reponse1"><button type="button" id="Bouton1" onclick="selectChoice(this)"></button></div>
                <div id="Reponse2"><button type="button" id="Bouton2" onclick="selectChoice(this)"></button></div>
                <div id="Reponse3"><button type="button" id="Bouton3" onclick="selectChoice(this)"></button></div>
                <div id="Reponse4"><button type="button" id="Bouton4" onclick="selectChoice(this)"></button></div>
            </div>

            <!-- Champs cachés pour stocker le score et le total -->
            <input type="hidden" name="score" id="scoreInput">
            <input type="hidden" name="total" id="totalInput">
        </form>
    </div>

    <div id="ContainerBouton">
        <div id="quizIntro">
            <button id="startButton" onclick="loadQuizData()" onclick="startQuiz()">Lancer Quiz</button>
        </div>
        <div id="Retour">
            <!-- Le bouton Retour soumet le formulaire et l'autre permet de faire un retour sur la page précédente -->
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
        $fetchStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR); //Associe le mail de l'user à la variable mail
        $fetchStmt->execute();
        $userData = $fetchStmt->fetch(PDO::FETCH_ASSOC); //Récupère une ligne a la fois du resulat de la requete

        if ($userData) {
            $_SESSION['Score_User'] = $userData['Score_User']; //Stocke le score de l'utilsateur la variable de la session
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
            $score = (int)$_POST['score']; //Convertit le score si str en entier pour éviter une comparaison entier / str
            $total = (int)$_POST['total'];
        

            try {
                $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // Vérifier si l'utilisateur a déjà une entrée dans la table Score
                $checkQuery = "SELECT COUNT(*) FROM Score WHERE Mail_User = :mail";
                $checkStmt = $mysqlClient->prepare($checkQuery);
                $checkStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR);
                $checkStmt->execute();
                $userExists = $checkStmt->fetchColumn(); //Récupere la premiere ligne du resultat de la requete


                if ($userExists) {
                    
                    $updateQuery = "UPDATE Score SET Score_User = Score_User + :score, Total_Play = Total_Play + :total WHERE Mail_User = :mail";
                    //Pour la requete , j'ai mis pas mal de temps pour avoir le resultat , la solution était d'ajouter l'ancien et le nouveau score et pas de le remplacer.
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

               
                // Récupère les nouvelles valeurs  pour la session
                $fetchQuery = "SELECT Score_User, Total_Play FROM Score WHERE Mail_User = :mail";
                $fetchStmt = $mysqlClient->prepare($fetchQuery);
                $fetchStmt->bindParam(':mail', $mailUser, PDO::PARAM_STR); //Associe le mail de l'user a la variable
                $fetchStmt->execute();
                $userData = $fetchStmt->fetch(PDO::FETCH_ASSOC);

                if ($userData) {
                    $_SESSION['Score_User'] = $userData['Score_User'];
                    $_SESSION['Total_Play'] = $userData['Total_Play'];
                    
                }
                header("Location: QuizPage.php"); //header permet de rediriger vers la page ciblée 
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
            WHERE z.Type_Quiz = 'QCM'
            ORDER BY q.Id_Question";
            
        $stmt = $mysqlClient->prepare($query);
        $stmt->execute();

        $quizData = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { //tableau associatif avec les noms de colonnes comme clé 
            $questionId = $row['Id_Question'];
            if (!isset($quizData[$questionId])) { //Verifie si la question n'est pas dans le tableau
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
        shuffle($quizData); //Mélange les elemnents de maniere aléatoire
        $selectedQuestions = array_slice($quizData, 0, min(10, count($quizData)));
        //Extrait toutes les questions si <10 sinon prend max 10 questions parmi le total
        //Slice permet de couper le tableau en morceau , et de garder max 10 questions


    } catch (Exception $e) {
        $selectedQuestions = ['error' => 'Erreur : ' . $e->getMessage()]; //Retour du message d'erreur avec getMessage
        //En Java , j'aurais utilisé getStackTrace()
    }
    ?>

    <script>
        const quizDataInitial = <?php echo json_encode($selectedQuestions); ?>; 
        //Cette fonction PHP convertit le tableau  PHP  en un format compréhensible par JS (sous forme tableau clé : valeur).
    </script>

    <script src="scriptFacile.js"></script>
</body>
</html>