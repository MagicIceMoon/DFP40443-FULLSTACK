<?php

session_start();
if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

require_once("config/app_config.php");

$result_array = null;
$mesej = null;
?>