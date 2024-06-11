<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["submit"])) {
        processFormSubmission();
    } else {
        header("location: ../profile.php");
        exit();
    }
}

function processFormSubmission()
{
    session_start();
    require_once '../config/dbaccess.php';
    require_once '../inc/functions.inc.php';

    $userUID    = $_SESSION["useruid"];
    $vn         = validateInput($_POST["vn"]);
    $nn         = validateInput($_POST["nn"]);
    $email      = validateInput($_POST["email"]);
    $oldpass    = validateInput($_POST["oldpass"]);
    $newpass    = validateInput($_POST["newpass"]);
    $newpass_wh = validateInput($_POST["newpass_wh"]);

    editProfile($conn, $userUID, $vn, $nn, $email, $oldpass, $newpass, $newpass_wh);
}

function validateInput($input)
{
    return htmlspecialchars(trim($input));
}

?>