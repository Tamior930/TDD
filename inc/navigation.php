<?php session_start();

require '../htdocs/config/dbaccess.php';
require '../htdocs/inc/functions.inc.php';

$sql  = "SELECT userTyp, userUID FROM benutzer where userUID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $_SESSION["useruid"]);
$stmt->execute();
$stmt->bind_result($userTyp, $userUID);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <title>Berlisa</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg fixed-top mask-custom shadow-0">
            <div class="container">

                <a class="navbar-brand" href="index.php"><span>Berlisa</span></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#Navbar"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="Navbar">
                    <ul class="navbar-nav ms-auto">

                        <?php

                        if ($userTyp == "Gast" || $userTyp == "Admin") {

                            if ($userTyp == "Admin") {
                                echo "<li class='nav-item'>
                                    <a class='nav-link' href='admin.php'>Verwaltung</a>
                                </li>";
                            }

                            echo "                        
                            <li class='nav-item'>
                                <a class='nav-link' href='profile.php'>" . $userUID . "</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='inc\logout.inc.php'>Logout</a>
                            </li>
                            <li class='nav-item'>
                            <a class='nav-link' href='hotel.php'>Hotels</a>
                            </li>";

                        } else {

                            echo "                        
                                <li class='nav-item'>
                                    <a class='nav-link' href='login.php'>Login</a>
                                </li>

                                <li class='nav-item'>
                                    <a class='nav-link' href='Registrierungsformular.php'>Sign up</a>
                                </li>";

                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="news.php">News</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Hilfe-Seite.php">Help & FAQ</a>
                        </li>

                    </ul>

                </div>
            </div>
        </nav>
    </header>

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .navbar {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-scroll .nav-link,
        .navbar-scroll .navbar-toggler-icon,
        .navbar-scroll .navbar-brand {
            color: #fff;
        }

        .navbar-scrolled .nav-link,
        .navbar-scrolled .navbar-toggler-icon,
        .navbar-scrolled .navbar-brand {
            color: #fff;
        }

        .navbar-scroll,
        .navbar-scrolled {
            background-color: #cbbcb1;
        }

        .dropdown-menu {
            backdrop-filter: blur(5px);
            border: none;
        }

        .mask-custom {
            backdrop-filter: blur(5px);
        }

        .navbar-brand {
            font-size: 1.75rem;
            letter-spacing: 3px;
        }

        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css");
    </style>