<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une question</title>
    <link rel="stylesheet" href="styleModifierQuestion.css">
</head>

<?php

// Connexion à la base de données
try {
    $mysqlClient = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

$idQuestion = (int)$_GET['id'];

$query = "
    SELECT q.Id_Question, q.Question_Text, rc.Reponse_Correcte, z.Type_Quiz
    FROM Questions q
    LEFT JOIN ReponseCorrecte rc ON q.Id_Question = rc.Id_Question
    LEFT JOIN Quiz z ON q.Id_Quiz = z.Id_Quiz
    WHERE q.Id_Question = :idQuestion
";


$stmt = $mysqlClient->prepare($query);
$stmt->execute(['idQuestion' => $idQuestion]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);


// Récupère les choix (si QCM)
$choix = ['', '', '', ''];
if ($question['Type_Quiz'] === 'QCM') {
    $queryChoix = "SELECT Choix_Text FROM Choix WHERE Id_Question = :idQuestion ORDER BY Id_Choix LIMIT 4";
    $stmtChoix = $mysqlClient->prepare($queryChoix);
    $stmtChoix->execute(['idQuestion' => $idQuestion]);
    $choixResult = $stmtChoix->fetchAll(PDO::FETCH_COLUMN);
    for ($i = 0; $i < min(4, count($choixResult)); $i++) { //Prendra tous les choix sauf si >4 alors il prendra 4
        $choix[$i] = $choixResult[$i];
    }
}

// Gestion de la soumission du formulaire
if (isset($_POST['update_question'])) {
    $questionText = $_POST['question_text'];
    $typeQuiz = $_POST['type_quiz'];
    $reponseCorrecte = $_POST['reponse_correcte'];

    // Met à jour la question
    $queryUpdateQuestion = "UPDATE Questions SET Question_Text = :questionText WHERE Id_Question = :idQuestion";
    $stmtUpdateQuestion = $mysqlClient->prepare($queryUpdateQuestion);
    $stmtUpdateQuestion->execute(['questionText' => $questionText, 'idQuestion' => $idQuestion]);

    // Metà jour ou insérer la réponse correcte
    $queryCheckReponse = "SELECT COUNT(*) FROM ReponseCorrecte WHERE Id_Question = :idQuestion";
    $stmtCheckReponse = $mysqlClient->prepare($queryCheckReponse);
    $stmtCheckReponse->execute(['idQuestion' => $idQuestion]);
    $reponseExists = $stmtCheckReponse->fetchColumn();

    if ($reponseExists) {
        $queryUpdateReponse = "UPDATE ReponseCorrecte SET Reponse_Correcte = :reponseCorrecte WHERE Id_Question = :idQuestion";
        $stmtUpdateReponse = $mysqlClient->prepare($queryUpdateReponse);
        $stmtUpdateReponse->execute(['reponseCorrecte' => $reponseCorrecte, 'idQuestion' => $idQuestion]);
    } else {
        $queryInsertReponse = "INSERT INTO ReponseCorrecte (Id_Question, Reponse_Correcte) VALUES (:idQuestion, :reponseCorrecte)";
        $stmtInsertReponse = $mysqlClient->prepare($queryInsertReponse);
        $stmtInsertReponse->execute(['idQuestion' => $idQuestion, 'reponseCorrecte' => $reponseCorrecte]);
    }

    // Si c'est un quiz "QCM"
    if ($typeQuiz === 'QCM') {
        // Supprimer les anciens choix
        $queryDeleteChoix = "DELETE FROM Choix WHERE Id_Question = :idQuestion";
        $stmtDeleteChoix = $mysqlClient->prepare($queryDeleteChoix);
        $stmtDeleteChoix->execute(['idQuestion' => $idQuestion]);

        // Insère les nouveaux choix
        for ($i = 1; $i <= 4; $i++) {
            $choixText = $_POST["choix_$i"] ?? '';
            if (!empty($choixText)) {
                $queryInsertChoix = "INSERT INTO Choix (Id_Question, Choix_Text) VALUES (:idQuestion, :choixText)";
                $stmtInsertChoix = $mysqlClient->prepare($queryInsertChoix);
                $stmtInsertChoix->execute(['idQuestion' => $idQuestion, 'choixText' => $choixText]);
            }
        }
    }

    header("Location: ModifQuiz.php"); //Redirection vers la page de modification de quiz 
    exit();
}
?>


<body>
    <h1>Modifier la question</h1>
    <div class="container">
        <form method="POST" action="modifier_question.php?id=<?php echo $idQuestion; ?>">
            <input type="hidden" name="type_quiz" value="<?php echo htmlspecialchars($question['Type_Quiz']); ?>">
            
            <div class="form-group">
                <label for="question_text">Question :</label>
                <textarea id="question_text" name="question_text" required><?php echo htmlspecialchars($question['Question_Text']); ?></textarea>
            </div>

            <?php if ($question['Type_Quiz'] === 'QCM'): ?>
                <div class="form-group">
                    <label>Propositions :</label>
                    <div class="choix-container">
                        <?php for ($i = 1; $i <= 4; $i++): ?> <!-- Boucle qui va parcourir les 4 propositions pour afficher 4 inputs en lignes -->
                            <div class="choix-item">
                                <label for="choix_<?php echo $i; ?>">Proposition <?php echo $i; ?> :</label>
                                <input type="text" id="choix_<?php echo $i; ?>" name="choix_<?php echo $i; ?>" value="<?php echo htmlspecialchars($choix[$i-1]); ?>" placeholder="Proposition <?php echo $i; ?>">
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="reponse_correcte">Réponse Correcte :</label>
                <input type="text" id="reponse_correcte" name="reponse_correcte" value="<?php echo htmlspecialchars($question['Reponse_Correcte'] ?? ''); ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" name="update_question" class="btn-enregistrer">Enregistrer</button>
                <a href="ModifQuiz.php" class="btn-annuler">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        // Validation avant soumission (pour QCM)
        document.querySelector('form').addEventListener('submit', function(e) {
            const typeQuiz = document.querySelector('input[name=type_quiz]').value;
            if (typeQuiz === 'QCM') {
                const choixInputs = [
                    document.querySelector('input[name=choix_1]').value,
                    document.querySelector('input[name=choix_2]').value,
                    document.querySelector('input[name=choix_3]').value,
                    document.querySelector('input[name=choix_4]').value
                ];
                const filledChoix = choixInputs.filter(choix => choix.trim() !== '').length;
                if (filledChoix < 2) { //Il doit y avoir au moins 2 propositions pour le QCM
                    return;
                }

                const reponseCorrecte = document.querySelector('input[name=reponse_correcte]').value;
                if (!choixInputs.includes(reponseCorrecte)) { //Si la reponse n'est pas dans les propositions
                    return;
                }
            }
        });
    </script>
</body>
</html>