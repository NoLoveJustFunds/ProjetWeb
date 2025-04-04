<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleProfil.css">
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


    // Préparation de la requête SQL pour récupérer les informations du joueur
    $DataUser = 'SELECT Nom_User , Prenom_User , Score_User, Total_Play FROM Score 
                 JOIN Utilisateurs ON Score.Mail_User = Utilisateurs.Mail_User
                 WHERE Utilisateurs.Mail_User = :email';

    $Prep = $mysqlClient->prepare($DataUser);
    $Prep->bindValue(":email", $email, PDO::PARAM_STR);
    $Prep->execute();
    $UtilisateursTab = $Prep->fetch(PDO::FETCH_ASSOC);

    if (!$UtilisateursTab) {
        // Si aucune donnée n'est trouvée
        echo "❌ Aucune donnée trouvée pour cet utilisateur.";
    }
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>


<body>

    <h1 class="Titre1">Score</h1>

    <div id="Container">
        <form>
            <div id="Intitule">Statistique de votre profil</div><br><br>

            <div class="Espace"> 
                <span class="Element"> Nom  : <?php  echo $UtilisateursTab['Nom_User']?></span><br>
            </div>

            <div class="Espace"> 
                <span class="Element"> Prenom  : <?php  echo $UtilisateursTab['Prenom_User']?></span><br>
            </div>

            
            <div class="Espace"> 
                <span class="Element"> Nombre de points  : <?php  echo $UtilisateursTab['Score_User']?></span><br>
            </div>


            <div class="Espace"> 
                <span class="Element">Questions totales : <?php  echo $UtilisateursTab['Total_Play']?></span><br>
            </div>

        </form>

    </div>

    <div id="Retour">
        <a href="QuizPage.php"><button id="returnButton">Retour</a></button>
    </div>
    
</body>

</html>