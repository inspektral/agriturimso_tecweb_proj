<?php
session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."logout.html");

if (session_unset()) {
    echo $html;
    header("refresh:2;url=index.php");
}else{
    header("url=errors/500.html");
}
?>