<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["submit"])) {
        processFormSubmission();
    } else {
        header("location: ../hotel.php");
        exit();
    }
}

function processFormSubmission()
{
    session_start();
    require_once '../config/dbaccess.php';
    require_once '../inc/functions.inc.php';

    $userID          = $_SESSION["userid"];
    $anreise         = validateInput($_POST["Anreise"]);
    $abreise         = validateInput($_POST["Abreise"]);
    $zimmer          = validateInput($_POST["Zimmer"]);
    $anzahl_personen = intval($_POST["Personen"]);

    $fruehstueck = isset($_POST["FH"]) ? 1 : 0;
    $parkplatz   = isset($_POST["PK"]) ? 1 : 0;
    $haustiere   = isset($_POST["HT"]) ? 1 : 0;

    insertReservation($conn, $userID, $anreise, $abreise, $zimmer, $anzahl_personen, $fruehstueck, $parkplatz, $haustiere);
}

function validateInput($input)
{
    return htmlspecialchars(trim($input));
}
