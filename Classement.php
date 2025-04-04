<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleClassement.css">
    <title>Document</title>
</head>

<?php
session_start();
$email = $_SESSION['Mail_User'];

try {
    // Connexion à la base de données
    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=test;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Récupérer le nombre total de joueurs
    $NbJoueur = 'SELECT COUNT(*) AS NbPlayer FROM Utilisateurs';

    $PrepNbJoueur = $mysqlClient->prepare($NbJoueur);
    $PrepNbJoueur->execute();
    $UtilisateursTab1 = $PrepNbJoueur->fetch(PDO::FETCH_ASSOC);

    // Récupérer la position du joueur
    $PosJoueur = 'SELECT COUNT(*)+1 AS PositionJoueur FROM Score
    WHERE Score_User > (
        SELECT Score_User
        FROM Score
        WHERE Mail_User = :email
    )';

    $PrepPosJoueur = $mysqlClient->prepare($PosJoueur);
    $PrepPosJoueur->bindValue(":email", $email, PDO::PARAM_STR);
    $PrepPosJoueur->execute();
    $UtilisateursTab2 = $PrepPosJoueur->fetch(PDO::FETCH_ASSOC);


    $AffichageClassement = 'SELECT Utilisateurs.Nom_User ,Utilisateurs.Prenom_User ,Score.Score_User  FROM Utilisateurs
    JOIN Score ON Score.Mail_User = Utilisateurs.Mail_User
    ORDER BY Score.Score_User DESC';

    $PrepAffichage = $mysqlClient->prepare($AffichageClassement);
    $PrepAffichage->execute();
  


    // Vérification si les données ont été récupérées
    if (!$UtilisateursTab1 || !$UtilisateursTab2){
        // Si aucune donnée n'est trouvée
        echo "❌ Aucune donnée trouvée pour cet utilisateur.";
    }
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>


<body>


    <h1 class="Titre1">Classement</h1>

    <div id="Container">
        <form>
            <div id="Intitule">Classement </div><br><br>

            <fieldset>
    <div id="GraphiqueClassement"> 

        <!-- Ouvrir le tableau avant la boucle -->
        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Score</th>
            </tr>

            <?php
                // Afficher les lignes du classement
                while($ligne = $PrepAffichage->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    echo "<td>" . $ligne['Nom_User'] . "</td>";
                    echo "<td>" . $ligne['Prenom_User'] . "</td>";
                    echo "<td>" . $ligne['Score_User'] . "</td>";
                    echo "</tr>";
                }
            ?>

        </table>

    </div>
</fieldset>

            <fieldset>
                <span class="Element"> Nombre de joueur : <?php echo $UtilisateursTab1['NbPlayer'] ?></span><br>
                
            </fieldset>

            <fieldset>
                <span class="Element">Votre position : <?php echo $UtilisateursTab2['PositionJoueur'] ?></span><br>
            </fieldset>

        </form>

    </div>

    <div id="Retour">
        <a href="QuizPage.php"><button id="returnButton">Retour</a></button>
    </div>
    
</body>
</html>