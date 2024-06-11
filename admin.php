<link rel="stylesheet" href="res/style/admin.css">

<?php
include 'inc/navigation.php';

// Überprüfen des Benutzertyps
if ($userTyp == "Admin") {
  displayUserList();
  displayProfileEditForm();
  displayUserReservation();

  include 'inc/footer.php';

} else {
  // Fehlermeldung für Benutzer ohne Adminrechte
  echo "<div class='alert alert-danger text-center container col-auto Box-1' role='alert'>Sie haben keine Rechte</div>";
  include 'inc/footer.php';
}

// Funktion zur Anzeige der Benutzerliste
function displayUserList()
{

  require '../htdocs/config/dbaccess.php';

  // Abrufen aller Benutzer aus der Datenbank
  $result = $conn->query("SELECT * FROM benutzer");

  // Überprüfen, ob Benutzer vorhanden sind
  if ($result->num_rows > 0) {
    echo '<div class="container rounded-3 pt-3 text-white Box-1">';
    echo "<div class='container'>";
    echo "<h2 class='text-center'>Liste aller Benutzer*innen</h2>";
    echo '<div style="max-height: 300px; overflow: auto;">';
    echo '<table class="table">';
    echo '<thead><tr><th>ID</th><th>Name</th><th>Nachname</th><th>Email</th><th>Status</th></tr></thead><tbody>';

    // Ausgabe der Benutzerdaten in einer Tabelle
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>{$row["userID"]}</td><td>{$row["userVN"]}</td><td>{$row["userNN"]}</td><td>{$row["userEMAIL"]}</td><td>{$row["userStatus"]}</td></tr>";
    }

    echo '</tbody></table>';
    echo '</div>';
    echo '</div>';
  } else {
    // Meldung bei fehlenden Benutzern
    echo '<div class="alert alert-info" role="alert">Keine Benutzer gefunden.</div>';
  }
}

// Funktion zur Anzeige des Profilbearbeitungsformulars
function displayProfileEditForm()
{
  ?>

  <div class="container mb-2">

    <div class="row">
      <div class="col-md-6">
        <h2 class="text-center">Profilbearbeitung</h2>
      </div>
      <div class="col-md-6">
        <h2 class="text-center">Reservations</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 Box-2 rounded-3 p-2">

        <!-- Anzeige des Profilbearbeitungsformulars -->
        <form method="post" action="inc\admin.inc.php">
          <div class="row">
            <div class="col-sm-6 mb-3">
              <label for="userID" class="form-label">Benutzer-ID:</label>
              <input type="text" name="userID" id="userID" class="form-control">
            </div>
            <div class="col-sm-6 mb-3">
              <label for="userEMAIL" class="form-label">Email:</label>
              <input type="email" name="userEMAIL" id="userEMAIL" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 mb-3">
              <label for="userVN" class="form-label">Vorname:</label>
              <input type="text" name="userVN" id="userVN" class="form-control">
            </div>
            <div class="col-sm-6 mb-3">
              <label for="userNN" class="form-label">Nachname:</label>
              <input type="text" name="userNN" id="userNN" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 mb-3">
              <label for="userPASS" class="form-label">Passwort:</label>
              <input type="password" name="userPASS" id="userPASS" class="form-control">
            </div>
            <div class="col-sm-6 mb-3">
              <label for="userPASS_wh" class="form-label">Passwort-WH:</label>
              <input type="password" name="userPASS_wh" name="userPASS_wh" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3 mb-3">
              <label for="newStatus" class="form-label">Neuer Status:</label>
              <select name="newStatus" class="form-select">
                <option value="active">Aktiv</option>
                <option value="inactive">Inaktiv</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <button type="submit" name="submit" class="btn btn-primary">Profil aktualisieren</button>
            </div>
          </div>
        </form>
      </div>
      <?php
}

