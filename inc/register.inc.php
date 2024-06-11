<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["submit"])) {
        processRegistrationForm();
    } else {
        header("location: ../Registrierungsformular.php");
        exit();
    }
}

function processRegistrationForm()
{
    require_once '../config/dbaccess.php';
    require_once '../inc/functions.inc.php';

    $anrede  = validateInput($_POST["anrede"]);
    $user    = validateInput($_POST["user"]);
    $vn      = validateInput($_POST["vn"]);
    $nn      = validateInput($_POST["nn"]);
    $email   = validateInput($_POST["email"]);
    $pass    = validateInput($_POST["pass"]);
    $pass_wh = validateInput($_POST["pass_wh"]);

    if (leereEingabeRegister($anrede, $vn, $nn, $user, $email, $pass, $pass_wh) !== false) {
        redirectWithError('leereEingabe');
    }

    if (invalidUser($user) !== false) {
        redirectWithError('ungueltigeUser');
    }

    if (invalidEmail($email) !== false) {
        redirectWithError('ungueltigeEmail');
    }

    if (passCheck($pass, $pass_wh) !== false) {
        redirectWithError('passwortFalsch');
    }

    if (userExistiert($conn, $user, $email) !== false) {
        redirectWithError('userExistiert');
    }

    erstelleUser($conn, $anrede, $vn, $nn, $email, $user, $pass);
}

function validateInput($input)
{
    return htmlspecialchars(trim($input));
}

function redirectWithError($errorType)
{
    header("location: ../Registrierungsformular.php?error=$errorType");
    exit();
}
?>