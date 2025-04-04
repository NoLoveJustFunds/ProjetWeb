<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleProfil.css">
    <title>Document</title>
</head>

<?php

session_start(); //Démarage de la session pour récuperer les info du joueur connecté

$email = $_SESSION['Mail_User'] ;
$password = $_SESSION['Mdp_User'] ;
$Nom = $_SESSION['Nom_User'] ;
$Prenom = $_SESSION['Prenom_User'] ;
$PhonenNumber = $_SESSION['NumTel_User'] ;
?>

<body>

    <h1 class="Titre1">Profil</h1>

    <div id="Container">
        <form>
            <div id="Intitule">Informations du profil</div><br><br>
            <div class="Espace">
                <span class="Element"> Name :  <?php echo $Nom ?> </span><br>
            
            </div>
            <div class="Espace">
                <span class="Element">Surname : <?php echo $Prenom ?> </span><br>
            </div>

            <div class="Espace">
                <span class="Element">Phone Number : <?php echo $PhonenNumber ?>   </span><br>
            </div>

            <div class="Espace">
                <span class="Element">Mail : <?php echo $email ?>  </span><br>
            </div>

        
        </form>

    </div>

    <div id="Retour">
        <a href="QuizPage.php"><button id="returnButton">Retour</a></button>
    </div>

    
</body>

</html>