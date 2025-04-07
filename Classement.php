<!DOCTYPE html>
<html lang="fr">
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

    // Récupère le nombre total de joueurs
    $NbJoueur = 'SELECT COUNT(*) AS NbPlayer FROM Utilisateurs';

    $PrepNbJoueur = $mysqlClient->prepare($NbJoueur);
    $PrepNbJoueur->execute();
    $UtilisateursTab1 = $PrepNbJoueur->fetch(PDO::FETCH_ASSOC);

    // Récupère la position du joueur
    $PosJoueur = 'SELECT COUNT(*)+1 AS PositionJoueur FROM Score
    WHERE Score_User > (
        SELECT Score_User
        FROM Score
        WHERE Mail_User = :email
    )';

    $PrepPosJoueur = $mysqlClient->prepare($PosJoueur);
    $PrepPosJoueur->bindValue(":email", $email, PDO::PARAM_STR);//Associe l'email de l'user à la varaible email
    $PrepPosJoueur->execute();
    $UtilisateursTab2 = $PrepPosJoueur->fetch(PDO::FETCH_ASSOC); 


    $AffichageClassement = 'SELECT Utilisateurs.Nom_User, Utilisateurs.Prenom_User, Utilisateurs.Mail_User, Score.Score_User  
                            FROM Utilisateurs
                            JOIN Score ON Score.Mail_User = Utilisateurs.Mail_User
                            ORDER BY Score.Score_User DESC';


    $PrepAffichage = $mysqlClient->prepare($AffichageClassement);
    $PrepAffichage->execute();
  

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage(); //En java , j'aurais utiliser getStackTrace()
}
?>
<body>

    <h1 class="Titre1">Classement</h1>

<div id="Container">
    <form>
            <div id="Intitule">Classement </div><br><br>

        <fieldset>
            <div id="GraphiqueClassement"> 

                <table>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Score</th>
                    </tr>

                    <?php
                        // Affiche les lignes du classement ( j'ai pris pas mal de temps à penser qu'il faut utiliser un boucle)
                        while($ligne = $PrepAffichage->fetch(PDO::FETCH_ASSOC)){
                            $isCurrentUser = ($ligne['Mail_User'] === $email); // Compare les emails
                        
                            // Si c'est le joueur connecté on le met en couleur
                            $rowClass = $isCurrentUser ? 'colorierUser' : ''; //ternaire -> si joueur connecté, on colorie la ligne
                            // Sinon pas de couleur sur la ligne
                        
                            echo "<tr class=\"$rowClass\">";
                            echo "<td>" . htmlspecialchars($ligne['Nom_User']) . "</td>"; //J'ai regarder sur plusieurs site ,
                            //que htmlspecialchars est recommandé pour récupérer les données et les injecter sous forme de tableau dans la page
                            echo "<td>" . htmlspecialchars($ligne['Prenom_User']) . "</td>";
                            echo "<td>" . htmlspecialchars($ligne['Score_User']) . "</td>";
                            echo "</tr>";
                        }
                        
                    ?>

                </table>

            </div>
        </fieldset>
            <fieldset>
                <span class="Element"> Nombre de joueur : <?php echo $UtilisateursTab1['NbPlayer'] ?></span><br><br><br>
                
                <span class="Element">Votre position : <?php echo $UtilisateursTab2['PositionJoueur'] ?></span><br>
            </fieldset>
     </form>
</div>

    <div id="Retour">
        <a href="QuizPage.php"><button id="returnButton">Retour</a></button>
    </div>
    
</body>
</html>