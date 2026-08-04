<?php 

session_start();

require '../config/database.php';
require '../config/mail.php';

$success = null;
$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $titre = trim($_POST['titre']);
    $message = trim($_POST['message']);

    if (empty($nom) || empty($titre) || empty($email) || empty($message)) {

        $erreur = "Tous les champs sont obligatoires.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erreur = "Adresse email invalide.";

    } else {

        $sql = '
        INSERT INTO contact 
        (titre, email, message, dateContact) 
        VALUES (?, ?, ?, NOW())
        ';

        $query = $pdo->prepare($sql);

        $query->execute([
            $titre,
            $email,
            $message
        ]);

        $nomSafe = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
        $emailSafe = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $titreSafe = htmlspecialchars($titre, ENT_QUOTES, 'UTF-8');
        $messageSafe = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        $sujetMail = "Nouveau message de contact : " . $titreSafe;

        $messageMail = "
        <h2>Nouveau message reçu</h2>

        <p><strong>Nom :</strong> {$nomSafe}</p>

        <p><strong>Email :</strong> {$emailSafe}</p>

        <p><strong>Titre :</strong> {$titreSafe}</p>

        <p><strong>Message :</strong></p>

        <p>{$messageSafe}</p>
        ";

        envoyerMail(
            "vitegourmandoff@gmail.com",
            "Administrateur",
            $sujetMail,
            $messageMail
        );

        $success = "Votre message a bien été envoyé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Vite & Gourmand</title>
    <link rel="stylesheet" href="../css/contact.css">
    <link rel="stylesheet" href="../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>
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


    <form method="POST" class="contact-form">

        <div class="row">
            <input type="text" name="nom" placeholder="Votre nom" required>
            <input type="text" name="titre"  placeholder="Titre" required>
        </div>

        <div>
            <input type="email" name="email" placeholder="Email" required>
            
            <textarea name="message" placeholder="Votre Message" required></textarea>
        </div>
       

        <button type="submit" class="btn">
            Envoyer le message →
        </button>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <?php if ($erreur): ?>
            <p class="error"><?= htmlspecialchars($erreur); ?></p>
        <?php endif; ?>

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