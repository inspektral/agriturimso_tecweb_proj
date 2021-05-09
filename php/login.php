<?php 
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";
use DBAccess;

session_start();

if (isset($_POST["email"]) && isset($_POST["password"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $dbAccess = new DbAccess();
    $success = $dbAccess->openDBConnection();

    if (!$success) {
        die("Errore nell'apertura del DB");
    }
    
    $user = $dbAccess->loginUser($email, $password);
    $dbAccess->closeConnection();
    if($user){        
        $_SESSION["user"] = $user;
        header("Location: http://localhost/index.php");
    }else{
        echo "<br/>utente non esiste";
    }
}
?>