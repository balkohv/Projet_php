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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $req1 = $conn->prepare('INSERT INTO user(username, password) VALUES(:username,:password)');
        if (!$req1){
            die('Erreur : ' . $req1.errorInfo());
        }

        $req1->execute(array(
            'username' => $username,
            'password' => hash('sha256',$password),
        ));
    if($req1){
        header("location: index.php");
        setcookie("user",$user, time() + 3600, "/");
    }else{
        echo 'query error: '. mysqli_error($conn);
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>création d'un compte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="margin-top:20vh;">
        <h2>création d'un compte</h2>
        <form method="POST" class="login" action="register.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>    
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Valider">
        </form>
        <a class="register-link" href="login.php">Connexion</a>
    </div>
</body>
</html>
