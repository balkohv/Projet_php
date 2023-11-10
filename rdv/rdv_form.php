<html>
    <head>
        <meta charset="UTF-8">
        <title>Prise de rendez vous</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <h1>Projet 1</h1>
        </header>
        <nav>
            <ul class="nav-bar">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php?page=contact">rdv</a></li>
                <li><a href="index.php?page=inscription">Inscription</a></li>
                <li><a href="index.php?page=connexion">Connexion</a></li>
            </ul>
        </nav>
        <div class="col-12">
            <h2>Prise de rendez vous</h2>
            <form action="index.php?page=rdv" method="POST" class="form col-12">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" required>
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                <label for="date">Date</label>
                <input type="date" name="date" id="date" required>
                <label for="heure">Heure</label>
                <input type="time" name="heure" id="heure" required>
                <input type="submit" value="Envoyer">
            </form>
        </div>
    </body>
</html>