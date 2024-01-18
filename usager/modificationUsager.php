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
if(!isset($_GET['id']) and !isset($_POST['id'])){
    header("location: ../index.php");
}else{
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }elseif(isset($_POST['id'])){
        $id = $_POST['id'];
    }
    $req1 = $conn->prepare('SELECT * FROM usager WHERE id_usager = :id AND archive = 0');
    $req1->execute(array(
        'id' => $id,
    ));
    $usager = $req1->fetch();
    if(!isset($usager['id_usager'])){
        header("location: ../index.php");
    }
    if(isset($_POST['secu'])){
        $id = $_POST['id'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $civilite = $_POST['civilite'];
        $cp = $_POST['cp'];
        $ville = $_POST['ville'];
        $adresse = $_POST['adresse'];
        $date_naissance = $_POST['dateN'];
        $lieu_naissance = $_POST['lieuN'];
        $num_secu = $_POST['secu'];
        if(isset($_POST['id_medecin']) && $_POST['id_medecin'] != 0){
            $id_medecin=$_POST['id_medecin'];
        }else{
            $id_medecin=NULL;
        }
        $req1 = $conn->prepare('UPDATE usager set num_secu = :num_secu, nom = :nom, prenom = :prenom,civilite = :civilite, cp = :cp, ville = :ville, adresse = :adresse, date_naissance = :date_naissance, lieu_naissance = :lieu_naissance, id_medecin = :id_medecin where id_usager = :id');

            if (!$req1){
                die('Erreur : ' . $req1.errorInfo());
            }

            $req1->execute(array(
                'num_secu' => $num_secu,
                'nom' => $nom,
                'prenom' => $prenom,
                'civilite' => $civilite,
                'cp' => $cp,
                'ville' => $ville,
                'adresse' => $adresse,
                'date_naissance' => $date_naissance,
                'lieu_naissance' => $lieu_naissance,
                'id_medecin' => $id_medecin,
                'id' => $id,
            ));
        if($req1){
           header("location: ../index.php");
        }else{
            echo 'query error: '. mysqli_error($conn);
        }
    }

}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>modifier un usager</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <?php include '../nav.php'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;">
                <h2>modifier un usager</h2>
                <form action="modificationUsager.php" method="POST" class="form col-12">
                    <div class="col-4">
                        <div class="col-2">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" value="<?= $usager['nom'] ?>" required>
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom" value="<?= $usager['prenom'] ?>" required>
                            <label for="civilite">Civilité</label>
                            <select name="civilite" id="civilite">
                                <option value="M" <?php if($usager['civilite'] == "M"){echo 'selected';}?>>M</option>
                                <option value="Mme" <?php if($usager['civilite'] == "Mme"){echo 'selected';}?>>Mme</option>
                            </select>
                            <label for="adresse">adresse</label>
                            <input type="text" name="adresse" id="adresse" value="<?= $usager['adresse'] ?>" required>
                            <label for="cp">Code postal</label>
                            <input type="text" name="cp" id="cp" value="<?= $usager['cp'] ?>" required>
                        </div>
                        <div class="col-2">
                            <label for="ville">Ville</label>
                            <input type="text" name="ville" id="ville" value="<?= $usager['ville'] ?>" required>
                            <label for="dateN">Date de naissance</label>
                            <input type="date" name="dateN" id="dateN" value="<?= $usager['date_naissance'] ?>" required>
                            <label for="lieuN">lieu de naissance</label>
                            <input type="text" name="lieuN" id="lieuN" value="<?= $usager['lieu_naissance'] ?>" required>
                            <label for="secu">Numéro de sécurité social</label>
                            <input type="text" name="secu" id="secu" value="<?= $usager['num_secu'] ?>" required>
                            <label for="id_medecin">Medecin</label>
                            <select name="id_medecin" id="id_medecin">
                                <?php
                                    $medecins = $conn->query('SELECT id_medecin, nom, prenom FROM medecin WHERE archive = 0 ORDER BY nom');
                                    while ($medecin = $medecins->fetch()){
                                        $selected="";
                                        if ($medecin['id_medecin'] == $usager['id_medecin']){
                                            $selected="selected";
                                        }
                                        echo '<option value="' . $medecin['id_medecin'] . '"'.$selected.'>' . $medecin['nom'] . ' ' . $medecin['prenom'] . '</option> ';
                                    }
                                ?>
                                <option value="0" <?php if($usager['id_medecin'] == null){echo 'selected';}?>>Aucun</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?= $id?>">
                    <input type="submit" value="Envoyer" class="col-2 submit">
                    <td><a class="delete col-2" href="suppressionUsager.php?id=<?= $usager['id_usager']?>">Supprimer</a></td>
                </form>
            </div>
        </div>
    </body>
</html>