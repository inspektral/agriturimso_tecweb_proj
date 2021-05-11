<?php
session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."logout.html");

if (session_unset()) {
    echo $html;
    header("refresh:2;Location: /");
}else{
    header("Location: /errors/500.php");
}
?>