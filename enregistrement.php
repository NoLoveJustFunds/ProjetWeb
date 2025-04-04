<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleLog.css">
    <title>Document</title>
</head>
<body>
<h1 class="Titre1">QuizzyLand</h1>
<h2 class="Connexion">Inscription</h2>

<div class="logo">
            <div class="profile-icon">
                <img src="profilTest.png" id="redimension">
            </div>
        </div>

<div id="Container">
    <form class="form-group" method="POST" action="enregistrement.php">

            <div class="Espace">

                <input type="text" id="Name" name="Name" placeholder="Nom" required>

            </div>

            <div class="Espace">
                <input type="text" id="Surname" name="Surname" placeholder="Prénom" required>
            </div>

            <div class="Espace">
                <input type="text" id="PhoneNumber" name="PhoneNumber" placeholder="Numéro de téléphone" required>
            </div>

            <div class="Espace">
                <input type="email" id="Mail" name="Mail" placeholder="E-mail" required>
            </div>

            <div class="Espace">
                <input type="password" id="MDP" name="MDP" placeholder="Mot de passe" required>
            </div>

        <span class="ContainerB">
            
            <button type="submit" class="btn">Sign In</button>
            
        </span>
    </form>
</div>
</body>

<?php

try {
    // Connexion à la base de données
    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=test;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {   // Vérification si le formulaire a été soumis

        // Récupération des données du formulaire
        $name = $_POST['Name'] ?? '';
        $surname = $_POST['Surname'] ?? '';
        $phoneNumber = $_POST['PhoneNumber'] ?? '';
        $email = $_POST['Mail'] ?? '';
        $password = $_POST['MDP'] ?? '';

        // Vérification des champs vides lors de l'inscription
        if (empty($name) || empty($surname) || empty($phoneNumber) || empty($email) || empty($password)) {
            throw new Exception("Tous les champs doivent être remplis.");
        }

        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparation de la requête SQL
        $sqlQuery = 'INSERT INTO Utilisateurs (Nom_User, Prenom_User, NumTel_User, Mail_User, Mdp_User) 
                     VALUES (:name, :surname, :phoneNumber, :email, :password)';

        $sqlQueryScore = 'INSERT INTO Score (Mail_User) 
                     VALUES (:email)';

        $insertRecipe = $mysqlClient->prepare($sqlQuery);
        $insertRecipeScore = $mysqlClient->prepare($sqlQueryScore);

        // Exécution de la requête avec les bons paramètres
        $insertRecipe->execute([
            ':name' => $name,
            ':surname' => $surname,
            ':phoneNumber' => $phoneNumber,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        $insertRecipeScore->execute([
            ':email' => $email
        ]);

        echo "Inscription réussie !";

        header("Refresh: 3; url=index.php");
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>



    


</html>