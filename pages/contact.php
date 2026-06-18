<?php 

require '../config/database.php';
require '../vendor/autoload.php';
$mailConfig = require '../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = null;
$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🔐 Sécurisation / validation
    $titre = trim($_POST['titre'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $message = trim($_POST['message'] ?? '');

    if (empty($titre) || empty($email) || empty($message)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {

        try {

            // 💾 INSERT BDD
            $sql = 'INSERT INTO contact (titre, email, message, dateContact) 
                    VALUES (?, ?, ?, NOW())';

            $query = $pdo->prepare($sql);
            $query->execute([$titre, $email, $message]);

            // 📧 ENVOI EMAIL
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig['username'];
            $mail->Password = $mailConfig['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom(
                $mailConfig['from_email'],
                $mailConfig['from_name']
                );
            
            $mail->addAddress(
                $mailConfig['to_email']
            );

            
            $mail->addReplyTo($email);

            $mail->isHTML(true);
            $mail->Subject = $titre;

            $mail->Body = "
                <h3>Nouveau message reçu</h3>
                <p><b>Email :</b> $email</p>
                <p><b>Titre :</b> $titre</p>
                <p><b>Message :</b><br>" . nl2br($message) . "</p>
            ";

            
            $mail->SMTPDebug = 0;

            $mail->send();

            $success = "Message envoyé avec succès.";

        } catch (Exception $e) {
            $erreur = "Erreur lors de l'envoi du message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Contact - Vite & Gourmand</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/contact.css">
    <link rel="stylesheet" href="../css/vite&gourmand.css">
</head>

<body>

<?php require '../includes/navbar.php'; ?>

<section class="hero">
    <h1>Contactez-nous</h1>
</section>

<main class="container">

<section class="contact-card">

    <span><img class="logo-contact" src="../images/contact.png" alt=""></span>

    <div class="contact-info">
        <div class="info-item">
            <div class="icon">📍</div>
            <div>
                <h3>Adresse</h3>
                <p>12 Rue des Saveurs<br>33000 Bordeaux</p>
            </div>
        </div>

        <div class="info-item">
            <div class="icon">📞</div>
            <div>
                <h3>Téléphone</h3>
                <p>05 56 00 00 00</p>
            </div>
        </div>

        <div class="info-item">
            <div class="icon">✉</div>
            <div>
                <h3>Email</h3>
                <p>contact@vitegourmand.fr</p>
            </div>
        </div>

        <div class="info-item">
            <div class="icon">🕒</div>
            <div>
                <h3>Horaire</h3>
                <p>Lundi - Samedi : 9h - 18h</p>
            </div>
        </div>
    </div>

    <?php if ($success): ?>
        <p class="success"><?= $success; ?></p>
    <?php endif; ?>

    <?php if ($erreur): ?>
        <p class="error"><?= $erreur; ?></p>
    <?php endif; ?>

    <form method="POST" class="contact-form">

        <div class="row">
            <input type="text" name="titre" placeholder="Titre">
            <input type="email" name="email" placeholder="Email">
        </div>

        <textarea name="message" placeholder="Votre Message"></textarea>

        <button type="submit" class="btn">
            Envoyer le message →
        </button>

    </form>

</section>

<section class="features">

    <div class="feature">
        <div class="feature-icon">🎧</div>
        <h3>Une équipe à votre écoute</h3>
        <p>Nous vous accompagnons dans l’organisation de vos événements</p>
    </div>

    <div class="feature">
        <div class="feature-icon">📋</div>
        <h3>Devis personnalisé</h3>
        <p>Réponse rapide et adaptée à vos besoins</p>
    </div>

    <div class="feature">
        <div class="feature-icon">👍</div>
        <h3>Services sur mesure</h3>
        <p>Menus créés pour vos moments d’exception</p>
    </div>

</section>

</main>

<?php require '../includes/footer.php'; ?>

</body>
</html>