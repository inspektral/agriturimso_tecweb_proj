<?php
session_start();
if (session_unset()) {
    header( "refresh:5;url=index.php" );
    header("Location: ");
}else{
    header("url=errors/500.html");
}
?>