// Funktion zur Anzeige der Benutzerreservierungen
function displayUserReservation()
{
  ?>
      <div class="col-md-6 Box-3 rounded-3 text-center d-flex justify-content-center align-items-center">
        <form method="POST" action="/admin.php" class="col-6">
          <div class="mb-3">
            <label for="searchUser" class="form-label">User ID (optional):</label>
            <input type="text" class="form-control" name="searchUser">
          </div>
          <button type="submit" name="reservationUserID" class="btn btn-primary">View Reservations</button>
        </form>
      </div>
    </div>
  </div>

  <?php
  // Anzeige von Fehlermeldungen, falls vorhanden
  if (isset($_GET["error"])) {
    displayErrorMessages($_GET["error"]);
  }

  require '../htdocs/config/dbaccess.php';

  // Überprüfen, ob das Formular abgesendet wurde
  if (isset($_POST["reservationUserID"])) {
    $searchUser = isset($_POST['searchUser']) ? $_POST['searchUser'] : null;

    if ($searchUser != null) {
      $sqlAbfrage = "SELECT * FROM reservations WHERE FK_UserID = ?";
      $stmt       = $conn->prepare($sqlAbfrage);
      $stmt->bind_param("i", $searchUser);
    } else {
      $sqlAbfrage = "SELECT * FROM reservations";
      $stmt       = $conn->prepare($sqlAbfrage);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      ?>
      <div class="container mb-2">
        <h3>Reservierungen
          <?php echo ($searchUser != null) ? "von UserID $searchUser" : "für alle User"; ?>
        </h3>
        <div style="max-height: 300px; overflow: auto;">
          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">UserID</th>
                  <th scope="col">Von</th>
                  <th scope="col">Bis</th>
                  <th scope="col">Zimmer</th>
                  <th scope="col">Personen</th>
                  <th scope="col">Frühstück</th>
                  <th scope="col">Parkplatz</th>
                  <th scope="col">Haustiere</th>
                  <th scope="col">Kosten</th>
                  <th scope="col">Status</th>
                  <th scope="col">Aktion</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($reservation = $result->fetch_assoc()) { ?>
                  <tr>
                    <td>
                      <?php echo $reservation["FK_UserID"]; ?>
                    </td>
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
                    <!-- Auswahl der Statuswerte -->
                    <td>
                      <select name="statusUpdate[<?php echo $reservation['reservationID']; ?>]">
                        <option value="neu" <?php echo ($reservation['RES_Status'] == 'Neu') ? 'selected' : ''; ?>>Neu</option>
                        <option value="bestätigt" <?php echo ($reservation['RES_Status'] == 'Bestätigt') ? 'selected' : ''; ?>>
                          Bestätigt</option>
                        <option value="storniert" <?php echo ($reservation['RES_Status'] == 'Storniert') ? 'selected' : ''; ?>>
                          Storniert</option>
                      </select>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <button type="submit" name="updateStatus" class="btn btn-primary">Update Status</button>
          </form>
        </div>
      </div>
    <?php } else {
      echo "<div class='alert alert-info container text-center' role='alert'>Keine Reservierungen gefunden.</div>";
    }

    $stmt->close();
  }

  // Aktualisierung der Statuswerte
  if (isset($_POST['updateStatus'])) {
    foreach ($_POST['statusUpdate'] as $reservationID => $newStatus) {

      $sql  = "UPDATE reservations SET RES_Status = ? WHERE reservationID = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("si", $newStatus, $reservationID);
      $stmt->execute();
      $stmt->close();
    }
  }
  ?>
  </div>
  <?php
}


// Funktion zur Anzeige von Fehlermeldungen
function displayErrorMessages($errorType)
{
  // Fehler- oder Erfolgsmeldungen anzeigen
  if (isset($_GET["error"])) {
    $errorType    = $_GET["error"];
    $errorMessage = "";

    switch ($errorType) {
      case "UpdateFailed":
        $errorMessage = "Fehler: Fehler beim Aktualisieren des Benutzerprofils. Bitte versuche es erneut.";
        break;
      case "noIDfound":
        $errorMessage = "Fehler: Die Benutzer-ID ist leer. Bitte fülle das Feld aus.";
        break;
      case "UserIDNotFound":
        $errorMessage = "Fehler: Die Benutzer-ID existiert nicht in unserer Datenbank. Überprüfe die eingegebene ID.";
        break;
      case "NewPasswordMismatch":
        $errorMessage = "Fehler: Die eingegebenen Passwörter stimmen nicht überein. Bitte überprüfe deine Eingabe.";
        break;
      case "PassConfirmationEmpty":
        $errorMessage = "Fehler: Die Passwortbestätigung ist leer. Bitte bestätige dein neues Passwort.";
        break;
      case "NoFieldsToUpdate":
        $errorMessage = "Fehler: Es wurden keine Änderungen vorgenommen. Bitte fülle mindestens ein Feld aus, um Aktualisierungen vorzunehmen.";
        break;
      case "EmailExists":
        $errorMessage = "Fehler: Diese E-Mail existiert bereits";
        break;
      case "none":
        $errorMessage = "Erfolg: Benutzerprofil erfolgreich aktualisiert.";
        break;
    }

    // Fehler- oder Erfolgsmeldung anzeigen
    $alertType = ($errorType === "none") ? "success" : "danger";
    echo "<div class='alert alert-$alertType' role='alert'>$errorMessage</div>";
  }
}
?>