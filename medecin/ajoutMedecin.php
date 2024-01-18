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
if(isset($_POST['civilite'])){
    $civilite = $_POST['civilite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $req1 = $conn->prepare('INSERT INTO medecin(civilite, nom, prenom) VALUES(:civilite, :nom, :prenom)');

        if (!$req1){
            die('Erreur : ' . $req1.errorInfo());
        }

        $req1->execute(array(
            'civilite' => $civilite,
            'nom' => $nom,
            'prenom' => $prenom,
        ));
    if($req1){
        header("location: ../index.php");
    }else{
        echo 'query error: '. mysqli_error($conn);
    }
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Ajouter un medecin</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <?php include '../nav.php'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;">
                <h2>Ajouter un medecin</h2>
                <form action="AjoutMedecin.php" method="POST" class="form col-12">
                    <div class="col-4">
                        <div class="col-2">
                            <label for="civilite">Civilité</label>
                            <select name="civilite" id="civilite" required>
                                <option value="M">M</option>
                                <option value="Mme">Mme</option>
                            </select>
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" required>
                        </div>
                        <div class="col-2">
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom" required>
                        </div>
                    </div>
                    <input type="submit" value="Envoyer" class="col-2 submit">
                </form>
            </div>
        </div>
    </body>
</html>