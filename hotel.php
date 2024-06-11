<link rel="stylesheet" href="res/style/hotel.css">
<?php

include 'inc/navigation.php';

// Fetch all reservations for the user from the database
$sqlAbfrage = "SELECT * FROM reservations WHERE FK_UserID = ?";
$stmt       = $conn->prepare($sqlAbfrage);
$stmt->bind_param('s', $_SESSION["userid"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

?>

<!-- Hauptcontainer für die Zimmerreservierung -->
<div class="Box-1 p-3 mx-auto col-lg-6 col-md-8 col-sm-10 col-12">

    <!-- Überschrift der Seite -->
    <h1 class="mb-4 text-center">Zimmerreservierung</h1>

    <?php if ($userTyp === "Gast" || $userTyp === "Admin") { ?>

        <!-- Formular für die Zimmerreservierung -->
        <form action="inc/hotels.inc.php" method="post">

            <div class="mb-3 row justify-content-center">
                <!-- Eingabefeld für das Anreisedatum -->
                <div class="col-md-5 col-sm-8 col-12">
                    <label for="Anreise" class="form-label">Anreise</label>
                    <input type="date" name="Anreise" id="Anreise" required class="form-control">
                </div>

                <!-- Eingabefeld für das Abreisedatum -->
                <div class="col-md-5 col-sm-8 col-12 mt-3 mt-sm-0">
                    <label for="Abreise" class="form-label">Abreise</label>
                    <input type="date" name="Abreise" id="Abreise" required class="form-control">
                </div>
            </div>

            <div class="mb-3 row justify-content-center">
                <!-- Dropdown für die Auswahl des Zimmers -->
                <div class="col-md-5 col-sm-8 col-12">
                    <label for="Zimmer" class="form-label">Zimmer</label>
                    <select name="Zimmer" id="Zimmer" class="form-select">
                        <option value="Einzelzimmer">Einzelzimmer</option>
                        <option value="Doppelzimmer">Doppelzimmer</option>
                        <option value="Familienzimmer">Familienzimmer</option>
                    </select>
                </div>

                <!-- Eingabefeld für die Anzahl der Personen -->
                <div class="col-md-5 col-sm-8 col-12 mt-3 mt-sm-0">
                    <label for="Personen" class="form-label">Anzahl Personen</label>
                    <input type="number" name="Personen" id="Personen" required class="form-control" min="1" max="5">
                </div>
            </div>

            <!-- Checkboxen für optionale Extras -->
            <div class="mb-3 form-check">
                <label class="form-check-label" for="FH">Mit Frühstück</label>
                <input type="checkbox" name="FH" id="FH" class="form-check-input">
            </div>

            <div class="mb-3 form-check">
                <label class="form-check-label" for="PK">Mit Parkplatz</label>
                <input type="checkbox" name="PK" id="PK" class="form-check-input">
            </div>

            <div class="mb-3 form-check">
                <label class="form-check-label" for="HT">Haustiere erlaubt</label>
                <input type="checkbox" name="HT" id="HT" class="form-check-input">
            </div>

            <!-- Button zum Absenden des Formulars -->
            <button type="submit" name="submit" class="btn btn-primary mb-3">Reservierung abschicken</button>

        </form>

        <?php
        if (isset($_GET["error"])) {
            displayErrorMessages($_GET["error"]);
        }
        ?>

        <!-- Reservierungen im HTML anzeigen -->
        <?php if ($result->num_rows > 0) { ?>
            <div class="mb-3">
                <div style="max-height: 300px; overflow: auto;">
                    <h3>Ihre Reservierungen</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Von</th>
                                <th scope="col">Bis</th>
                                <th scope="col">Zimmer</th>
                                <th scope="col">Personen</th>
                                <th scope="col">Frühstück</th>
                                <th scope="col">Parkplatz</th>
                                <th scope="col">Haustiere</th>
                                <th scope="col">Kosten</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($reservation = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <?php echo $reservation["Anreise"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $reservation["Abreise"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $reservation['Zimmer']; ?>
                                    </td>
                                    <td>
                                        <?php echo $reservation['Personen']; ?>
                                    </td>
                                    <td>
                                        <?php echo ($reservation['FH'] == 1) ? 'Ja' : 'Nein'; ?>
                                    </td>
                                    <td>
                                        <?php echo ($reservation['PK'] == 1) ? 'Ja' : 'Nein'; ?>
                                    </td>
                                    <td>
                                        <?php echo ($reservation['HT'] == 1) ? 'Ja' : 'Nein'; ?>
                                    </td>
                                    <td>
                                        <?php echo $reservation['Kosten']; ?>
                                    </td>
                                    <td>
                                        <?php echo $reservation['RES_Status']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } else { ?>
            <p class="mt-4">Sie haben noch keine Reservierungen.</p>
        <?php } ?>


    <?php } else { ?>
        <!-- Meldung für nicht eingeloggte Benutzer -->
        <div class='alert alert-danger text-center col-12' role='alert'>Sie müssen sich anmelden!</div>
    <?php } ?>

</div>

<?php
// Funktion zur Anzeige von Fehlermeldungen
function displayErrorMessages($errorType)
{
    // Fehler- oder Erfolgsmeldungen anzeigen
    if (isset($_GET["error"])) {
        $errorType    = $_GET["error"];
        $errorMessage = "";

        switch ($errorType) {
            case "TerminExists":
                $errorMessage = "Fehler: Der Termin existiert bereits zu dieser Zeit.";
                break;
            case "TerminFalsch":
                $errorMessage = "Fehler: Falsche Terminangabe.";
                break;
            case "stmtFehlgeschlagen":
                $errorMessage = "Fehler: Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.";
                break;
            case "InvalidPersonen":
                $errorMessage = "Fehler: Die Anzahl der Personen muss zwischen 1 und 5 liegen.";
                break;
            case "AnreiseFalsch":
                $errorMessage = "Fehler: Das Anreisedatum darf nicht in der Vergangenheit liegen.";
                break;
            case "none":
                $errorMessage = "Erfolg: Reservierung erfolgreich verschickt.";
                break;
        }

        // Fehler- oder Erfolgsmeldung anzeigen
        $alertType = ($errorType === "none") ? "success" : "danger";
        echo "<div class='alert alert-$alertType' role='alert'>$errorMessage</div>";
    }
}
?>

<?php include 'inc/footer.php'; ?>