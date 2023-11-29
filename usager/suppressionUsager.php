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
        $req = $conn->prepare('DELETE FROM rdv WHERE id_usager = :id');
        $req->execute(array(
            'id' => $_GET['id'],
        ));
        if(!$req){
            die('Erreur : ' . $req.errorInfo());
        }
        $req = $conn->prepare('UPDATE usager SET  archive = 1 WHERE id_usager = :id');
        $req->execute(array(
            'id' => $_GET['id'],
        ));
        if(!$req){
            die('Erreur : ' . $req.errorInfo());
        }
        header("location: ../index.php");
    }
?>