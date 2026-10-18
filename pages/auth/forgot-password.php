<?php

session_start();

require '../../config/database.php';
require '../../config/mail.php';
require '../../classes/utilisateur.php';

$success = "";
$erreur = "";

$token = $_GET['token'] ?? "";

$utilisateurClass = new Utilisateur($pdo);

/* =========================
   ETAPE 1 : DEMANDE EMAIL
========================= */

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && empty($token)
) {

    $email = trim($_POST['email']);

    $utilisateur =
        $utilisateurClass->getByEmail($email);

    if ($utilisateur) {

        $resetToken =
            bin2hex(random_bytes(32));

        $expire = date(
            'Y-m-d H:i:s',
            strtotime('+1 hour')
        );

        $utilisateurClass->creerResetToken(
            $email,
            $resetToken,
            $expire
        );

        $lien =
            "http://localhost/pages/auth/forgot-password.php?token="
            . $resetToken;

        $sujet =
            "Réinitialisation de votre mot de passe";

        $prenomSafe = htmlspecialchars(
            $utilisateur['prenom'],
            ENT_QUOTES,
            'UTF-8'
        );

        $lienSafe = htmlspecialchars(
            $lien,
            ENT_QUOTES,
            'UTF-8'
        );

        $message = "
            <h2>Réinitialisation du mot de passe</h2>

            <p>Bonjour {$prenomSafe},</p>

            <p>
                Cliquez sur le lien ci-dessous
                pour créer un nouveau mot de passe :
            </p>

            <p>
                <a href='{$lienSafe}'>
                    Réinitialiser mon mot de passe
                </a>
            </p>

            <p>
                Ce lien est valable pendant 1 heure.
            </p>
        ";

        envoyerMail(
            $email,
            $utilisateur['prenom'],
            $sujet,
            $message
        );
    }

    $success =
        "Si cette adresse existe, un email de réinitialisation a été envoyé.";
}

/* =========================
   ETAPE 2 : RESET PASSWORD
========================= */

$utilisateurToken = null;

if (!empty($token)) {

    $utilisateurToken =
        $utilisateurClass->getByResetToken(
            $token
        );

    if (!$utilisateurToken) {
        $erreur =
            "Lien invalide ou expiré.";
    }
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && !empty($token)
    && $utilisateurToken
) {

    $password =
        $_POST['password'];

    $confirmPassword =
        $_POST['confirmPassword'];

    if (
        !preg_match(
            '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/',
            $password
        )
    ) {

        $erreur =
            "Mot de passe non sécurisé.";

    } elseif (
        $password !== $confirmPassword
    ) {

        $erreur =
            "Les mots de passe ne correspondent pas.";

    } else {

        $utilisateurClass->modifierMotDePasse(
            (int) $utilisateurToken['idUtilisateur'],
            $password
        );

        $success =
            "Votre mot de passe a été modifié. Vous pouvez maintenant vous connecter.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../../css/admin_client/admin_client1.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    link
</head>

<body>

<?php require '../../includes/navbar.php'; ?>




<main class="container">

    <h1 class="page-title">Mot de passe oublié</h1>

    <?php if($success): ?>
        <p class="success"><?= $success; ?></p>
    <?php endif; ?>

    <?php if($erreur): ?>
        <p class="error"><?= $erreur; ?></p>
    <?php endif; ?>

    <?php if(empty($token)): ?>

        <form method="POST">

            <label>Email</label>

            <input
                type="email"
                name="email"
                required>

            <button type="submit" class="btn">
                Envoyer le lien
            </button>

        </form>

    <?php elseif($utilisateurToken): ?>

        <form method="POST">

            <label>Nouveau mot de passe</label>

            <input
                type="password"
                name="password"
                required>

            <label>Confirmer le mot de passe</label>

            <input
                type="password"
                name="confirmPassword"
                required>

            <button type="submit" class="btn">
                Modifier le mot de passe
            </button>

        </form>

    <?php endif; ?>

</main>

<?php require '../../includes/footer.php'; ?>

</body>
</html>