<?php
if(!isset($_COOKIE['user'])){
    header("location: ../login.php");
    var_dump($_COOKIE['user']);
}
?>

<header>
    <h1>MediCare</h1>
</header>
<nav>
    <ul class="nav-bar">
        <li>
            <ul class="usager">
                <li class="nav justify-content-center"><a>Usagers</a></li>
                <li class="nav justify-content-center"><a href="../usager/ajoutUsagers.php">ajout Usagers</a></li>
                <li class="nav justify-content-center"><a href="../usager/visualisationUsagers.php">Visualisation Usagers</a></li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="nav justify-content-center"><a>Medecins</a></li>
                <li class="nav justify-content-center"><a href="../medecin/ajoutMedecin.php">ajout Medecin</a></li>
                <li class="nav justify-content-center"><a href="../medecin/visualisationMedecin.php">Visualisation Medecin</a></li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="nav justify-content-center"><a>Consultations</a></li>
                <li class="nav justify-content-center"><a href="../consultation/ajoutConsultations.php">ajout Consultations</a></li>
                <li class="nav justify-content-center"><a href="../consultation/visualisationConsultations.php">Visualisation Consultations</a></li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="nav justify-content-center"><a>Statistiques</a></li>
                <li class="nav justify-content-center"><a href="../statistiques/visualisationStatistiques.php">Visualisation Statistiques</a></li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="nav justify-content-center"><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </li>
    </ul>
</nav>