<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["submit"])) {
        processFormSubmission();
    } else if (isset($_POST["delete"])) {
        deleteNews();
    }
}

function processFormSubmission()
{
    require_once '../config/dbaccess.php';
    require_once '../inc/functions.inc.php';

    $title = validateInput($_POST["title"]);
    $text  = validateInput($_POST["text"]);

    addNews($conn, $title, $text);
}

function deleteNews()
{
    require_once '../config/dbaccess.php';
    require_once '../inc/functions.inc.php';

    $newsID = validateInput($_POST["newsID"]);

    removeNews($conn, $newsID);

}

function validateInput($input)
{
    return htmlspecialchars(trim($input));
}