<?php

// Funktion zur Überprüfung, ob ein Eingabefeld bei der Registrierung leer ist
function leereEingabeRegister($anrede, $vn, $nn, $user, $email, $pass, $pass_wh)
{
    return empty($anrede) || empty($vn) || empty($nn) || empty($user) || empty($email) || empty($pass) || empty($pass_wh);
}

// Funktion zur Überprüfung, ob ein Benutzername nur Buchstaben und Zahlen enthält
function invalidUser($user)
{
    return !preg_match("/^[a-zA-Z0-9]*$/", $user);
}

// Funktion zur Überprüfung, ob eine E-Mail-Adresse gültig ist
function invalidEmail($email)
{
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Funktion zur Überprüfung, ob zwei Passwörter übereinstimmen
function passCheck($pass, $pass_wh)
{
    return $pass !== $pass_wh;
}

// Funktion zur Überprüfung, ob ein Benutzer mit dem angegebenen Benutzernamen oder der E-Mail-Adresse bereits in der Datenbank existiert
function userExistiert($conn, $user, $email)
{
    $sql  = "SELECT * FROM benutzer WHERE userUID = ? OR userEMAIL = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        redirectToRegisterPage("stmtFehlgeschlagen");
    }

    $stmt->bind_param("ss", $user, $email);
    $stmt->execute();

    $resultData = $stmt->get_result();

    if ($row = $resultData->fetch_assoc()) {
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return false;
    }
}

// Funktion zum Erstellen eines neuen Benutzers in der Datenbank
function erstelleUser($conn, $anrede, $vn, $nn, $email, $user, $pass)
{
    $userTyp  = 'Gast';
    $hashPass = password_hash($pass, PASSWORD_DEFAULT);

    $sql  = "INSERT INTO benutzer (userTyp, userANREDE, userVN, userNN, userEMAIL, userUID, userPWD) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        redirectToRegisterPage("stmtFehlgeschlagen");
    }

    $stmt->bind_param("sssssss", $userTyp, $anrede, $vn, $nn, $email, $user, $hashPass);
    $stmt->execute();
    $stmt->close();
    redirectToRegisterPage("none");
}

// Funktion zur Überprüfung, ob Login-Eingabefelder leer sind
function leereEingabeLogin($user, $pass)
{
    return empty($user) || empty($pass);
}

// Funktion zum Einloggen eines Benutzers
function loginUser($conn, $user, $pass)
{
    $userExists = userExistiert($conn, $user, $user);

    if ($userExists === false) {
        redirectToLoginPage("userNotExisting");
    }

    $passHashed = $userExists["userPWD"];
    $checkPass  = password_verify($pass, $passHashed);

    if ($checkPass === false) {
        redirectToLoginPage("ungueltigeDaten");
    } elseif ($checkPass === true) {
        session_start();
        $_SESSION["userid"]  = $userExists["userID"];
        $_SESSION["useruid"] = $userExists["userUID"];
        header("location: ../index.php");
    }
}

// Funktion zum Einfügen einer neuen Reservierung in die Datenbank
function insertReservation($conn, $userID, $anreise, $abreise, $zimmer, $anzahl_personen, $fruehstueck, $parkplatz, $haustiere)
{
    $status = 'neu';

    // Prüft ob die Werte zwischen 1 bis 5 liegen
    if ($anzahl_personen < 1 || $anzahl_personen > 5) {
        redirectToHotelPage("InvalidPersonen");
    }

    // Prüft ob Anreise in Vergangenheit liegt.
    $today = date("Y-m-d");
    if (strtotime($anreise) < strtotime($today)) {
        redirectToHotelPage("AnreiseFalsch");
    }

    $startTimestamp = strtotime($anreise);
    $endTimestamp   = strtotime($abreise);

    if ($endTimestamp < $startTimestamp) {
        redirectToHotelPage("TerminFalsch");
    } else {
        $sql  = "SELECT * FROM reservations WHERE Anreise <= ? AND Abreise >= ? AND RES_Status <>'Storniert'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $abreise, $anreise);
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) > 0) {
            redirectToHotelPage("TerminExists");
        }
    }

    $sql  = "INSERT INTO reservations (FK_UserID, Anreise, Abreise, Zimmer, Personen, FH, PK, HT, Kosten, RES_Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        redirectToHotelPage("stmtFehlgeschlagen");
    }

    $totalCost = calculateTotalCost($fruehstueck, $parkplatz, $haustiere, $zimmer);

    $stmt->bind_param("isssiiiids", $userID, $anreise, $abreise, $zimmer, $anzahl_personen, $fruehstueck, $parkplatz, $haustiere, $totalCost, $status);
    $stmt->execute();
    $stmt->close();
    redirectToHotelPage("none");
}

