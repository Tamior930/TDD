<link rel="stylesheet" href="res/style/contact.css">
<?php
include 'inc/navigation.php';

    // use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\SMTP;
    // use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["submit"]) && !empty($_POST["email"])) {

    // //Load Composer's autoloader
    // require 'vendor/autoload.php';

    $name    = htmlspecialchars($_POST["name"]);
    $email   = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    invalidEmail($email);

    if (invalidEmail($email) !== false) {
        $submissionMessage = "Fehler: Ungültige E-Mail Addresse.";
    }

    // $mail = new PHPMailer(true);

    //     try {
    //         //Server settings
    //         $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    //         $mail->isSMTP();                                            //Send using SMTP
    //         $mail->Host       = 'smtp.technikum-wien.at';               //Set the SMTP server to send through
    //         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    //         $mail->Username   = 'user@technikum-wien.at';               //SMTP username
    //         $mail->Password   = 'secure';                               //SMTP password
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
    //         $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //         //Recipients
    //         $mail->setFrom("$email", "$name");
    //         $mail->addAddress('user@technikum-wien.at');                //Name is optional

    //         //Content
    //         $mail->isHTML(true);                                        //Set email format to HTML
    //         $mail->Subject = "Neue Kontaktformular-Einsendung von $name";
    //         $mail->Body    = "E-Mail: $message\n";

    //         $mail->send();
               $submissionMessage = "Message has been sent";
    //     } catch (Exception $e) {
    //         $submissionMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    //     }
    } else {
        $submissionMessage = "Formular nicht übermittelt.";
    }
}
?>

<div class="container Box-1">
    <div class="justify-content-center align-items-center">
        <div class="row">
            <div class="col-md-6">
                <img src="res/img/Illustration.png" alt="Hotelbild" class="img-fluid">
            </div>
            <div class="col-md-6">
                <div class="col mt-5">
                    <h1>Kontaktiere uns</h1>
                    <p class="lead">Zögere nicht, uns bei Fragen oder Buchungen zu kontaktieren!</p>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Dein Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Deine E-Mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Deine Nachricht</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-dark btn-primary">Nachricht senden</button>
                    </form>

                    <?php if (!empty($submissionMessage)): ?>
                        <p>
                            <?php echo $submissionMessage; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>