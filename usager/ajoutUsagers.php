<?php
 error_reporting(E_ALL); 
 ini_set("display_errors", 1);
 try{
     $conn = new PDO('mysql:host=localhost;port=3306;dbname=projet_php','root','root');
 }
 catch (Exception $e)
 {
    die('Erreur : ' . $e->getMessage());
 }
if(isset($_POST['nom'])){
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $civilite = $_POST['civilite'];
    $adresse = $_POST['adresse'];
    $CP = $_POST['CP'];
    $ville = $_POST['ville'];
    $dateN = $_POST['dateN'];
    $lieuN = $_POST['lieuN'];
    $secu = $_POST['secu'];
    if(isset($_POST['id_medecin']) && $_POST['id_medecin'] != 0){
        $medecin=$_POST['id_medecin'];
    }else{
        $medecin=NULL;
    }
    $req1 = $conn->prepare('INSERT INTO usager(num_secu, nom, prenom,civilite, cp, ville, adresse, date_naissance, lieu_naissance, id_medecin) VALUES(:num_secu, :nom, :prenom, :civilite, :cp, :ville, :adresse, :date_naissance, :lieu_naissance, :id_medecin)');

        if (!$req1){
            die('Erreur : ' . $req1.errorInfo());
        }

        $req1->execute(array(
            'num_secu' => $secu,
            'nom' => $nom,
            'prenom' => $prenom,
            'civilite' => $civilite,
            'cp' => $CP,
            'ville' => $ville,
            'adresse' => $adresse,
            'date_naissance' => $dateN,
            'lieu_naissance' => $lieuN,
            'id_medecin' => $medecin,
        ));
    if($req1){
        header("location: ../index.php");
    }else{
        echo "coucou";
        echo 'query error: '. mysqli_error($conn);
    }
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Ajouter un usager</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <h1>Projet 1</h1>
        </header>
        
        <?php include '../nav.html'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;">
                <h2>Ajouter un usager</h2>
                <form action="AjoutUsagers.php" method="POST" class="form col-12">
                    <div class="col-4">
                        <div class="col-2">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" required>
                            <label for="prenom">Prénom</label>
                            <input type="text" name="nom" id="nom" required>
                            <label for="prenom">Civilité</label>
                            <select name="civilite" id="civilite">
                                <option value="M">M</option>
                                <option value="Mme">Mme</option>
                            </select>
                            <label for="adresse">adresse</label>
                            <input type="text" name="adresse" id="adresse" required>
                            <label for="CP">Code postal</label>
                            <input type="text" name="CP" id="CP" required>
                        </div>
                        <div class="col-2">
                            <label for="ville">Ville</label>
                            <input type="text" name="ville" id="ville" required>
                            <label for="dateN">Date de naissance</label>
                            <input type="date" name="dateN" id="dateN" required>
                            <label for="lieuN">lieu de naissance</label>
                            <input type="text" name="lieuN" id="lieuN" required>
                            <label for="secu">Numéro de sécurité social</label>
                            <input type="text" name="secu" id="secu" required>
                            <label for="id_medecin">Medecin</label>
                            <select name="id_medecin" id="id_medecin">
                                <?php
                                    $medecins = $conn->query('SELECT id_medecin, nom, prenom  FROM medecin WHERE archive = 0 ORDER BY nom');
                                    while ($medecin = $medecins->fetch()){
                                        echo '<option value="' . $medecin['id_medecin'] . '">' . $medecin['nom'] . ' ' . $medecin['prenom'] . '</option>';
                                    }
                                ?>
                                <option value="0">Aucun</option>
                            </select>
                        </div>
                    </div>
                    <input type="submit" value="Envoyer" class="col-2 submit">
                </form>
            </div>
        </div>
    </body>
</html>