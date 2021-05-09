<?php 
session_start();
$user= '';
$pwd = '';

if (isset($_POST["username"]) && isset($_POST["password"])){
    $user = $_POST["username"];
    $pwd = $_POST["password"];
    
    $conn = new mysqli("localhost", "root", "","agriturismo");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $qre = 'SELECT * FROM utenti WHERE `Username` = "' . $user . '" AND `password` = "' . $pwd . '"';
    
    $result = mysqli_query($conn,$qre);
    if($result){        
        $_SESSION["user"] = $user;
        header( "Location: http://localhost/index.php" );
    }else{
        echo "<br/>utente non esiste";
    }
}
?>