<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<div class="container">
        <div class="logo">
            <div class="profile-icon">
                <img src="Images/profilTest.png" id="redimension">
            </div>
            <h1>QuizzyLand</h1>
        </div>
        
        <h2>Login</h2>
        
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" placeholder="Votre adresse e-mail" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                <span class="password-toggle" onclick="afficherPassword()">Display</span>
            </div>
            
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="divider">
            <span>OR</span>
        </div>
        
        <p class="register-link">
            Vous n'avez pas de compte? <a href="enregistrement.php">Inscrivez-vous</a>
        </p>
</div>

    <script src="script.js"></script>

</body>
<?php

session_start();

$EmailAdmin ="Admin123@gmail.com"; //Email de login pour admin
$MDPAdmin ="Admin123"; //MDP de login pour admin

try {
    // Connexion à la base de données
    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=test;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    if ($_SERVER["REQUEST_METHOD"] == "POST") {  // Vérifie si le formulaire a été soumis

        // Récupération des données du formulaire
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Préparation de la requête SQL pour récupérer les informations du joueur
        $DataUser = 'SELECT Nom_User, Prenom_User, NumTel_User, Mdp_User FROM Utilisateurs WHERE Mail_User = :email';

        $Prep = $mysqlClient->prepare($DataUser);
        $Prep->bindValue(":email", $email, PDO::PARAM_STR); //Cette méthode est utilisée pour lier une valeur à un paramètre dans la requête préparée.
        $Prep->execute();
        $UtilisateursTab = $Prep->fetch(PDO::FETCH_ASSOC);  // Récupère les informations sous forme de tableau "clé :valeur"

        if ($UtilisateursTab) {  
            $HashMDP = $UtilisateursTab['Mdp_User']; // Mot de passe hashé depuis la BDD

            // Vérifie si le mot de passe saisi correspond au hash dans la BDD ( avec password_verifyc)
            if (password_verify($password, $HashMDP)) {

                // Stocke les informations de l'utilisateur dans la session
                $_SESSION['Mail_User'] = $email;
                $_SESSION['Nom_User'] = $UtilisateursTab['Nom_User'];
                $_SESSION['Prenom_User'] = $UtilisateursTab['Prenom_User'];
                $_SESSION['NumTel_User'] = $UtilisateursTab['NumTel_User'];

            
                header("Location: QuizPage.php"); //header permet la redirection vers une page
                exit();
            } 
        } else if($email==$EmailAdmin && $password ==$MDPAdmin){
            header("Location: Admin.php"); //Si Email et MDP correct -> partie admin
        }
        
}}catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

</html>