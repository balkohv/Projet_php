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
    if(!isset($_GET['id'])){
        header("location: ../index.php");
    }else{
        $req = $conn->prepare('DELETE FROM rdv WHERE id_medecin = :id and date_rdv = :date_rdv');
        $req->execute(array(
            'id' => $_GET['id'],
            'date_rdv' => $_GET['date_rdv'],
        ));
        if(!$req){
            die('Erreur : ' . $req.errorInfo());
        }
        header("location: ../index.php");
    }
?>