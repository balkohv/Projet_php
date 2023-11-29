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

$req1 = $conn->prepare('SELECT * FROM usager WHERE archive = 0');

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>visualiser les usagers</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <h1>Projet 1</h1>
        </header>
        <?php include '../nav.html'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;flex-direction:column;">
                <h2>Visualiation usagers</h2>
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>adrese</th>
                            <th>code postal</th>
                            <th>ville</th>
                            <th>date de naissance</th>
                            <th>lieu de naissance</th>
                            <th>numéro de sécurité sociale</th>
                            <th>medecin traitant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $req1->execute();
                            while($usager = $req1->fetch()){
                                if($usager['id_medecin'] != NULL){
                                    $req1 = $conn->prepare('SELECT * FROM medecin WHERE id_medecin = :id AND archive = 0');
                                    $req1->execute(array(
                                        'id' => $usager['id_medecin'],
                                    ));
                                    $medecin = $req1->fetch();
                                }else{
                                    $medecin['nom'] = "aucun";
                                    $medecin['prenom'] = "";
                                }
                                echo '<tr id="'.$usager['id_usager'].'" class="row">';
                                echo '<td>'.$usager['nom'].'</td>';
                                echo '<td>'.$usager['prenom'].'</td>';
                                echo '<td>'.$usager['adresse'].'</td>';
                                echo '<td>'.$usager['cp'].'</td>';
                                echo '<td>'.$usager['ville'].'</td>';
                                echo '<td>'.$usager['date_naissance'].'</td>';
                                echo '<td>'.$usager['lieu_naissance'].'</td>';
                                echo '<td>'.$usager['num_secu'].'</td>';
                                echo '<td>'.$medecin['nom'].', '.$medecin['prenom'].'</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
            </div>
        </div>
    </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.row').on('click',function(){
            modifier($(this).attr('id'));

        });

        function modifier(id){
            window.location.href = "modificationUsager.php?id="+id;
        }
    });
</script>