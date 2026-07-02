<?php

require '../../config/database.php';
require '../../config/mail.php';

$success = "";
$erreur = "";

$token = $_GET['token'] ?? "";

/* =========================
   ETAPE 1 : DEMANDE EMAIL
========================= */

if($_SERVER['REQUEST_METHOD'] === 'POST' && empty($token)){

    $email = trim($_POST['email']);

    $sql = "SELECT * FROM utilisateur WHERE email = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$email]);

    $utilisateur = $query->fetch();

    if($utilisateur){

        $resetToken = bin2hex(random_bytes(32));

        $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "
        UPDATE utilisateur
        SET resetToken = ?, resetExpire = ?
        WHERE email = ?
        ";

        $query = $pdo->prepare($sql);

        $query->execute([
            $resetToken,
            $expire,
            $email
        ]);

        $lien = "http://localhost/vite_gourmand/pages/auth/forgot-password.php?token=" . $resetToken;

        $sujet = "Réinitialisation de votre mot de passe";

        $message = "
        <h2>Réinitialisation du mot de passe</h2>

        <p>Bonjour {$utilisateur['prenom']},</p>

        <p>
        Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe :
        </p>

        <p>
            <a href='{$lien}'>Réinitialiser mon mot de passe</a>
        </p>

        <p>Ce lien est valable pendant 1 heure.</p>
        ";

        envoyerMail(
            $email,
            $utilisateur['prenom'],
            $sujet,
            $message
        );
    }

    $success = "Si cette adresse existe, un email de réinitialisation a été envoyé.";
}

/* =========================
   ETAPE 2 : RESET PASSWORD
========================= */

$utilisateurToken = null;

if(!empty($token)){

    $sql = "
    SELECT *
    FROM utilisateur
    WHERE resetToken = ?
    AND resetExpire > NOW()
    ";

    $query = $pdo->prepare($sql);
    $query->execute([$token]);

    $utilisateurToken = $query->fetch();

    if(!$utilisateurToken){
        $erreur = "Lien invalide ou expiré.";
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($token) && $utilisateurToken){

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/', $password)){

        $erreur = "Mot de passe non sécurisé.";

    }elseif($password !== $confirmPassword){

        $erreur = "Les mots de passe ne correspondent pas.";

    }else{

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
        UPDATE utilisateur
        SET
            motDePasse = ?,
            resetToken = NULL,
            resetExpire = NULL
        WHERE idUtilisateur = ?
        ";

        $query = $pdo->prepare($sql);

        $query->execute([
            $hashedPassword,
            $utilisateurToken['idUtilisateur']
        ]);

        $success = "Votre mot de passe a été modifié. Vous pouvez maintenant vous connecter.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    link
</head>

<body>

<?php require '../../includes/navbar.php'; ?>




<main class="container">

    <h1 class="page-title">Mot de passe oublié</h1>

    <?php if($success): ?>
        <p><?= $success; ?></p>
    <?php endif; ?>

    <?php if($erreur): ?>
        <p><?= $erreur; ?></p>
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