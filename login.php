<!DOCTYPE html>
<html>
<head>
    <title>Page de connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
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
 $error="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $req1 = $conn->prepare('SELECT * FROM user WHERE username = :username AND password = :password');
    $req1->execute(array(
        'username' => $username,
        'password' => hash('sha256',$password),
    ));
    $user = $req1->fetch();
    if (isset($user['username'])) {
        setcookie("user",$user['id_user'], time() + 3600, "/");
        header("Location: index.php");
        exit;
    } else {
        $error= "Invalid username or password";
    }
}
?>
<body>
    <div class="container" style="margin-top:20vh;">
        <h2>Login</h2>
        <form method="POST" class="login" action="login.php">
            <?echo '<p style ="color:red;">'.$error.'</p>';?>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
        <a class="register-link" href="register.php">Créer un compte</a>
    </div>
</body>
</html>