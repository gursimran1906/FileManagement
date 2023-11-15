<?php



session_start();
session_destroy();
$url = $_REQUEST['URL'];

header("Location: Login.php?URL=$url");