// Funktion zur Berechnung der Gesamtkosten
function calculateTotalCost($fruehstueck, $parkplatz, $haustiere, $zimmer)
{
    $fruehstueckKosten = 10;
    $parkplatzKosten   = 5;
    $haustierKosten    = 20;
    $basisZimmerpreis  = 0;

    switch ($zimmer) {
        case 'Einzelzimmer':
            $basisZimmerpreis = 80;
            break;
        case 'Doppelzimmer':
            $basisZimmerpreis = 120;
            break;
        case 'Familienzimmer':
            $basisZimmerpreis = 200;
            break;
    }

    $gesamtKosten = $basisZimmerpreis + $fruehstueckKosten * $fruehstueck + $parkplatzKosten * $parkplatz + $haustierKosten * $haustiere;

    // Runde die Gesamtkosten auf 2 Dezimalstellen
    $gesamtKosten = round($gesamtKosten, 2);

    return $gesamtKosten;
}

// Funktion zur Bearbeitung von Benutzerprofilinformationen
function editProfile($conn, $userUID, $vn, $nn, $email, $oldpass, $newpass, $newpass_wh)
{
    $checkEmailQuery = "SELECT userUID FROM benutzer WHERE userEMAIL=?";
    $checkEmailStmt  = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param('s', $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $checkEmailStmt->close();
        redirectToProfilePage("EmailExists");
    }

    $query = "SELECT userPWD FROM benutzer WHERE userUID = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", $userUID);
    $stmt->execute();
    $stmt->bind_result($dbPassword);
    $stmt->fetch();
    $stmt->close();

    $updateFields = [];
    $updateParams = [];

    if (!empty($vn)) {
        $updateFields[] = "userVN=?";
        $updateParams[] = $vn;
    }

    if (!empty($nn)) {
        $updateFields[] = "userNN=?";
        $updateParams[] = $nn;
    }

    if (!empty($email)) {
        $updateFields[] = "userEMAIL=?";
        $updateParams[] = $email;
    }

    if (!empty($newpass)) {

        if (password_verify($oldpass, $dbPassword)) {
            if ($newpass == $newpass_wh) {
                $hashedPassword = password_hash($newpass, PASSWORD_DEFAULT);
                $updateFields[] = "userPWD=?";
                $updateParams[] = $hashedPassword;
            } else {
                redirectToProfilePage("NewPasswordIncorrect");
            }
        } else {
            redirectToProfilePage("OldPasswordIncorrect");
        }
    }

    if (empty($updateParams)) {
        redirectToProfilePage("NoFieldsToUpdate");
    }

    $updateParams[] = $userUID;

    $query = "UPDATE benutzer SET " . implode(", ", $updateFields) . " WHERE userUID=?";
    $stmt  = $conn->prepare($query);

    $types = str_repeat('s', count($updateParams));
    $stmt->bind_param($types, ...$updateParams);
    $success = $stmt->execute();

    $stmt->close();

    if ($success) {
        redirectToProfilePage("none");
    } else {
        redirectToProfilePage("UpdateFailed");
    }
}

// Funktion zur Bearbeitung von Benutzerinformationen als Administrator
function admineditUser($conn, $userId, $newVN, $newNN, $newEmail, $newPass, $newPASS_wh, $newStatus)
{
    if (empty($userId)) {
        redirectToAdminPage("noIDfound");
    }

    $sql  = "SELECT userID FROM benutzer WHERE userID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        redirectToAdminPage("UserIDNotFound");
    }

    $sql  = "SELECT userID FROM benutzer WHERE userEMAIL=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $newEmail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        redirectToAdminPage("EmailExists");
    }

    $updateFields = [];
    $updateParams = [];

    if (!empty($newVN)) {
        $updateFields[] = "userVN=?";
        $updateParams[] = $newVN;
    }

    if (!empty($newNN)) {
        $updateFields[] = "userNN=?";
        $updateParams[] = $newNN;
    }

    if (!empty($newEmail)) {
        $updateFields[] = "userEMAIL=?";
        $updateParams[] = $newEmail;
    }

    if (!empty($newPass)) {
        if (!empty($newPASS_wh)) {
            if ($newPass === $newPASS_wh) {
                $hashedPassword = password_hash($newPass, PASSWORD_DEFAULT);
                $updateFields[] = "userPWD=?";
                $updateParams[] = $hashedPassword;
            } else {
                redirectToAdminPage("NewPasswordMismatch");
            }
        } else {
            redirectToAdminPage("PassConfirmationEmpty");
        }
    }

    if (!empty($newStatus)) {
        $updateFields[] = "userSTATUS=?";
        $updateParams[] = $newStatus;
    }

    if (empty($updateParams)) {
        redirectToAdminPage("NoFieldsToUpdate");
    }

    $updateParams[] = $userId;

    $updateQuery = "UPDATE benutzer SET " . implode(", ", $updateFields) . " WHERE userID=?";
    $stmt        = $conn->prepare($updateQuery);

    $types = str_repeat('s', count($updateParams));
    $stmt->bind_param($types, ...$updateParams);
    $success = $stmt->execute();

    $stmt->close();

    if ($success) {
        redirectToAdminPage("none");
    } else {
        redirectToAdminPage("UpdateFailed");
    }
}

