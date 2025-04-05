<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des questions</title>
    <link rel="stylesheet" href="styleModifQuiz.css">
</head>

<?php
// Connexion à la base de données avec gestion des erreurs
try {
    $dbConnection = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Suppression d'une question et de ses dépendances (choix + réponse correcte)
if (isset($_POST['delete_question'])) {
    $questionId = $_POST['id_question'];

    // Suppression des choix associés
    $deleteChoicesQuery = "DELETE FROM Choix WHERE Id_Question = :idQuestion";
    $deleteChoicesStmt = $dbConnection->prepare($deleteChoicesQuery);
    $deleteChoicesStmt->execute(['idQuestion' => $questionId]);

    // Suppression de la réponse correcte
    $deleteAnswerQuery = "DELETE FROM ReponseCorrecte WHERE Id_Question = :idQuestion";
    $deleteAnswerStmt = $dbConnection->prepare($deleteAnswerQuery);
    $deleteAnswerStmt->execute(['idQuestion' => $questionId]);

    // Suppression de la question elle-même
    $deleteQuestionQuery = "DELETE FROM Questions WHERE Id_Question = :idQuestion";
    $deleteQuestionStmt = $dbConnection->prepare($deleteQuestionQuery);
    $deleteQuestionStmt->execute(['idQuestion' => $questionId]);

    // Redirection vers la page après suppression
    header("Location: ModifQuiz.php");
    exit();
}

// Récupération de tous les types de quiz disponibles pour les filtres
$quizTypesQuery = "SELECT DISTINCT Type_Quiz FROM Quiz WHERE Type_Quiz IS NOT NULL";
$quizTypesStmt = $dbConnection->prepare($quizTypesQuery);
$quizTypesStmt->execute();
$availableQuizTypes = $quizTypesStmt->fetchAll(PDO::FETCH_COLUMN); // Renvoie un tableau contenant uniquement la colonne "Type_Quiz"

// Récupération des filtres passés en GET (recherche par nom + type de quiz)
$questionNameFilter = isset($_GET['search_text']) ? trim($_GET['search_text']) : '';
$quizTypeFilter = isset($_GET['search_type']) ? trim($_GET['search_type']) : '';


$questionsQuery = "
    SELECT q.Id_Question, q.Question_Text, rc.Reponse_Correcte, z.Type_Quiz, 
           GROUP_CONCAT(c.Choix_Text SEPARATOR ' | ') AS Choix
    FROM Questions q
    LEFT JOIN ReponseCorrecte rc ON q.Id_Question = rc.Id_Question
    LEFT JOIN Quiz z ON q.Id_Quiz = z.Id_Quiz
    LEFT JOIN Choix c ON q.Id_Question = c.Id_Question
    WHERE 1=1
";

// Tableau des paramètres pour la requête pour les filtres
$queryParams = [];

// Ajout d'un filtre sur le texte de la question
if (!empty($questionNameFilter)) {
    $questionsQuery .= " AND q.Question_Text LIKE :searchText";
    $queryParams['searchText'] = '%' . $questionNameFilter . '%';
}

// Ajout du filtre sur le type de quiz 
if (!empty($quizTypeFilter) && $quizTypeFilter !== 'all') {
    $questionsQuery .= " AND z.Type_Quiz = :searchType";
    $queryParams['searchType'] = $quizTypeFilter;
}

$questionsQuery .= " GROUP BY q.Id_Question";

// Exécution de la requête avec les filtres
$questionsStmt = $dbConnection->prepare($questionsQuery);
$questionsStmt->execute($queryParams);
$filteredQuestions = $questionsStmt->fetchAll(PDO::FETCH_ASSOC); // Récupère les résultats en tableau associatif
?>


<body>
    <h1>Gestion des questions du quiz</h1>

    <div class="container">
        <!-- Formulaire de filtre pour rechercher des questions -->
        <form method="GET" action="ModifQuiz.php" class="filter-form">
            <!-- Champ pour filtrer par texte -->
            <div class="filter-group">
                <label for="question_name_filter">Recherche par nom :</label>
                <input type="text" id="question_name_filter" name="search_text" 
                       value="<?php echo htmlspecialchars($questionNameFilter); ?>" 
                       placeholder="Nom de la question">
            </div>

            <!-- Menu déroulant pour filtrer par type de quiz -->
            <div class="filter-group">
                <label for="quiz_type_filter">Type de quiz :</label>
                <select id="quiz_type_filter" name="search_type">
                    <!-- Option pour afficher tous les types -->
                    <option value="all" <?php echo $quizTypeFilter === 'all' || empty($quizTypeFilter) ? 'selected' : ''; ?>>Tous</option>
                    
                    <!-- Boucle sur chaque type de quiz disponible -->
                    <?php foreach ($availableQuizTypes as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>" 
                                <?php echo $quizTypeFilter === $type ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Bouton pour lancer le filtrage -->
            <button type="submit" class="btn-filter">Filtrer</button>
        </form>

        <!-- Bouton de retour vers  admin -->
        <div id="LogoutBlock">
            <a href="Admin.php"><button id="AdminLogoutButton">Retour</button></a>
        </div>

        <!-- Tableau d'affichage des questions filtrées -->
        <table id="questionsTable">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Propositions</th>
                    <th>Réponse Correcte</th>
                    <th>Type de Quiz</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Si aucune question ne correspond aux filtres -->
                <?php if (empty($filteredQuestions)): ?>
                    <tr>
                        <td colspan="5">Aucune question trouvée.</td>
                    </tr>
                <?php else: ?>

                    <!-- Affichage des questions avec leurs données -->
                    <?php foreach ($filteredQuestions as $rowIndex => $questionData): ?>
                        <tr id="row-<?php echo $questionData['Id_Question']; ?>"> <!-- Création d'un nouvele ligne dans le tableau-->
                            <td><?php echo htmlspecialchars($questionData['Question_Text']); ?></td> <!------Sécurisation avec la fonction ----->
                            <td><?php echo htmlspecialchars($questionData['Choix'] ?? 'Aucune'); ?></td>
                            <td><?php echo htmlspecialchars($questionData['Reponse_Correcte'] ?? 'Non définie'); ?></td>
                            <td><?php echo htmlspecialchars($questionData['Type_Quiz'] ?? 'Non défini'); ?></td>
                            <td>
                                <!-- Lien vers la page de modification de la question -->
                                <a href="modifier_question.php?id=<?php echo $questionData['Id_Question']; ?>" class="btn-modifier">Modifier</a>
                                
                                <!-- Formulaire de suppression de la question -->
                                <form method="POST" action="ModifQuiz.php" style="display:inline;" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">
                                    <input type="hidden" name="id_question" value="<?php echo $questionData['Id_Question']; ?>">
                                    <button type="submit" name="delete_question" class="btn-supprimer">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>