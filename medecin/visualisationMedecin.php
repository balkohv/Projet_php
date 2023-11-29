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

$req1 = $conn->prepare('SELECT * FROM medecin WHERE archive = 0 ');

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>visualiser les medecins</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <h1>Projet 1</h1>
        </header>
        <?php include '../nav.html'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;flex-direction:column;">
                <h2>Visualiation medecins</h2>
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th>Civilité</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $req1->execute();
                            while($medecin = $req1->fetch()){
                                echo '<tr id="'.$medecin['id_medecin'].'" class="row">';
                                echo '<td>'.$medecin['civilite'].'</td>';
                                echo '<td>'.$medecin['nom'].'</td>';
                                echo '<td>'.$medecin['prenom'].'</td>';
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
            window.location.href = "modificationMedecin.php?id="+id;
        }
    });
</script>