// Funktion zum Einfügen von Nachrichten in die Datenbank
function news($conn, $title, $text, $originalFile, $thumbnailFile)
{
    $sql  = "INSERT INTO news (title, text, original_file, thumbnail) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        redirectToNewsPage("stmtFehlgeschlagen");
    }

    $stmt->bind_param("ssss", $title, $text, $originalFile, $thumbnailFile);
    $stmt->execute();
    $stmt->close();
    redirectToNewsPage("none");
}

function addNews($conn, $title, $text)
{
    if (!isset($_FILES["bild"]) || $_FILES["bild"]["error"] != UPLOAD_ERR_OK) {
        redirectToNewsPage("UPLOAD_ERROR");
    }

    $date          = new DateTime();
    $timestamp     = $date->getTimestamp();
    $uploadDir     = "../uploads/news/";
    $thumbnailDir  = "../uploads/thumbnails/";
    $fileExtension = strtolower(pathinfo(trim($_FILES["bild"]["name"]), PATHINFO_EXTENSION));
    $allowedTypes  = ["jpg", "jpeg", "png"];

    if (in_array($fileExtension, $allowedTypes)) {

        $picname       = explode(".", $_FILES["bild"]["name"]);
        $originalFile  = $uploadDir . $picname[0] . "_" . $timestamp . "." . end($picname);
        $thumbnailFile = $thumbnailDir . $picname[0] . "_" . $timestamp . "_thumb." . end($picname);

        // Hochladen der Orginal Bilder
        if (move_uploaded_file($_FILES["bild"]["tmp_name"], $originalFile)) {
            $thumbnail = thumbnail($originalFile, 720, 480);

            if (imagejpeg($thumbnail, $thumbnailFile)) {
                news($conn, $title, $text, $originalFile, $thumbnailFile);
            } else {
                redirectToNewsPage("THUMBNAIL_CREATION_ERROR");
            }
        } else {
            redirectToNewsPage("UPLOAD_ERROR");
        }
    } else {
        redirectToNewsPage("INVALID_FILE_TYPE");
    }
}

// Funktion zum Identifizieren und Reduzieren der Bilder
function thumbnail($originalFile, $width, $height)
{
    $imageType = exif_imagetype($originalFile);

    switch ($imageType) {
        case 2:
            // JPEG file
            $image = imagecreatefromjpeg($originalFile);
            break;
        case 3:
            // PNG file
            $image = imagecreatefrompng($originalFile);
            break;
    }

    $thumbnail = imagecreatetruecolor($width, $height);
    imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

    return $thumbnail;
}

// Funktion zum Löschen der News aus der DB
function removeNews($conn, $newsID)
{
    $stmt = $conn->prepare("DELETE FROM news WHERE newsID = ?");
    $stmt->bind_param("i", $newsID);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // News removed successfully
        $stmt->close();
        redirectToNewsPage("deletionSuccess");
    } else {
        // News not found or deletion failed
        $stmt->close();
        redirectToNewsPage("newsNotFound");
    }
}

// Funktion zum Abrufen des Benutzerstatus aus der Datenbank
function getUserStatus($conn, $user)
{
    $sql  = "SELECT userStatus FROM benutzer WHERE userUID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($status);
        $stmt->fetch();
        $stmt->close();
        return $status;
    }

    $conn->close();
}

// Hilfsfunktion zum Umleiten zu einer Fehlerseite
function redirectToRegisterPage($error)
{
    header("location: ../Registrierungsformular.php?error=$error");
    exit();
}

// Hilfsfunktion zum Umleiten zu einer Login-Seite mit einer Fehlermeldung
function redirectToLoginPage($error)
{
    header("location: ../login.php?error=$error");
    exit();
}

// Hilfsfunktion zum Umleiten zu einer Hotel-Seite mit einer Fehlermeldung
function redirectToHotelPage($error)
{
    header("location: ../hotel.php?error=$error");
    exit();
}

// Hilfsfunktion zum Umleiten zu einer Nachrichtenseite
function redirectToProfilePage($error)
{
    header("location: ../profile.php?error=$error");
    exit();
}

// Hilfsfunktion zum Umleiten zu einer News-Seite mit einer Fehlermeldung
function redirectToNewsPage($error)
{
    header("location: ../news.php?error=$error");
    exit();
}

// Hilfsfunktion zum Umleiten zu einer Nachrichtenseite
function redirectToAdminPage($error)
{
    header("location: ../admin.php?error=$error");
    exit();
}