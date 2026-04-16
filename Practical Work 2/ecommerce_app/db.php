<?php

$host = "127.0.0.1:3307";
$user = "root";
$pw = "";
$db = "ecommerce";

$conn = mysqli_connect($host,$user,$pw,$db);

if(!$conn) {
    die("Can't connect, connection failed". mysqli_connect());
}
?>