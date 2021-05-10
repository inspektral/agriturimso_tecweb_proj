<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . "php/userNameMenu.php";

$htmlPage = file_get_contents("accedi.html");

$strAccedi = '';

if (! isset($_SESSION["user"]) && isset($_SESSION["errLog"]) && $_SESSION["errLog"])
    $strAccedi = '<p class="errorLogin"><span xml:lang="en">Email</span> o <span xml:lang="en">password</span> errati</p>';

echo str_replace("<mailpwdErr/>", $strAccedi, $htmlPage);

?>