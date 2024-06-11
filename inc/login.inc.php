<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["submit"])) {
        processLoginForm();
    } else {
        header("location: ../login.php");
        exit();
    }
}

function processLoginForm()
{
    require_once '../config/dbaccess.php';
    require_once '../inc/functions.inc.php';

    $user = validateInput($_POST["user"]);
    $pass = validateInput($_POST["pass"]);

    if (isEmptyLoginInput($user, $pass)) {
        redirectToLoginWithError("leereEingabe");
    }

    $userStatus = getUserStatus($conn, $user);

    if ($userStatus === "active") {
        loginUser($conn, $user, $pass);
    } else {
        redirectToLoginWithError("inactiveOrInvalid");
    }
}

function validateInput($input)
{
    return htmlspecialchars(trim($input));
}

function isEmptyLoginInput($user, $pass)
{
    return leereEingabeLogin($user, $pass) !== false;
}

function redirectToLoginWithError($errorType)
{
    header("location: ../login.php?error=$errorType");
    exit();
}

?>