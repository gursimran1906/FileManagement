<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "filemanagement";
// Create connection
try{
    $con = mysqli_connect($servername, $username, $password,$database);
}   catch(Exception $e){
    echo "Caught Error: ", $e->getMessage(),"\n";
}

?>