<?php
require_once 'dbConnect.php';
session_start();

$dbConn = new DBAccess();

$conn = $dbConn->dbConnection();

if ($conn) {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        if ($dbConn->verifyLogin($_POST["username"], $_POST["password"])) {
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["password"] = $_POST["password"];
        }
    }    
    $dbConn->dbClose();
}


header("Location: http://localhost/index.php");
?>