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
    if(isset($_POST['id'])){
        $id = $_POST['id'];
    }elseif(isset($_GET['id'])){
        $id = $_GET['id'];
    }
    $req1 = $conn->prepare('SELECT * FROM medecin WHERE id_medecin = :id AND archive = 0');
    $req1->execute(array(
        'id' => $id,
    ));
    $medecin = $req1->fetch();
    if(isset($_POST['civilite'])){
        $id = $_POST['id'];
        $civilite = $_POST['civilite'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $req1 = $conn->prepare('UPDATE medecin set civilite = :civilite, nom = :nom, prenom = :prenom where id_medecin = :id');

            if (!$req1){
                die('Erreur : ' . $req1.errorInfo());
            }

            $req1->execute(array(
                'civilite' => $civilite,
                'nom' => $nom,
                'prenom' => $prenom,
                'id' => $id,
            ));
        if($req1){
            header("location: ../index.php");
        }else{
            echo 'query error: '. mysqli_error($conn);
        }
    }
    if(!isset($medecin['id_medecin'])){
        header("location: ../index.php");
    }

}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>modifier un medecin</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <h1>Projet 1</h1>
        </header>
        <?php include '../nav.html'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;">
                <h2>modifier un medecin</h2>
                <form action="modificationMedecin.php" method="POST" class="form col-12">
                    <div class="col-4">
                        <div class="col-2">
                            <label for="civilite">Civilité</label>
                            <select name="civilite" id="civilite" required>
                                <option value="M" >M</option>
                                <option value="Mme" <?php if($medecin['civilite']=='Mme' ){echo 'selected';}?>>Mme</option>
                            </select>
                            <label for="nom">Nom</label>
                            <input  type="text" name="nom" id="nom"  value='<?= $medecin['nom'] ?>' required>
                        </div>
                        <div class="col-2">
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom" value='<?= $medecin['prenom'] ?>' required>
                        </div>
                        <input name="id" type="hidden" value="<?php echo $medecin['id_medecin'] ?>">
                    </div>
                    <input type="submit" value="Envoyer" class="col-2 submit">
                    <td><a class="delete col-2" href="suppressionMedecin.php?id=<?= $medecin['id_medecin']?>">Supprimer</a></td>
                </form>
            </div>
        </div>
    </body>
</html>