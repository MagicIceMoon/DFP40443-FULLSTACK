<?php
// includes/functions.php

// Reusable logic to check if the user is authorized
function isValidUser($user, $pass) {
    $admin_user = "afiq@portal.edu";
    $admin_pass = "12345";

    return($user === $admin_user && $pass === $admin_pass);
}
?>