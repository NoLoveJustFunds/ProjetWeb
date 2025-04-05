# ProjetWeb

Le projet consiste à développer une application web interactive de quiz destinée à deux types d’utilisateurs : les administrateurs et les utilisateurs finaux. L’objectif principal est de permettre aux administrateurs de créer, modifier et supprimer des quiz, en y ajoutant divers types de questions comme des QCM, des questions de type vrai/faux ou encore des questions ouvertes avec des réponses attendues. 

Les utilisateurs peuvent s’inscrire via un formulaire sécurisé, se connecter à leur espace personnel, consulter leur profil et accéder à l’historique de leurs quiz passés. Une fois connectés, ils peuvent participer aux quiz proposés, avec un affichage dynamique des questions, une barre de progression, un chronomètre pour renforcer le défi, et un retour immédiat sur leurs réponses (bonnes ou mauvaises). À la fin de chaque quiz, un score est calculé en temps réel et intégré à un classement général.Le joueur peut alors connaitre sa position qui apparaitra en vert dans le classement en fonction de celle des joueurs.



-----------------------------------Instructions d'installation------------------------------------------------------

1. Préparer l'environnement serveur

Si vous n'avez pas encore de serveur local, installez un serveur Web comme XAMPP ou WAMP (pour Windows), MAMP (pour Mac), ou LAMP (pour Linux). Ces outils installeront Apache, PHP et MySQL nécessaires pour exécuter le quiz.


2. Télécharger et installer le projet

Téléchargez le code du projet (fichiers PHP, HTML, CSS, etc.).
Placez les fichiers dans le répertoire racine de votre serveur local, généralement htdocs dans XAMPP ou WAMP.


3. Configurer la base de données

Ouvrez phpMyAdmin via le panneau de contrôle de votre serveur local.
Créez une base de données (par exemple : quiz_db).
Importez le fichier SQL contenant les structures des tables pour le quiz (si disponible) ou créez manuellement les tables nécessaires (par exemple Quiz, Questions, Choix, ReponseCorrecte).


4. Configurer les fichiers PHP

Ouvrez le fichier de configuration PHP, généralement situé dans un fichier config.php, et configurez les paramètres de connexion à la base de données (hôte, utilisateur, mot de passe, etc.).


5. Lancer le serveur local

Démarrez Apache et MySQL via le panneau de contrôle de votre serveur local.
Accédez à votre projet en ouvrant un navigateur et en entrant l'adresse http://localhost/nom_du_projet.



--------------------------------------------Utilisation du quiz----------------------------------------------------------------------

---En tant que joueur

1. Se connecter à l'interface du quiz
    Accédez à la page de connexion du quiz.

2. Créer un compte (si nécessaire)
    Si vous n'avez pas encore de compte, inscrivez-vous en fournissant vos informations.
    Après l'inscription, vous serez redirigé automatiquement vers la page de connexion.

3. Se connecter avec vos identifiants
    Utilisez votre adresse email et votre mot de passe pour vous connecter.

4. Prêt à jouer
    Une fois connecté, vous pourrez accéder aux différents quiz et commencer à jouer, ainsi qu'explorer l'interface.



---En tant qu'administrateur

1. Se connecter à l'interface administrateur
    Accédez à la page de connexion de l'interface administrateur.

2. Récupérer les identifiants administrateur
    Ouvrez le fichier index.php pour obtenir les identifiants de l'administrateur(se trouve juste après la session_start()).
    Email : Admin123@gmail.com
    Mot de passe : Admin123

3. Accéder à l'interface de création
    Une fois connecté, vous serez redirigé vers l'interface de création de quiz.

4. Gestion des questions
    Vous trouverez des options pour modifier et supprimer des questions directement depuis cette interface.
    Faites preuve de réflexion pour gérer les questions efficacement !



-----------------------------------------------Prérequis-----------------------------------------------------------------------

-Serveur local (ex: XAMPP, WAMP, MAMP, LAMP)

-Un serveur local pour exécuter PHP et MySQL sur votre machine.
Navigateur web

-Un navigateur compatible pour tester l'interface (Chrome, Firefox, etc.).
PHP & MySQL PHP version 7.0 ou plus. 
MySQL version 5.x ou plus.

-Éditeur de texte
Un éditeur de texte pour développer le code, comme Visual Studio Code, Sublime Text, ou Notepad++.

-Connaissances de base en PHP, HTML, CSS et MySQL
Pour personnaliser et gérer le quiz, vous aurez besoin de connaissances de base en développement Web (PHP pour la logique du serveur, HTML/CSS pour la présentation, MySQL pour la gestion des données).



-----------------------------------------------Outils utilisés-----------------------------------------------------------------------

-PHP
Le langage utilisé pour le traitement côté serveur, la gestion des quiz et la connexion à la base de données.


-MySQL
Base de données relationnelle pour stocker les informations des utilisateurs, les quiz, les questions et les réponses.


-HTML & CSS
HTML est utilisé pour créer la structure des pages du quiz, et CSS pour la mise en forme et le style visuel de l'interface.


-JavaScript 
Utilisé pour améliorer l'interactivité et la validation côté client (comme vérifier si les réponses sont sélectionnées avant d'envoyer).


-phpMyAdmin 
Interface graphique pour gérer facilement les bases de données MySQL.