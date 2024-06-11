<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        processFormSubmission();
    } else {
        header("location: ../admin.php");
        exit();
    }
}

function processFormSubmission()
{
    require_once '../config/dbaccess.php';
    require_once '../inc/functions.inc.php';

    $userId     = validateInput($_POST["userID"]);
    $newVN      = validateInput($_POST["userVN"]);
    $newNN      = validateInput($_POST["userNN"]);
    $newEmail   = validateInput($_POST["userEMAIL"]);
    $newPass    = $_POST["userPASS"];
    $newPASS_wh = $_POST["userPASS_wh"];
    $newStatus  = validateInput($_POST["newStatus"]);

    admineditUser($conn, $userId, $newVN, $newNN, $newEmail, $newPass, $newPASS_wh, $newStatus);
}

function validateInput($input)
{
    return htmlspecialchars(trim($input));
}
?>