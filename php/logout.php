<?php
session_start();
if (session_unset()) {
    echo "<h1>Logout effettuato, verrai reindirizzato tra 2 secondi</h1>";
    header( "refresh:2;url= http://localhost/index.php" );
}else{
    header("url=errors/500.html");
}
?>