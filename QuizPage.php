<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleQuiz.css">
    <title>Document</title>
</head>
<body>
    <header> 
        <div><h1>QuizzyLand</h1></div>


        <nav>
            <div class="DropElement">
                <button class="Bouton"><h1>Menu</h1></button>
                <div class="ElementDeroulant">
                    <ul>
                        <li><a href="Profil.php">Profil</a></li>
                        <li><a href="Score.php">Score</a></li>
                        <li><a href="Classement.php">Classement</a></li>
                        <li><a href="index.php">Déconnexion</a></li>
                    </ul>
                </div>
            </div>
    
        </nav>
    
        
    </header>


    <div class = "Intitule">
        <h2>Mode de jeu</h2>
    </div>

    
    <div id="Container">

        <div class="Choix1">
            <a href="QuizFacile.php"><button type="submit" class="btnFMD">QCM</a</button>
        </div>
        <div class="Choix2">
            <a href="QuiMoyen.php"><button type="submit" class="btnFMD">Vrai/Faux</a</button>
        </div>
        <div class="Choix3">
            <a href="QuizDifficile.php"><button type="submit" class="btnFMD">Réponse Libre</a</button>
        </div>

    </div>

    <script src="script.js"></script>
    
</body>
</html>