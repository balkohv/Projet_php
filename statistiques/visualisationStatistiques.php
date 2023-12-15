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
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Statistiques</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        
        <header>
            <h1>Projet 1</h1>
        </header>
        <?php include '../nav.html'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;">
                <h2>Statistiques</h2>
                
            </div>
        </div>
    </body>
</html>